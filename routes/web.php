<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ContactController,
    PostulationController,
    AboutController,
    FileController,
    PanelController,
    CandateController,
    ExamController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::middleware('auth')->name('panel')->group(function () {
    Route::get('/panel', [PanelController::class, 'index']);
    Route::get('/perfil', [PanelController::class, 'profile'])->name('.profile');
    Route::get('/panel/archivos/{file}', [FileController::class, 'show'])->name('.files.show');
    Route::get('/panel/documentos', [FileController::class, 'index'])->name('.documents.upload');
    Route::put('/candidatos/update', [CandateController::class, 'update'])->name('.candidates.update');
    Route::get('/pruebas/{offer}/{linea}/examen', [ExamController::class, 'exam'])->name('.exam');
    Route::post('/pruebas/{offer}/{linea}/examen', [ExamController::class, 'storeExam'])->name('.exam');
});

Route::get('/', [PostulationController::class, 'index'])->name('home');
Route::get('/empleos/{cod}/aplicar', [PostulationController::class, 'apply'])->name('postulations.apply');
Route::post('/empleos/aplicar', [PostulationController::class, 'saveApply'])->name('postulations.saveApply');
Route::delete('/empleos/{cod}/reject', [PostulationController::class, 'rejectApply'])->name('postulations.rejectApply')->middleware('auth');
Route::get('/sobre-nosotros', [AboutController::class, 'index'])->name('about');
Route::get('/contactanos', [ContactController::class, 'index'])->name('contact');
Route::get('politica-de-privacidad', function () { return view('privacy-policy'); })->name('privacy-policy');
Route::get('logout', function () { Auth::logout(); return redirect('/'); })->name('logout');
