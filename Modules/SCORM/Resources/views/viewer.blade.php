<iframe class="video_iframe" id="video-id" height="100%" width="100%"
        src=""
></iframe>
<script src="{{asset('public/js/common.js')}}"></script>
<script>
    let video_element = $('#video-id');
    let url = "{{asset($link)}}";
    let progressBar = $('#progress-bar'); // Elemento della barra di progresso


    @if($version=="scorm_12")
    var API = {};

    (function ($) {
        $(document).ready(function () {
            setupScormApi()
            video_element.attr('src', url)
            loadScormProgress(); // Carica lo stato precedente del progresso
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
            displayLog("LMSGetValue: " + varname);
            return "";
        }

        function LMSSetValue(varname, varvalue) {
            displayLog("LMSSetValue: " + varname + "=" + varvalue);
            if (varname === "cmi.core.lesson_status" && varvalue === "completed") {
                updateProgressBar(100); // Aggiorna la barra di progresso a 100%
                saveScormProgress('completed'); // Salva lo stato SCORM come "completed"
                updateScormReport(); // Aggiorna il report SCORM
            }
            return "";
        }

        function LMSCommit(commitInput) {
            displayLog("LMSCommit: " + commitInput);
            return true;
        }

        function LMSFinish(finishInput) {
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

        // Funzione per aggiornare la barra di progresso
        function updateProgressBar(percent) {
            progressBar.css('width', percent + '%'); // Modifica la larghezza della barra
        }

        // Funzione per salvare il progresso SCORM (ad esempio nel database)
        function saveScormProgress(status) {
            $.ajax({
                url: '/save-scorm-progress', // Endpoint dove salvi il progresso
                method: 'POST',
                data: {
                    status: status,
                    user_id: {{ Auth::user()->id }},
                    course_id: {{ $course_id }},
                    lesson_id: {{ $lesson_id }},
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("Progressi salvati:", response);
                },
                error: function (error) {
                    console.error("Errore nel salvataggio del progresso:", error);
                }
            });
        }

        // Funzione per aggiornare il report SCORM
        function updateScormReport() {
            $.ajax({
                url: '/update-scorm-report', // Endpoint per aggiornare il report
                method: 'POST',
                data: {
                    user_id: {{ Auth::user()->id }},
                    course_id: {{ $course_id }},
                    lesson_id: {{ $lesson_id }},
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("Report SCORM aggiornato:", response);
                },
                error: function (error) {
                    console.error("Errore nell'aggiornamento del report:", error);
                }
            });
        }

        // Funzione per caricare lo stato del progresso
        function loadScormProgress() {
            $.ajax({
                url: '/load-scorm-progress', // Endpoint per caricare lo stato del progresso
                method: 'GET',
                data: {
                    user_id: {{ Auth::user()->id }},
                    course_id: {{ $course_id }},
                    lesson_id: {{ $lesson_id }},
                },
                success: function (response) {
                    if (response.progress) {
                        updateProgressBar(response.progress); // Aggiorna la barra con il progresso caricato
                    }
                },
                error: function (error) {
                    console.error("Errore nel caricare il progresso:", error);
                }
            });
        }

    })(jQuery);
    @else

    var API_1484_11 = {};

    (function ($) {
        $(document).ready(function () {
            setupScormApi();
            video_element.attr('src', url)
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
            displayLog('Terminate ' + parameter)
            return true
        }

        function GetValue(name) {
            displayLog('GetValue ' + name)
            return "";
        }

        function SetValue(name, value) {
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
    }
</script>
