<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag_fr')->unique();
            $table->string('tag_en')->unique();
            $table->string('tag_de')->unique();
            $table->integer('type');
            $table->boolean('isChecked')->default(1);
          
        });


        Schema::create('tags_location', function (Blueprint $table) {

            $table->increments('pivot_id');
            $table->integer('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
           // $table->primary(['pivot_id', 'tag_id'], 'cafes_users_tags_primary');
          //  $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('tags_location');
        Schema::drop('tags');
     
    }
}
