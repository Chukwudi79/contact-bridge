<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ContactSource;
use App\Models\ContactSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Primary Administrator', 'email' => 'admin@example.com', 'password' => 'Admin@12345'],
            ['name' => 'Operations Manager', 'email' => 'operations@example.com', 'password' => 'Operations@12345'],
            ['name' => 'Support Agent', 'email' => 'support@example.com', 'password' => 'Support@12345'],
        ] as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'is_admin' => true, 'email_verified_at' => now()],
            );
        }

        $platforms = [
            ['origin' => 'https://bothsign.example', 'recipient' => 'sales@bothsign.example', 'email_heading' => 'A new BothSign prospect is waiting'],
            ['origin' => 'https://monierp.example', 'recipient' => 'growth@monierp.example', 'email_heading' => 'New MoniERP conversation'],
            ['origin' => 'https://sqotes.example', 'recipient' => 'team@sqotes.example', 'email_heading' => 'A new Sqotes enquiry arrived'],
            ['origin' => 'https://dataphem.example', 'recipient' => 'hello@dataphem.example', 'email_heading' => 'New Dataphem website message'],
        ];

        foreach ($platforms as $platform) {
            ContactSource::updateOrCreate(
                ['origin' => $platform['origin']],
                [
                    'recipient' => $platform['recipient'],
                    'is_active' => true,
                    'email_eyebrow' => 'Contact Bridge',
                    'email_heading' => $platform['email_heading'],
                    'email_intro' => 'A new contact form message needs your attention.',
                    'email_footer' => 'Sent securely through Contact Bridge.',
                ],
            );
        }

        $submissions = [
            ['origin' => 'https://bothsign.example', 'recipient' => 'sales@bothsign.example', 'first' => 'Adeleke', 'last' => 'Igwe', 'email' => 'adeleke@northstar.test', 'product' => 'BothSign', 'message' => 'We need a secure signing workflow for our operations team.', 'status' => 'sent', 'days_ago' => 6],
            ['origin' => 'https://monierp.example', 'recipient' => 'growth@monierp.example', 'first' => 'Amara', 'last' => 'Okafor', 'email' => 'amara@greenfield.test', 'product' => 'MoniERP', 'message' => 'Can we arrange an ERP walkthrough for a growing distribution business?', 'status' => 'sent', 'days_ago' => 5],
            ['origin' => 'https://sqotes.example', 'recipient' => 'team@sqotes.example', 'first' => 'Tunde', 'last' => 'Adebayo', 'email' => 'tunde@atelier.test', 'product' => 'Sqotes', 'message' => 'Our team wants a faster way to prepare client quotations.', 'status' => 'in_progress', 'days_ago' => 4],
            ['origin' => 'https://dataphem.example', 'recipient' => 'hello@dataphem.example', 'first' => 'Zainab', 'last' => 'Bello', 'email' => 'zainab@orbit.test', 'product' => 'General inquiry', 'message' => 'Please share more details about your technology services.', 'status' => 'resolved', 'days_ago' => 4],
            ['origin' => 'https://bothsign.example', 'recipient' => 'sales@bothsign.example', 'first' => 'Chinedu', 'last' => 'Eze', 'email' => 'chinedu@crest.test', 'product' => 'BothSign', 'message' => 'Is there a sandbox environment available for integration testing?', 'status' => 'failed', 'days_ago' => 3],
            ['origin' => 'https://monierp.example', 'recipient' => 'growth@monierp.example', 'first' => 'Kemi', 'last' => 'Ojo', 'email' => 'kemi@harbor.test', 'product' => 'MoniERP', 'message' => 'We are evaluating finance and inventory tools for three locations.', 'status' => 'sent', 'days_ago' => 2],
            ['origin' => 'https://sqotes.example', 'recipient' => 'team@sqotes.example', 'first' => 'Ifeanyi', 'last' => 'Nwosu', 'email' => 'ifeanyi@vertex.test', 'product' => 'Sqotes', 'message' => 'Could you show us your approval flow for quote revisions?', 'status' => 'pending', 'days_ago' => 2],
            ['origin' => 'https://dataphem.example', 'recipient' => 'hello@dataphem.example', 'first' => 'Lola', 'last' => 'Adeyemi', 'email' => 'lola@kinetic.test', 'product' => 'General inquiry', 'message' => 'We would like to discuss a possible product partnership.', 'status' => 'sent', 'days_ago' => 1],
            ['origin' => 'https://bothsign.example', 'recipient' => 'sales@bothsign.example', 'first' => 'Bola', 'last' => 'Onyeka', 'email' => 'bola@insight.test', 'product' => 'BothSign', 'message' => 'Please contact me about enterprise pricing and onboarding.', 'status' => 'in_progress', 'days_ago' => 0],
            ['origin' => 'https://monierp.example', 'recipient' => 'growth@monierp.example', 'first' => 'Femi', 'last' => 'Lawal', 'email' => 'femi@pioneer.test', 'product' => 'MoniERP', 'message' => 'Can MoniERP support multiple warehouse locations?', 'status' => 'sent', 'days_ago' => 0],
        ];

        foreach ($submissions as $data) {
            $submittedAt = now()->subDays($data['days_ago'])->setTime(10 + $data['days_ago'], 15);
            $submission = ContactSubmission::updateOrCreate(
                ['website_origin' => $data['origin'], 'email' => $data['email'], 'message' => $data['message']],
                [
                    'recipient' => $data['recipient'],
                    'first_name' => $data['first'],
                    'last_name' => $data['last'],
                    'product' => $data['product'],
                    'status' => $data['status'],
                    'sent_at' => $data['status'] === 'sent' ? $submittedAt->copy()->addMinute() : null,
                    'failure_reason' => $data['status'] === 'failed' ? 'Demo SMTP connection timeout. Use resend to test the recovery flow.' : null,
                ],
            );
            $submission->forceFill(['created_at' => $submittedAt, 'updated_at' => $submittedAt])->save();

            $submission->events()->firstOrCreate(
                ['event' => 'submission_received'],
                ['status' => 'pending', 'message' => 'Demo contact form submission received from an approved origin.'],
            );
            $submission->events()->firstOrCreate(
                ['event' => $data['status'] === 'failed' ? 'delivery_failed' : 'delivery_sent'],
                [
                    'status' => $data['status'] === 'failed' ? 'failed' : 'sent',
                    'message' => $data['status'] === 'failed' ? 'Demo SMTP connection timeout.' : 'Demo SMTP delivery completed successfully.',
                ],
            );
        }
    }
}
