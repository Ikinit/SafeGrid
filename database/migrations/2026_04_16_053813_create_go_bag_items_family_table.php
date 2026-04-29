<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('go_bag_items_family', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_profile_id')->constrained()->onDelete('cascade'); 
            $table->string('name');
            $table->string('category'); 
            $table->boolean('is_packed')->default(false); 
 
            $table->text('nutritional_info')->nullable(); 
            $table->date('expiry_date')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('go_bag_items_family');
    }
};