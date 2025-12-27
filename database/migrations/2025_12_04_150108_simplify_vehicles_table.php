<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, add the new 'name' column
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('name')->after('customer_id')->nullable();
        });

        // Migrate existing data: combine brand + model + plate_number into name
        DB::table('vehicles')->get()->each(function ($vehicle) {
            $name = trim($vehicle->brand . ' ' . $vehicle->model);
            if ($vehicle->plate_number) {
                $name .= ' (' . $vehicle->plate_number . ')';
            }
            DB::table('vehicles')->where('id', $vehicle->id)->update(['name' => $name]);
        });

        // Make name required and drop old columns
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropColumn(['plate_number', 'brand', 'model', 'year', 'color', 'notes']);
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('plate_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('color')->nullable();
            $table->text('notes')->nullable();
        });

        // Restore name to plate_number
        DB::table('vehicles')->get()->each(function ($vehicle) {
            DB::table('vehicles')->where('id', $vehicle->id)->update([
                'plate_number' => $vehicle->name,
                'brand' => '-',
                'model' => '-',
            ]);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
