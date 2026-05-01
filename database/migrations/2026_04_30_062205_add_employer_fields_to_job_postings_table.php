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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->foreignId('category_id')->after('description')->nullable()->constrained()->nullOnDelete();
            $table->string('salary_range')->after('category_id')->nullable();
            $table->string('location')->after('salary_range')->nullable();
            $table->date('deadline')->after('location')->nullable();
            $table->boolean('is_active')->after('status')->default(true);

            $table->index('category_id');
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'salary_range', 'location', 'deadline', 'is_active']);
        });
    }
};
