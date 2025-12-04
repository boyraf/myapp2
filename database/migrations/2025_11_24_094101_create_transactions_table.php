<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->enum('type', ['saving_deposit','saving_withdrawal','loan_disbursement','repayment','share_purchase','share_sale']);
            $table->decimal('amount', 10,2);
            $table->decimal('balance_after', 10,2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
