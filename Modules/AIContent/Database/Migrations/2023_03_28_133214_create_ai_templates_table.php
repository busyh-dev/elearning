<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\AIContent\Entities\AITemplate;

class CreateAITemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('ai_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->integer('type')->default(1)->comment('1=Pre define, 2=user define');
            $table->integer('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->integer('created_by')->nullable();
            $table->integer('lms_id')->nullable()->default(1);
            $table->timestamps();
        });

        $system_templates = [
            'Course Title',
            'Course Short Description',
            'Course Long Description',
            'Course Requirements',
            'Course Outlines',
            'Course meta keywords',
            'Course meta description',
            'Course lesson text',
            'FAQ',
            'Blog title',
            'Blog details',
        ];
        foreach ($system_templates as $key => $template) {
            AITemplate::create([
                'name' => $template,
                'slug' => str_replace(' ', '-', strtolower($template)),
                'icon' => 'fa fa-file',
                'type' => 1,
                'status' => 1,
                'created_by' => 1,
                'lms_id' => 1,
            ]);
        }


    }

    public function down()
    {
        Schema::dropIfExists('ai_templates');
    }
}
