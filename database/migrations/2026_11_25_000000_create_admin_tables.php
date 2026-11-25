<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tables for the admin panel: the book catalogue (moved out of
 * config/catalog.php), coupons, editable site settings, an admin role on
 * users, and the order fields the admin works with.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('customer')->after('password')->index();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');                      // e.g. "Mathematics" or "Smart Scores"
            $table->string('subject')->nullable();        // line under the title, e.g. "Semester 1"
            $table->string('subject_filter', 80)->index(); // subject used by the catalogue filter
            $table->string('series', 80)->default('Shubhanga');
            $table->string('class', 10)->index();          // Nursery, PP1, PP2, 1..10
            $table->string('section', 40)->index();        // Pre-primary, Primary, State Board High School, CBSE High School
            $table->json('boards');                        // every board page the book is listed on
            $table->string('listed_as')->nullable();       // listing title on the old site
            $table->string('type', 30)->default('Textbooks');
            $table->string('image')->nullable();
            $table->text('description')->nullable();       // optional; generated text is used when empty
            $table->unsignedInteger('mrp');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock')->default(100);
            $table->unsignedInteger('sold_count')->default(0);
            $table->decimal('rating', 2, 1)->default(4.5); // sample until real reviews exist
            $table->unsignedInteger('reviews')->default(0);
            $table->string('badge', 30)->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('cover_pending')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('type', 10);                    // percent | flat
            $table->unsignedInteger('value');
            $table->unsignedInteger('min_cart_value')->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 40)->index();
            $table->string('key', 80)->unique();
            $table->string('label');
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text');   // text | textarea | number | email | url
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('discount')->default(0)->after('shipping');
            $table->string('coupon_code', 30)->nullable()->after('discount');
            $table->string('tracking_number', 100)->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['discount', 'coupon_code', 'tracking_number', 'admin_notes', 'shipped_at', 'delivered_at']);
        });
        Schema::dropIfExists('settings');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('books');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
