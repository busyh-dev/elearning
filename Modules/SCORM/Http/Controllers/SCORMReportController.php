<?php

namespace Modules\SCORM\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Lesson;
use Modules\SCORM\Entities\ScormReport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class SCORMReportController extends Controller
{

//     public function storeScormReport(Request $request)
// {
//     // Convalida i dati in arrivo (opzionale, ma consigliato)
//     $request->validate([
//         'course_id' => 'required|integer',
//         'lesson_id' => 'required|integer',
//         'key' => 'required|string',
//         'value' => 'required|string',
//     ]);

//     // Crea un nuovo report SCORM
//     $scormReport = new ScormReport();
//     $scormReport->course_id = $request->course_id;
//     $scormReport->lesson_id = $request->lesson_id;
//     $scormReport->key = $request->key;
//     $scormReport->value = $request->value;
//     $scormReport->date_hour = now();  // Imposta la data e ora corrente

//     // Salva il report nel database
//     if ($scormReport->save()) {
//         return response()->json(['success' => 'Progress saved'], 200);
//     } else {
//         return response()->json(['error' => 'Failed to save progress'], 500);
//     }
// }

public function saveScormProgress(Request $request)
{
    try {
        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'lesson_id_lms_id' => 'required|integer',
            'key' => 'required|string',
            'value' => 'required|string',
            'date_hour' => 'required|date',
            'slide_number' => 'required|integer',
            'time_spent' => 'required|integer',
            'contest' => 'required|string',
            'component' => 'required|string',
            'event' => 'required|string',
            'description' => 'required|string',
            'orgin' => 'required|string',
            'ip_url' => 'nullable|url', // Reso nullable per evitare errori se il formato non è valido
            'cmi_core_score_raw' => 'nullable|string',
            'lesson_location' => 'nullable|string',
            'suspend_data' => 'nullable|string',
            'completion_status' => 'nullable|string',
        ]);

        // Log dei dati ricevuti
        \Log::info('📥 Dati ricevuti per il salvataggio SCORM:', $validated);

        // Creazione del report SCORM
        $report = ScormReport::create([
            'user_id' => $validated['user_id'],
            'course_id' => $validated['course_id'],
            'lesson_id' => $validated['lesson_id'],
            'lesson_id_lms_id' => $validated['lesson_id_lms_id'],
            'key' => $validated['key'],
            'value' => $validated['value'],
            'date_hour' => $validated['date_hour'],
            'slide_number' => $validated['slide_number'],
            'time_spent' => $validated['time_spent'],
            'contest' => $validated['contest'],
            'component' => $validated['component'],
            'event' => $validated['event'],
            'description' => $validated['description'],
            'orgin' => $validated['orgin'],
            'ip_url' => $validated['ip_url'] ?? request()->ip(), // Se `ip_url` non è valido, salva l'IP dell'utente
            'lesson_location' => $validated['lesson_location'] ?? null,
            'suspend_data' => $validated['suspend_data'] ?? null,
            'completion_status' => $validated['completion_status'] ?? null,
            'cmi_core_score_raw' => $validated['cmi_core_score_raw'] ?? null,
        ]);

        \Log::info('✅ Report SCORM salvato con successo:', $report->toArray());

        return response()->json([
            'message' => 'Progresso SCORM salvato con successo!',
            'data' => $report
        ]);
    } catch (\Exception $e) {
        \Log::error("❌ Errore nel salvataggio SCORM:", ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Errore nel salvataggio del progresso SCORM.',
            'error' => $e->getMessage()
        ], 500);
    }
}


//     public function updateScormReport(Request $request)
// {
//     // Ottieni i dati dalla richiesta
//     $course_id = $request->input('course_id');
//     $lesson_id = $request->input('lesson_id');
//     $key = $request->input('key');
//     $value = $request->input('value');

//     // Logica di salvataggio nel database
//     $report = new ScormReport();
//     $report->course_id = $course_id;
//     $report->lesson_id = $lesson_id;
//     $report->key = $key;
//     $report->value = $value;
//     $report->save();

//     return response()->json(['message' => 'Progress saved successfully']);
// }


    public function index()
    {
        return view('scorm::report.index');
    }
    

    public function details($id)
    {
        $enroll = CourseEnrolled::with('course.lessons')->find($id);
        $user_id = $enroll->user_id;
        $course = $enroll->course;
        $lessons = $course->lessons->where('host', 'SCORM');

        foreach ($lessons as $lesson) {
            $reports = ScormReport::where('user_id', $user_id)->where('lesson_id', $lesson->id)->get();
            $lesson->reports = $reports;
        }
        return view('scorm::report.details', compact('lessons', 'user_id', 'course'));
    }

    public function data()
    {


        $query = CourseEnrolled::select(['course_enrolleds.*'])
            ->with('course', 'user', 'course.lessons');

        $query->whereHas('course', function ($q) {
            return $q->where('type', 1);
        });

        $query->whereHas('course.lessons', function ($q) {
            return $q->where('host', 'SCORM');
        });

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('user.name', function ($query) {
                return $query->user->name;
            })
            ->editColumn('course.title', function ($query) {
                return $query->course->title;
            })
            ->addColumn('lesson', function ($query) {
                return count($query->course->lessons->where('host', 'SCORM'));
            })
            ->addColumn('action', function ($query) {


                return ' <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu2" data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        ' . trans('common.Action') . '
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu2">
                                                         <a class="dropdown-item" href="' . route('scorm.report.details', $query->id) . '" data-id="' . $query->id . '" type="button">' . trans('courses.Lesson List') . '</a>
                                                    </div>
                                                </div>';


            })->rawColumns(['action'])
            ->make(true);
    }

    public function storeScormReport(Request $request)
{
    \Log::info('Dati ricevuti in storeScormReport:', $request->all());

    $request->validate([
        'course_id' => 'required|integer',
        'lesson_id' => 'required|integer',
        'key' => 'required|string',
        'value' => 'required|string',
    ]);

    $scormReport = new ScormReport();
    $scormReport->course_id = $request->course_id;
    $scormReport->lesson_id = $request->lesson_id;
    $scormReport->key = $request->key;
    $scormReport->value = $request->value;
    $scormReport->date_hour = now();

    if ($scormReport->save()) {
        return response()->json(['success' => 'Progress saved'], 200);
    } else {
        return response()->json(['error' => 'Failed to save progress'], 500);
    }
}

//     public function storeScormReport(Request $request)
// {
//     $request->validate([
//         'course_id' => 'required|integer',
//         'lesson_id' => 'required|integer',
//         'key' => 'required|string',
//         'value' => 'required|string',
//     ]);

//     // Se la chiave è 'cmi.suspend_data', non salvare nulla
//     if ($request->key === 'cmi.suspend_data') {
//         return response()->json(['message' => 'Suspend data not stored'], 200);
//     }

//     // Verifica se il record esiste già
//     $scormReport = ScormReport::where('key', $request->key)
//         ->where('user_id', auth()->id())
//         ->where('course_id', $request->course_id)
//         ->where('lesson_id', $request->lesson_id)
//         ->first();

//     if ($scormReport) {
//         // Se esiste, aggiorna il valore
//         $scormReport->update([
//             'value' => $request->value,
//             'updated_at' => now()
//         ]);
//     } else {
//         // Se non esiste, crea un nuovo report
//         $scormReport = ScormReport::create([
//             'user_id' => auth()->id(),
//             'course_id' => $request->course_id,
//             'lesson_id' => $request->lesson_id,
//             'key' => $request->key,
//             'value' => $request->value,
//             'date_hour' => now(),
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);
//     }

//     return response()->json(['message' => 'Progress saved successfully', 'data' => $scormReport], 200);
// }



    public function getProgress($lesson_id) {
        try {
            \Log::info("Chiamata API getProgress per lesson_id: " . $lesson_id);
    
            $query = ScormReport::where('lesson_id', $lesson_id)
                                ->orderBy('date_hour', 'desc')
                                ->toSql(); // Ottieni la query SQL
            
            \Log::info("Query generata: " . $query);
    
            $progress = ScormReport::where('lesson_id', $lesson_id)
                                   ->orderBy('date_hour', 'desc')
                                   ->first();
    
            if (!$progress) {
                \Log::info("Nessun progresso trovato per lesson_id: " . $lesson_id);
                return response()->json(['message' => 'No progress found'], 404);
            }
    
            \Log::info("Progresso SCORM trovato: ", $progress->toArray());
    
            return response()->json([
                'lesson_location' => $progress->lesson_location ?? '',
                'suspend_data' => $progress->suspend_data ?? ''
            ]);
        } catch (\Exception $e) {
            \Log::error("Errore in getProgress: " . $e->getMessage());
            return response()->json(['error' => 'Errore nel server'], 500);
        }
    }
    
    

    public function lessonDetails($user_id, $lesson_id)
    {
        $user = User::FindOrFail($user_id);
        $lesson = Lesson::FindOrFail($lesson_id);
        $reports = ScormReport::where('user_id', $user_id)->where('lesson_id', $lesson_id)->get();
        return view('scorm::report.lessons', compact('user', 'reports', 'lesson'));
    }

}
