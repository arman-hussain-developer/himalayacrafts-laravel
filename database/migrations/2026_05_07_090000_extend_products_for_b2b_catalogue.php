<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'branding_details')) {
                $table->longText('branding_details')->nullable()->after('description');
            }

            if (! Schema::hasColumn('products', 'packaging_details')) {
                $table->longText('packaging_details')->nullable()->after('branding_details');
            }

            if (! Schema::hasColumn('products', 'production_timeline')) {
                $table->string('production_timeline')->nullable()->after('packaging_details');
            }

            if (! Schema::hasColumn('products', 'moq')) {
                $table->string('moq')->nullable()->after('production_timeline');
            }

            if (! Schema::hasColumn('products', 'printing_methods')) {
                $table->text('printing_methods')->nullable()->after('moq');
            }
        });

        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (! Schema::hasColumn('product_images', 'image_type')) {
                    $table->string('image_type')->default('gallery')->after('image');
                }

                if (! Schema::hasColumn('product_images', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('image_type');
                }
            });
        }

        if (Schema::hasTable('product_colors')) {
            Schema::table('product_colors', function (Blueprint $table) {
                if (! Schema::hasColumn('product_colors', 'color_image')) {
                    $table->string('color_image')->nullable()->after('color_code');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_colors') && Schema::hasColumn('product_colors', 'color_image')) {
            Schema::table('product_colors', function (Blueprint $table) {
                $table->dropColumn('color_image');
            });
        }

        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (Schema::hasColumn('product_images', 'sort_order')) {
                    $table->dropColumn('sort_order');
                }

                if (Schema::hasColumn('product_images', 'image_type')) {
                    $table->dropColumn('image_type');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                foreach ([
                    'printing_methods',
                    'moq',
                    'production_timeline',
                    'packaging_details',
                    'branding_details',
                ] as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
