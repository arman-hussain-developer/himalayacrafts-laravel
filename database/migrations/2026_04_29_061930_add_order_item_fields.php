<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->after('order_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('order_items', 'variant_id')) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            }

            if (! Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('variant_id');
            }

            if (! Schema::hasColumn('order_items', 'variant_label')) {
                $table->string('variant_label')->nullable()->after('product_name');
            }

            if (! Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1)->after('variant_label');
            }

            if (! Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('quantity');
            }

            if (! Schema::hasColumn('order_items', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0)->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
