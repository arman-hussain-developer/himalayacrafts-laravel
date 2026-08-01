<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bulk_quote_requests')) {
            Schema::table('bulk_quote_requests', function (Blueprint $table) {
                if (! Schema::hasColumn('bulk_quote_requests', 'name')) {
                    $table->string('name')->after('id');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'phone')) {
                    $table->string('phone')->after('name');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'email')) {
                    $table->string('email')->nullable()->after('phone');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'company_name')) {
                    $table->string('company_name')->nullable()->after('email');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'category_id')) {
                    $table->foreignId('category_id')->nullable()->after('company_name')->constrained()->nullOnDelete();
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'requirement')) {
                    $table->text('requirement')->after('category_id');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'quantity')) {
                    $table->integer('quantity')->nullable()->after('requirement');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'status')) {
                    $table->string('status')->default('new')->after('quantity');
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }

                if (! Schema::hasColumn('bulk_quote_requests', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });

            return;
        }

        Schema::create('bulk_quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('requirement');
            $table->integer('quantity')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_quote_requests');
    }
};
