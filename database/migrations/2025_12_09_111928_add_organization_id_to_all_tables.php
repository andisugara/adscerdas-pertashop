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
        // Add organization_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('operator')->change(); // superadmin, owner, operator
            $table->foreignId('active_organization_id')->nullable()->after('role')->constrained('organizations')->nullOnDelete();
        });

        // Add organization_id to settings
        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to shifts
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to daily_reports
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to tank_additions
        Schema::table('tank_additions', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to expenses
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to deposits
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });

        // Add organization_id to salaries
        Schema::table('salaries', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_organization_id']);
            $table->dropColumn('active_organization_id');
        });

        $tables = ['settings', 'shifts', 'daily_reports', 'tank_additions', 'expenses', 'deposits', 'salaries'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            });
        }
    }
};
