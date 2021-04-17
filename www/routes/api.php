<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LabelController;


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

/**
 * implicit route model binding IS NOT used
 * bc of softDeletes
 * it does not give a proper 404 when {model} is given
 */

/////////////  USER  /////////////

// List users
// (filter by name OR/AND email OR/AND verified OR/AND country)
Route::get('/users', [UserController::class, 'listAllAndFilter'])->name('listAllUsers');


// 1. Add users (create & add an email with verification token
// (plain text) TO QUEUE)
Route::post('/users', [UserController::class, 'store'])->name('storeUser');


// 2. Verify user
Route::patch('/users', [UserController::class, 'verify'])->name('verifylUsers');


// 3. Edit users
Route::put('/users', [UserController::class, 'update'])->name('updateUser');


// 4. Delete users
// (HINT: Pay attention to “userS”: bulk actions) // probobly about SVIAZY so do detuh
Route::delete('/users', [UserController::class, 'delete'])->name('deleteUser');


/////////////  PROJECT  /////////////

// 1. Add projects
Route::middleware('auth:api')->post('/projects', [ProjectController::class, 'store'])->name('storeProject');


// 2. Link projects to users
Route::put('/projects', [ProjectController::class, 'link'])->name('linkProject');


// 3. List projects incl. labels
// (filter by user.email OR/AND user.continent OR/AND labels)
Route::middleware('auth:api')->get('/projects', [ProjectController::class, 'listAllAndFilter'])->name('listAllProjects');


// 4. Delete projects
Route::middleware('auth:api')->delete('/projects', [ProjectController::class, 'delete'])->name('deleteProject');


/////////////  LABEL  /////////////

// 1. Add labels
Route::middleware('auth:api')->post('/labels', [LabelController::class, 'store'])->name('storeLabel');


// 3. List labels (filter by user.email OR/AND projects)
Route::middleware('auth:api')->get('/labels', [LabelController::class, 'listAllAndFilter'])->name('listAllLabel');


// 2. Link labels to projects
Route::put('/labels', [LabelController::class, 'link'])->name('linkLabel');


// 4. Delete labels
Route::middleware('auth:api')->delete('/labels', [LabelController::class, 'delete'])->name('deleteLabel');
