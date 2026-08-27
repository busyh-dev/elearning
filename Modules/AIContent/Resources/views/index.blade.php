@extends('backend.master')
@php
    $table_name='categories';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')
    @include("backend.partials.alertMessage")
    @php
        $LanguageList = getLanguageList();
    @endphp
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex flex-wrap mb-0">
                                <h3 class="mb-0">{{_trans('ai-content.Generated Content List')}}</h3>
                            </div>
                        </div>
                        <div class="  QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table ">
                                <!-- table-responsive -->
                                <div class="">
                                    <table id="lms_table" class="table table-data">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ _trans('ai-content.SL') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Template') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Input') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Output') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Model') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Tokens') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Words') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Temperature') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Lang') }}</th>
                                            <th scope="col">{{ _trans('ai-content.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contents as $key => $content)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{@$content->template->name}}</td>
                                                <td>{{$content->input_text}}</td>
                                                <td>{!! $content->output_text !!}</td>
                                                <td>
                                                    {{Modules\AIContent\Entities\Enums\AIModels::OPEN_AI_MODELS[$content->model]??''}}
                                                </td>
                                                <td>{{$content->tokens}}</td>
                                                <td>{{$content->words}}</td>
                                                <td>
                                                    {{Modules\AIContent\Entities\Enums\AIModels::AI_CREATIVITY[$content->temperature]??''}}
                                                </td>
                                                <td>{{$content->lang}}</td>


                                                <td>
                                                    <!-- shortby  -->
                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="dropdownMenu1{{@$category->id}}"
                                                                data-bs-toggle="dropdown"
                                                                aria-haspopup="true"
                                                                aria-expanded="false">
                                                            {{ __('common.Select') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                             aria-labelledby="dropdownMenu1{{@$content->id}}">
                                                            <a class="dropdown-item copy_output" href="#"
                                                               data-output="{{$content->output_text}}">{{__('common.Copy')}}</a>
                                                            <a class="dropdown-item edit_output" href="#"
                                                               data-output="{{$content->output_text}}"
                                                               data-id="{{$content->id}}">{{__('common.Edit')}}</a>
                                                            <a onclick="confirm_modal('{{route('ai-content.delete', $content->id)}}');"
                                                               class="dropdown-item edit_brand">{{__('common.Delete')}}</a>
                                                        </div>
                                                    </div>
                                                    <!-- shortby  -->
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade admin-query" id="edit_output_modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ _trans('ai-content.Update AI response') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{route('ai-content.update')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="ai_output_id" value="">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="primary_input mb-35">
                                    <label class="primary_input_label" for="">{{_trans('ai-content.AI Output')}} <span
                                            class="text-danger">*</span> </label>
                                    <textarea class="lms_summernote" name="output" id="ai_output" cols="30"
                                              rows="10">{{ old('output') }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center pt_15">
                                <div class="d-flex justify-content-center">
                                    <button class="primary-btn semi_large2  fix-gr-bg"
                                            type="submit"><i
                                            class="ti-check"></i> {{__('common.Update')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="status_route" class="status_route" value="{{ route('course.category.status_update') }}">
    @include('backend.partials.delete_modal')
@endsection
@push('scripts')
    <script src="{{asset('public/backend/js/category.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.copy_output', function (e) {
                e.preventDefault();
                var output = $(this).data('output');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(output).select();
                document.execCommand("copy");
                $temp.remove();
                toastr.success('Copied');
            });

            $('.edit_output').on('click', function (e) {
                e.preventDefault();
                var output = $(this).data('output');
                var id = $(this).data('id');
                $('#edit_output_modal').modal('show');
                $('#ai_output').summernote("code", output);
                $('#edit_output_modal').find('#ai_output_id').val(id);
            });
        });
    </script>
@endpush
