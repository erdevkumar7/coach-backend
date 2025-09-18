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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();  
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');         
            $table->boolean('new_coach_match_alert')->default(true);
            $table->boolean('message_notifications')->default(true);
            $table->boolean('booking_reminders')->default(true);
            $table->boolean('coaching_request_status')->default(true);
            $table->boolean('platform_announcements')->default(true);
            $table->boolean('blog_article_recommendations')->default(true);
            $table->boolean('billing_updates')->default(true);
            $table->string('communication_preference')->default('email'); 
            $table->string('profile_visibility')->default('public'); 
            $table->boolean('allow_ai_matching')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
