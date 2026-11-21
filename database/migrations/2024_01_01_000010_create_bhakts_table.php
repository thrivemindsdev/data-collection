<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bhakts', function (Blueprint $table) {
            $table->id();
            $table->string('bhakt_id')->unique()->nullable(); // SAD-000001
            $table->string('name');
            $table->string('mobile', 15)->unique();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->text('address')->nullable();
            $table->string('branch')->nullable(); // Ashram branch
            $table->boolean('diksha_taken')->default(false);
            $table->date('diksha_date')->nullable();
            $table->string('diksha_place')->nullable();
            $table->string('diksha_guru')->nullable();
            $table->text('family_members')->nullable();
            $table->string('seva')->nullable(); // Service/volunteer
            $table->boolean('whatsapp_consent')->default(false);
            $table->boolean('sms_consent')->default(false);
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'correction'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhakts');
    }
};
