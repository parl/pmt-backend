<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternalBriefingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use App\Models\Developing;
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
Route::post('/auth/register/client', [AuthController::class, 'registerClient']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/authenticated', [AuthController::class, 'notAuthenticated'])->name('auth');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'admincheck']], function () {

    Route::get('/user', [UserController::class, 'getAllUser']);

    Route::post('/team', [TeamController::class, 'createTeam']);
    Route::delete('/team/{id}', [TeamController::class, 'deleteTeam']);
    Route::put('/team/{id}', [TeamController::class, 'updateTeam']);

    Route::post('/requirement', [RequirementController::class, 'createRequirement']);
    Route::delete('/requirement/{id}', [RequirementController::class, 'deleteRequirement']);
    Route::put('/requirement/{id}', [RequirementController::class, 'updateRequirement']);

    Route::post('/test', [TestController::class, 'createTest']);
    Route::delete('/test/{id}', [TestController::class, 'deleteTest']);
    Route::put('/test/{id}', [TestController::class, 'updateTest']);

    Route::post('/review', [ReviewController::class, 'createReview']);
    Route::delete('/review/{id}', [ReviewController::class, 'deleteReview']);
    Route::put('/review/{id}', [ReviewController::class, 'updateReview']);

    Route::post('/developing', [Developing::class, 'createDeveloping']);
    Route::delete('/developing/{id}', [Developing::class, 'deleteDeveloping']);
    Route::put('/developing/{id}', [Developing::class, 'updateDeveloping']);

    Route::post('/internalBriefing', [InternalBriefingController::class, 'createInternalBriefing']);
    Route::delete('/internalBriefing/{id}', [InternalBriefingController::class, 'deleteInternalBriefing']);
    Route::put('/internalBriefing/{id}', [InternalBriefingController::class, 'updateInternalBriefing']);

    Route::post('/team-member', [TeamMemberController::class, 'createTeamMember']);
    Route::delete('/team-member/{id}', [TeamMemberController::class, 'deleteTeamMember']);

    Route::post('/project', [ProjectController::class, 'createProject']);
    Route::delete('/project/{id}', [ProjectController::class, 'deleteProject']);
    Route::put('/project/{id}', [ProjectController::class, 'updateProject']);

    Route::post('/test', [TestController::class, 'createTest']);
});

Route::group(['middleware' => ['auth:sanctum', 'usercheck']], function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
    Route::get('/user/{id}', [UserController::class, 'getUserById']);
    Route::get('/client/{id}', [UserController::class, 'getClientById']);
    Route::get('/client', [UserController::class, 'getAllClient']);

    Route::get('/developing/{task_id}', [Developing::class, 'getDeveloping']);

    Route::get('/team/{id}', [TeamController::class, 'getTeam']);
    Route::get('/teamById/{id}', [TeamController::class, 'getTeamById']);
    Route::get('/team', [TeamController::class, 'getAllTeam']);
    Route::get('/team-member/{teamId}', [TeamMemberController::class, 'getTeamMember']);

    Route::get('/review/{taskId}', [ReviewController::class, 'getReview']);
    Route::get('/reviewById/{Id}', [ReviewController::class, 'getReviewById']);

    Route::get('/requirement/{projectId}', [RequirementController::class, 'getRequirement']);

    Route::get('/internalBriefing/{projectId}', [InternalBriefingController::class, 'getInternalBriefing']);

    Route::get('/project', [ProjectController::class, 'getProject']);
    Route::get('/project/{id}', [ProjectController::class, 'getProjectById']);

    Route::get('/test/{id}', [TestController::class, 'getTest']);

    Route::get('/attachment/{testId}', [TestController::class, 'getAttachment']);
});
