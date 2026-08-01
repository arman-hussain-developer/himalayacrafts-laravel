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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'order_no')) {
                    $table->string('order_no')->nullable()->unique()->after('id');
                }

                if (! Schema::hasColumn('orders', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('order_no')->constrained()->nullOnDelete();
                }

                if (! Schema::hasColumn('orders', 'customer_name')) {
                    $table->string('customer_name')->nullable()->after('user_id');
                }

                if (! Schema::hasColumn('orders', 'customer_email')) {
                    $table->string('customer_email')->nullable()->after('customer_name');
                }

                if (! Schema::hasColumn('orders', 'customer_phone')) {
                    $table->string('customer_phone')->nullable()->after('customer_email');
                }

                if (! Schema::hasColumn('orders', 'customer_address')) {
                    $table->text('customer_address')->nullable()->after('customer_phone');
                }

                if (! Schema::hasColumn('orders', 'city')) {
                    $table->string('city')->nullable()->after('customer_address');
                }

                if (! Schema::hasColumn('orders', 'state')) {
                    $table->string('state')->nullable()->after('city');
                }

                if (! Schema::hasColumn('orders', 'pincode')) {
                    $table->string('pincode')->nullable()->after('state');
                }

                if (! Schema::hasColumn('orders', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0)->after('pincode');
                }

                if (! Schema::hasColumn('orders', 'shipping_amount')) {
                    $table->decimal('shipping_amount', 10, 2)->default(0)->after('subtotal');
                }

                if (! Schema::hasColumn('orders', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_amount');
                }

                if (! Schema::hasColumn('orders', 'total')) {
                    $table->decimal('total', 10, 2)->default(0)->after('discount_amount');
                }

                if (! Schema::hasColumn('orders', 'status')) {
                    $table->string('status')->default('pending')->after('total');
                }

                if (! Schema::hasColumn('orders', 'payment_status')) {
                    $table->string('payment_status')->default('pending')->after('status');
                }

                if (! Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->default('cod')->after('payment_status');
                }

                if (! Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('payment_method');
                }
            });
        }

        if (Schema::hasTable('order_items')) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
