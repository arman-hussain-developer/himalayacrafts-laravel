<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_specifications')) {
            Schema::create('product_specifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('label');
                $table->string('value');
                $table->string('icon')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('product_specifications', function (Blueprint $table) {
            if (! Schema::hasColumn('product_specifications', 'product_id')) {
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('product_specifications', 'label')) {
                $table->string('label');
            }

            if (! Schema::hasColumn('product_specifications', 'value')) {
                $table->string('value');
            }

            if (! Schema::hasColumn('product_specifications', 'icon')) {
                $table->string('icon')->nullable();
            }

            if (! Schema::hasColumn('product_specifications', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }

            if (! Schema::hasColumn('product_specifications', 'created_at') && ! Schema::hasColumn('product_specifications', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
