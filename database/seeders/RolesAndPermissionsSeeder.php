<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\RoleMeta;

class RolesAndPermissionsSeeder extends Seeder
{
    // All permissions grouped by section
    // key format: resource.action  — matches the blade JS exactly
    public const PERMISSIONS = [
        'Pages' => [
            'pages.view'    => 'View Pages',
            'pages.create'  => 'Create Pages',
            'pages.edit'    => 'Edit Pages',
            'pages.delete'  => 'Delete Pages',
            'pages.publish' => 'Publish Pages',
        ],
        'Blog' => [
            'blog.view'    => 'View Blogs',
            'blog.create'  => 'Write Blogs',
            'blog.edit'    => 'Edit Blogs',
            'blog.delete'  => 'Delete Blogs',
            'blog.publish' => 'Publish Blogs',
        ],
        'Media' => [
            'media.view'   => 'View Media',
            'media.upload' => 'Upload Media',
            'media.delete' => 'Delete Media',
        ],
        'Users' => [
            'users.view'   => 'View Users',
            'users.invite' => 'Invite Users',
            'users.edit'   => 'Edit Users',
            'users.delete' => 'Delete Users',
        ],
        'Settings' => [
            'settings.view'    => 'View Settings',
            'settings.edit'    => 'Edit Settings',
            'settings.billing' => 'Billing Access',
        ],
        'Clients' => [
            'clients.view'   => 'View Clients',
            'clients.create' => 'Create Clients',
            'clients.edit'   => 'Edit Clients',
            'clients.delete' => 'Delete Clients',
        ],
        'Tasks' => [
            'tasks.view'           => 'View Tasks',
            'tasks.create'         => 'Create Tasks',
            'tasks.edit'           => 'Edit Tasks',
            'tasks.delete'         => 'Delete Tasks',
            'tasks.manage-columns' => 'Manage Task Columns',
        ],
    ];

    // Default permission sets per role
    public const ROLE_PERMISSIONS = [
        'super-admin' => '*', // all
        'admin' => [
            'pages.view','pages.create','pages.edit','pages.publish',
            'blog.view','blog.create','blog.edit','blog.publish',
            'media.view','media.upload',
            'users.view','users.invite','users.edit',
            'settings.view',
            'tasks.view','tasks.create','tasks.edit','tasks.delete','tasks.manage-columns',
        ],
        'editor' => [
            'pages.view','pages.create','pages.edit',
            'blog.view','blog.create','blog.edit',
            'media.view','media.upload',
            'tasks.view','tasks.create','tasks.edit',
        ],
        'viewer'  => ['pages.view','blog.view','media.view','tasks.view'],
        'content-manager' => [
            'pages.view','pages.create','pages.edit','pages.publish',
            'blog.view','blog.create','blog.edit','blog.publish',
            'media.view','media.upload',
            'users.view',
            'tasks.view','tasks.create','tasks.edit',
        ],
        'client' => [
            'clients.view',
        ],
    ];

    // Visual metadata per role
    public const ROLE_META = [
        'super-admin' => ['color' => 'superadmin', 'icon' => 'fas fa-crown', 'description' => 'Full unrestricted access to all features and settings.', 'is_system' => true  ],
        'admin' => ['color' => 'admin', 'icon' => 'fas fa-shield-alt', 'description' => 'Manage users, content, and most settings.', 'is_system' => true  ],
        'editor' => ['color' => 'editor', 'icon' => 'fas fa-pen-nib', 'description' => 'Create and edit content pages, blogs, and media.', 'is_system' => false ],
        'viewer' => ['color' => 'viewer', 'icon' => 'fas fa-eye', 'description' => 'Read-only access to published content.', 'is_system' => false ],
        'content-manager' => ['color' => 'custom', 'icon' => 'fas fa-folder', 'description' => 'Full content control with limited settings access.', 'is_system' => false ],
        'client' => ['color' => 'client', 'icon' => 'fas fa-user-tie', 'description' => 'Client access to assigned projects and reports.', 'is_system'   => false,],
    ];

    public function run(): void
    {
        // Reset cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create all permissions
        $allPermissions = [];
        foreach (self::PERMISSIONS as $section => $perms) {
            foreach ($perms as $key => $label) {
                $permission = Permission::firstOrCreate(
                    ['name' => $key],
                    ['guard_name' => 'web']
                );
                $allPermissions[$key] = $permission;
            }
        }

        // 2. Create roles, assign permissions, seed role_meta
        foreach (self::ROLE_PERMISSIONS as $roleName => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );

            if ($perms === '*') {
                $role->syncPermissions(array_values($allPermissions));
            } else {
                $role->syncPermissions(
                    collect($perms)
                        ->map(fn($p) => $allPermissions[$p] ?? null)
                        ->filter()
                        ->values()
                        ->all()
                );
            }

            // Seed role meta
            $meta = self::ROLE_META[$roleName] ?? [];
            RoleMeta::updateOrCreate(
                ['role_name' => $roleName],
                [
                    'color'       => $meta['color']       ?? 'viewer',
                    'icon'        => $meta['icon']        ?? 'fas fa-user',
                    'description' => $meta['description'] ?? '',
                    'is_system'   => $meta['is_system']   ?? false,
                ]
            );
        }

        $this->command->info('✅ Roles, permissions, and role meta seeded.');
    }
}