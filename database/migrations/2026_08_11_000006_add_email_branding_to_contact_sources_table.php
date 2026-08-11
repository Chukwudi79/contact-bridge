<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_sources', function (Blueprint $table) {
            $table->string('email_logo_path', 255)->nullable()->after('email_header_image_path');
            $table->string('email_header_color', 7)->nullable()->after('email_logo_path');
            $table->string('email_accent_color', 7)->nullable()->after('email_header_color');
            $table->string('email_background_color', 7)->nullable()->after('email_accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('contact_sources', function (Blueprint $table) {
            $table->dropColumn(['email_logo_path', 'email_header_color', 'email_accent_color', 'email_background_color']);
        });
    }
};
