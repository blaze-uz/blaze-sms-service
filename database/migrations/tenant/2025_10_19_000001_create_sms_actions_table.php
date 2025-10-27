<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->boolean('is_active')->default(true); // sms yoqilgan yoki o‘chirilgan
            $table->foreignId('sms_template_id')->nullable(); // bog‘langan template
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
