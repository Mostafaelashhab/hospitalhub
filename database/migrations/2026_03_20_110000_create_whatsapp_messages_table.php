<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable()->index();
            $table->string('instance_id')->nullable();
            $table->string('from_number', 30)->index();
            $table->string('to_number', 30)->nullable();
            $table->text('body');
            $table->string('type', 20)->default('chat');
            $table->string('sender_name')->nullable();
            $table->boolean('is_group')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamp('message_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
