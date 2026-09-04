<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->string('payment_mode')->nullable()->after('application_payment_status');
            $table->string('razorpay_transaction_id')->nullable()->after('payment_mode');
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['payment_mode', 'razorpay_transaction_id']);
        });
    }
};
