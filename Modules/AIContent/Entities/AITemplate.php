<?php

namespace Modules\AIContent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AITemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'type',
        'status',
        'created_by',
        'lms_id',
    ];

    protected $table = 'ai_templates';

    public function template_content(): BelongsTo
    {
        return $this->belongsTo(AITemplateContent::class, 'id', 'template_id');
    }

}
