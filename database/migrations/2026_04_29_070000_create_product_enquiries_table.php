<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_enquiries')) {
            Schema::table('product_enquiries', function (Blueprint $table) {
                if (! Schema::hasColumn('product_enquiries', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->after('id')->constrained()->nullOnDelete();
                }

                if (! Schema::hasColumn('product_enquiries', 'variant_id')) {
                    $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
                }

                if (! Schema::hasColumn('product_enquiries', 'product_name')) {
                    $table->string('product_name')->nullable()->after('variant_id');
                }

                if (! Schema::hasColumn('product_enquiries', 'customer_name')) {
                    $table->string('customer_name')->after('product_name');
                }

                if (! Schema::hasColumn('product_enquiries', 'customer_phone')) {
                    $table->string('customer_phone')->after('customer_name');
                }

                if (! Schema::hasColumn('product_enquiries', 'customer_email')) {
                    $table->string('customer_email')->nullable()->after('customer_phone');
                }

                if (! Schema::hasColumn('product_enquiries', 'message')) {
                    $table->text('message')->nullable()->after('customer_email');
                }

                if (! Schema::hasColumn('product_enquiries', 'status')) {
                    $table->string('status')->default('new')->after('message');
                }

                if (! Schema::hasColumn('product_enquiries', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }

                if (! Schema::hasColumn('product_enquiries', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });

            return;
        }

        Schema::create('product_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_enquiries');
    }
};
