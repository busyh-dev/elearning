<?php

namespace Modules\AIContent\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\AIContent\Entities\Enums\AIModels;

class AISettingsController extends Controller
{
    public function index()
    {

        $data['title'] = 'AI Content Settings';
        $data['ai_models'] = AIModels::OPEN_AI_MODELS;
        $data['ai_tones'] = AIModels::AI_TONES;
        $data['languages'] = Language::where('status', 1)->get();
        return view('aicontent::settings.index', compact('data'));
    }

    public function store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $request = $request->except('_token');
            foreach ($request as $key => $value) {
                if ($key == 'open_ai_secrete_key') {
                    putEnvConfigration('OPENAI_SECRET_KEY', $value);
                    UpdateGeneralSetting($key, $value);
                } else {
                    UpdateGeneralSetting($key, $value);
                }
            }
            Toastr::success(_trans('ai-content.AI setup has been updated Successfully'), trans('common.Success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            GettingError($th->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }


}
