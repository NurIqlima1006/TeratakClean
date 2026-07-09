<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\StaffTaskHistoryController;

use App\Http\Controllers\StaffController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\MaintenanceTaskController;
use App\Http\Controllers\WorkerTaskController;
use App\Http\Controllers\TaskApprovalController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {

    return match(auth()->user()->role) {

        'admin' => redirect()->route('admin.dashboard'),

        'owner' => redirect()->route('owner.dashboard'),

        'staff',
        'handyman',
        'gardener' => redirect()->route('staff.dashboard'),

        default => redirect()->route('login'),

    };

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth','admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class,'adminDashboard'])
            ->name('admin.dashboard');

        Route::resource('staff', StaffController::class)
            ->except(['show']);

        Route::get('/staff/export-pdf', [StaffController::class, 'exportPdf'])
            ->name('staff.export-pdf');

        Route::resource('units', UnitController::class);

        Route::get('/housekeeping/checkouts',
            [GuestCheckoutController::class,'index'])
            ->name('housekeeping.checkouts');

        Route::get('/housekeeping/checkouts/create',
            [GuestCheckoutController::class,'create'])
            ->name('housekeeping.checkouts.create');

        Route::post('/housekeeping/checkouts',
            [GuestCheckoutController::class,'store'])
            ->name('housekeeping.checkouts.store');

        Route::delete('/housekeeping/checkouts/{checkout}',
            [GuestCheckoutController::class,'destroy'])
            ->name('housekeeping.checkouts.destroy');

        Route::post('/housekeeping/assign-tasks',
            [GuestCheckoutController::class,'assignTasks'])
            ->name('housekeeping.assign-tasks');

        Route::get('/tasks/assignment',
            [TaskAssignmentController::class,'index'])
            ->name('tasks.assignment');

        Route::post('/tasks/auto-assign',
            [TaskAssignmentController::class,'autoAssign'])
            ->name('tasks.auto-assign');

        Route::get('/tasks/generate-rotation',
            [TaskAssignmentController::class,'generateRotationTasks'])
            ->name('tasks.generate-rotation');

        Route::get('/tasks/export-pdf',
            [TaskAssignmentController::class,'exportPdf'])
            ->name('tasks.export-pdf');

        Route::post('/tasks/complete',
            [TaskAssignmentController::class,'completeTask'])
            ->name('tasks.complete');

        Route::post('/tasks/approve/{completion}',
            [TaskAssignmentController::class, 'approve'])
            ->name('tasks.approve');

        Route::post('/maintenance/quick-create',
            [MaintenanceTaskController::class,'quickCreate'])
            ->name('maintenance.quick-create');

        Route::get('/workers/{user}/tasks',
            [WorkerTaskController::class,'show'])
            ->name('workers.tasks');

        Route::get('/tasks/approval',
            [TaskAssignmentController::class, 'approval'])
            ->name('tasks.approval');

        Route::get('/tasks/history', [TaskAssignmentController::class, 'history'])
            ->name('tasks.history');


    });
    /*
|--------------------------------------------------------------------------
| Owner
|--------------------------------------------------------------------------
*/

Route::prefix('owner')
    ->middleware(['auth', 'owner'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])
            ->name('owner.dashboard');

        Route::get('/units', [UnitController::class, 'ownerIndex'])
            ->name('owner.units');

        Route::get('/tasks', [TaskAssignmentController::class, 'ownerIndex'])
            ->name('owner.tasks');

        Route::get('/tasks/approval', [TaskAssignmentController::class, 'ownerApproval'])
            ->name('owner.tasks.approval');
        
        Route::get('/tasks/history', [TaskAssignmentController::class, 'ownerHistory'])
            ->name('owner.tasks.history');

    });

/*
|--------------------------------------------------------------------------
| Staff
|--------------------------------------------------------------------------
*/

Route::prefix('staff')
    ->middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('staff.dashboard');

        Route::get('/tasks', [StaffTaskController::class, 'index'])
            ->name('staff.tasks');

        Route::get('/tasks/{task}', [StaffTaskController::class, 'show'])
            ->name('staff.tasks.show');

        Route::post('/tasks/{task}/complete', [StaffTaskController::class, 'complete'])
            ->name('staff.tasks.complete');

        Route::get('/history', [StaffTaskHistoryController::class, 'index'])
            ->name('staff.history');

    });

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';