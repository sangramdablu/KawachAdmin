<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BillingAndAgreementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ClientBillingController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;

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
| CSRF Token Refresh (public, no auth required)
|--------------------------------------------------------------------------
*/
Route::get('/csrf-refresh', function () {
    return response()->json(['token' => csrf_token()]);
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
| Logout — must work for every authenticated role, never role-restricted.
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
    // 1. Only register 'index' since your BlogController doesn't have a 'show' method
    Route::middleware('check-permission:blog.view')->group(function () {
        Route::resource('blogs', BlogController::class)->only(['index']);
    });

    // 2. Registers create, store, edit, update, and destroy (skipping index and show)
    Route::middleware('check-permission:blog.edit')->group(function () {
        Route::resource('blogs', BlogController::class)->except(['index', 'show']);

        // Autosave route
        Route::post('/blogs/fhy6adv645gv5zd5', [BlogController::class, 'autosave'])
             ->name('blogs.fhy6adv645gv5zd5');

        // Inline "+ Add New" category button in the blog editor sidebar — it
        // used to only append a fake client-side <option value="new_...">
        // with no backend record behind it, so saving the post with that
        // category selected always failed validation
        // (category_id => nullable|exists:categories,id). This gives it a
        // real endpoint so the new option points at a persisted category.
        Route::post('/blogs/category/store', [BlogController::class, 'storeCategory'])
             ->name('blogs.category.store');
    });

    // 3. Inline editor image uploads — gated by blog.edit since this is only
    //    ever called from inside the blog editor itself.
    Route::post('/upload-image', [BlogController::class, 'uploadImage'])
         ->name('blogs.upload.image')
         ->middleware('check-permission:blog.edit');

    // Route::middleware('check-permission:blog.view')->group(function () {
    //     Route::resource('blogs', BlogController::class);
    // });

    // // Autosave (obfuscated URL — no extra permission check needed, edit implies this)
    // Route::post('/blogs/fhy6adv645gv5zd5', [BlogController::class, 'autosave'])->name('blogs.fhy6adv645gv5zd5')->middleware('check-permission:blog.edit');

    // // Quill inline image upload
    // Route::post('/upload-image', [BlogController::class, 'uploadImage'])
    //      ->name('blogs.upload.image')
    //      ->middleware('check-permission:media.upload');

    /*
    |----------------------------------------------------------------------
    | Page Builder Routes
    |----------------------------------------------------------------------
    | IMPORTANT — static segments (trashed, bulk, check-slug) MUST be
    | declared BEFORE Route::resource() so Laravel doesn't treat
    | "trashed" or "bulk" as a {page} wildcard.
    |----------------------------------------------------------------------
    */

    /*
    |----------------------------------------------------------------------
    | Page Builder Routes
    |----------------------------------------------------------------------
    */

    // 1. Allow Viewers to access the main list page
    Route::middleware('check-permission:pages.view')->group(function () {
        // This ONLY registers the GET /pages route (pages.index)
        Route::resource('pages', PageController::class)->only(['index']);
        // Static helpers that viewers might need to read data
        Route::post('/pages/check-slug', [PageController::class, 'checkSlug'])->name('pages.check-slug');
    });

    // 2. Block Viewers here. Only allow users who can create/edit/delete
    Route::middleware('check-permission:pages.create')->group(function () {
        // Static segments (Declared BEFORE resource wildcards)
        Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed');
        Route::post('/pages/bulk', [PageController::class, 'bulk'])->name('pages.bulk');
        Route::post('/pages/category/store', [PageController::class, 'storeCategory'])->name('pages.category.store');
        // Registers create, store, edit, update, and destroy (skipping index and show)
        Route::resource('pages', PageController::class)->except(['index', 'show']);
        // Wildcard-param routes (Declared AFTER resource)
        Route::get('/pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
        Route::delete('/pages/{id}/force', [PageController::class, 'forceDelete'])->name('pages.force-delete');
        Route::patch('/pages/{page}/toggle', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');
    });
    
    // ── Static helpers (before resource) ──────────────────────────────
    // Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed');
    // Route::post('/pages/bulk', [PageController::class, 'bulk'])->name('pages.bulk');
    // Route::post('/pages/check-slug', [PageController::class, 'checkSlug'])->name('pages.check-slug');
    // Route::post('/pages/category/store', [PageController::class, 'storeCategory'])->name('pages.category.store');

    // // ── Resource ──────────────────────────────────────────────────────
    // Route::resource('pages', PageController::class)->except(['show']);

    // // ── Wildcard-param routes (after resource) ─────────────────────
    // Route::get('/pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
    // Route::delete('/pages/{id}/force', [PageController::class, 'forceDelete'])->name('pages.force-delete');
    // Route::patch('/pages/{page}/toggle', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

    /*
    |----------------------------------------------------------------------
    | Roles & Access Management
    | Restricted to super-admin and admin only.
    | The index route lives here inside the auth+admin group.
    |----------------------------------------------------------------------
    */
    Route::get('/admin/roles-access', [RoleAccessController::class, 'index'])->name('roles-access.index')->middleware('check-role:super-admin,admin');

    /*
    |----------------------------------------------------------------------
    | Tasks (Kanban board) Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('tasks')->name('tasks.')->group(function () {

        Route::middleware('check-permission:tasks.view')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/cards/{card}/comments', [TaskController::class, 'getComments'])->name('cards.comments.index');
            Route::post('/cards/{card}/comments', [TaskController::class, 'storeComment'])->name('cards.comments.store');
            Route::delete('/comments/{comment}', [TaskController::class, 'destroyComment'])->name('cards.comments.destroy');
        });

        Route::middleware('check-permission:tasks.create')->group(function () {
            Route::post('/cards', [TaskController::class, 'storeCard'])->name('cards.store');
        });

        Route::middleware('check-permission:tasks.edit')->group(function () {
            Route::put('/cards/{card}', [TaskController::class, 'updateCard'])->name('cards.update');
            Route::patch('/cards/{card}/move', [TaskController::class, 'moveCard'])->name('cards.move');
        });

        Route::middleware('check-permission:tasks.delete')->group(function () {
            Route::delete('/cards/{card}', [TaskController::class, 'destroyCard'])->name('cards.destroy');
        });

        Route::middleware('check-permission:tasks.manage-columns')->group(function () {
            Route::post('/lists', [TaskController::class, 'storeList'])->name('lists.store');
            Route::put('/lists/{taskList}', [TaskController::class, 'updateList'])->name('lists.update');
            Route::patch('/lists/reorder', [TaskController::class, 'reorderLists'])->name('lists.reorder');
            Route::delete('/lists/{taskList}', [TaskController::class, 'destroyList'])->name('lists.destroy');
        });
    });

    /*
    |----------------------------------------------------------------------
    | Notifications (generic — used by comments today, any future event)
    |----------------------------------------------------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('read-all');
    });

});

/*
|--------------------------------------------------------------------------
| Billing & Agreement Routes
| Place inside your existing web.php, inside the auth+admin middleware group
|--------------------------------------------------------------------------
*/
 
Route::middleware(['auth', 'check-role:super-admin,admin', 'check-permission:settings.billing'])->prefix('billing')->name('billing.')->group(function () {
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

/*
|--------------------------------------------------------------------------
| Clients — API-style AJAX routes
| Separate group so JSON error responses work correctly.
| Still requires auth + super-admin or admin role.
|--------------------------------------------------------------------------
*/
 
// ── CLIENT PORTAL (for logged-in client users) ────────────────────────────
// Middleware: 'auth' + 'role:client'  (Spatie check-role alias)
Route::middleware(['auth', 'check-role:client'])->group(function () {
    Route::get('/client/portal', [ClientPortalController::class, 'index'])
         ->name('client.portal');

    // Read-only view of the client's own agreements — no create/edit/delete.
    Route::get('/client/billing',            [ClientBillingController::class, 'index'])->name('client.billing.index');
    Route::get('/client/billing/{uuid}',     [ClientBillingController::class, 'show'])->name('client.billing.show');
    Route::get('/client/billing/{uuid}/pdf', [ClientBillingController::class, 'pdf'])->name('client.billing.pdf');
});
 
// ── ADMIN: Client management ──────────────────────────────────────────────
Route::middleware(['auth', 'check-role:super-admin,admin'])->group(function () {
    // Client users (CRUD)
    Route::get('/clients',                [ClientController::class, 'index'])  ->middleware('check-permission:clients.view')->name('clients.index');
    Route::get('/clients/create',         [ClientController::class, 'create']) ->middleware('check-permission:clients.create')->name('clients.create');
    Route::post('/clients',               [ClientController::class, 'store'])  ->middleware('check-permission:clients.create')->name('clients.store');
    Route::get('/clients/{client}/edit',  [ClientController::class, 'edit'])   ->middleware('check-permission:clients.edit')->name('clients.edit');
    Route::put('/clients/{client}',       [ClientController::class, 'update']) ->middleware('check-permission:clients.edit')->name('clients.update');
    Route::delete('/clients/{client}',    [ClientController::class, 'destroy'])->middleware('check-permission:clients.delete')->name('clients.destroy');
    // Project progress management
    Route::put('/client-projects/{project}/progress', [ClientController::class, 'updateProject'])->middleware('check-permission:clients.edit')->name('client-projects.update');
    Route::post('/client-projects/{project}/tasks',   [ClientController::class, 'storeTask'])->middleware('check-permission:clients.edit')->name('client-projects.tasks.store');
    Route::post('/client-projects/{project}/team',    [ClientController::class, 'storeTeamMember'])->middleware('check-permission:clients.edit')->name('client-projects.team.store');
    // Invoice management
    Route::post('/clients/{client}/invoices', [ClientController::class, 'storeInvoice'])->middleware('check-permission:clients.edit')->name('clients.invoices.store');
});

// Route::post( '/agreement/sign/{token}', [BillingAndAgreementController::class, 'submitSignature'] )->name('billing.sign.submit');
// Route::get( '/agreement/sign/{token}', [BillingAndAgreementController::class, 'signAgreement'] )->name('billing.sign');

// Route::post('/billing/{uuid}/publish', [BillingAndAgreementController::class, 'publish'])->name('billing.publish');


Route::middleware(['throttle:10,1'])->group(function () {
    Route::get(
        '/invitation/{token}',
        [InvitationController::class, 'accept']
    )->name('invitation.accept');

    Route::post(
        '/invitation/{token}',
        [InvitationController::class, 'register']
    )->name('invitation.register');
});



// ══════════════════════════════════════════════════════════════
// ROUTES — add to routes/web.php
// ══════════════════════════════════════════════════════════════

// ── Public signing routes (NO auth — token-gated) ────────────────────────
// These must be OUTSIDE any auth middleware group
Route::get('/agreements/sign/{token}', [BillingAndAgreementController::class, 'signAgreement'])
     ->name('billing.sign')
     ->where('token', '[a-f0-9]{64}');   // only 64-char hex tokens accepted

Route::post('/agreements/sign/{token}', [BillingAndAgreementController::class, 'submitSignature'])
     ->name('billing.sign.submit')
     ->where('token', '[a-f0-9]{64}')
     ->middleware('throttle:5,10');       // 5 requests per 10 minutes per IP

Route::get('/agreements/signed/thank-you', [BillingAndAgreementController::class, 'signSuccess'])
     ->name('billing.sign.success');


// ── Admin billing routes (auth protected) ────────────────────────────────
Route::middleware(['auth', 'check-role:super-admin,admin', 'check-permission:settings.billing'])->group(function () {
    Route::get('/billing',                          [BillingAndAgreementController::class, 'index'])->name('billing.index');
    Route::get('/billing/create',                   [BillingAndAgreementController::class, 'create'])->name('billing.create');
    Route::post('/billing',                         [BillingAndAgreementController::class, 'store'])->name('billing.store');
    Route::get('/billing/{uuid}',                   [BillingAndAgreementController::class, 'show'])->name('billing.show');
    Route::get('/billing/{uuid}/edit',              [BillingAndAgreementController::class, 'edit'])->name('billing.edit');
    Route::put('/billing/{uuid}',                   [BillingAndAgreementController::class, 'update'])->name('billing.update');
    Route::delete('/billing/{uuid}',                [BillingAndAgreementController::class, 'destroy'])->name('billing.destroy');
    Route::get('/billing/{uuid}/pdf',               [BillingAndAgreementController::class, 'generatePdf'])->name('billing.pdf');
    Route::post('/billing/{uuid}/send-email',       [BillingAndAgreementController::class, 'sendEmail'])->name('billing.send-email');
    Route::post('/billing/{uuid}/resend-invitation',[BillingAndAgreementController::class, 'resendSigningInvitation'])->name('billing.resend-invitation');
    Route::post('/billing/calculate',               [BillingAndAgreementController::class, 'calculate'])->name('billing.calculate');
});