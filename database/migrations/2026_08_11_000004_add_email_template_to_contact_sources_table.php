<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_sources', function (Blueprint $table) {
            $table->string('email_subject', 255)->nullable()->after('recipient');
            $table->string('email_eyebrow', 100)->nullable()->after('email_subject');
            $table->string('email_heading', 255)->nullable()->after('email_eyebrow');
            $table->text('email_intro')->nullable()->after('email_heading');
            $table->string('email_footer', 255)->nullable()->after('email_intro');
            $table->string('email_header_image_path', 255)->nullable()->after('email_footer');
        });
    }

    public function down(): void
    {
        Schema::table('contact_sources', function (Blueprint $table) {
            $table->dropColumn([
                'email_subject', 'email_eyebrow', 'email_heading', 'email_intro',
                'email_footer', 'email_header_image_path',
            ]);
        });
    }
};
