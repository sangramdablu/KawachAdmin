<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageCaseStudy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * A single, hand-written case study for Orbit (orbitzr.com) — a real-time
 * project management SaaS product built and owned in-house by Kawach
 * Technology, not a third-party client engagement. It's seeded here so the
 * public site can show it as proof of engineering capability: featured and
 * given the lowest sort_order so it surfaces first among case studies.
 *
 * Every fact below is sourced from the live orbitzr.com site (homepage +
 * /pricing, fetched directly) and from Orbit's own codebase
 * (C:\Users\sangr\Desktop\kawach\Orbit360\Orbit360 — composer.json,
 * package.json, .env) for the real tech stack. No user counts, revenue,
 * uptime, or compliance-certification claims are included since none are
 * publicly verifiable — see the "no fabrication" note on testimonial below.
 *
 * The real end-user quote (Maya Kessler, Northwind Labs, from orbitzr.com's
 * own homepage) is deliberately NOT placed in testimonial_quote/_name/_role:
 * that field renders inside a "Client Testimonial" block with a hardcoded
 * 5-star widget (case_study_details.blade.php) and also feeds the
 * homepage's "What Our Clients Say" carousel — both would misrepresent a
 * genuine Orbit *product user's* quote as a statement about hiring Kawach
 * for a client engagement. The quote is instead woven into the narrative
 * HTML below, correctly attributed as what it actually is.
 */
class OrbitCaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $slug = 'orbit-real-time-project-management-case-study';
        $page = Page::where('slug', $slug)->first();

        if ($page) {
            $createdAt = $page->created_at;
            $updatedAt = Carbon::now();
        } else {
            $createdAt = Carbon::now();
            $updatedAt = Carbon::now();
            $page = new Page();
            $page->slug = $slug;
        }

        $page->fill([
            'page_type'          => 'casestudy',
            'title'              => 'Building Orbit: Kawach Technology\'s Own Real-Time Project Management Platform',
            'status'             => 'published',
            'visibility'         => 'public',
            'page_password'      => null,
            'is_featured'        => true,
            'sort_order'         => 0,
            'category_id'        => 2, // "Case study"
            'author_id'          => 1,
            'published_at'       => $createdAt,
            'featured_image'     => 'avatars/' . $slug . '.png',
            'image_alt'          => 'Orbit — real-time project management platform built by Kawach Technology',
            'image_title'        => 'Orbit Case Study',
            'focus_keyword'      => 'real-time project management software development',
            'meta_title'         => 'Orbit Case Study — Real-Time Project Management SaaS | Kawach Technology',
            'meta_description'   => 'How Kawach Technology designed, built, and continues to develop Orbit (orbitzr.com), a real-time project management platform with sub-second sync, built-in automation, and a free-forever plan.',
            'meta_keywords'      => 'Orbit case study, orbitzr.com, real-time project management software, SaaS product development, Kawach Technology product',
            'canonical_url'      => null,
            'robots'             => 'index, follow',
            'schema_type'        => 'WebPage',
            'og_title'           => 'Orbit Case Study — Real-Time Project Management SaaS | Kawach Technology',
            'og_description'     => 'How Kawach Technology designed, built, and continues to develop Orbit, a real-time project management platform with sub-second sync and built-in automation.',
            'og_image'           => 'avatars/' . $slug . '.png',
            'twitter_card'       => 'summary_large_image',
            'hreflang'           => 'en',
            'sitemap_priority'   => 0.9,
            'sitemap_changefreq' => 'monthly',
            'custom_head_script' => null,
            'tags'               => 'Orbit case study, orbitzr.com, real-time project management software, SaaS product development, Kawach Technology product',
        ]);

        $page->timestamps = false;
        $page->created_at = $createdAt;
        $page->updated_at = $updatedAt;
        $page->save();

        $caseStudy = PageCaseStudy::firstOrNew(['page_id' => $page->id]);
        $caseStudy->fill([
            'client_name'      => 'Orbit (Kawach Technology — In-House Product)',
            'client_industry'  => 'SaaS / Productivity Software',
            'business_size'    => 'In-house product, self-funded',
            'location'         => 'Remote-first, global SaaS',
            'business_model'   => 'Freemium SaaS — Free, Standard, Premium, and Enterprise plans',
            'project_duration' => 'Ongoing — in active development since 2026',
            'completion_date'  => null,
            'project_url'      => 'https://orbitzr.com',

            'challenge' => <<<'HTML'
<p>Most teams don't lose track of work because they lack a tool — they lose track of it because they're using three or four tools at once. A task gets assigned in a chat thread, a deadline lives in someone's calendar app, and the actual status update only exists in somebody's memory until the next stand-up. Every one of those handoffs is a place where a task quietly falls through the cracks.</p>
<p>Kawach Technology sees this exact problem from the client side constantly — teams asking us to build internal tools because their existing project software feels like it's fighting them rather than helping them. Rather than just describing that capability in a sales pitch, we decided to build a real product that solves it, and use it ourselves: Orbit, a project board where updates sync in real time, so nothing gets lost in a chat thread or forgotten in someone's inbox.</p>
<p>Building it also meant confronting a second, more technical challenge: most "real-time" project tools aren't actually real-time — they poll for updates every few seconds, or require a manual refresh. Making Orbit's sync genuinely instant, at a price point small teams and freelancers could actually afford, meant real architectural decisions, not just a marketing claim.</p>
HTML,

            'existing_challenges' => [
                ['text' => 'Teams juggle chat apps, spreadsheets, and separate calendar tools to track work that should live in one place.'],
                ['text' => 'Most project-management tools that claim "real-time" updates actually rely on polling or manual refresh, so teammates still see stale boards.'],
                ['text' => 'Automating repetitive steps (moving a card, notifying a teammate, applying a label) usually requires bolting on a separate automation tool.'],
                ['text' => 'Sharing progress with an external client or stakeholder usually means giving them a full account, or exporting a static screenshot that\'s outdated the moment it\'s sent.'],
                ['text' => 'Entry-level and free tiers on most competing tools cap out too low for a small team or a freelancer to rely on long-term.'],
            ],

            'solution' => <<<'HTML'
<p>We built Orbit on Laravel 12, using Laravel Reverb — Laravel's own WebSocket broadcasting server — as the backbone for real-time sync, rather than a polling-based "refresh every few seconds" approach. That decision is why a card move, a comment, or an edit shows up on a teammate's screen in under a second: the change is pushed the moment it happens, not fetched on the next poll cycle.</p>
<p>On top of that real-time core, we built the features a team actually needs day to day: drag-and-drop boards and cards, an Inbox for capturing tasks the instant they come up, checklists with progress bars, comments with emoji reactions, and file attachments with cover images. Premium workspaces add Calendar, Timeline, Table, Dashboard, and Map views of the same underlying data — so a manager can see a timeline while an individual contributor works the board view, both looking at the same live data.</p>
<p>To solve the "automation shouldn't need a separate tool" problem, we built Pilot — Orbit's own trigger-condition-action automation engine, directly inside the product. And to solve the "sharing progress with outsiders" problem, we built read-only public board links that need no login at all — useful for exactly the kind of client-visibility use case Kawach's own client work runs into constantly.</p>
<p>Billing runs on Stripe across four tiers (Free, Standard, Premium, Enterprise), with role-based access via Spatie's laravel-permission package splitting workspace members into Admins (who manage billing, invites, and see every board) and Participants (who only see boards they're added to). The Free plan is deliberately generous — unlimited cards, up to 10 boards, no credit card required — because we wanted small teams and freelancers to be able to actually run their real work on it, not just trial it.</p>
<p>One of Orbit's actual users summed up the result better than our own marketing copy could: <em>"We closed four other tabs the week we switched. Everything that used to live in a spreadsheet now just lives on a board people actually open."</em> — Maya Kessler, Head of Ops at Northwind Labs, an Orbit customer.</p>
HTML,

            'goals' => [
                ['title' => 'True Real-Time Sync', 'desc' => 'Push updates over WebSockets the instant they happen, instead of a polling-based refresh.', 'icon' => 'fas fa-bolt', 'color' => '#1a73e8'],
                ['title' => 'Automation Built In', 'desc' => 'Let teams automate repetitive workflow steps natively, without bolting on a separate tool.', 'icon' => 'fas fa-gears', 'color' => '#00c896'],
                ['title' => 'Accessible Pricing', 'desc' => 'Make the free tier genuinely usable for small teams and freelancers, not just a trial.', 'icon' => 'fas fa-hand-holding-dollar', 'color' => '#ffb830'],
                ['title' => 'Transparent Sharing', 'desc' => 'Let teams share live progress externally without handing out full account access.', 'icon' => 'fas fa-share-nodes', 'color' => '#7c3aed'],
            ],

            'solution_modules' => [
                ['name' => 'Boards & Cards', 'desc' => 'Drag-and-drop lists and cards, with checklists, comments, emoji reactions, attachments, and cover images.', 'icon' => 'fas fa-table-columns'],
                ['name' => 'Real-Time Sync Engine', 'desc' => 'Laravel Reverb WebSocket broadcasting pushes card moves, comments, and edits to every open board in under a second.', 'icon' => 'fas fa-tower-broadcast'],
                ['name' => 'Inbox', 'desc' => 'Capture a task the moment it comes up, then sort it onto a board when ready.', 'icon' => 'fas fa-inbox'],
                ['name' => 'Multiple Views', 'desc' => 'Calendar, Timeline, Table, Dashboard, and Map views of the same live board data (Premium and above).', 'icon' => 'fas fa-calendar-days'],
                ['name' => 'Pilot Automation', 'desc' => 'A built-in trigger-condition-action automation engine that handles repetitive workflow steps automatically.', 'icon' => 'fas fa-robot'],
                ['name' => 'Public Sharing & Power-Ups', 'desc' => 'Read-only public board links with no login required, plus a Google Sheets Power-Up for connecting boards to spreadsheets.', 'icon' => 'fas fa-link'],
            ],

            'kpis' => [
                ['label' => 'Real-Time Sync Latency', 'value' => '< 1 sec'],
                ['label' => 'Free Plan Boards', 'value' => '10'],
                ['label' => 'Pricing Tiers Shipped', 'value' => '4'],
                ['label' => 'Free Plan Cost', 'value' => '$0'],
            ],

            'technologies' => 'Laravel 12, Laravel Reverb, MySQL, Tailwind CSS 4, Vite, Stripe, Spatie Permissions',

            'tech_stack' => [
                ['category' => 'Backend', 'items' => 'Laravel 12, PHP 8.2, MySQL'],
                ['category' => 'Real-Time', 'items' => 'Laravel Reverb (WebSocket broadcasting)'],
                ['category' => 'Frontend', 'items' => 'Tailwind CSS 4, Vite, Axios'],
                ['category' => 'Billing & Access', 'items' => 'Stripe, Laravel Sanctum, Spatie Laravel-Permission'],
            ],

            'cs_process_steps' => [
                ['badge' => '01', 'title' => 'Problem Framing', 'desc' => 'Drew on patterns seen across Kawach\'s own client work — teams losing track of tasks across scattered tools — to scope what a genuinely better board needed to do.'],
                ['badge' => '02', 'title' => 'Real-Time Architecture', 'desc' => 'Chose Laravel Reverb over a polling-based approach so sync is pushed instantly rather than fetched on an interval.'],
                ['badge' => '03', 'title' => 'Core Board Build', 'desc' => 'Shipped boards, cards, checklists, comments, and attachments as the foundation before layering on advanced views.'],
                ['badge' => '04', 'title' => 'Billing & Access Control', 'desc' => 'Integrated Stripe across four pricing tiers and built Admin/Participant roles with Spatie\'s permission package.'],
                ['badge' => '05', 'title' => 'Pilot Automation Engine', 'desc' => 'Built Orbit\'s own trigger-condition-action automation system directly into the product, rather than relying on a third-party integration.'],
                ['badge' => '06', 'title' => 'Public Launch & Iteration', 'desc' => 'Shipped a free-forever plan to lower the barrier to entry, then kept iterating based on real usage and feedback from early users.'],
            ],

            'achievements' => [
                ['title' => 'Sub-Second Real-Time Sync in Production', 'desc' => 'Card moves, comments, and edits sync across teammates\' screens in under a second, backed by Laravel Reverb rather than polling.'],
                ['title' => 'Built-In Automation Engine Shipped', 'desc' => 'Pilot lets teams automate repetitive workflow steps without a separate integration or third-party tool.'],
                ['title' => 'Genuinely Usable Free Tier', 'desc' => 'The Free plan offers unlimited cards and up to 10 boards with no credit card required — enough for a small team to actually run on, not just trial.'],
                ['title' => 'Real Positive User Feedback', 'desc' => 'Early customers like Northwind Labs report replacing spreadsheets and chat-thread tracking entirely after switching to Orbit.'],
            ],

            'before_after' => [
                ['before' => 'Tasks tracked across chat threads, spreadsheets, and calendar apps', 'after' => 'One board where tasks, comments, and updates live together'],
                ['before' => '"Real-time" boards that actually rely on polling or manual refresh', 'after' => 'True WebSocket-pushed sync, updating in under a second'],
                ['before' => 'Automating a workflow step means adding a separate tool', 'after' => 'Pilot automates it natively, inside the board'],
                ['before' => 'Sharing progress externally means full account access or a stale screenshot', 'after' => 'A live, read-only public link — no login required'],
            ],

            'compliance_items' => [
                ['title' => 'Payment Data Security', 'desc' => 'Billing is processed through Stripe, a PCI-DSS Level 1 certified payment processor — no card data is ever stored on Orbit\'s own servers.', 'icon' => 'fas fa-lock'],
                ['title' => 'Role-Based Access Control', 'desc' => 'Admin and Participant roles (via Spatie\'s laravel-permission package) ensure workspace members only see the boards they\'ve been added to.', 'icon' => 'fas fa-user-shield'],
            ],

            'cs_features' => [
                ['icon' => 'fas fa-bolt', 'title' => 'Real-Time Sync', 'desc' => 'WebSocket-pushed updates via Laravel Reverb — under a second, not a polling delay.'],
                ['icon' => 'fas fa-robot', 'title' => 'Pilot Automation', 'desc' => 'A native trigger-condition-action engine for automating repetitive workflow steps.'],
                ['icon' => 'fas fa-link', 'title' => 'Public Board Sharing', 'desc' => 'Read-only public links for sharing live progress externally, no login required.'],
                ['icon' => 'fas fa-mobile-alt', 'title' => 'Mobile App & Pulse Feed', 'desc' => 'A mobile app with a feed of recent activity, so teams stay current from anywhere.'],
                ['icon' => 'fas fa-table', 'title' => 'Google Sheets Power-Up', 'desc' => 'Connects boards directly to the spreadsheets teams already use.'],
            ],

            'cs_faqs' => [
                ['question' => 'Is Orbit a client project or a Kawach Technology product?', 'answer' => 'Orbit is built and owned in-house by Kawach Technology — not a client engagement. We use it as both a real product and a demonstration of what our engineering team can build and ship.'],
                ['question' => 'What makes Orbit\'s "real-time" sync different from other project tools?', 'answer' => 'Orbit uses Laravel Reverb, a WebSocket broadcasting server, to push updates the instant they happen. Many competing tools instead poll for changes every few seconds or require a manual refresh.'],
                ['question' => 'Is Orbit still being actively developed?', 'answer' => 'Yes. Pilot, Orbit\'s built-in automation engine, is one of the more recent additions, and development continues alongside Kawach\'s client work.'],
                ['question' => 'Can I try Orbit myself?', 'answer' => 'Yes — Orbit has a free-forever plan with unlimited cards and up to 10 boards, no credit card required, at orbitzr.com.'],
            ],

            'gallery' => [],
            'testimonial_quote' => null,
            'testimonial_name'  => null,
            'testimonial_role'  => null,
        ]);
        $caseStudy->timestamps = false;
        $caseStudy->created_at = $createdAt;
        $caseStudy->updated_at = $updatedAt;
        $caseStudy->save();

        $this->command->info('✅ Orbit case study seeded (Page + PageCaseStudy), slug: ' . $slug);
    }
}
