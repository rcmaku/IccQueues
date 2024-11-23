<?php

use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentStatus;
use App\Models\AgentStatusHistory;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request as HttpRequest;

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


Route::get('/support', [UsersController::class, 'showUserStatusGrid'])->middleware('auth');


Route::get('/status-updates', [UsersController::class, 'fetchStatusUpdates'])->name('status.updates');

Route::get('/fetch-updated-status', [UsersController::class, 'fetchUpdatedStatus'])->name('fetchUpdatedStatus');

Route::get('/it-queue', [UsersController::class, 'showUserStatusGrid'])->name('it-queue');

Route::post('/update-status', [UsersController::class, 'updateStatus'])->name('updateStatus');

Route::post('/logout', function () {
    // Get the currently authenticated user
    $user = Auth::user();

    if ($user) {

        $offlineStatus = AgentStatus::firstOrCreate(['name' => 'offline']);

        $user->agentStatus()->associate($offlineStatus);
        $user->save();

        AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $offlineStatus->id,
            'changed_at' => now(),
        ]);
    }
    Auth::logout();

    return redirect('/login');
})->name('logout');

Route::group(['prefix' => 'agent'], function () {
    Route::get('/', function (HttpRequest $request) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->index();
    })->name('agent.index');

    Route::get('/create', function (HttpRequest $request) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->create();
    })->name('agent.create');

    Route::post('/', function (HttpRequest $request) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->store($request);
    })->name('agent.store');

    Route::get('/{id}', function (HttpRequest $request, $id) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->show($id);
    })->name('agent.show');

    Route::get('/{id}/edit', function (HttpRequest $request, $id) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->edit($id);
    })->name('agent.edit');

    Route::put('/{id}', function (HttpRequest $request, $id) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->update($request, $id);
    })->name('agent.update');

    Route::delete('/{id}', function (HttpRequest $request, $id) {
        if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\UsersController::class)->destroy($id);
    })->name('agent.destroy');
});

Route::get('/report', function (HttpRequest $request) {
    if (!Auth::check() || !Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
        abort(403, 'Unauthorized');
    }

    return app(\App\Http\Controllers\ReportController::class)->generateReport($request);
})->name('report.generate');


Route::middleware('auth')->group(function () {

    Route::get('roles', function () {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->index();
    })->name('roles.index');

    Route::get('roles/create', function () {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->create();
    })->name('roles.create');

    Route::post('roles', function () {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->store(request());
    })->name('roles.store');

    Route::get('roles/{role}/edit', function ($role) {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->edit($role);
    })->name('roles.edit');

    Route::put('roles/{role}', function ($role) {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->update(request(), $role);
    })->name('roles.update');

    Route::delete('roles/{role}', function ($role) {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->destroy($role);
    })->name('roles.destroy');


    Route::post('users/{user}/roles', function (\App\Models\User $user) {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->assignRole(request(), $user);
    })->name('roles.assign');

    Route::delete('users/{user}/roles', function (\App\Models\User $user) {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->removeRole(request(), $user);
    })->name('roles.remove');


    Route::get('roles/list', function () {
        if (!Auth::user()->roles()->whereIn('roleName', ['admin', 'manager'])->exists()) {
            abort(403, 'Unauthorized');
        }
        return app(\App\Http\Controllers\RolesController::class)->list();
    })->name('roles.list');
});

Route::post('users/{user}/roles', function (\App\Models\User $user) {

    if (!Auth::check()) {
        abort(403, 'Unauthorized');
    }


    return app(\App\Http\Controllers\RolesController::class)->assignRole(request(), $user);
})->name('roles.assign');

Route::post('/complete-task', [UsersController::class, 'completeTask'])->name('completeTask');

Route::get('/check-email', function () {
    $email = request('email');
    $exists = User::where('email', $email)->exists();
    return response()->json(['exists' => $exists]);
});

