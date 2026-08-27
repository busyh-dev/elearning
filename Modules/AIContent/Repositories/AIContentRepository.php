<?php

namespace Modules\AIContent\Repositories;

use App\Repositories\Eloquents\BaseRepository;
use Modules\AIContent\Entities\AITemplate;
use Modules\AIContent\Entities\AiGeneratedContent;

class AIContentRepository extends BaseRepository
{
    public function __construct(AiGeneratedContent $model)
    {
        $this->model = $model;
    }


    public function getUserContent()
    {
        return $this->model->where('user_id', auth()->id())->with('template')->get();
    }

    function storeData($response_data)
    {
        try {
            $content = new $this->model;
            $content->user_id = $response_data['user_id'];
            $content->input_text = $response_data['input_text'];
            $content->output_text = $response_data['output_text'];
            $content->model = $response_data['model'];
            $content->tokens = $response_data['tokens'];
            $content->words = $response_data['words'];
            $content->temperature = $response_data['temperature'];
            $content->template_id = $response_data['template_id'];
            $content->lang = $response_data['lang'];
            $content->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateData($request)
    {

        try {
            $content = $this->model->find($request['id']);
            $content->output_text = $request['output'];
            $content->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function distroyData($id)
    {
        try {
            $content = $this->model->find($id);
            $content->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}
