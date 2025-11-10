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
        // First, update any 'users' values to 'user'
        DB::table('users')
            ->where('role', 'users')
            ->update(['role' => 'user']);

        // Then modify the column
        DB::statement("ALTER TABLE users MODIFY role ENUM('root', 'admin', 'user') NOT NULL DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('root', 'admin', 'users') NOT NULL DEFAULT 'users'");
        
        // Convert any 'user' back to 'users'
        DB::table('users')
            ->where('role', 'user')
            ->update(['role' => 'users']);
    }
};
