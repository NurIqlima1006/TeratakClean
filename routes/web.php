<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\StaffController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\MaintenanceTaskController;
use App\Http\Controllers\WorkerTaskController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {

    return match (auth()->user()->role) {

        'admin' => redirect()->route('admin.dashboard'),

        'owner' => redirect()->route('owner.dashboard'),

        default => redirect('/'),

    };

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class,'adminDashboard'])
        ->name('admin.dashboard');

    Route::resource('staff', StaffController::class);

    Route::resource('units', UnitController::class);

    Route::get('/staff/export-pdf',[StaffController::class,'exportPdf'])
        ->name('staff.export-pdf');

    Route::get('/tasks/export-pdf',[TaskAssignmentController::class,'exportPdf'])
        ->name('tasks.export-pdf');

    Route::get('/housekeeping/checkouts',[GuestCheckoutController::class,'index'])
        ->name('housekeeping.checkouts');

    Route::get('/housekeeping/checkouts/create',[GuestCheckoutController::class,'create'])
        ->name('housekeeping.checkouts.create');

    Route::post('/housekeeping/checkouts',[GuestCheckoutController::class,'store'])
        ->name('housekeeping.checkouts.store');

    Route::delete('/housekeeping/checkouts/{checkout}',[GuestCheckoutController::class,'destroy'])
        ->name('housekeeping.checkouts.destroy');

    Route::post('/housekeeping/assign-tasks',[GuestCheckoutController::class,'assignTasks'])
        ->name('housekeeping.assign-tasks');

    Route::get('/tasks/assignment',[TaskAssignmentController::class,'index'])
        ->name('tasks.assignment');

    Route::post('/tasks/auto-assign',[TaskAssignmentController::class,'autoAssign'])
        ->name('tasks.auto-assign');

    Route::get('/tasks/generate-rotation',[TaskAssignmentController::class,'generateRotationTasks'])
        ->name('tasks.generate-rotation');

    Route::post('/maintenance/quick-create',[MaintenanceTaskController::class,'quickCreate'])
        ->name('maintenance.quick-create');

    Route::post('/tasks/complete',[TaskAssignmentController::class,'completeTask'])
        ->name('tasks.complete');

    Route::get('/workers/{user}/tasks',[WorkerTaskController::class,'show'])
        ->name('workers.tasks');

});

/*
|--------------------------------------------------------------------------
| Owner
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','owner'])->group(function () {

    Route::get('/owner/dashboard',[DashboardController::class,'ownerDashboard'])
        ->name('owner.dashboard');

    Route::get('/owner/units',[UnitController::class,'ownerIndex'])
        ->name('owner.units');

    Route::get('/owner/tasks',[TaskAssignmentController::class,'ownerIndex'])
        ->name('owner.tasks');

});

/*
|--------------------------------------------------------------------------
| Staff
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile',[ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::patch('/profile',[ProfileController::class,'update'])
        ->name('profile.update');

    Route::delete('/profile',[ProfileController::class,'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';