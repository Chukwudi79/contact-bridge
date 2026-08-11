<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('website_origin', 191)->index('contact_submissions_origin_index');
            $table->string('recipient', 254);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 254);
            $table->string('product', 100)->nullable();
            $table->text('message');
            $table->string('status', 30)->default('pending')->index('contact_submissions_status_index');
            $table->text('failure_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
