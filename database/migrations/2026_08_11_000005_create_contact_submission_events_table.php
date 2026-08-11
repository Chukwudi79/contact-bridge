<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submission_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_submission_id');
            $table->foreign('contact_submission_id', 'submission_events_submission_fk')->references('id')->on('contact_submissions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id', 'submission_events_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->string('event', 80)->index('submission_events_event_index');
            $table->string('status', 40)->nullable()->index('submission_events_status_index');
            $table->text('message')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submission_events');
    }
};
