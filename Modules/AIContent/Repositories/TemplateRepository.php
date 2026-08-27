<?php

namespace Modules\AIContent\Repositories;

use App\Repositories\Eloquents\BaseRepository;
use Modules\AIContent\Entities\AITemplate;

class TemplateRepository extends BaseRepository
{

    public function __construct(AITemplate $model)
    {
        $this->model = $model;
    }


    function getTemplateDetails($template_id)
    {
        return $this->model->where('id', $template_id)
            ->with('template_content')
            ->first();
    }

}
