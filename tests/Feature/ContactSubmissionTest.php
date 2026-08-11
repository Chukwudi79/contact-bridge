<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ContactSource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ContactSource::create([
            'origin' => 'https://platform-a.example',
            'recipient' => 'inbox@example.com',
            'is_active' => true,
        ]);
    }

    public function test_an_allowed_origin_can_submit_and_sets_reply_to(): void
    {
        Mail::fake();

        $response = $this->withHeader('Origin', 'https://platform-a.example')->postJson('/api/contact', [
            'firstName' => 'Adeleke',
            'lastName' => 'Igwe',
            'email' => 'adeleke@example.com',
            'product' => 'BothSign',
            'recipient' => 'inbox@example.com',
            'message' => 'I would like a demo.',
        ]);

        $response->assertAccepted()->assertJson(['message' => 'Your message has been sent successfully.']);
        Mail::assertSent(\App\Mail\ContactSubmission::class, function ($mail) {
            return $mail->hasTo('inbox@example.com')
                && $mail->hasReplyTo('adeleke@example.com')
                && $mail->submission['website_origin'] === 'https://platform-a.example';
        });
    }

    public function test_an_unregistered_origin_is_rejected_before_delivery(): void
    {
        Mail::fake();

        $response = $this->withHeader('Origin', 'https://unknown.example')->postJson('/api/contact', [
            'firstName' => 'Adeleke',
            'lastName' => 'Igwe',
            'email' => 'adeleke@example.com',
            'message' => 'Hello.',
            'recipient' => 'inbox@example.com',
        ]);

        $response->assertForbidden();
        Mail::assertNothingSent();
    }

    public function test_a_missing_origin_is_rejected(): void
    {
        $this->postJson('/api/contact', [])->assertForbidden();
    }

    public function test_an_admin_can_sign_in_and_view_submissions(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.submissions.index'));

        $this->get(route('admin.submissions.index'))->assertOk();
    }
}
