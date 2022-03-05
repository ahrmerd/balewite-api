<?php

use App\Models\Materials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Department;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// require_once('fortify.php');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('users', UserController::class);
// ->middleware('auth:sanctum');

//admin routes
Route::resource('departments', DepartmentController::class);
Route::resource('faculties', FacultyController::class);
Route::resource('levels', LevelController::class);
Route::resource('days', DayController::class);
Route::resource('periods', PeriodController::class);

//mod routes
Route::get('courses/{course}/departments', [CourseController::class, 'departments']);
Route::get('departments/{department}/courses', [DepartmentController::class, 'courses']);
Route::get('departments/{department}/materials', [DepartmentController::class, 'materials']);
Route::get('departments/{department}/quizzes', [DepartmentController::class, 'quizzes']);
Route::resource('courses', CourseController::class);
Route::resource('quizzes', QuizController::class);
Route::resource('questions', QuestionController::class);
Route::resource('choices', ChoiceController::class);
Route::resource('lectures', LectureController::class);
Route::resource('messages', MessageController::class);
Route::resource('materials', MaterialController::class);
Route::resource('articles', ArticleController::class);
Route::resource('announcements', AnnouncementController::class);

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
