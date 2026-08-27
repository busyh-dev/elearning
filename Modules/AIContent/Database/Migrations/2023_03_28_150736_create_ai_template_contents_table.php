<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\AIContent\Entities\AITemplateContent;

class CreateAITemplateContentsTable extends Migration
{

    public function up()
    {
        Schema::create('ai_template_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });

        $messages = [
            //course title
            1 => 'Write a course title',
            2 => 'Write a course short description',
            3 => 'Write a course long description',
            4 => 'Write a course requirement',
            5 => 'Write a course outlines',
            6 => 'Write a course meta keywords',
            7 => 'Write a course meta description',
            8 => 'Write a course lesson text',
            9 => 'Write a FAQ',
            10 => 'Write a blog title',
            11 => 'Write a blog details',

        ];
        foreach ($messages as $key => $message) {
            AITemplateContent::create([
                'template_id' => $key,
                'content' => $message
            ]);
        }
    }


    public function down()
    {
        Schema::dropIfExists('ai_template_contents');
    }
}
