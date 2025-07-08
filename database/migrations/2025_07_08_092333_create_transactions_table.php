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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // Mã giao dịch duy nhất
            $table->foreignId('dat_ve_id')->constrained('dat_ves')->onDelete('cascade'); // ID đặt vé
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID người dùng

            // Thông tin thanh toán
            $table->enum('payment_method', ['zalopay', 'vnpay', 'momo', 'banking', 'cod']); // Phương thức thanh toán
            $table->decimal('amount', 15, 2); // Số tiền
            $table->string('currency', 3)->default('VND'); // Đơn vị tiền tệ

            // Thông tin từ cổng thanh toán
            $table->string('gateway_transaction_id')->nullable(); // ID giao dịch từ cổng thanh toán
            $table->string('gateway_order_id')->nullable(); // Mã đơn hàng từ cổng thanh toán
            $table->json('gateway_response')->nullable(); // Response từ cổng thanh toán

            // Trạng thái giao dịch
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_url')->nullable(); // URL thanh toán
            $table->timestamp('paid_at')->nullable(); // Thời gian thanh toán thành công
            $table->text('note')->nullable(); // Ghi chú

            $table->timestamps();

            // Indexes
            $table->index('transaction_code');
            $table->index('gateway_transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
