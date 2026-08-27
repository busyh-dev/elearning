<?php

namespace Modules\SCORM\Entities;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use Modules\Lesson\Entities\Lesson; // Se Lesson è in un modulo separato

class ScormReport extends Model
{
    use Tenantable;

    protected $guarded = ['id'];

    protected $table = 'scorm_reports'; // Assicurati che il nome sia corretto

    // Relazione con il modello User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con il modello Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relazione con il modello Lesson (se esiste)
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}

