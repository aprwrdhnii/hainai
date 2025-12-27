<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, change to VARCHAR to allow any value
        DB::statement("ALTER TABLE services MODIFY COLUMN status VARCHAR(20) DEFAULT 'bon'");
        
        // Update existing data
        DB::table('services')->where('status', 'pending')->update(['status' => 'bon']);
        DB::table('services')->where('status', 'in_progress')->update(['status' => 'bon']);
        DB::table('services')->where('status', 'completed')->update(['status' => 'lunas']);
        DB::table('services')->where('status', 'cancelled')->update(['status' => 'bon']);
        
        // Now change back to ENUM with new values
        DB::statement("ALTER TABLE services MODIFY COLUMN status ENUM('bon', 'lunas') DEFAULT 'bon'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE services MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'");
        DB::table('services')->where('status', 'bon')->update(['status' => 'pending']);
        DB::table('services')->where('status', 'lunas')->update(['status' => 'completed']);
        DB::statement("ALTER TABLE services MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
