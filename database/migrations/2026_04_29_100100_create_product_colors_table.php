<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_colors')) {
            Schema::create('product_colors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('color_name');
                $table->string('color_code', 20)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('product_colors', function (Blueprint $table) {
            if (! Schema::hasColumn('product_colors', 'product_id')) {
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('product_colors', 'color_name')) {
                $table->string('color_name');
            }

            if (! Schema::hasColumn('product_colors', 'color_code')) {
                $table->string('color_code', 20)->nullable();
            }

            if (! Schema::hasColumn('product_colors', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }

            if (! Schema::hasColumn('product_colors', 'created_at') && ! Schema::hasColumn('product_colors', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
