<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name')->nullable();
            $table->string('path_image')->nullable();
            $table->string('status')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();
        }); 

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('categorie_id')->nullable();
            $table->string('name')->nullable();
            $table->string('path_image')->nullable();
            $table->bigInteger('price')->nullable();
            // $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();

            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
        });        

        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->string('name')->nullable();
            $table->string('path_image')->nullable();
            // $table->string('category')->nullable(); //kering / basah
            $table->string('stock_quantity')->nullable();
            $table->string('unit')->nullable();
            $table->bigInteger('price_per_unit')->nullable();
            $table->string('supplier')->nullable();
            $table->timestamp('last_restocked')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();
        });

        Schema::create('products_inventories_many2many', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('product_id')->nullable();
            $table->uuid('inventory_id')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->foreignId('user_id')->nullable()->index(); //meja
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();
        });
        
        Schema::create('checkouts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('order_id')->nullable();
            $table->uuid('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->bigInteger('product_price')->nullable();
            $table->bigInteger('product_quantity')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('order_id')->nullable();
            $table->foreignId('user_id')->nullable()->index();
            $table->bigInteger('total_amount')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesTz();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products_inventories_many2many');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};