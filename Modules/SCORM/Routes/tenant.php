<?php


use Illuminate\Support\Facades\Route;

// Assicurati di importare i controller se non usi il fallback del namespace:
use Modules\SCORM\Http\Controllers\SCORMController;
use Modules\SCORM\Http\Controllers\SCORMReportController;
Route::get('/scorm/get-progress/{lesson_id}', [SCORMReportController::class, 'getProgress'])->name('scorm.report.getScormProgress');
// Route::post('/scorm/report/store', [SCORMReportController::class, 'store'])
//     ->name('scorm.report.store')
//     ->middleware('auth');
    // Route::post('/scorm/report/store', [SCORMReportController::class, 'storeScormReport'])->name('scorm.report.store')->middleware('auth');;

    // Route::post('/scorm/save-progress', [SCORMReportController::class, 'saveScormProgress']);
    // Route::get('/scorm/save-progress', [SCORMReportController::class, 'storeScormReport'])->name('scorm.report.storeScormReport');
    Route::post('/scorm/save-progress', [SCORMReportController::class, 'saveScormProgress']);
Route::prefix('scorm')->as('scorm.')->middleware('auth')->group(function () {
  // Route progress SCORM
  // Route::get('/report-progress', [SCORMReportController::class, 'getProgress'])->name('scorm.report.getProgress')->middleware('auth');
  Route::get('/report-details/{id}', [SCORMReportController::class, 'details'])->name('scorm.report.details')->middleware('auth');
  // Route::post('/report-store', [SCORMReportController::class, 'store'])->name('report.store')->middleware('auth');
  
  // Route per salvare il progresso SCORM
  
  // Route::get('/scorm/get-progress/{lesson_id}', [SCORMReportController::class, 'getProgress'])->name('scorm.report.getProgress');
 
// Route::get('/report-progress', [SCORMReportController::class, 'getProgress'])->name('scorm.report.getProgress')->middleware('auth');
    // Route::post('/scorm/report/store', [SCORMReportController::class, 'store'])->name('scorm.report.store');
    // Pagina principale o dashboard SCORM
    Route::get('/', 'SCORMController@index');
     // Visualizzatore SCORM, con versione e link (potrebbe essere il pacchetto SCORM o URL al pacchetto)
    // Route::get('/viewer/{version}/{link}', 'SCORMController@viewer')->name('viewer');
    // Azioni generiche per SCORM (ad esempio, comandi specifici)
    Route::get('/action', 'SCORMController@action')->name('action');
    // Endpoint per la ricezione di statement SCORM (xAPI)
    Route::post('/statement', 'SCORMController@statement')->name('statement');
      // Report SCORM: Salvataggio dei dati di tracciamento
    //   Route::post('/scorm/report/store', [SCORMReportController::class, 'store'])->name('scorm.report.store');
    // Route::post('/report-store', 'SCORMReportController@store')->name('report.store');
    // Visualizzazione dei report SCORM
    Route::get('/report', 'SCORMReportController@index')->name('report.index')->middleware('RoutePermissionCheck:scorm.report.index');
   // Dati per DataTables, ad esempio
    Route::get('/report-data', 'SCORMReportController@data')->name('report.data')->middleware('RoutePermissionCheck:scorm.report.index');
    // Route::get('/report-data', [SCORMReportController::class, 'data'])->name('report.data')->middleware('RoutePermissionCheck:scorm.report.index');

    // Dettagli di un report specifico
    Route::get('/report-details/{id}', 'SCORMReportController@details')->name('report.details')->middleware('RoutePermissionCheck:scorm.report.index');
    // Dettagli della lezione per un dato utente
    Route::get('/report-lesson-details/{user_id}/{lesson_id}', 'SCORMReportController@lessonDetails')->name('report.lesson-details')->middleware('RoutePermissionCheck:scorm.report.index');


  //   Route::prefix('scorm')->as('scorm.')->middleware('auth')->group(function () {
  //     Route::get('/', 'SCORMController@index');
  //     Route::get('/viewer/{version}/{link}', 'SCORMController@viewer')->name('viewer');
  //     Route::get('/action', 'SCORMController@action')->name('action');
  //     Route::post('/statement', 'SCORMController@statement')->name('statement');
  //     Route::post('/report-store', 'SCORMReportController@store')->name('report.store');
  //     Route::get('/report', 'SCORMReportController@index')->name('report.index')->middleware('RoutePermissionCheck:scorm.report.index');
  //     Route::get('/report-data', 'SCORMReportController@data')->name('report.data')->middleware('RoutePermissionCheck:scorm.report.index');
  //     Route::get('/report-details/{id}', 'SCORMReportController@details')->name('report.details')->middleware('RoutePermissionCheck:scorm.report.index');
  //     Route::get('/report-lesson-details/{user_id}/{lesson_id}', 'SCORMReportController@lessonDetails')->name('report.lesson-details')->middleware('RoutePermissionCheck:scorm.report.index');
  // });
});
