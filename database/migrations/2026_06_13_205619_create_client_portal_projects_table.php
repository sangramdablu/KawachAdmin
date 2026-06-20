<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_agreement_id')
                  ->constrained('billing_agreements')
                  ->cascadeOnDelete();
            $table->foreignId('client_user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Display info
            $table->string('project_name');
            $table->string('project_type')->nullable();
            $table->string('icon')->default('fas fa-code');
            $table->string('color_bg')->default('#e8f1fd');
            $table->string('color')->default('var(--primary)');

            // Status & progress
            $table->enum('status', ['inprogress', 'active', 'review', 'onhold', 'completed'])
                  ->default('inprogress');
            $table->unsignedTinyInteger('progress')->default(0); // 0–100

            // Phase tracking (JSON array of phase objects)
            // Each phase: { "name": "Discovery", "state": "done|active|pending" }
            $table->json('phases')->nullable();

            // Tags (JSON array)
            // Each tag: { "label": "Laravel", "icon": "fas fa-tag", "class": "" }
            $table->json('tags')->nullable();

            // Dates
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();

            // Task counters (admin-managed)
            $table->unsignedSmallInteger('done_tasks')->default(0);
            $table->unsignedSmallInteger('total_tasks')->default(0);

            $table->timestamps();
        });

        // ── Pending tasks table (items awaiting client action) ──────────────
        Schema::create('client_portal_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_project_id')
                  ->constrained('client_portal_projects')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->enum('state', ['pending', 'active', 'done'])->default('active');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->date('due')->nullable();
            $table->boolean('overdue')->default(false);
            $table->timestamps();
        });

        // ── Team members assigned to a project ──────────────────────────────
        Schema::create('client_portal_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portal_project_id')
                  ->constrained('client_portal_projects')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('role');
            $table->string('initials', 4);
            $table->string('color')->default('#1a73e8');
            $table->enum('status', ['online', 'offline', 'away'])->default('offline');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ── Invoices for the portal ─────────────────────────────────────────
        Schema::create('client_portal_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('billing_agreement_id')
                  ->nullable()
                  ->constrained('billing_agreements')
                  ->nullOnDelete();
            $table->string('invoice_id');           // e.g. INV-2025-04
            $table->date('invoice_date');
            $table->unsignedBigInteger('amount_cents');
            $table->string('currency_symbol')->default('₹');
            $table->enum('status', ['paid', 'due', 'overdue'])->default('due');
            $table->string('icon_bg')->default('#e8f1fd');
            $table->string('icon_color')->default('var(--primary)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_invoices');
        Schema::dropIfExists('client_portal_team');
        Schema::dropIfExists('client_portal_tasks');
        Schema::dropIfExists('client_portal_projects');
    }
};