<?php

namespace Modules\AIContent\Entities;

use Illuminate\Database\Eloquent\Model;

class AiGeneratedContent extends Model
{
    protected $fillable = [];


    public function template()
    {
        return $this->belongsTo(AITemplate::class, 'template_id');
    }
}
