@if ($lesson->host == 'XAPI' || $lesson->host == 'XAPI-AwsS3')
    <script>
        @if (!isset($lesson->completed->status))
        console.log("Status è nullo o non esiste");

        function checkCompleteStatus() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var course_id = "{{ $course->id }}";
            var lesson_id = "{{ $lesson->id }}";

            $.ajax({
                type: 'POST',
                url: '{{ route('xapi.checkLessonStatus') }}',
                data: {
                    course_id: course_id,
                    lesson_id: lesson_id
                },
                success: function (data) {
                    if (data == 1) {
                        if ($('#autoNext').is(':checked')) {
                            if ($('#next_lesson_btn').length) {
                                jQuery('#next_lesson_btn').click();
                            } else {
                                location.reload();
                            }
                        }
                    }
                }
            });
        }

        setInterval(checkCompleteStatus, 2000);
        @endif
    </script>
@endif

@if (
    $lesson->host == 'SCORM' ||
    $lesson->host == 'SCORM-AwsS3' ||
    $lesson->host == 'XAPI' ||
    $lesson->host == 'XAPI-AwsS3'
)
    <script>
        let video_element = $('#video-id');
        let url = "{{ asset($lesson->video_url) }}";
        let LESSON_ID = "{{$lesson->id}}";
        @auth
        let full_name = "{{ auth()->user()->name }}";
        @if (isModuleActive('Org'))
        let org_chart_name = "{{ auth()->user()->branch->group }}";
        @endif
        @endauth
        @guest()
        let full_name = "Guest";
        let org_chart_name = "";
        @endguest
        let course_name = "{{ $course->title }}";

        @if ($lesson->scorm_version == 'scorm_12')

        var API = {};

        (function ($) {
            $(document).ready(function () {
                setupScormApi12();
                loadScormProgress();
                video_element.attr('src', url);
                window.addEventListener('unload', saveScormProgress);
                window.addEventListener('beforeunload', saveScormProgress);
            });

            function setupScormApi12() {
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
                loadScormProgress();
                saveScormProgress();
                displayLog("LMSInitialize: " + initializeInput);
                return true;
            }

            function LMSGetValue(varname) {
                var score = API.LMSGetValue("cmi.core.score.raw");
                displayLog("Punteggio utente: " + score);
                displayLog("LMSGetValue: " + varname);
                return varname;
            }

            function LMSSetValue(varname, varvalue) {
                updateScormReport(varname, varvalue);
                saveScormProgress();
                var score = API.LMSGetValue("cmi.core.score.raw");
                API.LMSSetValue("cmi.core.score.raw", score); // Imposta un punteggio di 85
                if (varvalue == 'completed' || varvalue == 'passed') {
                    lessonAutoComplete(course_id, {{ showPicName(Request::url()) }});
                }
                return "";
            }

            function LMSCommit(commitInput) {
                saveScormProgress();
                displayLog("LMSCommit: " + commitInput);
                return true;
            }

            function LMSFinish(finishInput) {
                saveScormProgress();
                lessonAutoComplete(course_id, {{ showPicName(Request::url()) }});
                updateProgressBar();  // Aggiungi questa riga per aggiornare la barra alla fine della lezione
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

            function saveScormProgress() {
    var lessonLocation = API.LMSGetValue('cmi.location');
    var suspendData = API.LMSGetValue('cmi.suspend_data');
    
    // if (lessonLocation && suspendData) {
    //     console.log("Dati inviati per il salvataggio:", {
    //         user_id: "{{ auth()->user()->id }}",
    //         course_id: "{{ $course->id }}",
    //         lesson_id_lms_id: LESSON_ID,
    //         date_hour: new Date().toISOString(),
    //         contest: "SCORM",
    //         component: "Tracking",
    //         event: "Progress Update",
    //         description: "User completed SCORM lesson",
    //         orgin: "LMS",
    //         ip_url: window.location.href,
    //         lesson_location: lessonLocation,
    //         suspend_data: suspendData,
    //         completion_status: API.GetValue("cmi.completion_status")
    //     });

    console.log("Dati per il salvataggio:", {
    user_id: "{{ auth()->user()->id }}",
    course_id: "{{ $course->id }}",
    lesson_id_lms_id: LESSON_ID,
    date_hour: new Date().toISOString(),
    contest: "SCORM",
    component: "Tracking",
    event: "Progress Update",
    description: "User completed SCORM lesson",
    origin: "LMS",
    ip_url: window.location.href,
    lesson_location: lessonLocation,
    suspend_data: suspendData,
    completion_status: API.GetValue("cmi.completion_status")
});

        fetch("{{url('/scorm/save-progress')}}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify({
                user_id: "{{ auth()->user()->id }}",
                course_id: "{{ $course->id }}",
                lesson_id_lms_id: LESSON_ID,
                date_hour: new Date().toISOString(),
                contest: "SCORM",
                component: "Tracking",
                event: "Progress Update",
                description: "User completed SCORM lesson",
                orgin: "LMS",
                ip_url: window.location.href,
                lesson_location: lessonLocation,
                suspend_data: suspendData,
                completion_status: API.LMSGetValue("cmi.completion_status")
            })
        }).then(response => response.json())
        .then(data => console.log('Progress saved:', data))
        .catch(error => console.log('Error saving progress:', error));
    }
// }


            // function saveScormProgress() {
            //     var lessonLocation = API_1484_11.GetValue('cmi.location');
            //     var suspendData = API_1484_11.GetValue('cmi.suspend_data');

            //     if (lessonLocation && suspendData) {
            //         fetch("{{url('/scorm/save-progress')}}", {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //             },
            //             body: JSON.stringify({
            //                 lesson_id: LESSON_ID,
            //                 lesson_location: lessonLocation,
            //                 suspend_data: suspendData
            //             })
            //         }).then(response => response.json())
            //             .then(data => displayLog('Progress saved'))
            //             .catch(error => displayLog('Error saving progress: ' + error));
            //     }
            // }
            

            function loadScormProgress() {
    fetch("{{ url('/scorm/get-progress/'.$lesson->id) }}")
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! Status1: ' + response.status);
            }
            return response.text(); // Leggiamo il testo per debug
        })
        .then(text => {
            console.log('Raw response:', text); // Stampa la risposta ricevuta
            let data = JSON.parse(text); // Converte in JSON
            if (data.lesson_location && data.suspend_data) {
                API.SetValue('cmi.location', data.lesson_location);
                API.SetValue('cmi.suspend_data', data.suspend_data);
                console.log('Progress loaded:', data);
            } else {
                console.log('No previous progress found.');
            }
        })
        .catch(error => console.log('Error loading progress:', error));
}

            

            // function loadScormProgress() {
            //     fetch("{{url('/scorm/get-progress/'.$lesson->id)}}")
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data && data.lesson_location && data.suspend_data) {
            //                 API_1484_11.SetValue('cmi.location', data.lesson_location);
            //                 API_1484_11.SetValue('cmi.suspend_data', data.suspend_data);
            //                 displayLog('Progress loaded');
            //             }
            //         }).catch(error => displayLog('Error loading progress: ' + error));
            // }


        })(jQuery);

        @elseif ($lesson->scorm_version == 'scorm_2004')

        var API_1484_11 = {};

        (function ($) {
            $(document).ready(function () {
                setupScormApi();
                video_element.attr('src', url);

                window.addEventListener('unload', saveScormProgress12);
                window.addEventListener('beforeunload', saveScormProgress12);
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
                loadScormProgress12();
                saveScormProgress12();
                displayLog('Initialize ' + parameter);
                return true;
            }

            function Commit(parameter) {
                displayLog('Commit ' + parameter);
                saveScormProgress12();
                return true;
            }

            function Terminate(parameter) {
                displayLog('Terminate ' + parameter);                
                saveScormProgress12();
                updateProgressBar();  // Aggiungi questa riga per aggiornare la barra alla fine della lezione
                return true;
            }

            function GetValue(name) {
                displayLog('GetValue ' + name);
                return localStorage.getItem('scorm_' + LESSON_ID + '_' + name) || "";
            }

            function SetValue(name, value) {
                localStorage.setItem('scorm_' + LESSON_ID + '_' + name, value);
                var score = API_1484_11.SetValue("cmi.core.score.raw");
                API_1484_11.SetValue("cmi.core.score.raw", score); // Imposta un punteggio di 85
                updateScormReport(name, value);               
                API_1484_11.SetValue('cmi.location', data.lesson_location);
                API_1484_11.SetValue('cmi.suspend_data', data.suspend_data);
                if (value == 'completed' || value == 'passed') {
                    lessonAutoComplete(course_id, {{ showPicName(Request::url()) }});
                }
                displayLog('SetValue ' + name + ' = ' + value);
                saveScormProgress12();
                return true;
            }

            function GetErrorString() {
                displayLog('GetErrorString');
                return '';
            }

            function GetDiagnostic() {
                displayLog('GetDiagnostic');
                return '';
            }

            function GetLastError() {
                displayLog('GetLastError');
                return 0;
            }

            function saveScormProgress12() {
           
    var lessonLocation = API_1484_11.GetValue('cmi.location');
    var suspendData = API_1484_11.GetValue('cmi.suspend_data');
    
    
    // if (lessonLocation && suspendData) {
    //     console.log("Dati inviati per il salvataggio:", {
    //         user_id: "{{ auth()->user()->id }}",
    //         course_id: "{{ $course->id }}",
    //         lesson_id_lms_id: LESSON_ID,
    //         date_hour: new Date().toISOString(),
    //         contest: "SCORM",
    //         component: "Tracking",
    //         event: "Progress Update",
    //         description: "User completed SCORM lesson",
    //         orgin: "LMS",
    //         ip_url: window.location.href,
    //         lesson_location: lessonLocation,
    //         suspend_data: suspendData,
    //         completion_status: API_1484_11.GetValue("cmi.completion_status")
    //     });

    console.log("Dati per il salvataggio:", {
    user_id: "{{ auth()->user()->id }}",
    course_id: "{{ $course->id }}",
    lesson_id_lms_id: LESSON_ID,
    date_hour: new Date().toISOString(),
    contest: "SCORM",
    component: "Tracking",
    event: "Progress Update",
    description: "User completed SCORM lesson",
    origin: "LMS",
    ip_url: window.location.href,
    lesson_location: lessonLocation,
    suspend_data: suspendData,
    completion_status: API.GetValue("cmi.completion_status")
});

        fetch("{{url('/scorm/save-progress')}}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify({
                user_id: "{{ auth()->user()->id }}",
                course_id: "{{ $course->id }}",
                lesson_id_lms_id: LESSON_ID,
                date_hour: new Date().toISOString(),
                contest: "SCORM",
                component: "Tracking",
                event: "Progress Update",
                description: "User completed SCORM lesson",
                orgin: "LMS",
                ip_url: window.location.href,
                lesson_location: lessonLocation,
                suspend_data: suspendData,
                completion_status: API_1484_11.GetValue("cmi.completion_status")
            })
        }).then(response => response.json())
        .then(data => console.log('Progress saved:', data))
        .catch(error => console.log('Error saving progress:', error));
    }
// }

            function loadScormProgress12() {
    let lessonId = "{{$lesson->id}}";
    let courseId = "{{$course->id}}";

    fetch(`/scorm/get-progress?lesson_id=${lessonId}&course_id=${courseId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nel caricamento dei progressi: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.lesson_location) {
                API_1484_11.SetValue('cmi.location', data.lesson_location);
                console.log('Progress loaded:', data.lesson_location);
            } else {
                console.log('Nessun progresso trovato.');
            }
        })
        .catch(error => console.error('Errore nel caricamento dei progressi:', error));
}

            // function loadScormProgress() {
            //     fetch("{{url('/scorm/get-progress/'.$lesson->id)}}")
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data && data.lesson_location && data.suspend_data) {
            //                 API_1484_11.SetValue('cmi.location', data.lesson_location);
            //                 API_1484_11.SetValue('cmi.suspend_data', data.suspend_data);
            //                 displayLog('Progress loaded');
            //             }
            //         }).catch(error => displayLog('Error loading progress: ' + error));
            // }

        })(jQuery);
        @endif

        function displayLog(textToDisplay) {
            // console.log(textToDisplay);
        }

        @if (isModuleActive('SCORM'))
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function updateScormReport(key, value) {
    @if (!isset($lesson->completed->status))
    console.log("Status è nullo o non esiste");

    var course_id = "{{ $course->id }}";
    var lesson_id = "{{ $lesson->id }}";
    var url = "{{ url('/scorm/report/store') }}";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            course_id: course_id,
            lesson_id: lesson_id,
            key: key,
            value: value,
            _token: $('meta[name="csrf-token"]').attr('content') // Includi il token CSRF
        },
        success: function (data) {
            console.log(data);  // Controlla cosa restituisce il controller
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
    @endif
}

        @endif
    </script>
@endif
