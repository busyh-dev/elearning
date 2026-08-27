<?php

namespace Modules\SCORM\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;
use Modules\SCORM\Entities\ScormReport;

class SCORMDatabaseSeeder extends Seeder
{

    public function run()
    {
        Model::unguard();


        $user = User::where('role_id', 3)->first();
        $course = Course::find(1);
        if ($user && $course) {
            $lesson = Lesson::create([
                'course_id' => $course->id,
                'name' => 'SCORM lesson 1',
                'description' => '',
                'video_url' => '',
                'host' => 'SCORM',
                'scorm_title' => 'SCORM 1',
                'scorm_version' => 'scorm_12',
                'scorm_identifier' => 'QU605nLdlABqu',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
                'key' => 'cmi.core.session_time',
                'value' => '00:02:02',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
                'key' => 'cmi.core.lesson_status',
                'value' => 'incomplete',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
                'key' => 'cmi.core.exit',
                'value' => 'suspend',
            ]);


            $lesson2 = Lesson::create([
                'course_id' => $course->id,
                'name' => 'SCORM lesson 2',
                'description' => '',
                'video_url' => '',
                'host' => 'SCORM',
                'scorm_title' => 'SCORM 2',
                'scorm_version' => 'scorm_12',
                'scorm_identifier' => '_5Wi4nXRFLHh',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson2->id,
                'key' => 'cmi.core.session_time',
                'value' => '0000:39:01.95',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson2->id,
                'key' => 'cmi.core.lesson_status',
                'value' => 'passed',
            ]);
            ScormReport::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson2->id,
                'key' => 'cmi.core.exit',
                'value' => 'suspend',
            ]);
        }

    }
}
