<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price_per_share', 14, 2)->default(0);
            $table->decimal('total_value', 16, 2)->default(0);
            $table->timestamp('acquired_at')->nullable();
            $table->string('status')->default('active');
            $table->boolean('controlled_by_admin')->default(false);
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shares');
    }
};
