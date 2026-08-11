<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ContactSource;
use App\Models\ContactSubmission;
use App\Mail\AdminSubmissionReply;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
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
        Queue::fake();

        $response = $this->withHeader('Origin', 'https://platform-a.example')->postJson('/api/contact', [
            'firstName' => 'Adeleke',
            'lastName' => 'Igwe',
            'email' => 'adeleke@example.com',
            'product' => 'BothSign',
            'recipient' => 'inbox@example.com',
            'message' => 'I would like a demo.',
        ]);

        $response->assertAccepted()->assertJson(['message' => 'Your message has been queued for delivery.', 'status' => 'pending']);
        Queue::assertPushed(\App\Jobs\SendContactSubmission::class);
        (new \App\Jobs\SendContactSubmission(1))->handle();
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

    public function test_an_admin_can_view_the_operations_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Submission volume')
            ->assertSee('Delivery health');
    }

    public function test_an_admin_can_save_a_source_email_template(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $source = ContactSource::firstOrFail();

        $this->actingAs($user)->post(route('admin.sources.template.update', $source), [
            '_method' => 'PATCH',
            'email_subject' => 'New BothSign enquiry',
            'email_eyebrow' => 'BothSign leads',
            'email_heading' => 'A new prospect is waiting',
            'email_intro' => 'Respond while the conversation is fresh.',
            'email_footer' => 'Sent by the BothSign team.',
        ])->assertRedirect(route('admin.sources.template.edit', $source));

        $source->refresh();
        $this->assertSame('New BothSign enquiry', $source->email_subject);
        $this->assertSame('A new prospect is waiting', $source->email_heading);

        $this->actingAs($user)
            ->get(route('admin.sources.template.preview', $source))
            ->assertOk()
            ->assertSee('See it before it sends.');

        $source->update(['email_logo_path' => 'contact-email-logos/demo-logo.png']);

        $this->actingAs($user)
            ->get(route('admin.sources.template.preview.email', $source))
            ->assertOk()
            ->assertSee('A new prospect is waiting')
            ->assertSee('/storage/contact-email-logos/demo-logo.png');
    }

    public function test_an_admin_can_resend_a_failed_delivery_and_reply_to_the_visitor(): void
    {
        Mail::fake();
        $user = User::factory()->create(['is_admin' => true]);
        $submission = ContactSubmission::create([
            'website_origin' => 'https://platform-a.example',
            'recipient' => 'inbox@example.com',
            'first_name' => 'Adeleke',
            'last_name' => 'Igwe',
            'email' => 'adeleke@example.com',
            'product' => 'BothSign',
            'message' => 'I would like a demo.',
            'status' => 'failed',
            'failure_reason' => 'SMTP connection refused.',
        ]);

        $this->actingAs($user)
            ->post(route('admin.submissions.resend', $submission))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_submission_events', [
            'contact_submission_id' => $submission->id,
            'event' => 'delivery_resent',
            'status' => 'sent',
        ]);

        $this->actingAs($user)
            ->post(route('admin.submissions.reply', $submission), [
                'subject' => 'Re: Your BothSign request',
                'body' => 'Thank you. Our team will be in touch shortly.',
            ])
            ->assertSessionHas('success');

        Mail::assertSent(AdminSubmissionReply::class, fn (AdminSubmissionReply $mail) => $mail->hasTo('adeleke@example.com'));
        $this->assertDatabaseHas('contact_submission_events', [
            'contact_submission_id' => $submission->id,
            'event' => 'reply_sent',
            'status' => 'sent',
        ]);
    }

    public function test_an_admin_can_create_a_workspace_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New Workspace User',
                'email' => 'new.user@example.com',
                'password' => 'SecurePass123',
                'password_confirmation' => 'SecurePass123',
                'is_admin' => '1',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'new.user@example.com',
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()->assertSee('New Workspace User');
    }

    public function test_an_admin_can_edit_a_source_origin_and_recipient(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $source = ContactSource::firstOrFail();

        $this->actingAs($admin)
            ->patch(route('admin.sources.details.update', $source), [
                'origin' => 'https://updated-platform.example',
                'recipient' => 'updated@example.com',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.sources.index'));

        $this->assertDatabaseHas('contact_sources', ['id' => $source->id, 'origin' => 'https://updated-platform.example', 'recipient' => 'updated@example.com']);
    }
}
