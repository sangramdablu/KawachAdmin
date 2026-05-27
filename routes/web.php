<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\BillingAndAgreementController;
use App\Http\Controllers\InvitationController;

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (unauthenticated users only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register-form', [AuthController::class, 'registerForm'])->name('register-form');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
| 'auth'  — must be logged in
| 'admin' — must have any recognised Spatie role (see AdminMiddleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard & Auth
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', fn() => view('dashboard.dashboard'))->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Blog Routes
    |----------------------------------------------------------------------
    | blogs.index   GET    /blogs
    | blogs.create  GET    /blogs/create
    | blogs.store   POST   /blogs
    | blogs.show    GET    /blogs/{blog}
    | blogs.edit    GET    /blogs/{blog}/edit
    | blogs.update  PUT    /blogs/{blog}
    | blogs.destroy DELETE /blogs/{blog}
    |----------------------------------------------------------------------
    */
    Route::middleware('check-permission:blog.view')->group(function () {
        Route::resource('blogs', BlogController::class);
    });

    // Autosave (obfuscated URL — no extra permission check needed, edit implies this)
    Route::post('/blogs/fhy6adv645gv5zd5', [BlogController::class, 'autosave'])->name('blogs.fhy6adv645gv5zd5')->middleware('check-permission:blog.edit');

    // Quill inline image upload
    Route::post('/upload-image', [BlogController::class, 'uploadImage'])
         ->name('blogs.upload.image')
         ->middleware('check-permission:media.upload');

    /*
    |----------------------------------------------------------------------
    | Page Builder Routes
    |----------------------------------------------------------------------
    | IMPORTANT — static segments (trashed, bulk, check-slug) MUST be
    | declared BEFORE Route::resource() so Laravel doesn't treat
    | "trashed" or "bulk" as a {page} wildcard.
    |----------------------------------------------------------------------
    */

    // ── Static helpers (before resource) ──────────────────────────────
    Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed');
    Route::post('/pages/bulk', [PageController::class, 'bulk'])->name('pages.bulk');
    Route::post('/pages/check-slug', [PageController::class, 'checkSlug'])->name('pages.check-slug');
    Route::post('/pages/category/store', [PageController::class, 'storeCategory'])->name('pages.category.store');

    // ── Resource ──────────────────────────────────────────────────────
    Route::resource('pages', PageController::class)->except(['show']);

    // ── Wildcard-param routes (after resource) ─────────────────────
    Route::get('/pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
    Route::delete('/pages/{id}/force', [PageController::class, 'forceDelete'])->name('pages.force-delete');
    Route::patch('/pages/{page}/toggle', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

    /*
    |----------------------------------------------------------------------
    | Roles & Access Management
    | Restricted to super-admin and admin only.
    | The index route lives here inside the auth+admin group.
    |----------------------------------------------------------------------
    */
    Route::get('/admin/roles-access', [RoleAccessController::class, 'index'])
         ->name('roles-access.index')
         ->middleware('check-role:super-admin,admin');

});

/*
|--------------------------------------------------------------------------
| Billing & Agreement Routes
| Place inside your existing web.php, inside the auth+admin middleware group
|--------------------------------------------------------------------------
*/
 
Route::middleware(['auth', 'check-role:super-admin,admin'])->prefix('billing')->name('billing.')->group(function () {
        // ── AJAX: live calculation (called on every form change) ──────────
        Route::post('calculate', [BillingAndAgreementController::class, 'calculate'])->name('calculate');
        // ── CRUD ──────────────────────────────────────────────────────────
        Route::get('/',                [BillingAndAgreementController::class, 'index'])->name('index');
        Route::get('/create',          [BillingAndAgreementController::class, 'create'])->name('create');
        Route::post('/',               [BillingAndAgreementController::class, 'store'])->name('store');
        Route::get('/{uuid}',          [BillingAndAgreementController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit',     [BillingAndAgreementController::class, 'edit'])->name('edit');
        Route::put('/{uuid}',          [BillingAndAgreementController::class, 'update'])->name('update');
        Route::delete('/{uuid}',       [BillingAndAgreementController::class, 'destroy'])->name('destroy');
        // ── PDF download ──────────────────────────────────────────────────
        Route::get('/{uuid}/pdf',      [BillingAndAgreementController::class, 'generatePdf'])->name('pdf');
        // ── Send to client via email ──────────────────────────────────────
        Route::post('/{uuid}/send-email', [BillingAndAgreementController::class, 'sendEmail'])->name('send-email');
    });
/*
|--------------------------------------------------------------------------
| Roles & Access — API-style AJAX routes
| Separate group so JSON error responses work correctly.
| Still requires auth + super-admin or admin role.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check-role:super-admin,admin'])->prefix('admin/roles-access')->name('roles-access.')->group(function () {
    Route::get('roles', [RoleAccessController::class, 'index'])->name('roles.index');
    // ── Users ──────────────────────────────────────────────────────
    Route::get('users', [RoleAccessController::class, 'usersData'])->name('users.data');
    Route::get('users/export', [RoleAccessController::class, 'exportUsers'])->name('users.export');   // GET before {user} wildcard
    Route::post('users/bulk', [RoleAccessController::class, 'bulkAction'])->name('users.bulk');      // POST before {user} wildcard
    Route::patch('users/{user}', [RoleAccessController::class, 'updateUser'])->name('users.update');
    Route::patch('users/{user}/toggle-ban', [RoleAccessController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::delete('users/{user}', [RoleAccessController::class, 'removeUser'])->name('users.destroy');
    Route::post('users/{user}/reset-pwd', [RoleAccessController::class, 'resetPassword'])->name('users.reset-password');
    // ── Roles ──────────────────────────────────────────────────────
    Route::post  ('roles', [RoleAccessController::class, 'storeRole'])->name('roles.store');
    Route::patch ('roles/{role}', [RoleAccessController::class, 'updateRole'])->name('roles.update');
    Route::delete('roles/{role}', [RoleAccessController::class, 'destroyRole'])->name('roles.destroy');
    // ── Permission matrix ──────────────────────────────────────────
    Route::post('permissions/matrix', [RoleAccessController::class, 'savePermissionMatrix'])->name('permissions.matrix');
    // ── Invitations ────────────────────────────────────────────────
    Route::post('invitations', [RoleAccessController::class, 'sendInvitation'])->name('invitations.send');
    // ── Activity log ───────────────────────────────────────────────
    Route::get('activity', [RoleAccessController::class, 'activityLog'])->name('activity');
});

Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/register', [InvitationController::class, 'accept'])->name('invitation.accept');
    Route::post('/register/invitation', [InvitationController::class, 'register'])->name('invitation.register');
});