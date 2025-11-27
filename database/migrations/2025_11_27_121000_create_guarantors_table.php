<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('guarantor_id');
            $table->decimal('amount_guaranteed', 14, 2)->default(0);
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            // Use NO ACTION for guarantor foreign key to avoid multiple cascade paths on SQL Server
            $table->foreign('guarantor_id')->references('id')->on('members')->onDelete('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guarantors');
    }
};
