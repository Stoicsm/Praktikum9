<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new column
            $table->enum('new_role', ['root', 'admin', 'user'])->default('admin')->after('role');
        });

        // Copy data, converting 'users' to 'user'
        DB::statement("UPDATE users SET new_role = CASE 
            WHEN role = 'users' THEN 'user'
            ELSE role 
        END");

        Schema::table('users', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Rename new column to old name
            $table->renameColumn('new_role', 'role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add old column back
            $table->enum('old_role', ['root', 'admin', 'users'])->default('users')->after('role');
        });

        // Copy data back, converting 'user' to 'users'
        DB::statement("UPDATE users SET old_role = CASE 
            WHEN role = 'user' THEN 'users'
            ELSE role 
        END");

        Schema::table('users', function (Blueprint $table) {
            // Drop new column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Rename old column back
            $table->renameColumn('old_role', 'role');
        });
    }
};