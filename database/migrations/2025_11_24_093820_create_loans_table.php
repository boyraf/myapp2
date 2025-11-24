<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->decimal('amount', 10,2);
            $table->decimal('interest_rate',5,2);
            $table->integer('repayment_period'); // in months
            $table->enum('status', ['pending','approved','paid','overdue']);
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('balance', 10,2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
