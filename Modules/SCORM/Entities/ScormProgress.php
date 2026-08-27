<?php

namespace Modules\SCORM\Entities;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ScormProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'progress', 'status'];

    // Definisce la relazione con l'utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisce la relazione con la lezione
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}