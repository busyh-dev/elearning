<!DOCTYPE html>
<html>

<head>
    <title>Scorm Player</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{asset('public/js/common.js')}}"></script>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <style>
        iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>
    <script type="application/javascript">
        @if($user)
        window.full_name = "{{$user->name}}";
        window.course_name = "{{ $course->title}}";
        @if(isModuleActive('Org'))
        window.org_chart_name = "{{$user->branch->group}}";
        @endif
        @else
        window.full_name = "Guest";
        window.course_name = "{{ $course->title}}";
        window.org_chart_name = "";
        @endif
    </script>

    @if($lesson)

    @if($lesson->host=='XAPI')
    <iframe id="video-id" class="video_iframe"
        src="{{asset($lesson->video_url)}}?actor=%7B%22mbox%22%3A%22mailto%3A{{Settings('email')}}%22%2C%22name%22%3A%22{{Settings('site_title')}}%22%2C%22objectType%22%3A%22Agent%22%7D&amp;endpoint={{url('xapi')}}&amp;course_id={{$course->id}}&amp;lesson_id={{$lesson->id}}&amp;user_id=@if($user){{$user->id}}@endif"></iframe>
    @else
    @if ($lesson->host=='SCORM' || $lesson->host=='SCORM-AwsS3')
    @if(!empty($lesson->video_url))

    <iframe class=" video_iframe" id="video-id"
        src=""
        @if($lesson->scorm_version=="scorm_12")
        onbeforeunload="API.LMSFinish('');"
        onunload="API.LMSFinish('');"
        @endif
        ></iframe>

    @endif
    @endif

    @endif
    @endif

    @if ($lesson->host=='SCORM' || $lesson->host=='SCORM-AwsS3' || $lesson->host=='XAPI' || $lesson->host=='XAPI-AwsS3')
    <script>
        let video_element = $('#video-id');
        let url = "{{asset($lesson->video_url)}}";

        let course_id = {
            {
                $course-> id
            }
        };

        console.log(document.getElementById("video-id").src);

        @if($lesson->scorm_version == "scorm_12")

        var API = {};

        (function($) {
            $(document).ready(function() {
                setupScormApi()
                video_element.attr('src', url)
            });

            function setupScormApi() {
                API.LMSInitialize = LMSInitialize;
                API.LMSGetValue = LMSGetValue;
                API.LMSSetValue = LMSSetValue;
                API.LMSCommit = LMSCommit;
                API.LMSFinish = LMSFinish;
                API.LMSGetLastError = LMSGetLastError;
                API.LMSGetDiagnostic = LMSGetDiagnostic;
                API.LMSGetErrorString = LMSGetErrorString;
            }

            function LMSInitialize(initializeInput) {
                displayLog("LMSInitialize: " + initializeInput);
                return true;
            }

            function LMSGetValue(varname) {
                console.log("LMSGetValue richiesto per: " + varname);
                if (varname === "cmi.core.lesson_location") {
                    let savedLocation = localStorage.getItem("scorm_lesson_location");
                    console.log("Restituisco la posizione salvata: " + savedLocation);
                    return savedLocation || "";
                }

                displayLog("LMSGetValue: " + varname);
                // return varname;
                return "";
            }

            function LMSSetValue(varname, varvalue) {
                console.log("SCORM LMSSetValue chiamato con", varname, varvalue);
                console.log("LMSSetValue: " + varname + " = " + varvalue);

                // Salva la posizione della lezione
                if (varname === "cmi.core.lesson_location") {
                    localStorage.setItem("scorm_lesson_location", varvalue);
                }
                if (varvalue == 'completed' || varvalue == 'passed') {
                    console.log("Stato completato, chiamata lessonAutoComplete");
                    lessonAutoComplete(course_id, {
                        {
                            showPicName(Request::url())
                        }
                    });
                }
                // displayLog("LMSSetValue: " + varname + "=" + varvalue);
                // Invia al backend per persistenza
                updateScormReport(varname, varvalue);
                return "true";
            }

            function LMSCommit(commitInput) {
                displayLog("LMSCommit: " + commitInput);
                return true;
            }

            function LMSFinish(finishInput) {
                lessonAutoComplete(course_id, {
                    {
                        showPicName(Request::url())
                    }
                });
                displayLog("LMSFinish: " + finishInput);
                return true;
            }

            function LMSGetLastError() {
                displayLog("LMSGetLastError: ");
                return 0;
            }

            function LMSGetDiagnostic(errorCode) {
                displayLog("LMSGetDiagnostic: " + errorCode);
                return "";
            }

            function LMSGetErrorString(errorCode) {
                displayLog("LMSGetErrorString: " + errorCode);
                return "";
            }

        })(jQuery);


        @elseif($lesson->scorm_version == "scorm_2004")

        var API_1484_11 = {};

        (function($) {
            $(document).ready(function() {
                setupScormApi();
                video_element.attr('src', url);
            });

            function setupScormApi() {
                API_1484_11.Initialize = Initialize;
                API_1484_11.Commit = Commit;
                API_1484_11.Terminate = Terminate;
                API_1484_11.GetValue = GetValue;
                API_1484_11.SetValue = SetValue;
                API_1484_11.GetErrorString = GetErrorString;
                API_1484_11.GetDiagnostic = GetDiagnostic;
                API_1484_11.GetLastError = GetLastError;
            }

            function Initialize(parameter) {
                displayLog('Initialize ' + parameter)
                return true
            }

            function Commit(parameter) {
                displayLog('Commit ' + parameter)
                return true
            }

            function Terminate(parameter) {
                {
                    {
                        --lessonAutoComplete(course_id, {
                            {
                                showPicName(Request::url())
                            }
                        });
                        --
                    }
                }
                displayLog('Terminate ' + parameter)
                return true
            }

            function GetValue(name) {
                displayLog('GetValue ' + name)
                console.log("SCORM GetValue richiesto per: " + name);
                if (name === "cmi.location") {
                    let savedLocation = localStorage.getItem("scorm_lesson_location");
                    console.log("Restituisco la posizione salvata: " + savedLocation);
                    return savedLocation || "";
                }
                return "";
            }

            function SetValue(name, value) {
                console.log("SCORM SetValue: " + name + " = " + value);
                updateScormReport(name, value);
                // Salva la posizione della lezione
                if (name === "cmi.location") {
                    localStorage.setItem("scorm_lesson_location", value);
                }

                if (value == 'completed' || value == 'passed') {
                    lessonAutoComplete(course_id, {
                        {
                            showPicName(Request::url())
                        }
                    });
                }
                displayLog('SetValue ' + name + ' = ' + value)
                return true
            }

            function GetErrorString() {
                displayLog('GetErrorString')
                return ''
            }

            function GetDiagnostic() {
                displayLog('GetDiagnostic')
                return ''
            }

            function GetLastError() {
                displayLog('GetLastError')
                return 0
            }


        })(jQuery);


        @endif


        function displayLog(textToDisplay) {
            console.log(textToDisplay);
        }
        @if(isModuleActive('SCORM'))

        function updateScormReport(key, value) {
            console.log('Updating SCORM report', key, value); // Log per il debug
            @if(!isset($lesson->completed->status))
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var course_id = "{{$course->id}}";
            var lesson_id = "{{$lesson->id}}";
            var url = "{{ url('/scorm/report/store') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    course_id: course_id,
                    lesson_id: lesson_id,
                    key: key,
                    value: value
                },
                success: function(data) {
                    console.log("Scorm report updated:", data);
                    // Dopo l'aggiornamento, recupera il progresso
                    getScormProgress(course_id, lesson_id);
                },
                error: function(xhr, status, error) {
                    console.error("Error updating scorm report:", error);
                }
            });
            @endif
        }

        function getScormProgress(course_id, lesson_id) {
            $.ajax({
                type: 'GET',
                url: '{{ route("scorm.report.getProgress") }}',
                data: {
                    course_id: course_id,
                    lesson_id: lesson_id
                },
                success: function(response) {
                    console.log("Scorm progress retrieved:", response.lesson_location);
                    // Aggiorna la cache globale di SCORM con la posizione salvata
                    if (response.lesson_location !== undefined) {
                        GB_SCORM.cache["cmi.core.lesson_location"] = response.lesson_location;
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error retrieving scorm progress:", error);
                }
            });
        }


        // function updateScormReport(key, value) {
        //     @if(!isset($lesson - > completed - > status))
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     var course_id = "{{$course->id}}";
        //     var lesson_id = "{{$lesson->id}}";
        //     $.ajax({
        //         type: 'POST',
        //         url: '{{route('
        //         scorm.report.store ')}}',
        //         data: {
        //             course_id: course_id,
        //             lesson_id: lesson_id,
        //             key: key,
        //             value: value
        //         },
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     });
        //     @endif


        // }

        @endif
    </script>
    @endif
    <script>
        function lessonAutoComplete(course_id, lesson_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                "_token": "{{ csrf_token() }}",
                url: '{{route("lesson.complete.ajax")}}',
                data: {
                    course_id: course_id,
                    lesson_id: lesson_id
                },
                success: function(data) {
                    console.log("Success: " + data);
                },
                error: function(data) {
                    console.log("Error: " + data);
                }
            });
        }
    </script>

    @if ($lesson->host=='XAPI' || $lesson->host=='XAPI-AwsS3')
    <script>
        @if(!isset($lesson->completed->status))

        function checkCompleteStatus() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var course_id = "{{$course->id}}";
            var lesson_id = "{{$lesson->id}}";
            $.ajax({
                type: 'POST',
                url: '{{route("xapi.checkLessonStatus")}}',
                data: {
                    course_id: course_id,
                    lesson_id: lesson_id
                },
                success: function(data) {
                    if (data == 1) {
                        console.log("xapi-complete");
                    } else {
                        console.log("not");

                    }
                }
            });
        }

        setInterval(checkCompleteStatus, 2000)
        @endif
    </script>
    @endif
</body>

</html>