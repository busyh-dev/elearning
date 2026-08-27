<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAiGeneratedContentsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_generated_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('input_text')->nullable();
            $table->longText('output_text')->nullable();
            $table->string('model')->nullable();
            $table->integer('tokens')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('words')->nullable();
            $table->integer('temperature')->nullable();
            $table->integer('frequency_penalty')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('ai_generated_contents');
    }
}
