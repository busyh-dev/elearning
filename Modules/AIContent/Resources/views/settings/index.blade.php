@extends('backend.master')

@section('mainContent')
    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- tab-content  -->
                            <div class="tab-content " id="myTabContent">
                                <!-- General -->
                                <div class="tab-pane fade white-box show active" id="Activation" role="tabpanel"
                                     aria-labelledby="Activation-tab">
                                    <div class="main-title mb-25">
                                        <div class="main-title mb-25">
                                            <h3 class="mb-0">{{ _trans('ai-content.Open AI Setup') }}</h3>
                                        </div>
                                        @if (permissionCheck('ai-content.update_settings'))
                                            <form action="{{ route('ai-content.update_settings') }}" method="POST"
                                                  enctype="multipart/form-data">
                                                @endif
                                                @csrf
                                                <div class="row">
                                                    <div class="col-xl-6">

                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.Open AI Model') }}
                                                                <small>
                                                                    <a href="https://platform.openai.com/docs/models">Check Available Models</a>
                                                                    <span>(Use can use gpt model for generate content)</span>
                                                                </small>
                                                            </label>
                                                            <input class="primary_input_field" placeholder="gpt-4"
                                                                   type="text"
                                                                   id="ai_default_model" name="ai_default_model"
                                                                   value="{{ Settings('ai_default_model') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.AI Default Language') }}</label>
                                                            <select class="primary_select mb-25"
                                                                    name="ai_default_language"
                                                                    id="ai_default_language">
                                                                {{-- @foreach (app()->languages as $language) --}}
                                                                @foreach (getLanguageList() as $key => $language)
                                                                    <option value="{{$language->name}}"
                                                                            @if (Settings('ai_default_language') == $language->name) selected @endif>
                                                                        {{ $language->native }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.AI Tones') }}</label>
                                                            <select class="primary_select mb-25" name="ai_default_tone"
                                                                    id="ai_default_tone">
                                                                @foreach ($data['ai_tones'] as $key => $ai_model)
                                                                    <option value="{{ $key }}"
                                                                            @if (Settings('ai_default_tone') == $key) selected @endif>
                                                                        {{ $ai_model }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.Max Result Length') }}</label>
                                                            <input class="primary_input_field" placeholder="200"
                                                                   type="number"
                                                                   id="ai_max_result_length" name="ai_max_result_length"
                                                                   value="{{ Settings('ai_max_result_length')??200 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.AI Creativity') }}</label>
                                                            <select class="primary_select mb-25"
                                                                    name="ai_default_creativity"
                                                                    id="ai_default_creativity">
                                                                @foreach (Modules\AIContent\Entities\Enums\AIModels::AI_CREATIVITY as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{Settings('ai_default_creativity') == $key ? 'selected':''}}>{{ $value }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-xl-12">
                                                        <div class="primary_input mb-25">
                                                            <label class="primary_input_label"
                                                                   for="">{{ _trans('ai-content.OpenAI Secrete Key ') }}</label>
                                                            <input class="primary_input_field" placeholder="sk-"
                                                                   type="text"
                                                                   id="open_ai_secrete_key" name="open_ai_secrete_key"
                                                                   value="{{ Settings('open_ai_secrete_key') }}">
                                                        </div>
                                                    </div>

                                                </div>
                                                @php
                                                    $tooltip = '';
                                                    if (permissionCheck('settings.general_setting_update')) {
                                                        $tooltip = '';
                                                    } else {
                                                        $tooltip = 'You have no permission to add';
                                                    }
                                                @endphp
                                                <div class="submit_btn text-center mt-4">
                                                    <button class="primary-btn fix-gr-bg" type="submit"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $tooltip }}"><i class="ti-check"></i>
                                                        {{ __('common.Save') }}</button>
                                                </div>
                                            </form>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="">
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>
        </div> -->
    </section>
@endsection

@include('setting::page_components.script')
