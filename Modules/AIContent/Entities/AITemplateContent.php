<?php

namespace Modules\AIContent\Entities;

use Illuminate\Database\Eloquent\Model;

class AITemplateContent extends Model
{
    protected $fillable = [
        'template_id',
        'content',
    ];
    protected $table = 'ai_template_contents';

    protected $casts = [
        'content' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(AITemplate::class, 'template_id');
    }
}
