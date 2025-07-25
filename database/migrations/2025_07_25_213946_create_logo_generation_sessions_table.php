<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logo_generation_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('app_name');
            $table->json('business_context')->nullable();
            $table->text('prompt_template')->nullable();
            $table->json('generated_options')->nullable();
            $table->string('selected_option_id')->nullable();
            $table->enum('status', ['pending', 'generating', 'ready', 'completed', 'expired'])
                ->default('pending');
            $table->timestamps();
            $table->timestamp('expires_at');

            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_generation_sessions');
    }
};
