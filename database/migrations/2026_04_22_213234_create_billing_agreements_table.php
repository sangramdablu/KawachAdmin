<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Client PII fields (name, contact, email, phone, address) are stored
     * AES-256 encrypted via Laravel's built-in encrypt()/decrypt() helpers.
     * Billing figures are stored as integers (cents) to avoid float drift.
     */
    public function up(): void
    {
        Schema::create('billing_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();

            // ── Invoice metadata ─────────────────────────────────────────
            $table->string('invoice_no')->unique();
            $table->date('agreement_date');
            $table->date('contract_start');
            $table->unsignedTinyInteger('duration_months');
            $table->enum('status', ['draft', 'sent', 'signed', 'cancelled'])->default('draft');

            // ── Client info (AES-256 encrypted, TEXT for cipher overhead) ──
            $table->text('client_name_enc');
            $table->text('client_contact_enc');
            $table->text('client_email_enc');
            $table->text('client_phone_enc')->nullable();
            $table->text('client_address_enc')->nullable();

            // ── Project details ──────────────────────────────────────────
            $table->string('project_name');
            $table->string('project_type')->nullable();
            $table->text('project_scope')->nullable();

            // ── Billing config ───────────────────────────────────────────
            $table->unsignedTinyInteger('daily_hours')->default(8);
            $table->unsignedTinyInteger('monthly_days')->default(22);
            $table->enum('payment_cycle', ['monthly', 'biweekly', 'weekly', 'milestone'])->default('monthly');
            $table->unsignedTinyInteger('payment_due_days')->default(30);
            $table->decimal('late_fee_pct', 4, 2)->default(1.50);
            $table->decimal('advance_pct', 5, 2)->default(25.00);
            $table->decimal('tax_pct', 5, 2)->default(18.00);
            $table->string('currency', 8)->default('USD');
            $table->string('currency_symbol', 8)->default('$');
            $table->string('payment_methods')->nullable();

            // ── Computed totals (stored as cents to avoid float issues) ──
            $table->unsignedBigInteger('subtotal_cents')->default(0);  // pre-tax monthly
            $table->unsignedBigInteger('tax_cents')->default(0);       // monthly tax
            $table->unsignedBigInteger('monthly_total_cents')->default(0);
            $table->unsignedBigInteger('advance_cents')->default(0);
            $table->unsignedBigInteger('grand_total_cents')->default(0);// full contract incl tax

            // ── Custom clauses ───────────────────────────────────────────
            $table->text('custom_clauses')->nullable();

            // ── Signature images (base64 data URIs) ─────────────────────
            $table->text('company_signature')->nullable();
            $table->text('client_signature')->nullable();

            // ── Audit / meta ─────────────────────────────────────────────
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('billing_agreement_developers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_agreement_id')
                  ->constrained('billing_agreements')
                  ->cascadeOnDelete();

            $table->string('developer_label');          // display name e.g. "John Doe"
            $table->string('technology');               // e.g. "React.js"
            $table->string('tech_icon', 10)->default('💻');
            $table->unsignedSmallInteger('hourly_rate_cents'); // rate in cents, e.g. 3500 = $35.00
            $table->unsignedTinyInteger('quantity')->default(1);

            // Derived (stored for audit / PDF replay without recalc)
            $table->unsignedInteger('monthly_hours');
            $table->unsignedBigInteger('monthly_cost_cents');  // rate_cents * hours * qty

            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_agreement_developers');
        Schema::dropIfExists('billing_agreements');
    }
};