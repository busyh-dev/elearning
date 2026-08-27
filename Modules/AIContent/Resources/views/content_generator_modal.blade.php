<link rel="stylesheet" href="{{ asset('Modules\AIContent\Resources\assets\css\ai_content.css') }}">

<div class="modal fade admin-query" id="ai_text_generation_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ _trans('ai-content.AI Text Generation') }}</h4>
                <button type="button" class="close " data-bs-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ai-content.generate_text') }}" method="POST" id="text_generator_form"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label" for="">{{ __('common.Language') }}
                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="language" id="language">
                                    <option data-display="{{ __('common.Select') }} {{ __('common.Language') }}"
                                            value="">{{ __('common.Select') }} {{ __('common.Language') }} </option>
                                    @foreach (getLanguageList() as $key => $language)
                                        <option value="{{$language->name}}"
                                                @if (Settings('ai_default_language') == $language->name) selected @endif>
                                            {{ $language->native }}</option>
                                    @endforeach

                                </select>
                                <small class="text-danger" id="language_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label" for="">{{ _trans('ai-content.Template') }}
                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="template" id="ai_template">
                                    <option
                                        data-display="{{ __('common.Select') }} {{ _trans('ai-content.Template') }}"
                                        value="">{{ __('common.Select') }} {{ _trans('ai-content.Template') }}
                                    </option>
                                    @foreach (app()->ai_templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach

                                </select>
                                <small class="text-danger" id="template_error_message"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row collapse" id="AdvanceOption">

                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                       for="">{{ _trans('ai-content.Tone') }}<strong
                                        class="text-danger">*</strong></label>
                                <select class="primary_select" name="tone" id="tone">
                                    <option data-display="{{ __('common.Select') }} {{ _trans('ai-content.Tone') }}"
                                            value="">{{ __('common.Select') }} {{ _trans('ai-content.Tone') }}
                                    </option>
                                    @foreach (Modules\AIContent\Entities\Enums\AIModels::AI_TONES as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ Settings('ai_default_tone') == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach

                                </select>
                                <small class="text-danger" id="tone_error_message"></small>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{ _trans('ai-content.AI Creativity') }}</label>
                                <select class="primary_select mb-25" name="creativity" id="creativity">
                                    @foreach (Modules\AIContent\Entities\Enums\AIModels::AI_CREATIVITY as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ Settings('ai_default_creativity') == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{ _trans('ai-content.Number of Results') }}</label>
                                <select class="primary_select mb-25" name="number_of_result" id="number_of_result">
                                    @for ($i = 1; $i < 11; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{ _trans('ai-content.Max Result Length') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="max_result_length" id="max_result_length"
                                       placeholder="{{ _trans('ai-content.max_result') }}"
                                       value="{{ Settings('ai_max_result_length') }}" min="10"
                                       max="{{ Settings('ai_max_result_length') }}" type="number">
                                <small class="text-danger" id="max_result_error_message"></small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ _trans('ai-content.Keyword') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="keyword" id="keyword"
                                       placeholder="{{ _trans('ai-content.Keyword') }}" value="" type="text">
                                <small>{{ _trans('ai-content.You can add multiple keywords use comma(,) to separate keyword') }}</small>
                                <small class="text-danger" id="keyword_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none" id="titleDiv">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ _trans('ai-content.Title') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="title" id="title"
                                       placeholder="{{ _trans('ai-content.Title') }}" value="" type="text">
                                <small class="text-danger" id="title_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-end">

                            {{ _trans('ai-content.Show Advanced Options') }}
                            <button class="primary-btn radius_30px ml-10 fix-gr-bg extraBtn"
                                    id="ai_advance_section_collapse" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#AdvanceOption" aria-expanded="false" aria-controls="AdvanceOption">
                                <i class="fa fa-angle-down" id="ai_advance_icon"></i>
                            </button>

                        </div>
                    </div>
                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="generate_content" type="button">
                                <i class="fa fa-robot" id="show_ai_icon"></i>
                                {{ _trans('ai-content.Generate') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row align-center">
                    <div class="col-lg-12">
                        <span class="text-danger" id="error_message">
                        </span>
                    </div>
                    <div class="col-lg-12" id="generation_result" style="display: block">

                        <div class="primary_input mb-25">
                            <label class="primary_input_label"
                                   for="">{{ _trans('ai-content.Generated Content') }}</label>
                            <div class="" id="generated_result">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('Modules/AIContent/Resources/assets/js/ai_content.js') }}"></script>
    <script>
        $(document).on('click', '#show_ai_text_generator', function () {
            var selected_template = $(this).data('selected_template');
            var ai_template = $('#ai_template');
            if (selected_template) {
                ai_template.val(selected_template);
                $('#ai_template').niceSelect('update');
            }
            $("#ai_text_generation_modal").modal('show');
        });

        $(document).on('change', '#ai_template', function (e) {
            let templateId = $(this).val();
            if (templateId == 1 || templateId == 11) {
                $('#titleDiv').addClass('d-none');
            } else {
                $('#titleDiv').removeClass('d-none');

            }
        });
    </script>
@endpush
