<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('admin_id')->nullable();   // admin performing action
            $table->unsignedBigInteger('member_id')->nullable();  // member performing action
            
            $table->string('action');        // e.g., 'Created Loan'
            $table->string('table_name')->nullable(); // e.g., 'loans'
            $table->unsignedBigInteger('record_id')->nullable(); // affected record
            
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            $table->text('details')->nullable(); // extra info / notes
            
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('admin_id');
            $table->index('member_id');
            $table->index('table_name');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
