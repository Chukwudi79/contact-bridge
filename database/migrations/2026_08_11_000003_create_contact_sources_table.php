<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_sources', function (Blueprint $table) {
            $table->id();
            $table->string('origin', 191)->unique('contact_sources_origin_unique');
            $table->string('recipient', 254);
            $table->boolean('is_active')->default(true)->index('contact_sources_is_active_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_sources');
    }
};
