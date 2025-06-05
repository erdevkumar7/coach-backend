<?php

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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            // Optional: link to the form (if you have multiple forms)
            $table->string('form_id')->index();

            // Optional: link to page if forms vary by page
            $table->string('page_id')->nullable()->index();

            // Main form data (JSON of all fields)
            $table->json('form_data');

            // Optional: link to user who submitted
            $table->string('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
