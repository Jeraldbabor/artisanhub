<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSlugsUniquePerUserInCategories extends Migration
{
    public function up()
    {
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']); 
        });

       
        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['user_id', 'slug']); 
        });
    }

    public function down()
    {
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'slug']); 
            $table->unique(['slug']); 
        });
    }
}