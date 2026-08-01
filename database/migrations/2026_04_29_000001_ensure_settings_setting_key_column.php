<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            if (Schema::hasColumn('settings', 'key') && ! Schema::hasColumn('settings', 'setting_key')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->renameColumn('key', 'setting_key');
                });
            }

            if (! Schema::hasColumn('settings', 'setting_key')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->string('setting_key')->unique()->after('id');
                });
            }

            DB::table('settings')
                ->whereNull('setting_key')
                ->orWhere('setting_key', '')
                ->orderBy('id')
                ->get(['id'])
                ->each(function ($setting): void {
                    DB::table('settings')
                        ->where('id', $setting->id)
                        ->update(['setting_key' => 'setting_' . $setting->id]);
                });

            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE settings MODIFY setting_key VARCHAR(255) NOT NULL');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
