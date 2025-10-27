<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();

            $table->nullableMorphs('receiver');
            $table->foreignId('sms_template_id')->nullable()->constrained('sms_templates');
            $table->string('driver')->nullable();
            $table->string('phone');
            $table->text('message')->nullable();
            $table->string('status')->nullable();
            $table->json('response')->nullable();

            // external columns
            $table->string('external_id')->nullable();
            $table->string('sent_at')->nullable();
            $table->text('error_message')->nullable();

            // other columns
            $table->string('eskiz_status')->nullable();
            $table->string('result_date')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('parts_count')->nullable();
            $table->boolean('is_ad')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
