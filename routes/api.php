<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/authenticated', [AuthController::class, 'notAuthenticated'])->name('auth');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'admincheck']], function () {

    Route::get('/user', [UserController::class, 'getAllUser']);

    Route::post('/team', [TeamController::class, 'createTeam']);
    Route::get('/team', [TeamController::class, 'getTeam']);
    Route::delete('/team/{id}', [TeamController::class, 'deleteTeam']);
    Route::put('/team/{id}', [TeamController::class, 'updateTeam']);

    Route::post('/team-member', [TeamMemberController::class, 'createTeamMember']);
    Route::get('/team-member/{teamId}', [TeamMemberController::class, 'getTeamMember']);
    Route::delete('/team-member/{id}', [TeamMemberController::class, 'deleteTeamMember']);

    Route::post('/project', [ProjectController::class, 'createProject']);
    Route::get('/project', [ProjectController::class, 'getProject']);
    Route::delete('/project/{id}', [ProjectController::class, 'deleteProject']);
    Route::put('/project/{id}', [ProjectController::class, 'updateProject']);

    Route::get('/test', [TestController::class, 'test']);
});
