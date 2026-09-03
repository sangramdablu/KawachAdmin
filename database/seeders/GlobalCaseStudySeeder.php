<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageCaseStudy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GlobalCaseStudySeeder extends Seeder
{
    // 10 hand-written USA/Europe case studies. Separate from CaseStudySeeder
    // (the original 5 India-based studies) — this batch is not to replace it.
    // Featured images intentionally left null (same reasoning as before).
    public function run(): void
    {
        foreach ($this->caseStudies() as $data) {
            $page = Page::where('slug', $data['slug'])->first();

            if ($page) {
                $createdAt = $page->created_at;
                $updatedAt = Carbon::now();
            } else {
                $createdAt = $this->randomDate('2025-01-01 08:00:00', '2026-08-31 20:00:00');
                $updatedAt = $this->randomDate($createdAt->copy()->toDateTimeString(), '2026-08-31 20:00:00');
                $page = new Page();
                $page->slug = $data['slug'];
            }

            $page->fill([
                'page_type'          => 'casestudy',
                'title'              => $data['title'],
                'status'             => 'published',
                'visibility'         => 'public',
                'page_password'      => null,
                'is_featured'        => $data['is_featured'],
                'sort_order'         => $data['sort_order'],
                'category_id'        => 2,
                'author_id'          => $data['author_id'],
                'published_at'       => $createdAt,
                'featured_image'     => null,
                'image_alt'          => $data['client_name'] . ' — ' . $data['title'],
                'image_title'        => $data['title'],
                'focus_keyword'      => $data['focus_keyword'],
                'meta_title'         => $data['meta_title'],
                'meta_description'  => $data['meta_description'],
                'meta_keywords'      => $data['tags'],
                'canonical_url'      => null,
                'robots'             => 'index, follow',
                'schema_type'        => 'WebPage',
                'og_title'           => $data['meta_title'],
                'og_description'     => $data['meta_description'],
                'og_image'           => null,
                'twitter_card'       => 'summary_large_image',
                'hreflang'           => 'en',
                'sitemap_priority'   => 0.8,
                'sitemap_changefreq' => 'monthly',
                'custom_head_script' => null,
                'tags'               => $data['tags'],
            ]);

            $page->timestamps = false;
            $page->created_at = $createdAt;
            $page->updated_at = $updatedAt;
            $page->save();

            $caseStudy = PageCaseStudy::firstOrNew(['page_id' => $page->id]);
            $caseStudy->fill([
                'client_name'         => $data['client_name'],
                'client_industry'     => $data['client_industry'],
                'business_size'       => $data['business_size'],
                'location'            => $data['location'],
                'business_model'      => $data['business_model'],
                'project_duration'    => $data['project_duration'],
                'completion_date'     => $data['completion_date'],
                'project_url'         => null,
                'challenge'           => $data['challenge'],
                'existing_challenges' => $data['existing_challenges'],
                'solution'            => $data['solution'],
                'goals'               => $data['goals'],
                'solution_modules'    => $data['solution_modules'],
                'kpis'                => $data['kpis'],
                'technologies'        => $data['technologies'],
                'tech_stack'          => $data['tech_stack'],
                'cs_process_steps'    => $data['cs_process_steps'],
                'achievements'        => $data['achievements'],
                'before_after'        => $data['before_after'],
                'compliance_items'    => $data['compliance_items'],
                'gallery'             => [],
                'testimonial_quote'   => $data['testimonial_quote'],
                'testimonial_name'    => $data['testimonial_name'],
                'testimonial_role'    => $data['testimonial_role'],
                'cs_features'         => $data['cs_features'],
                'cs_faqs'             => $data['cs_faqs'],
            ]);
            $caseStudy->timestamps = false;
            $caseStudy->created_at = $createdAt;
            $caseStudy->updated_at = $updatedAt;
            $caseStudy->save();
        }

        $this->command->info('✅ 10 global case studies seeded (Page + PageCaseStudy).');
    }

    private function randomDate(string $start, string $end): Carbon
    {
        $startTs = Carbon::parse($start)->timestamp;
        $endTs   = Carbon::parse($end)->timestamp;

        if ($endTs <= $startTs) {
            return Carbon::parse($start);
        }

        return Carbon::createFromTimestamp(random_int($startTs, $endTs));
    }

    private function caseStudies(): array
    {
        return [
            // ══════════════════════════════════════════════════════════════
            // 1. SaaS — Platform Re-Architecture (USA — Austin, TX)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'meridian-flow-technologies-saas-platform-case-study',
                'title'            => "Rebuilding Meridian Flow's Core Platform to Support 12,000+ Teams Without Sacrificing Speed",
                'is_featured'      => true,
                'sort_order'       => 6,
                'author_id'        => 1,
                'client_name'      => 'Meridian Flow Technologies',
                'client_industry'  => 'SaaS / Project Management Software',
                'business_size'    => '80-person SaaS company, 12,000+ active teams',
                'location'         => 'Austin, Texas, USA',
                'business_model'   => 'B2B SaaS — Project & Workflow Management',
                'project_duration' => '11 months',
                'completion_date'  => 'April 2026',
                'focus_keyword'    => 'SaaS platform re-architecture',
                'meta_title'       => 'SaaS Platform Re-Architecture Case Study — Meridian Flow | Kawach Technology',
                'meta_description' => 'How Kawach Technology re-architected Meridian Flow\'s monolithic SaaS platform for 12,000+ teams, cutting page load times 68% with zero-downtime migration.',
                'tags'             => 'SaaS development, project management software, multi-tenant architecture, platform scalability',

                'challenge' => <<<'HTML'
<p>Meridian Flow started as a scrappy workflow tool three engineers built in a weekend, and by the time they came to us it was running project management for more than 12,000 teams. That's a success story, but it had left the original Ruby on Rails monolith carrying far more weight than it was ever designed for. Every new feature meant touching a codebase where nobody fully understood every downstream effect anymore, and releases had become something the engineering team quietly dreaded rather than celebrated.</p>
<p>The deeper structural problem was that every customer's data lived in one shared database. That had been a reasonable shortcut in the early days, but as Meridian Flow started closing larger enterprise deals, security questionnaires began asking pointed questions about data isolation that the platform genuinely couldn't answer well. It wasn't just a compliance box to check — a handful of deals had actually stalled over it.</p>
<p>Performance was degrading in a way that tracked almost exactly with customer growth: page loads for larger teams had crept past four seconds, and "the app feels slow" had become a recurring theme in support tickets. Worse, the team had no real observability into where time was actually going — every performance investigation started from guesswork rather than data.</p>
<p>And because there was no way to ship a change to a subset of customers first, every release was an all-or-nothing bet on 12,000 teams at once. That risk aversion had itself become a drag on the product roadmap — the team was shipping less, specifically because shipping had become so nerve-wracking.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'The original monolithic Rails application had grown into a single sprawling codebase that made every new feature riskier to ship.'],
                    ['text' => 'All customer data lived in one shared database, creating both a performance bottleneck and a data-isolation risk as enterprise customers began asking pointed security questions.'],
                    ['text' => "Page load times had crept past 4 seconds for larger teams, and support tickets about \"the app feels slow\" were rising every quarter."],
                    ['text' => 'Releasing a new feature meant a nerve-wracking full-platform deploy, since there was no way to ship changes to a subset of customers first.'],
                    ['text' => 'The engineering team had no reliable way to see which parts of the platform were actually driving performance complaints, since observability had never been built in.'],
                ],

                'solution' => <<<'HTML'
<p>We didn't rewrite Meridian Flow from scratch — a full rewrite of a platform serving 12,000 live teams would have been its own kind of reckless. Instead we used a strangler-fig approach: extracting one bounded module at a time into an isolated service while the monolith kept serving everything we hadn't migrated yet, so the platform stayed live and stable throughout.</p>
<p>Tenant isolation came first, since it was both the most urgent business risk and the foundation everything else depended on. We designed a data architecture that gives each tenant genuinely isolated storage rather than a shared table with a tenant_id column, then built migration tooling capable of moving a live tenant's data over with zero downtime — no maintenance windows, no "please don't use the app for the next hour" emails to customers.</p>
<p>On top of that isolation layer, we introduced feature flags via LaunchDarkly so new releases could roll out to 1% of tenants, then 10%, then everyone — turning what used to be an all-or-nothing bet into a controlled, reversible rollout. We also built proper observability from the ground up with Datadog, so for the first time the team could see exactly which queries, endpoints, or tenants were driving slow page loads instead of guessing.</p>
<p>The caching layer we added in front of the most expensive queries did more for raw page-load speed than any other single change — cutting load times for the largest teams from over four seconds to well under a second and a half. We migrated all 12,000+ tenants over roughly eight months, watching migration health metrics closely enough that if anything looked wrong for even one tenant, we could pause and investigate before moving further.</p>
HTML,

                'goals' => [
                    ['title' => 'Modularize the Monolith', 'desc' => 'Break the single sprawling Rails codebase into isolated, independently deployable services.', 'icon' => 'fas fa-cubes', 'color' => '#1a73e8'],
                    ['title' => 'Guarantee Tenant Data Isolation', 'desc' => 'Give every customer genuinely isolated data storage, not just a shared table with a tenant column.', 'icon' => 'fas fa-shield-halved', 'color' => '#00c896'],
                    ['title' => 'Cut Page Load Times', 'desc' => 'Bring load times for the largest teams back under 1.5 seconds.', 'icon' => 'fas fa-gauge-high', 'color' => '#ffb830'],
                    ['title' => 'Enable Safer Releases', 'desc' => 'Replace all-or-nothing deploys with progressive, flag-controlled rollouts.', 'icon' => 'fas fa-flag', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Tenant Isolation Layer', 'desc' => 'Dedicated, isolated data storage per tenant rather than shared tables, closing the security gap that had stalled enterprise deals.', 'icon' => 'fas fa-vault'],
                    ['name' => 'Modular Service Boundaries', 'desc' => 'Bounded services extracted from the monolith using a strangler-fig migration pattern.', 'icon' => 'fas fa-diagram-project'],
                    ['name' => 'Feature Flag & Progressive Rollout', 'desc' => 'LaunchDarkly-powered rollouts that ship to a small percentage of tenants before going wide.', 'icon' => 'fas fa-toggle-on'],
                    ['name' => 'Observability & Performance Monitoring', 'desc' => 'Datadog-based request-level tracing so performance issues are diagnosed with data, not guesswork.', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Caching Layer', 'desc' => 'Redis caching in front of the most expensive queries, the single biggest lever on page load time.', 'icon' => 'fas fa-bolt'],
                    ['name' => 'Zero-Downtime Migration Toolkit', 'desc' => 'Purpose-built tooling to move a live tenant onto the new architecture with no service interruption.', 'icon' => 'fas fa-arrows-turn-to-dots'],
                ],

                'kpis' => [
                    ['label' => 'Page Load Time', 'value' => '-68%'],
                    ['label' => 'Deployment Frequency', 'value' => '+4x'],
                    ['label' => 'Customer-Reported Incidents', 'value' => '-55%'],
                    ['label' => 'Tenant Migration Downtime', 'value' => '0 min'],
                ],

                'technologies' => 'Ruby on Rails, PostgreSQL, Redis, Kubernetes, Datadog, LaunchDarkly',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Ruby on Rails 7, PostgreSQL 15'],
                    ['category' => 'Infrastructure', 'items' => 'Kubernetes, AWS EKS, Redis'],
                    ['category' => 'Observability', 'items' => 'Datadog, Sentry'],
                    ['category' => 'Release Management', 'items' => 'LaunchDarkly feature flags, GitHub Actions CI/CD'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Audit & Bottleneck Mapping', 'desc' => 'Profiled the monolith under real production load to identify exactly which queries and modules were driving slowness.'],
                    ['badge' => '02', 'title' => 'Tenant Isolation Design', 'desc' => 'Designed a data architecture giving each tenant genuinely isolated storage, addressing the security gap stalling enterprise deals.'],
                    ['badge' => '03', 'title' => 'Modular Extraction', 'desc' => 'Used a strangler-fig pattern to extract bounded services one at a time while the monolith kept serving everything else.'],
                    ['badge' => '04', 'title' => 'Zero-Downtime Migration Tooling', 'desc' => 'Built and tested tooling to move a live tenant onto the new architecture without any service interruption.'],
                    ['badge' => '05', 'title' => 'Progressive Rollout Framework', 'desc' => 'Introduced feature flags so new releases ship to a small percentage of tenants before going wide.'],
                    ['badge' => '06', 'title' => 'Post-Migration Performance Tuning', 'desc' => 'Layered in caching and query optimization once the new architecture was live, using real observability data.'],
                ],

                'achievements' => [
                    ['title' => 'Zero-Downtime Migration for 12,000+ Tenants', 'desc' => 'Every tenant was moved onto the new isolated architecture without a single reported service interruption.'],
                    ['title' => 'Cut Incident Rate by Over Half', 'desc' => 'Customer-reported incidents dropped 55% once tenant isolation and observability were in place.'],
                    ['title' => 'Unblocked Stalled Enterprise Deals', 'desc' => 'Genuine tenant data isolation resolved the security questionnaire concerns that had stalled several enterprise sales conversations.'],
                ],

                'before_after' => [
                    ['before' => 'All tenants in one shared database', 'after' => 'Genuinely isolated per-tenant data storage'],
                    ['before' => '4+ second load times for larger teams', 'after' => 'Sub-1.5-second load times platform-wide'],
                    ['before' => 'Full-platform, all-or-nothing deploys', 'after' => 'Progressive, flag-controlled rollouts'],
                    ['before' => 'No visibility into performance issues', 'after' => 'Full request-level observability via Datadog'],
                ],

                'compliance_items' => [
                    ['title' => 'SOC 2 Type II Readiness', 'desc' => 'The isolation and access-control architecture was built to directly support SOC 2 Type II audit requirements.', 'icon' => 'fas fa-certificate'],
                    ['title' => 'Tenant Data Isolation', 'desc' => 'Each customer\'s data is stored in genuinely isolated infrastructure, not just logically separated rows.', 'icon' => 'fas fa-vault'],
                    ['title' => 'Encryption in Transit & at Rest', 'desc' => 'All tenant data is encrypted both while stored and while moving between services.', 'icon' => 'fas fa-lock'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-vault', 'title' => 'Multi-Tenant Isolation', 'desc' => 'Genuinely isolated data storage per customer, not shared tables.'],
                    ['icon' => 'fas fa-toggle-on', 'title' => 'Feature Flag Rollouts', 'desc' => 'Ship new features to a subset of tenants before going wide.'],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Real-Time Observability', 'desc' => 'Request-level tracing to diagnose performance issues with data.'],
                    ['icon' => 'fas fa-bolt', 'title' => 'Redis Caching Layer', 'desc' => 'Caches the most expensive queries to keep pages fast at scale.'],
                    ['icon' => 'fas fa-arrows-turn-to-dots', 'title' => 'Zero-Downtime Migrations', 'desc' => 'Move tenants onto new infrastructure with no service interruption.'],
                    ['icon' => 'fas fa-cubes', 'title' => 'Modular Service Architecture', 'desc' => 'Independently deployable services instead of one sprawling monolith.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How did you migrate 12,000+ live tenants without any downtime?', 'answer' => 'We built dedicated migration tooling that copies a tenant\'s data to the new isolated architecture in the background, verifies it matches exactly, then atomically switches that tenant over — with automatic rollback if anything looked off.'],
                    ['question' => 'Did customers notice the migration happening?', 'answer' => 'No — that was the point. Each tenant was moved individually with no maintenance window, and the vast majority of customers had no idea a migration had even occurred.'],
                    ['question' => 'How does the new architecture support SOC 2 compliance?', 'answer' => 'Genuine per-tenant data isolation, full audit logging, and encryption at rest and in transit were built in from the start specifically to satisfy SOC 2 Type II control requirements.'],
                    ['question' => 'Did existing API integrations break during the re-architecture?', 'answer' => "No. We kept the public API contract stable throughout — the re-architecture happened entirely behind the existing API surface, so integrations built by Meridian Flow's customers kept working without changes."],
                ],

                'testimonial_quote' => "We knew our platform had outgrown its foundations, but a project like this is terrifying to greenlight — you're operating on a plane while it's still flying. Kawach's migration-by-tenant approach meant we never had a single all-hands-on-deck moment. Our page loads are faster, our incident rate is down more than half, and we finally have a real answer when enterprise security teams ask about data isolation.",
                'testimonial_name' => 'Jordan Whitfield',
                'testimonial_role' => 'VP of Engineering, Meridian Flow Technologies',
            ],

            // ══════════════════════════════════════════════════════════════
            // 2. PropTech — Real Estate Platform (USA — San Francisco, CA)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'sequoia-peak-realty-group-proptech-case-study',
                'title'            => 'Building a Unified Listings & Tenant Management Platform for Sequoia Peak Realty Group',
                'is_featured'      => false,
                'sort_order'       => 7,
                'author_id'        => 2,
                'client_name'      => 'Sequoia Peak Realty Group',
                'client_industry'  => 'Real Estate / PropTech',
                'business_size'    => '40+ offices, 2,000+ agents nationwide',
                'location'         => 'San Francisco, California, USA',
                'business_model'   => 'Real Estate Brokerage & Property Management',
                'project_duration' => '6 months',
                'completion_date'  => 'October 2025',
                'focus_keyword'    => 'real estate software development',
                'meta_title'       => 'PropTech Platform Case Study — Sequoia Peak Realty Group | Kawach Technology',
                'meta_description' => 'How Kawach Technology unified listings, leads, and lease management for Sequoia Peak Realty Group across 40+ offices and 2,000+ agents.',
                'tags'             => 'proptech platform, real estate CRM, property listing software, tenant management system',

                'challenge' => <<<'HTML'
<p>Sequoia Peak Realty Group had grown to more than 40 offices and 2,000 agents largely through acquisition, which meant it had also inherited more than 40 different ways of doing the same basic job. Listings were updated manually across the MLS, the public website, and an aging internal CRM — three separate systems that were supposed to show the same information but routinely didn't, because nothing kept them in sync.</p>
<p>That data drift had a real cost on the sales side. Leads captured through the website often sat unassigned for a day or more before reaching an actual agent, by which point plenty of prospective buyers had already called a competitor. On the property management side of the business, leases and maintenance requests were tracked in spreadsheets that varied office to office, making it nearly impossible for regional managers to get a consistent view of occupancy or renewal risk.</p>
<p>Client-facing paperwork hadn't caught up to the times either — offers and disclosures in many offices still relied on emailed PDFs printed, signed by hand, scanned, and emailed back, adding days to transactions that should have taken hours. And because none of these systems talked to each other, regional managers had no reliable way to see agent performance or listing velocity without calling each office individually and asking.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Listings were updated manually across MLS, the website, and the internal CRM, leading to stale and mismatched data.'],
                    ['text' => 'Leads captured on the website often sat unassigned for a day or more before reaching an agent.'],
                    ['text' => "The property management arm tracked leases and maintenance requests in spreadsheets that varied office to office."],
                    ['text' => 'Regional managers had no centralized view of agent performance or listing velocity across offices.'],
                    ['text' => 'Client document signing for offers and disclosures still relied on printed, hand-signed, and scanned PDFs in many offices.'],
                ],

                'solution' => <<<'HTML'
<p>The fix had to start with data, not features: we built a unified listing sync engine that treats one system as the single source of truth and pushes updates out to the MLS, the public website, and the internal CRM automatically. Once that was live, an agent updating a listing's price or status in one place saw it reflected everywhere within minutes, not whenever someone remembered to update the other two systems.</p>
<p>For lead handling, we built automated routing rules based on property type, location, and agent availability, so a new web lead reaches an assigned agent's phone within minutes of being submitted rather than sitting in a shared inbox. The property management side got its own tenant and lease portal, giving both tenants and managers a shared view of lease terms, renewal dates, and maintenance requests — no more spreadsheet reconciliation across offices.</p>
<p>We integrated DocuSign directly into the offer and disclosure workflow, cutting a process that used to take days down to same-day turnaround in most cases. On top of all of this, we built regional performance dashboards pulling live data from every connected office, so managers finally had real numbers instead of anecdotes when evaluating listing velocity or agent performance.</p>
<p>We piloted the full system across three offices of varying size before the national rollout, deliberately choosing offices with different existing workflows to surface edge cases early — a decision that caught several data-mapping issues in the MLS sync we would rather have found in three offices than forty.</p>
HTML,

                'goals' => [
                    ['title' => 'Unify Listing Data', 'desc' => 'Make MLS, website, and internal CRM listing data agree, always.', 'icon' => 'fas fa-house-signal', 'color' => '#1a73e8'],
                    ['title' => 'Speed Up Lead Response', 'desc' => 'Route new leads to an available agent within minutes of submission.', 'icon' => 'fas fa-bolt', 'color' => '#00c896'],
                    ['title' => 'Digitize Lease Management', 'desc' => 'Give tenants and property managers a shared, real-time view of lease and maintenance status.', 'icon' => 'fas fa-file-contract', 'color' => '#ffb830'],
                    ['title' => 'Give Managers Real Visibility', 'desc' => 'Replace office-by-office phone calls with live regional performance dashboards.', 'icon' => 'fas fa-chart-pie', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Unified Listing Sync Engine', 'desc' => 'A single source of truth that pushes listing updates to MLS, website, and CRM automatically.', 'icon' => 'fas fa-arrows-spin'],
                    ['name' => 'Automated Lead Routing', 'desc' => 'Routes new leads to an available agent based on property type, location, and workload.', 'icon' => 'fas fa-route'],
                    ['name' => 'Tenant & Lease Portal', 'desc' => 'A shared view of lease terms, renewal dates, and payment status for tenants and managers.', 'icon' => 'fas fa-key'],
                    ['name' => 'Maintenance Request Tracking', 'desc' => 'Digital submission and status tracking for property maintenance requests.', 'icon' => 'fas fa-screwdriver-wrench'],
                    ['name' => 'E-Signature for Offers & Disclosures', 'desc' => 'DocuSign-integrated signing for offers, disclosures, and lease documents.', 'icon' => 'fas fa-file-signature'],
                    ['name' => 'Regional Performance Dashboards', 'desc' => 'Live listing velocity and agent performance data across all connected offices.', 'icon' => 'fas fa-chart-column'],
                ],

                'kpis' => [
                    ['label' => 'Lead Response Time', 'value' => '18 hrs → 12 min'],
                    ['label' => 'Listing Data Accuracy', 'value' => '99%+'],
                    ['label' => 'Lease Renewal Rate', 'value' => '+19%'],
                    ['label' => 'Agent Onboarding Time', 'value' => '-40%'],
                ],

                'technologies' => 'Laravel, Vue.js, MySQL, DocuSign API, AWS, MLS RETS/RESO integration',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, MySQL 8'],
                    ['category' => 'Frontend', 'items' => 'Vue.js 3, Tailwind CSS'],
                    ['category' => 'Integrations', 'items' => 'MLS RESO Web API, DocuSign API, Twilio'],
                    ['category' => 'Infrastructure', 'items' => 'AWS EC2, RDS, S3'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Multi-Office Discovery', 'desc' => 'Surveyed workflows across offices of different sizes to map every variation before designing the unified system.'],
                    ['badge' => '02', 'title' => 'Listing Sync Architecture', 'desc' => 'Designed the single-source-of-truth data model feeding MLS, website, and CRM automatically.'],
                    ['badge' => '03', 'title' => 'Lead Routing Rules Design', 'desc' => "Built routing logic based on property type, location, and agent availability, tuned with the sales team's input."],
                    ['badge' => '04', 'title' => 'Tenant Portal Build', 'desc' => 'Developed the shared lease and maintenance portal for the property management arm.'],
                    ['badge' => '05', 'title' => 'Pilot Across 3 Offices', 'desc' => 'Rolled out to three offices of varying size and workflow maturity to surface edge cases before going national.'],
                    ['badge' => '06', 'title' => 'National Rollout', 'desc' => 'Deployed to all 40+ offices in stages, with dedicated onboarding support for each region.'],
                ],

                'achievements' => [
                    ['title' => 'Rolled Out to 40+ Offices in 6 Months', 'desc' => 'The full national rollout across all offices and 2,000+ agents completed within the original 6-month timeline.'],
                    ['title' => 'Lead Response Cut from Hours to Minutes', 'desc' => 'Automated routing brought average lead response time down from roughly 18 hours to about 12 minutes.'],
                    ['title' => 'Double-Digit Lease Renewal Improvement', 'desc' => 'The tenant portal and proactive renewal reminders lifted lease renewal rates by 19%.'],
                ],

                'before_after' => [
                    ['before' => 'Listings updated manually across 3 separate systems', 'after' => 'One source of truth synced everywhere automatically'],
                    ['before' => 'Leads unassigned for a day or more', 'after' => 'Leads auto-routed to an agent within minutes'],
                    ['before' => 'Paper-based offers and disclosures', 'after' => 'Same-day e-signature turnaround via DocuSign'],
                    ['before' => 'No cross-office visibility for regional managers', 'after' => 'Live regional performance dashboards'],
                ],

                'compliance_items' => [
                    ['title' => 'Fair Housing Act Alignment', 'desc' => 'Listing and disclosure workflows were reviewed to align with Fair Housing Act disclosure requirements.', 'icon' => 'fas fa-scale-balanced'],
                    ['title' => 'ESIGN Act Compliant Signatures', 'desc' => 'All e-signatures captured through the platform meet U.S. ESIGN Act legal requirements.', 'icon' => 'fas fa-signature'],
                    ['title' => 'Secure Document Storage', 'desc' => 'Signed offers, disclosures, and leases are stored encrypted with role-based access.', 'icon' => 'fas fa-lock'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-arrows-spin', 'title' => 'Unified Listing Sync', 'desc' => 'One source of truth pushed automatically to MLS, website, and CRM.'],
                    ['icon' => 'fas fa-route', 'title' => 'Automated Lead Routing', 'desc' => 'New leads reach an available agent within minutes.'],
                    ['icon' => 'fas fa-key', 'title' => 'Tenant & Lease Portal', 'desc' => 'Shared lease and maintenance visibility for tenants and managers.'],
                    ['icon' => 'fas fa-file-signature', 'title' => 'E-Signature Workflows', 'desc' => 'DocuSign-integrated signing for offers and disclosures.'],
                    ['icon' => 'fas fa-chart-column', 'title' => 'Regional Dashboards', 'desc' => 'Live listing velocity and agent performance across offices.'],
                    ['icon' => 'fas fa-screwdriver-wrench', 'title' => 'Maintenance Tracking', 'desc' => 'Digital submission and status tracking for property requests.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How complex was integrating with the MLS across different regional boards?', 'answer' => "Each regional MLS board has its own data quirks, so we built the sync engine with a mapping layer per board rather than assuming one schema fits all — that flexibility was validated during the 3-office pilot before national rollout."],
                    ['question' => 'How did you get 2,000+ agents across 40 offices to adopt a new system?', 'answer' => 'We piloted with offices of different sizes and workflow styles first, refined the interface based on their feedback, then rolled out nationally with dedicated onboarding support for each region rather than a single company-wide training day.'],
                    ['question' => 'Are e-signatures collected through the platform legally binding?', 'answer' => 'Yes — the DocuSign integration meets ESIGN Act requirements for legally binding electronic signatures on real estate documents.'],
                    ['question' => 'How was existing CRM data migrated without loss?', 'answer' => "We ran the old and new systems in parallel during each office's transition window, reconciling listing and contact data before fully cutting over, rather than a one-shot migration."],
                ],

                'testimonial_quote' => "Growing through acquisition meant we'd inherited 40 different ways of running a real estate office. Kawach didn't try to force one rigid system on everyone — they built something flexible enough to unify our data while still fitting how each office actually worked. Our lead response time alone probably justified the whole project.",
                'testimonial_name' => 'Diane Castellano',
                'testimonial_role' => 'Chief Operating Officer, Sequoia Peak Realty Group',
            ],

            // ══════════════════════════════════════════════════════════════
            // 3. Industrial IoT — Predictive Maintenance (Germany — Stuttgart)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'nordholt-manufacturing-industrial-iot-case-study',
                'title'            => 'Predictive Maintenance: How Nordholt Manufacturing Cut Unplanned Downtime by 47% with Industrial IoT',
                'is_featured'      => false,
                'sort_order'       => 8,
                'author_id'        => 1,
                'client_name'      => 'Nordholt Manufacturing GmbH',
                'client_industry'  => 'Precision Component Manufacturing',
                'business_size'    => '3 factories, 900+ employees',
                'location'         => 'Stuttgart, Germany',
                'business_model'   => 'B2B Industrial Manufacturing',
                'project_duration' => '12 months',
                'completion_date'  => 'February 2026',
                'focus_keyword'    => 'industrial IoT software development',
                'meta_title'       => 'Industrial IoT Case Study — Nordholt Manufacturing | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a GDPR-compliant predictive maintenance platform for Nordholt Manufacturing, cutting unplanned downtime by 47% across 3 factories.',
                'tags'             => 'industrial IoT, predictive maintenance, manufacturing software, GDPR compliant',

                'challenge' => <<<'HTML'
<p>Nordholt Manufacturing runs three precision-component factories across Germany, and for years its maintenance philosophy was the industry default: service every machine on a fixed calendar interval, regardless of how much actual wear that specific machine had accumulated. It's a reasonable-sounding approach until you realize it means perfectly healthy machines get serviced unnecessarily while others fail well before their scheduled maintenance date.</p>
<p>Those unplanned failures were the real cost. A single unannounced breakdown could halt an entire production line with no warning, and because each factory kept its own paper-based maintenance logs, there was no way to spot patterns — a machine that failed the same way twice, three months apart, at two different sites, looked like two unrelated incidents rather than one identifiable pattern.</p>
<p>Plant managers had no real-time way to check machine health beyond physically walking the floor, and where sensor data existed at all, it sat locally on individual machine controllers rather than being collected anywhere central. Making this harder still, any system touching workforce shift data in Germany has to take GDPR seriously from day one — this wasn't a system we could bolt privacy onto after the fact.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Maintenance was scheduled on fixed calendar intervals regardless of actual machine wear.'],
                    ['text' => 'Unplanned breakdowns halted production lines with no early warning.'],
                    ['text' => 'Each factory kept its own paper-based maintenance logs, with no cross-site pattern detection.'],
                    ['text' => 'Plant managers had no real-time dashboard of machine health across sites.'],
                    ['text' => 'Machine sensor data, where it existed, was not centrally collected or analyzed.'],
                ],

                'solution' => <<<'HTML'
<p>We started on the factory floor, literally — walking each production line with maintenance staff to understand which machines were the highest-value candidates for sensor retrofitting, since instrumenting all 600+ machines on day one wasn't realistic or necessary. Vibration, temperature, and power-draw sensors were fitted to priority machines first, streaming data over MQTT to a central ingestion pipeline.</p>
<p>That data feeds a predictive maintenance model trained specifically on Nordholt's own historical failure patterns rather than a generic industry model, since failure signatures genuinely differ by machine make, age, and workload. The model flags machines showing early signs of the wear patterns that have historically preceded failure, giving maintenance teams days or weeks of lead time instead of a floor manager discovering a stalled line.</p>
<p>Because any system touching German workforce data has to take GDPR seriously, we designed the shift-scheduling and maintenance-assignment features to anonymize or aggregate any employee-linked data at the point of collection wherever the analysis didn't actually require an individual identity — machine health monitoring almost never needs to know which specific technician was on shift, for instance.</p>
<p>We piloted the sensor retrofit and predictive model on a single production line for several weeks, comparing its predictions against what actually happened, before expanding to the other lines and eventually all three factories. That validation period mattered — it caught a few false-positive patterns in the early model that would have eroded trust with the maintenance team if they'd shown up during full rollout instead.</p>
HTML,

                'goals' => [
                    ['title' => 'Predict Failures Before They Happen', 'desc' => 'Move from calendar-based maintenance to data-driven, predictive scheduling.', 'icon' => 'fas fa-brain', 'color' => '#1a73e8'],
                    ['title' => 'Centralize Machine Health Data', 'desc' => 'Bring sensor data from all 3 factories into one central platform.', 'icon' => 'fas fa-server', 'color' => '#00c896'],
                    ['title' => 'Reduce Unplanned Downtime', 'desc' => 'Give maintenance teams early warning instead of discovering failures after the fact.', 'icon' => 'fas fa-triangle-exclamation', 'color' => '#ffb830'],
                    ['title' => 'Standardize Maintenance Across Factories', 'desc' => 'Replace inconsistent paper logs with one digital system across all sites.', 'icon' => 'fas fa-clipboard-list', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'IoT Sensor Integration', 'desc' => 'Vibration, temperature, and power-draw sensors on priority machines, streaming data via MQTT.', 'icon' => 'fas fa-microchip'],
                    ['name' => 'Predictive Maintenance Engine', 'desc' => 'A machine-learning model trained on Nordholt\'s own historical failure data to flag early warning signs.', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Central Machine Health Dashboard', 'desc' => 'Real-time machine status across all 3 factories in one view.', 'icon' => 'fas fa-gauge'],
                    ['name' => 'Maintenance Scheduling & Work Orders', 'desc' => 'Digital work orders generated automatically from predictive alerts.', 'icon' => 'fas fa-list-check'],
                    ['name' => 'Cross-Factory Reporting', 'desc' => 'Pattern detection across sites to catch recurring failure types before they repeat.', 'icon' => 'fas fa-magnifying-glass-chart'],
                    ['name' => 'Alerting & Escalation', 'desc' => 'Automated alerts to the right maintenance team as soon as a machine shows early warning signs.', 'icon' => 'fas fa-bell'],
                ],

                'kpis' => [
                    ['label' => 'Unplanned Downtime', 'value' => '-47%'],
                    ['label' => 'Maintenance Cost', 'value' => '-22%'],
                    ['label' => 'Mean Time to Repair', 'value' => '-35%'],
                    ['label' => 'Sensor Coverage', 'value' => '600+ machines'],
                ],

                'technologies' => 'Python, TensorFlow, InfluxDB, MQTT, Azure IoT Hub, React',

                'tech_stack' => [
                    ['category' => 'Data Ingestion', 'items' => 'MQTT, Azure IoT Hub'],
                    ['category' => 'ML & Analytics', 'items' => 'Python, TensorFlow, InfluxDB'],
                    ['category' => 'Frontend', 'items' => 'React, D3.js'],
                    ['category' => 'Infrastructure', 'items' => 'Azure (EU region), on-prem edge gateways'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Factory Floor Assessment', 'desc' => 'Walked each production line with maintenance staff to identify the highest-value machines for sensor retrofitting.'],
                    ['badge' => '02', 'title' => 'Sensor Retrofit Planning', 'desc' => 'Fitted vibration, temperature, and power-draw sensors to priority machines without halting production.'],
                    ['badge' => '03', 'title' => 'Data Pipeline Build', 'desc' => 'Built the MQTT-to-cloud ingestion pipeline feeding a central time-series database.'],
                    ['badge' => '04', 'title' => 'Predictive Model Training', 'desc' => "Trained the failure-prediction model on Nordholt's own historical maintenance and failure records."],
                    ['badge' => '05', 'title' => 'Pilot on One Production Line', 'desc' => 'Validated model predictions against real outcomes on a single line for several weeks before expanding.'],
                    ['badge' => '06', 'title' => 'Rollout Across 3 Factories', 'desc' => 'Expanded sensor coverage and the predictive model to all production lines at all 3 sites.'],
                ],

                'achievements' => [
                    ['title' => '600+ Machines Instrumented', 'desc' => 'Sensor coverage now spans priority machines across all 3 factories.'],
                    ['title' => 'Unplanned Downtime Nearly Halved', 'desc' => 'Predictive alerts cut unplanned downtime by 47% within the first full year.'],
                    ['title' => 'Maintenance Costs Down Without Added Headcount', 'desc' => 'More targeted, data-driven maintenance reduced costs while using the existing maintenance team.'],
                ],

                'before_after' => [
                    ['before' => 'Fixed-interval maintenance regardless of wear', 'after' => 'Predictive, data-driven maintenance scheduling'],
                    ['before' => 'No cross-factory machine health visibility', 'after' => 'Central dashboard covering all 3 sites'],
                    ['before' => 'Paper-based maintenance logs', 'after' => 'Digital work orders with full history'],
                    ['before' => 'Failures discovered after the line stopped', 'after' => 'Early-warning alerts days or weeks ahead'],
                ],

                'compliance_items' => [
                    ['title' => 'GDPR-Compliant Data Handling', 'desc' => 'Any employee-linked shift data is anonymized or aggregated wherever individual identity isn\'t required for the analysis.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'EU Data Residency', 'desc' => 'All machine and operational data is stored on Azure infrastructure within the EU.', 'icon' => 'fas fa-earth-europe'],
                    ['title' => 'Industrial Data Security', 'desc' => 'Sensor and network architecture aligned with IEC 62443 industrial security principles.', 'icon' => 'fas fa-shield-halved'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-brain', 'title' => 'Predictive Failure Alerts', 'desc' => 'Early warning days or weeks before a machine would historically fail.'],
                    ['icon' => 'fas fa-gauge', 'title' => 'Central Machine Health Dashboard', 'desc' => 'Real-time visibility into every instrumented machine across 3 factories.'],
                    ['icon' => 'fas fa-list-check', 'title' => 'Digital Work Orders', 'desc' => 'Auto-generated maintenance work orders from predictive alerts.'],
                    ['icon' => 'fas fa-magnifying-glass-chart', 'title' => 'Cross-Factory Reporting', 'desc' => 'Pattern detection across sites to catch recurring failure types.'],
                    ['icon' => 'fas fa-microchip', 'title' => 'Sensor Retrofit Kit', 'desc' => 'Non-invasive sensor installation on existing machines.'],
                    ['icon' => 'fas fa-user-shield', 'title' => 'GDPR-Compliant Data Pipeline', 'desc' => 'Employee-linked data anonymized wherever identity isn\'t required.'],
                ],

                'cs_faqs' => [
                    ['question' => 'Did you need to replace existing machines to add sensors?', 'answer' => 'No — the sensor retrofit kit was designed to attach to existing machines non-invasively, so no equipment replacement was needed.'],
                    ['question' => 'How is GDPR handled for shift and machine-operator data?', 'answer' => 'Wherever the maintenance or predictive analysis doesn\'t actually require knowing which individual employee was involved, that data is anonymized or aggregated at the point of collection rather than stored in identifiable form.'],
                    ['question' => 'How accurate are the predictive maintenance alerts?', 'answer' => "We validated the model against real outcomes on a pilot production line for several weeks before wider rollout, and continue to tune it against Nordholt's own ongoing maintenance data rather than relying on a static, generic model."],
                    ['question' => 'Did the sensor installation disrupt production?', 'answer' => 'Installation was scheduled around existing planned maintenance windows specifically to avoid any additional production disruption.'],
                ],

                'testimonial_quote' => "We'd been maintaining machines on the calendar for decades because that's simply how it's always been done in manufacturing. Seeing real predictive alerts catch a bearing failure two weeks before it would have stopped a line — that changed how our maintenance team thinks about their job. Downtime is down nearly half, and just as importantly, our technicians trust the alerts now.",
                'testimonial_name' => 'Klaus Reinholt',
                'testimonial_role' => 'Head of Plant Operations, Nordholt Manufacturing GmbH',
            ],

            // ══════════════════════════════════════════════════════════════
            // 4. Hospitality — Direct Booking Platform (UK — London)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'harborview-hospitality-group-booking-platform-case-study',
                'title'            => 'How Harborview Hospitality Group Cut OTA Commission Costs with a Direct Booking Platform',
                'is_featured'      => true,
                'sort_order'       => 9,
                'author_id'        => 2,
                'client_name'      => 'Harborview Hospitality Group',
                'client_industry'  => 'Hospitality / Boutique Hotels',
                'business_size'    => '22 boutique hotels across the UK and Ireland',
                'location'         => 'London, United Kingdom',
                'business_model'   => 'Boutique Hotel Group',
                'project_duration' => '7 months',
                'completion_date'  => 'December 2025',
                'focus_keyword'    => 'hotel booking platform development',
                'meta_title'       => 'Hotel Booking Platform Case Study — Harborview Hospitality | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a direct booking platform for Harborview Hospitality Group, growing direct bookings 31% and cutting OTA commission costs 24%.',
                'tags'             => 'hotel booking software, hospitality technology, direct booking platform, channel manager integration',

                'challenge' => <<<'HTML'
<p>Harborview Hospitality Group runs 22 boutique hotels across the UK and Ireland, each with its own character but sharing one uncomfortable business reality: roughly 70% of reservations arrived through third-party online travel agencies charging 15-20% commission on every booking. That's a substantial and growing tax on revenue for a hotel group whose guests, once they'd stayed once, often genuinely preferred booking directly — they just weren't given a good enough reason or interface to do so.</p>
<p>Each property still ran its own booking widget, and most of them were badly outdated relics from a decade of incremental patches rather than any real redesign. Guest profiles didn't carry over between properties either, so a guest who'd stayed at the Bath property twice and was now booking the London property for the first time was treated, digitally, as a total stranger — no recognition, no personalization, nothing that might have nudged them toward loyalty.</p>
<p>Behind the scenes, rate parity across OTAs and the direct site was checked and adjusted by hand, which meant occasional embarrassing mismatches where the OTA listing undercut Harborview's own direct rate. And there was no loyalty or rewards program of any kind — nothing systematically encouraging the guests most likely to book direct to actually do so.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Roughly 70% of bookings came through OTAs charging 15-20% commission per reservation.'],
                    ['text' => 'Each property had its own outdated booking widget, discouraging direct bookings.'],
                    ["text" => "Guest profiles didn't carry over between properties, so repeat guests were treated as first-timers."],
                    ['text' => 'Rate parity across OTAs and the direct site was checked and adjusted manually, risking rate mismatches.'],
                    ['text' => 'There was no loyalty or personalization program to encourage guests to book direct.'],
                ],

                'solution' => <<<'HTML'
<p>We built a single, modern direct-booking engine to replace the patchwork of outdated per-property widgets, integrated with a channel manager so availability and rates sync automatically across every OTA Harborview lists on, plus the direct site. That sync is what made automated rate parity possible — the platform now compares direct and OTA rates continuously and flags mismatches before a guest ever sees one.</p>
<p>The bigger structural change was a unified guest profile that follows a traveler across all 22 properties. A guest who's stayed at the Bath property now gets recognized the moment they start booking the London property — same loyalty tier, same preferences on file, same booking history — which is the foundation the new loyalty and rewards program builds on.</p>
<p>We piloted the new booking engine and guest CRM on four flagship properties first, chosen specifically to represent Harborview's range from city-center business hotels to countryside retreats, since guest behavior differs meaningfully between them. That pilot shaped several UX decisions — countryside-property guests, for instance, booked further in advance and cared more about photos of the grounds than city-property guests did, which changed how we prioritized content on each property's booking page.</p>
<p>Group-wide rollout followed once the pilot metrics looked solid, alongside the loyalty program launch — timed together deliberately, since a loyalty program with no meaningfully improved booking experience behind it tends to underwhelm.</p>
HTML,

                'goals' => [
                    ['title' => 'Increase Direct Bookings', 'desc' => 'Reduce dependency on high-commission OTA bookings.', 'icon' => 'fas fa-bed', 'color' => '#1a73e8'],
                    ['title' => 'Unify Guest Profiles Group-Wide', 'desc' => 'Recognize repeat guests across all 22 properties, not just one.', 'icon' => 'fas fa-id-badge', 'color' => '#00c896'],
                    ['title' => 'Automate Rate Parity', 'desc' => 'Keep direct and OTA rates consistent without manual checking.', 'icon' => 'fas fa-scale-balanced', 'color' => '#ffb830'],
                    ['title' => 'Launch a Loyalty Program', 'desc' => 'Give guests a real incentive to book direct and stay loyal to the group.', 'icon' => 'fas fa-crown', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Direct Booking Engine', 'desc' => 'A single modern booking flow replacing 22 separate outdated property widgets.', 'icon' => 'fas fa-calendar-check'],
                    ['name' => 'Channel Manager Integration', 'desc' => 'Automatic availability and rate sync across every listed OTA plus the direct site.', 'icon' => 'fas fa-diagram-project'],
                    ['name' => 'Unified Guest Profile (CRM)', 'desc' => 'One guest history and preference record that follows travelers across all 22 properties.', 'icon' => 'fas fa-user-group'],
                    ['name' => 'Dynamic Rate Parity Automation', 'desc' => 'Continuous comparison of direct vs. OTA rates with automatic mismatch alerts.', 'icon' => 'fas fa-scale-balanced'],
                    ['name' => 'Loyalty & Rewards Program', 'desc' => 'Tiered rewards for direct bookings and repeat stays across the group.', 'icon' => 'fas fa-crown'],
                    ['name' => 'PMS Sync', 'desc' => 'Real-time sync with each property\'s existing property management system.', 'icon' => 'fas fa-plug'],
                ],

                'kpis' => [
                    ['label' => 'Direct Booking Share', 'value' => '+31%'],
                    ['label' => 'OTA Commission Costs', 'value' => '-24%'],
                    ['label' => 'Repeat Guest Rate', 'value' => '+17%'],
                    ['label' => 'Booking Conversion Rate', 'value' => '+2.3x'],
                ],

                'technologies' => 'Laravel, React, PostgreSQL, Channel Manager API, Stripe, AWS',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel, PostgreSQL'],
                    ['category' => 'Frontend', 'items' => 'React, Next.js'],
                    ['category' => 'Integrations', 'items' => 'Channel Manager API, PMS sync, Stripe payments'],
                    ['category' => 'Infrastructure', 'items' => 'AWS, CloudFront'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Property & OTA Dependency Audit', 'desc' => 'Assessed booking mix and commission spend across all 22 properties to prioritize where direct booking gains mattered most.'],
                    ['badge' => '02', 'title' => 'Booking Engine Design', 'desc' => 'Designed a single modern booking flow to replace 22 separate outdated widgets.'],
                    ['badge' => '03', 'title' => 'Channel Manager Integration', 'desc' => 'Connected the new engine to the channel manager for automatic rate and availability sync.'],
                    ['badge' => '04', 'title' => 'Guest CRM Migration', 'desc' => 'Consolidated guest history from all properties into one unified profile system.'],
                    ['badge' => '05', 'title' => 'Pilot on 4 Flagship Properties', 'desc' => 'Tested the new booking engine and CRM on a representative mix of city and countryside properties.'],
                    ['badge' => '06', 'title' => 'Group-Wide Rollout', 'desc' => 'Launched across all 22 properties alongside the new loyalty program.'],
                ],

                'achievements' => [
                    ['title' => 'Direct Bookings Grew from ~30% to Over 60%', 'desc' => 'The share of bookings coming directly rather than through OTAs more than doubled within a year.'],
                    ['title' => 'Material Reduction in OTA Commission Spend', 'desc' => 'Overall OTA commission costs dropped 24% as direct booking share grew.'],
                    ['title' => 'Thousands of Loyalty Signups in the First Quarter', 'desc' => 'The new loyalty program attracted strong guest signup numbers immediately after launch.'],
                ],

                'before_after' => [
                    ['before' => '~70% OTA-dependent bookings', 'after' => 'Majority of bookings now direct'],
                    ['before' => 'Guest profiles isolated per property', 'after' => 'Unified guest history across all 22 properties'],
                    ['before' => 'Manual rate parity checks', 'after' => 'Automated parity monitoring across channels'],
                    ['before' => 'No loyalty program', 'after' => 'Active tiered rewards program driving repeat stays'],
                ],

                'compliance_items' => [
                    ['title' => 'PCI-DSS Compliant Payments', 'desc' => 'All booking payments are processed through a PCI-DSS compliant gateway.', 'icon' => 'fas fa-credit-card'],
                    ['title' => 'GDPR-Compliant Guest Data', 'desc' => 'Guest profile and booking data handling complies with UK GDPR across both UK and Irish properties.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'Secure Guest Profile Storage', 'desc' => 'Unified guest data is encrypted and access-controlled by role.', 'icon' => 'fas fa-lock'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-calendar-check', 'title' => 'Direct Booking Engine', 'desc' => 'A modern booking flow replacing outdated per-property widgets.'],
                    ['icon' => 'fas fa-user-group', 'title' => 'Unified Guest Profiles', 'desc' => 'Guest history and preferences follow travelers across all properties.'],
                    ['icon' => 'fas fa-scale-balanced', 'title' => 'Automated Rate Parity', 'desc' => 'Continuous monitoring keeps direct and OTA rates aligned.'],
                    ['icon' => 'fas fa-crown', 'title' => 'Loyalty Program', 'desc' => 'Tiered rewards encouraging direct bookings and repeat stays.'],
                    ['icon' => 'fas fa-diagram-project', 'title' => 'Channel Manager Sync', 'desc' => 'Automatic availability and rate sync across every OTA.'],
                    ['icon' => 'fas fa-mobile-screen', 'title' => 'Mobile-Optimized Booking', 'desc' => 'A fast, modern booking flow on any device.'],
                ],

                'cs_faqs' => [
                    ['question' => "Did Harborview stop listing on OTAs entirely?", 'answer' => 'No — OTAs still bring valuable discovery traffic, especially for new guests. The goal was shifting the mix toward direct bookings for guests likely to book direct anyway, not eliminating OTA presence.'],
                    ['question' => 'How is guest data handled across UK and Irish properties under GDPR?', 'answer' => 'The unified guest profile system was built with UK GDPR compliance as a first-class requirement from the start, including clear consent capture and data handling consistent across both jurisdictions.'],
                    ['question' => 'How did the platform integrate with each property\'s existing PMS?', 'answer' => "We built a PMS sync layer that connects to each property's existing property management system individually, since the 22 hotels didn't all run the same PMS — a unifying layer was more practical than forcing a single PMS switch."],
                    ['question' => 'How quickly did guests adopt the new loyalty program?', 'answer' => 'Signups reached the thousands within the first quarter after launch, helped by timing the loyalty launch alongside the new, meaningfully better booking experience rather than as a standalone announcement.'],
                ],

                'testimonial_quote' => "Watching nearly three-quarters of our bookings flow through OTA commissions for years, you start to feel like you're renting your own guests back. The direct booking platform and loyalty program together completely changed that math — and honestly, the unified guest profiles have done more for how personal our service feels than any single property-level initiative we've tried.",
                'testimonial_name' => 'Fiona Marsh',
                'testimonial_role' => 'Group Revenue Director, Harborview Hospitality Group',
            ],

            // ══════════════════════════════════════════════════════════════
            // 5. Legal Tech — Case Management (USA — New York, NY)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'sterling-cross-legal-partners-case-management-case-study',
                'title'            => 'Modernizing Case Management for Sterling & Cross Legal Partners Across 5 Offices',
                'is_featured'      => false,
                'sort_order'       => 10,
                'author_id'        => 1,
                'client_name'      => 'Sterling & Cross Legal Partners',
                'client_industry'  => 'Corporate Law',
                'business_size'    => '140 attorneys, 5 U.S. offices',
                'location'         => 'New York, New York, USA',
                'business_model'   => 'Corporate Law Firm',
                'project_duration' => '9 months',
                'completion_date'  => 'May 2026',
                'focus_keyword'    => 'legal case management software',
                'meta_title'       => 'Legal Case Management Case Study — Sterling & Cross | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a centralized case management platform for Sterling & Cross Legal Partners, cutting billing cycle time 60% across 5 offices.',
                'tags'             => 'legal tech, case management software, law firm technology, document automation',

                'challenge' => <<<'HTML'
<p>Sterling & Cross Legal Partners has 140 attorneys spread across five U.S. offices, and until recently, "where is the file for this case" didn't have a reliable answer. Case documents were scattered across shared network drives, email threads, and individual attorneys' personal folders — a filing system that worked well enough for any one attorney's own matters but fell apart the moment a case needed input from colleagues in another office.</p>
<p>Billing was tracked in spreadsheets attorney by attorney, which meant reconstructing a single client's monthly bill could take days of cross-referencing multiple people's records. Conflict-of-interest checks — a genuinely serious obligation before accepting any new client — were done manually, with staff searching old files and asking around the office, an approach that scales badly and carries real risk of missing something.</p>
<p>Day-to-day collaboration had its own friction: multiple attorneys editing the same document via shared drives led to constant version confusion, and more than once, real edits were lost when two people saved over each other's work. And because none of this generated structured data, partners had no consolidated view of case status or associate workload across the firm — decisions about staffing and case assignment were made on incomplete information.</p>
HTML,

                'existing_challenges' => [
                    ["text" => "Case files were scattered across shared network drives, email threads, and individual attorneys' personal folders."],
                    ['text' => "Billable hours were tracked in spreadsheets, and reconstructing a month's billing for a client took days."],
                    ['text' => 'Conflict-of-interest checks before accepting a new client were done manually by searching old files and asking around.'],
                    ['text' => 'Multiple attorneys editing the same document led to constant version confusion and occasionally lost edits.'],
                    ['text' => "Partners had no consolidated view of case status or associate workload across the firm's 5 offices."],
                ],

                'solution' => <<<'HTML'
<p>We began with practice-group-level discovery across all five offices, since litigation, M&A, and IP practice groups each organized their case files quite differently — a single rigid taxonomy would have satisfied none of them. What we built instead was a centralized case repository with a flexible-but-structured filing convention, backed by real document version control so simultaneous edits no longer meant a coin-flip on whose changes survived.</p>
<p>The conflict-of-interest database was one of the more sensitive pieces of this project: it needed to be searchable enough to genuinely catch potential conflicts, while respecting the ethical walls that must exist between attorneys working on adverse matters. We built role-based access controls specifically so a conflict search can confirm whether a name appears in the system without exposing the substantive contents of a screened-off matter to someone who shouldn't see it.</p>
<p>Time tracking and billing were automated by tying entries directly to case activity rather than relying on attorneys to reconstruct their day from memory at month's end — a change that alone cut billing cycle time by more than half. On top of all of this, we built a partner dashboard giving firm leadership a real-time view of case status and associate workload across all five offices, replacing what used to require calling each office manager individually.</p>
<p>We piloted the system with two practice groups before firm-wide rollout, deliberately choosing groups with different document-heavy workflows — litigation's discovery-document volume and M&A's contract-negotiation cycles are very different beasts, and the pilot surfaced workflow gaps in each that we could fix before all 140 attorneys were relying on the system.</p>
HTML,

                'goals' => [
                    ['title' => 'Centralize Case Files', 'desc' => 'Replace scattered drives and email with one searchable case repository.', 'icon' => 'fas fa-folder-tree', 'color' => '#1a73e8'],
                    ['title' => 'Automate Time & Billing', 'desc' => 'Tie time entries directly to case activity instead of manual reconstruction.', 'icon' => 'fas fa-clock', 'color' => '#00c896'],
                    ['title' => 'Systematize Conflict Checks', 'desc' => 'Make conflict-of-interest checks searchable and auditable, not manual and ad hoc.', 'icon' => 'fas fa-magnifying-glass', 'color' => '#ffb830'],
                    ['title' => 'Give Partners Firm-Wide Visibility', 'desc' => 'Show case status and associate workload across all 5 offices in real time.', 'icon' => 'fas fa-chart-pie', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Centralized Case Repository', 'desc' => 'A single, searchable home for case documents replacing drives, email, and personal folders.', 'icon' => 'fas fa-folder-open'],
                    ['name' => 'Document Version Control', 'desc' => 'Real version history so simultaneous edits no longer risk lost work.', 'icon' => 'fas fa-code-branch'],
                    ['name' => 'Automated Time Tracking', 'desc' => 'Time entries tied directly to case activity rather than end-of-month reconstruction.', 'icon' => 'fas fa-stopwatch'],
                    ['name' => 'Conflict-of-Interest Database', 'desc' => 'A searchable conflict database respecting ethical walls between screened matters.', 'icon' => 'fas fa-user-shield'],
                    ['name' => 'Billing & Invoice Generation', 'desc' => 'Client invoices generated directly from tracked time and case activity.', 'icon' => 'fas fa-file-invoice-dollar'],
                    ['name' => 'Partner Dashboard', 'desc' => 'Real-time case status and associate workload visibility across all 5 offices.', 'icon' => 'fas fa-chart-column'],
                ],

                'kpis' => [
                    ['label' => 'Billing Cycle Time', 'value' => '-60%'],
                    ['label' => 'Conflict Check Time', 'value' => 'Days → Minutes'],
                    ['label' => 'Document Version Errors', 'value' => '-90%'],
                    ['label' => 'Associate Utilization Visibility', 'value' => '100%'],
                ],

                'technologies' => 'Laravel, Vue.js, PostgreSQL, Elasticsearch, AWS, DocuSign',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel, PostgreSQL'],
                    ['category' => 'Search', 'items' => 'Elasticsearch (case file & conflict search)'],
                    ['category' => 'Frontend', 'items' => 'Vue.js, Tailwind CSS'],
                    ['category' => 'Integrations', 'items' => 'DocuSign, Outlook/Exchange sync'],
                    ['category' => 'Infrastructure', 'items' => 'AWS with encrypted storage'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Practice Group Discovery', 'desc' => 'Studied how litigation, M&A, and IP practice groups organize case files differently across all 5 offices.'],
                    ['badge' => '02', 'title' => 'Document Taxonomy & Version Control Design', 'desc' => 'Designed a flexible-but-structured filing system with real version history.'],
                    ['badge' => '03', 'title' => 'Conflict Database Build', 'desc' => 'Built a searchable conflict-of-interest system respecting ethical walls between screened matters.'],
                    ['badge' => '04', 'title' => 'Time & Billing Automation', 'desc' => 'Tied time entries directly to case activity to eliminate manual end-of-month reconstruction.'],
                    ['badge' => '05', 'title' => 'Pilot with 2 Practice Groups', 'desc' => 'Tested the system with litigation and M&A groups, whose document workflows differ significantly.'],
                    ['badge' => '06', 'title' => 'Firm-Wide Rollout & Training', 'desc' => 'Trained all 140 attorneys across 5 offices in role-specific sessions.'],
                ],

                'achievements' => [
                    ['title' => 'Consolidated Files for 140 Attorneys', 'desc' => 'Case files that were once scattered across drives and inboxes now live in one searchable system.'],
                    ['title' => 'Billing Cycle Cut by More Than Half', 'desc' => "Automated time tracking cut the firm's billing cycle time by 60%."],
                    ['title' => 'Conflict Checks: Days to Minutes', 'desc' => 'What used to take days of manual searching now takes minutes, with a full audit trail.'],
                ],

                'before_after' => [
                    ['before' => 'Case files scattered across drives, email, and folders', 'after' => 'One centralized, searchable case repository'],
                    ['before' => 'Manual spreadsheet billing', 'after' => 'Automated time tracking and invoicing'],
                    ['before' => 'Days-long manual conflict checks', 'after' => 'Searchable conflict database in minutes'],
                    ['before' => 'No firm-wide case visibility', 'after' => 'Partner dashboard across all 5 offices'],
                ],

                'compliance_items' => [
                    ['title' => 'Attorney-Client Privilege Protection', 'desc' => 'Access controls are designed specifically to preserve privilege and confidentiality boundaries.', 'icon' => 'fas fa-user-secret'],
                    ['title' => 'Ethical Wall Access Controls', 'desc' => 'Screened matters are access-restricted so conflicted attorneys cannot view substantive case content.', 'icon' => 'fas fa-shield-halved'],
                    ['title' => 'Encrypted Document Storage', 'desc' => 'All case documents are encrypted at rest and in transit.', 'icon' => 'fas fa-lock'],
                    ['title' => 'Bar Record-Retention Compliance', 'desc' => 'Document retention policies align with state bar association requirements.', 'icon' => 'fas fa-scale-balanced'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-folder-open', 'title' => 'Centralized Case Repository', 'desc' => 'One searchable home for every case file across all offices.'],
                    ['icon' => 'fas fa-code-branch', 'title' => 'Document Version Control', 'desc' => 'Real version history eliminating lost-edit conflicts.'],
                    ['icon' => 'fas fa-stopwatch', 'title' => 'Automated Time Tracking', 'desc' => 'Time entries generated from actual case activity.'],
                    ['icon' => 'fas fa-magnifying-glass', 'title' => 'Conflict-of-Interest Search', 'desc' => 'Fast, auditable conflict checks respecting ethical walls.'],
                    ['icon' => 'fas fa-chart-column', 'title' => 'Partner Dashboard', 'desc' => 'Firm-wide case status and workload visibility.'],
                    ['icon' => 'fas fa-lock', 'title' => 'Secure Client Portal', 'desc' => 'A confidential channel for client document sharing.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How do ethical walls work within a shared case management system?', 'answer' => "Role-based access controls let the conflict database confirm whether a name appears in the system without exposing the substantive contents of a screened-off matter to an attorney who shouldn't see it."],
                    ['question' => 'How was years of existing case data migrated?', 'answer' => 'We migrated practice-group by practice-group rather than all at once, verifying document integrity and access permissions at each stage before moving to the next.'],
                    ['question' => 'Did attorneys resist moving away from familiar personal folders?', 'answer' => 'Some did initially — the pilot with two practice groups was partly about working through that resistance and refining the interface before firm-wide rollout, which made adoption smoother than a single company-wide mandate would have.'],
                    ['question' => 'How is client confidentiality protected in the new system?', 'answer' => 'All documents are encrypted at rest and in transit, with granular role-based access controls and a full audit trail of who accessed what and when.'],
                ],

                'testimonial_quote' => "Every managing partner has a story about a conflict check that took half a day and still felt like a coin flip. Ours is now a search that takes minutes and something we can actually stand behind if questioned. Between that and cutting our billing cycle in half, this was one of the highest-leverage investments the firm has made in years.",
                'testimonial_name' => 'Margaret Ellison',
                'testimonial_role' => 'Managing Partner, Sterling & Cross Legal Partners',
            ],

            // ══════════════════════════════════════════════════════════════
            // 6. Renewable Energy — Member Solar Monitoring (Netherlands — Amsterdam)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'zonneveld-energy-cooperative-solar-monitoring-case-study',
                'title'            => "Giving Zonneveld Energy Cooperative's 45,000 Members Real-Time Visibility into Their Solar & Wind Output",
                'is_featured'      => false,
                'sort_order'       => 11,
                'author_id'        => 2,
                'client_name'      => 'Zonneveld Energy Cooperative',
                'client_industry'  => 'Renewable Energy',
                'business_size'    => '45,000+ member households, 60 solar/wind sites',
                'location'         => 'Amsterdam, Netherlands',
                'business_model'   => 'Renewable Energy Cooperative',
                'project_duration' => '8 months',
                'completion_date'  => 'July 2025',
                'focus_keyword'    => 'renewable energy monitoring platform',
                'meta_title'       => 'Renewable Energy Platform Case Study — Zonneveld Energy | Kawach Technology',
                'meta_description' => 'How Kawach Technology gave Zonneveld Energy Cooperative\'s 45,000 members real-time solar and wind monitoring, cutting fault detection time from weeks to hours.',
                'tags'             => 'renewable energy software, solar monitoring platform, energy cooperative technology, GDPR compliant',

                'challenge' => <<<'HTML'
<p>Zonneveld Energy Cooperative lets more than 45,000 Dutch households buy a financial share in one of 60 shared solar and wind sites, which is a genuinely appealing model for people who want to support renewable energy without installing panels on their own roof. The problem was that once a member bought in, they had almost no visibility into what their share was actually doing — how much energy it generated, or what their return actually looked like month to month.</p>
<p>That opacity had real operational costs too. Inverter faults and underperforming panels at remote sites often went unnoticed for weeks, since nobody was watching site performance in real time — a fault only surfaced when someone happened to notice a site's output looked off during a periodic review. The member support team, meanwhile, fielded a steady stream of "how much did my share generate this month" requests that had to be answered by manually pulling reports, taking days to turn around a single batch.</p>
<p>Operations had no consolidated cross-site view either — checking on 60 sites meant checking on 60 sites, one at a time. And member payouts were calculated in spreadsheets kept entirely separate from the actual metered generation data, which made the payout process harder to audit than it should have been for a cooperative whose entire value proposition rests on member trust.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Members had no way to see how much energy their invested share of a solar or wind site actually generated.'],
                    ['text' => 'Inverter faults and underperforming panels at remote sites often went unnoticed for weeks.'],
                    ['text' => 'The support team manually compiled generation and payout reports for members on request, taking days per batch.'],
                    ['text' => "There was no consolidated view across 60 sites for the cooperative's own operations team."],
                    ['text' => 'Financial payouts to members were calculated in spreadsheets separate from the actual generation data.'],
                ],

                'solution' => <<<'HTML'
<p>The foundation of everything was getting live telemetry out of all 60 sites into one central time-series platform — inverter output, panel-level performance where available, and turbine data for the wind sites, streamed continuously rather than checked periodically. That data feed made two things possible at once: a real-time member dashboard, and automated fault detection that flags underperformance within hours instead of weeks.</p>
<p>For members, we built a dashboard and companion mobile app showing exactly how their specific invested share is performing — real generation numbers, not an estimate — answering the "how much did I generate" question without a single support ticket. Because member data crosses into GDPR territory and Zonneveld's cooperative model depends heavily on member trust, we made EU data residency and clear data-handling practices a first-class design requirement rather than an afterthought.</p>
<p>The payout calculation engine ties directly to the same metered generation data members see on their own dashboard, which closed the gap between "what members are told they earned" and "what the numbers actually show" — the same number, sourced the same way, visible to both the member and the cooperative's finance team. That single change did more for member trust than any dashboard feature on its own.</p>
<p>We rolled the platform out to members in phases rather than all 45,000 at once, starting with members at a handful of pilot sites so we could validate that automated payout calculations matched what the finance team's manual process had been producing before fully trusting the new system with real money.</p>
HTML,

                'goals' => [
                    ['title' => 'Give Members Real-Time Visibility', 'desc' => 'Show each member exactly how their invested share is performing.', 'icon' => 'fas fa-solar-panel', 'color' => '#1a73e8'],
                    ['title' => 'Detect Site Faults Early', 'desc' => 'Catch underperforming panels and inverter faults within hours, not weeks.', 'icon' => 'fas fa-triangle-exclamation', 'color' => '#00c896'],
                    ['title' => 'Automate Member Payouts', 'desc' => 'Tie payouts directly to metered generation data for full auditability.', 'icon' => 'fas fa-sack-dollar', 'color' => '#ffb830'],
                    ['title' => 'Unify Operations Across 60 Sites', 'desc' => 'Give the operations team one consolidated view instead of 60 separate checks.', 'icon' => 'fas fa-map-location-dot', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Member Generation Dashboard', 'desc' => 'Real-time visibility into exactly how much a member\'s invested share has generated.', 'icon' => 'fas fa-chart-simple'],
                    ['name' => 'Live Site Telemetry Integration', 'desc' => 'Continuous data streaming from inverters and turbines across all 60 sites.', 'icon' => 'fas fa-satellite-dish'],
                    ['name' => 'Automated Fault Detection & Alerts', 'desc' => 'Flags underperforming panels or inverter faults within hours.', 'icon' => 'fas fa-bell'],
                    ['name' => 'Payout Calculation Engine', 'desc' => 'Automated, auditable member payouts tied directly to metered generation data.', 'icon' => 'fas fa-calculator'],
                    ['name' => 'Operations Command Center', 'desc' => 'A single consolidated view of all 60 sites for the operations team.', 'icon' => 'fas fa-display'],
                    ['name' => 'Member Mobile App', 'desc' => 'On-the-go access to generation data and payout history.', 'icon' => 'fas fa-mobile-screen'],
                ],

                'kpis' => [
                    ['label' => 'Fault Detection Time', 'value' => 'Weeks → Hours'],
                    ['label' => 'Support Ticket Volume', 'value' => '-58%'],
                    ['label' => 'Payout Processing Time', 'value' => '-75%'],
                    ['label' => 'Member App Adoption', 'value' => '81%'],
                ],

                'technologies' => 'Python, Django, TimescaleDB, MQTT, Vue.js, AWS',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Django, TimescaleDB (time-series telemetry)'],
                    ['category' => 'Data Ingestion', 'items' => 'MQTT from inverters and turbine controllers'],
                    ['category' => 'Frontend', 'items' => 'Vue.js, mobile-responsive PWA'],
                    ['category' => 'Infrastructure', 'items' => 'AWS, EU-based data residency'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Site Telemetry Audit', 'desc' => 'Assessed existing monitoring hardware and data availability across all 60 solar and wind sites.'],
                    ['badge' => '02', 'title' => 'Data Pipeline & Time-Series Architecture', 'desc' => 'Built continuous telemetry ingestion into a central time-series database.'],
                    ['badge' => '03', 'title' => 'Fault Detection Model Build', 'desc' => 'Developed automated underperformance and fault detection logic per site type.'],
                    ['badge' => '04', 'title' => 'Member Dashboard & App Design', 'desc' => 'Designed the member-facing dashboard and mobile app around real generation data.'],
                    ['badge' => '05', 'title' => 'Payout Engine Validation', 'desc' => "Validated automated payout calculations against the finance team's historical manual process before go-live."],
                    ['badge' => '06', 'title' => 'Phased Member Rollout', 'desc' => 'Rolled out to members at pilot sites first, then expanded to all 45,000+ members.'],
                ],

                'achievements' => [
                    ['title' => 'Fault Detection Cut from Weeks to Hours', 'desc' => 'Automated monitoring across all 60 sites catches underperformance far earlier than manual review ever did.'],
                    ['title' => 'Support Tickets Cut by More Than Half', 'desc' => 'Self-service generation data eliminated the majority of "how much did I generate" support requests.'],
                    ['title' => 'Automated Payouts for 45,000+ Members', 'desc' => 'Payouts are now calculated automatically and audibly tied to real metered generation data.'],
                ],

                'before_after' => [
                    ['before' => 'No member visibility into generation or returns', 'after' => 'Real-time dashboard per member'],
                    ['before' => 'Faults undetected for weeks', 'after' => 'Automated alerts within hours'],
                    ['before' => 'Manual spreadsheet payouts', 'after' => 'Automated, metered-data-driven payouts'],
                    ['before' => 'No cross-site operations view', 'after' => 'Unified command center for all 60 sites'],
                ],

                'compliance_items' => [
                    ['title' => 'GDPR-Compliant Member Data', 'desc' => 'Member account and usage data handling complies fully with EU GDPR requirements.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'EU Data Residency', 'desc' => 'All member and site data is stored within EU-based infrastructure.', 'icon' => 'fas fa-earth-europe'],
                    ['title' => 'Financial Payout Audit Trail', 'desc' => 'Every payout is traceable back to the specific metered generation data behind it.', 'icon' => 'fas fa-file-invoice'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-chart-simple', 'title' => 'Real-Time Generation Dashboard', 'desc' => "Members see exactly how their invested share is performing."],
                    ['icon' => 'fas fa-bell', 'title' => 'Automated Fault Alerts', 'desc' => 'Underperformance and faults flagged within hours.'],
                    ['icon' => 'fas fa-mobile-screen', 'title' => 'Member Mobile App', 'desc' => 'On-the-go access to generation and payout data.'],
                    ['icon' => 'fas fa-calculator', 'title' => 'Automated Payout Engine', 'desc' => 'Payouts tied directly to real, auditable generation data.'],
                    ['icon' => 'fas fa-display', 'title' => 'Operations Command Center', 'desc' => 'One consolidated view across all 60 sites.'],
                    ['icon' => 'fas fa-earth-europe', 'title' => 'EU Data Residency', 'desc' => 'Member data stored and processed within the EU.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How is member data kept GDPR-compliant?', 'answer' => 'All member data is stored on EU-based infrastructure with clear consent capture and data-handling practices built in as a core design requirement, not an afterthought.'],
                    ['question' => 'How are member payouts calculated and verified?', 'answer' => "Payouts are calculated directly from the same metered generation data members see on their own dashboard, and we validated the automated calculations against the finance team's historical manual figures before trusting the system with real payouts."],
                    ['question' => 'How accurate is the fault detection system?', 'answer' => 'It was tuned per site type — solar panel arrays and wind turbines fail in different ways — and validated against real historical fault events across pilot sites before wider rollout.'],
                    ['question' => 'How did you onboard 45,000 existing members to a new system?', 'answer' => 'We rolled out in phases starting with members at a handful of pilot sites, which let us fix onboarding friction points before scaling to the full membership base.'],
                ],

                'testimonial_quote' => "Our members invest in renewable energy because they believe in it, but belief only goes so far without transparency. Now a member can open the app and see, in real numbers, exactly what their share of a wind turbine generated this week. Support tickets dropped, trust went up, and honestly, our own operations team finally has the visibility across 60 sites that we should have had years ago.",
                'testimonial_name' => 'Sanne Dekker',
                'testimonial_role' => 'Director of Member Services, Zonneveld Energy Cooperative',
            ],

            // ══════════════════════════════════════════════════════════════
            // 7. InsurTech — Claims Automation (USA — Chicago, IL)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'lakeshore-mutual-insurance-claims-automation-case-study',
                'title'            => "Automating Claims Intake and Triage for Lakeshore Mutual Insurance's 300,000 Policyholders",
                'is_featured'      => false,
                'sort_order'       => 12,
                'author_id'        => 1,
                'client_name'      => 'Lakeshore Mutual Insurance',
                'client_industry'  => 'Property & Casualty Insurance',
                'business_size'    => 'Regional insurer, 300,000+ policyholders',
                'location'         => 'Chicago, Illinois, USA',
                'business_model'   => 'Property & Casualty Insurance',
                'project_duration' => '10 months',
                'completion_date'  => 'March 2026',
                'focus_keyword'    => 'insurance claims automation software',
                'meta_title'       => 'Insurance Claims Automation Case Study — Lakeshore Mutual | Kawach Technology',
                'meta_description' => 'How Kawach Technology automated claims intake and fraud detection for Lakeshore Mutual Insurance, cutting response time from 7 days to 4 hours.',
                'tags'             => 'insurtech, claims automation, insurance software development, fraud detection',

                'challenge' => <<<'HTML'
<p>Lakeshore Mutual Insurance serves more than 300,000 policyholders across the Midwest, but filing a claim in 2024 still meant picking up the phone, sending a fax, or mailing in paperwork — there was no digital self-service option at all. Every claim that came in was manually triaged and assigned to an adjuster by a claims coordinator working through a queue, which alone added days before anyone had actually reviewed the claim's substance.</p>
<p>Policyholders routinely waited over a week just to hear back after filing, which is a genuinely difficult experience when the claim in question is often about something stressful — storm damage to a home, a car accident, a burst pipe. Meanwhile, fraud detection relied entirely on adjuster intuition built from experience and after-the-fact audits; there was no systematic way to flag a suspicious claim pattern before the payout had already gone out.</p>
<p>Every status inquiry required a phone call, which meant the call center absorbed a steady stream of "what's happening with my claim" calls that a self-service system could have handled instantly — straining call center capacity and making policyholders wait even to ask a simple question.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Claims could only be filed by phone, fax, or mail, with no digital self-service option.'],
                    ['text' => 'Every claim was manually triaged and assigned to an adjuster, adding days before any actual review began.'],
                    ['text' => 'Policyholders often waited over a week just to hear back after filing a claim.'],
                    ['text' => 'Fraud detection relied entirely on adjuster intuition and after-the-fact audits, with no systematic flagging.'],
                    ['text' => 'Claims status updates required policyholders to call in, adding to already-strained call center volume.'],
                ],

                'solution' => <<<'HTML'
<p>We built a digital claims intake portal as the front door to everything else — policyholders can now file a claim online or via mobile in minutes, uploading photos and documentation directly rather than mailing them in. That structured digital intake is what made automated triage possible in the first place: the system now routes each claim to the right adjuster based on claim type, complexity, and current workload, work that used to require a human coordinator manually reading every submission.</p>
<p>Fraud detection was the piece Lakeshore's claims leadership was most cautious about, understandably — false positives that delay a legitimate claim create real harm. We trained a machine-learning model on Lakeshore's own historical claims data, looking for the patterns that had actually preceded confirmed fraud in the past rather than generic industry heuristics, and we ran it in parallel with the claims team's normal process for an extended validation period before it influenced any live triage decision.</p>
<p>Self-service status tracking closed the loop on the call-center problem: policyholders can now check exactly where their claim stands without calling in, which took a meaningful chunk of routine inquiry volume off the phone lines. We rolled the new system out by claim type rather than all at once — starting with simpler, lower-complexity claim categories where automated triage had the clearest track record, before extending to more complex claim types once the team had built confidence in the system's judgment.</p>
HTML,

                'goals' => [
                    ['title' => 'Enable Digital Claims Filing', 'desc' => 'Give policyholders a fast, self-service way to file a claim.', 'icon' => 'fas fa-file-circle-plus', 'color' => '#1a73e8'],
                    ['title' => 'Automate Triage & Assignment', 'desc' => 'Route claims to the right adjuster automatically based on type and complexity.', 'icon' => 'fas fa-route', 'color' => '#00c896'],
                    ['title' => 'Flag Potential Fraud Earlier', 'desc' => 'Catch suspicious claim patterns before payout, not after.', 'icon' => 'fas fa-magnifying-glass', 'color' => '#ffb830'],
                    ['title' => 'Reduce Call Center Load', 'desc' => 'Let policyholders self-serve claim status instead of calling in.', 'icon' => 'fas fa-headset', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Digital Claims Intake Portal', 'desc' => 'Online and mobile claims filing with direct photo and document upload.', 'icon' => 'fas fa-file-circle-plus'],
                    ['name' => 'Automated Triage Engine', 'desc' => 'Routes claims to the right adjuster based on type, complexity, and workload.', 'icon' => 'fas fa-route'],
                    ['name' => 'ML Fraud Flagging', 'desc' => "A model trained on Lakeshore's own historical claims data to flag suspicious patterns pre-payout.", 'icon' => 'fas fa-shield-halved'],
                    ['name' => 'Adjuster Assignment & Workload Balancing', 'desc' => 'Balances new claims across adjusters based on current caseload.', 'icon' => 'fas fa-scale-balanced'],
                    ['name' => 'Self-Service Status Tracking', 'desc' => 'Policyholders check claim status anytime without calling in.', 'icon' => 'fas fa-magnifying-glass-location'],
                    ['name' => 'Document & Photo Upload', 'desc' => 'Direct upload of claim evidence from any device.', 'icon' => 'fas fa-camera'],
                ],

                'kpis' => [
                    ['label' => 'Initial Response Time', 'value' => '7 days → 4 hrs'],
                    ['label' => 'Digital Filing Adoption', 'value' => '76%'],
                    ['label' => 'Suspected Fraud Caught Pre-Payout', 'value' => '+3.2x'],
                    ['label' => 'Call Center Volume', 'value' => '-38%'],
                ],

                'technologies' => 'Java, Spring Boot, PostgreSQL, Python (ML), React, AWS',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Java, Spring Boot, PostgreSQL'],
                    ['category' => 'Fraud Detection', 'items' => 'Python, scikit-learn'],
                    ['category' => 'Frontend', 'items' => 'React, mobile-responsive'],
                    ['category' => 'Infrastructure', 'items' => 'AWS, encrypted claims storage'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Claims Workflow Discovery', 'desc' => 'Mapped the full claims lifecycle from filing through payout across claim types.'],
                    ['badge' => '02', 'title' => 'Digital Intake Portal Design', 'desc' => 'Built the online/mobile filing flow with direct evidence upload.'],
                    ['badge' => '03', 'title' => 'Triage & Assignment Automation', 'desc' => 'Automated adjuster routing based on claim type, complexity, and workload.'],
                    ['badge' => '04', 'title' => 'Fraud Model Training', 'desc' => "Trained the fraud detection model on Lakeshore's own historical claims data."],
                    ['badge' => '05', 'title' => 'Parallel-Run Validation', 'desc' => 'Ran automated triage and fraud flagging alongside the existing claims team process before trusting live decisions to it.'],
                    ['badge' => '06', 'title' => 'Phased Rollout by Claim Type', 'desc' => 'Launched with simpler claim categories first, expanding to more complex types as confidence grew.'],
                ],

                'achievements' => [
                    ['title' => 'Response Time Cut from a Week to Hours', 'desc' => 'Initial claims response time dropped from about 7 days to roughly 4 hours.'],
                    ['title' => '76% Digital Filing Adoption', 'desc' => 'More than three-quarters of policyholders now file claims digitally rather than by phone, fax, or mail.'],
                    ['title' => 'Fraud Detection More Than Tripled', 'desc' => 'Suspected fraud caught before payout increased 3.2x compared to the prior manual-only process.'],
                ],

                'before_after' => [
                    ['before' => 'Phone/fax/mail-only claims filing', 'after' => 'Digital self-service filing portal'],
                    ['before' => 'Manual multi-day triage', 'after' => 'Automated triage and adjuster assignment'],
                    ['before' => 'Fraud caught only after payout, if at all', 'after' => 'ML flagging before payout'],
                    ['before' => 'Policyholders called in for status updates', 'after' => 'Self-service status tracking'],
                ],

                'compliance_items' => [
                    ['title' => 'State Insurance Regulatory Compliance', 'desc' => 'Claims workflows align with Illinois and multi-state insurance regulatory requirements.', 'icon' => 'fas fa-landmark'],
                    ['title' => 'Claims Data Encryption', 'desc' => 'All claims data, including uploaded evidence, is encrypted at rest and in transit.', 'icon' => 'fas fa-lock'],
                    ['title' => 'Fair Claims Practices Compliance', 'desc' => 'Automated triage and fraud flagging were designed to align with fair claims handling regulations.', 'icon' => 'fas fa-scale-balanced'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-file-circle-plus', 'title' => 'Digital Claims Portal', 'desc' => 'File a claim online or via mobile in minutes.'],
                    ['icon' => 'fas fa-route', 'title' => 'Automated Triage', 'desc' => 'Claims routed to the right adjuster automatically.'],
                    ['icon' => 'fas fa-shield-halved', 'title' => 'ML Fraud Detection', 'desc' => 'Suspicious patterns flagged before payout.'],
                    ['icon' => 'fas fa-magnifying-glass-location', 'title' => 'Self-Service Status Tracking', 'desc' => 'Check claim status anytime without calling in.'],
                    ['icon' => 'fas fa-scale-balanced', 'title' => 'Adjuster Workload Balancing', 'desc' => 'New claims distributed based on current caseload.'],
                    ['icon' => 'fas fa-camera', 'title' => 'Mobile Photo/Document Upload', 'desc' => 'Submit claim evidence directly from any device.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How do you avoid false positives delaying legitimate claims?', 'answer' => "We ran the fraud model in parallel with the claims team's normal process for an extended period, comparing its flags against real outcomes, before letting it influence any live triage decision — and it flags for adjuster review rather than auto-denying anything."],
                    ['question' => 'Are adjusters being replaced by the automated system?', 'answer' => 'No. The system handles triage and routing so adjusters spend their time on actual claim assessment rather than administrative sorting — every claim is still reviewed by a human adjuster.'],
                    ['question' => 'How does the platform stay compliant across different state insurance regulations?', 'answer' => "Claims workflows were built with Lakeshore's compliance team to align with Illinois and neighboring-state insurance regulatory requirements, including fair claims handling rules."],
                    ['question' => 'Why was rollout phased by claim type instead of all at once?', 'answer' => 'Starting with simpler, lower-complexity claims let the team validate the automated triage and fraud flagging track record before extending it to more complex, higher-stakes claim types.'],
                ],

                'testimonial_quote' => "A week-long wait just to hear back on a claim is a terrible experience for someone dealing with storm damage or a car accident. Cutting that to about four hours changed how our policyholders feel about us at the exact moment it matters most. And the fraud model catching more than three times what we caught manually, without slowing down legitimate claims, is the kind of result that's easy to sell internally.",
                'testimonial_name' => 'Patrick O\'Malley',
                'testimonial_role' => 'VP of Claims Operations, Lakeshore Mutual Insurance',
            ],

            // ══════════════════════════════════════════════════════════════
            // 8. Restaurant Tech — Multi-Location Platform (France — Lyon)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'bistro-nationale-group-restaurant-platform-case-study',
                'title'            => 'How Bistro Nationale Group Unified Ordering and Kitchen Operations Across 85 Locations',
                'is_featured'      => false,
                'sort_order'       => 13,
                'author_id'        => 2,
                'client_name'      => 'Bistro Nationale Group',
                'client_industry'  => 'Restaurant / Food Service',
                'business_size'    => '85 restaurant locations across France',
                'location'         => 'Lyon, France',
                'business_model'   => 'Casual Dining Restaurant Chain',
                'project_duration' => '6 months',
                'completion_date'  => 'September 2025',
                'focus_keyword'    => 'restaurant management software development',
                'meta_title'       => 'Restaurant Technology Case Study — Bistro Nationale Group | Kawach Technology',
                'meta_description' => 'How Kawach Technology unified POS, online ordering, and kitchen operations for Bistro Nationale Group across 85 restaurant locations in France.',
                'tags'             => 'restaurant technology, kitchen display system, online ordering platform, multi-location POS integration',

                'challenge' => <<<'HTML'
<p>Bistro Nationale Group grew to 85 locations across France partly through acquiring smaller regional chains, and each acquisition brought its own point-of-sale system along with it. By the time Bistro Nationale came to us, the company was running a patchwork of different POS platforms accumulated over years of independent local decisions — nothing that head office could see or report on consistently.</p>
<p>Online ordering existed only through third-party delivery aggregators charging steep per-order fees, cutting meaningfully into margin on every digital order with no first-party alternative. Inside the kitchens, order tickets still printed on paper, which slowed order flow noticeably during peak dinner service and made it harder to track exactly how long an order had been sitting.</p>
<p>Head office had no real-time visibility into sales or inventory levels across locations, and menu or pricing updates had to be pushed to each location's system individually — a process that could take days to fully roll out chain-wide, during which some locations were selling at old prices while others had already updated.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'The 85 locations ran a patchwork of different POS systems accumulated over years of independent local decisions.'],
                    ['text' => 'Online ordering went entirely through third-party delivery aggregators charging steep per-order fees.'],
                    ['text' => 'Kitchens still relied on printed paper tickets, slowing order flow during peak service.'],
                    ['text' => 'Head office had no real-time visibility into sales or inventory levels across locations.'],
                    ['text' => 'Menu and pricing updates had to be manually pushed to each location individually, taking days to roll out chain-wide.'],
                ],

                'solution' => <<<'HTML'
<p>Rather than ripping out 85 different POS setups at once — a change management nightmare and an unnecessary one — we built a unified data layer that sits above the existing POS hardware wherever possible, normalizing sales and inventory data into one consistent format regardless of which underlying system a given location ran. That data layer is what finally made a real chain-wide dashboard possible for head office.</p>
<p>For online ordering, we built a first-party ordering website and app so Bistro Nationale could capture direct orders without paying aggregator commission on every single one — while deliberately keeping a presence on the aggregators too, since they still bring in customers who wouldn't have found Bistro Nationale otherwise. The goal was shifting the mix, not eliminating a channel that still has real value.</p>
<p>Paper kitchen tickets were replaced with a digital kitchen display system running on tablets, which cut ticket-to-completion time noticeably during peak service and gave kitchen staff a much clearer view of order queue and timing. Centralized menu management meant a price change or new seasonal item could roll out chain-wide in minutes instead of days, with head office controlling exactly which locations received which menu at what time.</p>
<p>We piloted the kitchen display system and unified POS layer at a handful of locations before the full rollout, going location by location afterward — each restaurant has its own service rhythm and staff comfort with new technology, so a single "flip the switch everywhere" rollout would have created more disruption than the phased approach we used.</p>
HTML,

                'goals' => [
                    ['title' => 'Unify POS Data Chain-Wide', 'desc' => 'Give head office one consistent view of sales and inventory across all locations.', 'icon' => 'fas fa-layer-group', 'color' => '#1a73e8'],
                    ['title' => 'Reduce Aggregator Dependency', 'desc' => 'Capture more online orders directly instead of paying steep aggregator commissions.', 'icon' => 'fas fa-utensils', 'color' => '#00c896'],
                    ['title' => 'Digitize Kitchen Operations', 'desc' => 'Replace paper tickets with a faster, clearer digital kitchen display.', 'icon' => 'fas fa-kitchen-set', 'color' => '#ffb830'],
                    ['title' => 'Centralize Menu & Pricing Management', 'desc' => 'Roll out menu and price changes chain-wide in minutes, not days.', 'icon' => 'fas fa-book-open', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Unified POS Data Layer', 'desc' => 'Normalizes sales and inventory data across different underlying POS systems.', 'icon' => 'fas fa-layer-group'],
                    ['name' => 'First-Party Online Ordering', 'desc' => 'A branded ordering website and app that captures orders without aggregator commission.', 'icon' => 'fas fa-mobile-screen'],
                    ['name' => 'Digital Kitchen Display System (KDS)', 'desc' => 'Tablet-based order tickets replacing paper, with clear queue and timing visibility.', 'icon' => 'fas fa-kitchen-set'],
                    ['name' => 'Chain-Wide Sales & Inventory Dashboard', 'desc' => 'Real-time visibility into every location for head office.', 'icon' => 'fas fa-chart-column'],
                    ['name' => 'Centralized Menu Management', 'desc' => 'Push menu and pricing updates chain-wide, or to specific locations, in minutes.', 'icon' => 'fas fa-book-open'],
                    ['name' => 'Loyalty & Promotions Engine', 'desc' => 'Chain-wide loyalty rewards and location-specific promotions.', 'icon' => 'fas fa-gift'],
                ],

                'kpis' => [
                    ['label' => 'Aggregator Fee Spend', 'value' => '-34%'],
                    ['label' => 'Kitchen Ticket Time', 'value' => '-26%'],
                    ['label' => 'Menu Update Rollout Time', 'value' => 'Days → Minutes'],
                    ['label' => 'First-Party Order Share', 'value' => '+45%'],
                ],

                'technologies' => 'Node.js, React, PostgreSQL, Stripe, AWS, POS Integration APIs',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Node.js, PostgreSQL'],
                    ['category' => 'Frontend', 'items' => 'React, React Native (kitchen display tablets)'],
                    ['category' => 'Payments', 'items' => 'Stripe'],
                    ['category' => 'Infrastructure', 'items' => 'AWS, regional edge caching for France'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Multi-POS Landscape Audit', 'desc' => 'Cataloged every POS system in use across the 85 locations and their data export capabilities.'],
                    ['badge' => '02', 'title' => 'Unified Data Layer Design', 'desc' => 'Built a normalization layer sitting above existing POS hardware wherever possible.'],
                    ['badge' => '03', 'title' => 'First-Party Ordering Platform Build', 'desc' => 'Developed the branded ordering website and app to reduce aggregator dependency.'],
                    ['badge' => '04', 'title' => 'Kitchen Display System Pilot', 'desc' => 'Tested the digital KDS at a handful of locations before wider rollout.'],
                    ['badge' => '05', 'title' => 'Location-by-Location Rollout', 'desc' => 'Rolled out the full platform gradually, respecting each location\'s own service rhythm.'],
                    ['badge' => '06', 'title' => 'Head Office Dashboard Launch', 'desc' => 'Launched the chain-wide sales and inventory dashboard once enough locations were connected.'],
                ],

                'achievements' => [
                    ['title' => 'Unified Data Across 85 Locations', 'desc' => 'Previously disconnected POS systems now feed one consistent chain-wide data layer.'],
                    ['title' => 'Aggregator Fee Spend Down a Third', 'desc' => 'First-party ordering adoption cut overall aggregator commission spend by 34%.'],
                    ['title' => 'Menu Updates: Days to Minutes', 'desc' => 'Chain-wide menu and pricing changes that used to take days now roll out in minutes.'],
                ],

                'before_after' => [
                    ['before' => 'Fragmented POS systems per location', 'after' => 'Unified data layer across all 85 locations'],
                    ['before' => 'Heavy dependence on aggregator fees', 'after' => 'First-party ordering capturing significant share'],
                    ['before' => 'Paper kitchen tickets', 'after' => 'Digital kitchen display system'],
                    ['before' => 'Days to roll out menu changes', 'after' => 'Minutes, chain-wide'],
                ],

                'compliance_items' => [
                    ['title' => 'PCI-DSS Compliant Payments', 'desc' => 'All online and in-app payments are processed through a PCI-DSS compliant gateway.', 'icon' => 'fas fa-credit-card'],
                    ['title' => 'French Food Service Regulations', 'desc' => 'Ordering and allergen-disclosure workflows align with French food service regulatory requirements.', 'icon' => 'fas fa-scale-balanced'],
                    ['title' => 'GDPR-Compliant Ordering Data', 'desc' => 'Customer ordering and loyalty data handling complies with EU GDPR.', 'icon' => 'fas fa-user-shield'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-layer-group', 'title' => 'Unified POS Layer', 'desc' => 'Consistent sales and inventory data across every location.'],
                    ['icon' => 'fas fa-mobile-screen', 'title' => 'First-Party Online Ordering', 'desc' => 'Branded ordering without aggregator commission.'],
                    ['icon' => 'fas fa-kitchen-set', 'title' => 'Digital Kitchen Display', 'desc' => 'Faster, clearer order tickets replacing paper.'],
                    ['icon' => 'fas fa-chart-column', 'title' => 'Chain-Wide Dashboard', 'desc' => 'Real-time sales and inventory visibility for head office.'],
                    ['icon' => 'fas fa-book-open', 'title' => 'Centralized Menu Management', 'desc' => 'Push menu and price changes chain-wide in minutes.'],
                    ['icon' => 'fas fa-gift', 'title' => 'Loyalty & Promotions', 'desc' => 'Chain-wide rewards and location-specific promotions.'],
                ],

                'cs_faqs' => [
                    ['question' => 'Did all 85 locations need new POS hardware?', 'answer' => 'No — the unified data layer was built to sit above existing POS hardware wherever possible, avoiding a costly hardware replacement across all 85 locations.'],
                    ['question' => 'Did Bistro Nationale drop the third-party delivery aggregators entirely?', 'answer' => "No. The goal was shifting order mix toward first-party ordering to reduce commission costs, not eliminating aggregators, which still bring in customers who wouldn't otherwise discover the brand."],
                    ['question' => 'How did kitchen staff adapt to the digital ticket system?', 'answer' => 'We piloted the kitchen display system at a handful of locations first and adjusted the interface based on real kitchen staff feedback before rolling it out further.'],
                    ['question' => 'How is customer ordering data handled under GDPR?', 'answer' => 'Customer ordering and loyalty program data is handled in compliance with EU GDPR, with clear consent capture built into the first-party ordering flow.'],
                ],

                'testimonial_quote' => "We'd grown to 85 locations through acquisitions, which sounds great until you realize you've also acquired 85 different ways of running a POS system. Kawach found a way to unify our data without forcing every location to rip out their hardware, and cutting our aggregator fee spend by a third funded a big chunk of the project on its own.",
                'testimonial_name' => 'Émilie Rousseau',
                'testimonial_role' => 'Director of Operations, Bistro Nationale Group',
            ],

            // ══════════════════════════════════════════════════════════════
            // 9. Nonprofit — Donor Management (USA — Washington, D.C.)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'horizon-give-foundation-donor-platform-case-study',
                'title'            => "Modernizing Donor Management and Fundraising for Horizon Give Foundation's 220,000 Donors",
                'is_featured'      => false,
                'sort_order'       => 14,
                'author_id'        => 1,
                'client_name'      => 'Horizon Give Foundation',
                'client_industry'  => 'Nonprofit / Charitable Foundation',
                'business_size'    => 'National nonprofit, 220,000+ donors',
                'location'         => 'Washington, D.C., USA',
                'business_model'   => 'Nonprofit / Charitable Foundation',
                'project_duration' => '7 months',
                'completion_date'  => 'January 2026',
                'focus_keyword'    => 'nonprofit donor management software',
                'meta_title'       => 'Nonprofit Donor Platform Case Study — Horizon Give Foundation | Kawach Technology',
                'meta_description' => 'How Kawach Technology unified donor data and automated recurring-donation recovery for Horizon Give Foundation\'s 220,000+ donors.',
                'tags'             => 'nonprofit technology, donor management platform, fundraising software, recurring donation automation',

                'challenge' => <<<'HTML'
<p>Horizon Give Foundation has built a donor base of more than 220,000 people over its history, but that history was part of the problem: donor records were split across an aging CRM, assorted spreadsheets maintained by different staff over the years, and a separate online donation tool that had never been properly connected back to anything else. A single donor's giving history might be scattered across all three, with no one system showing the complete picture.</p>
<p>Recurring monthly donations — the backbone of any sustainable nonprofit's revenue — had a quietly high silent failure rate. Expired cards and other routine payment failures caused donations to simply stop, with no automated process to reach out and recover them; a donor who wanted to keep giving might simply fall off without anyone noticing until someone happened to run a report.</p>
<p>Compiling donor and impact reports for grant applications and board meetings took the development team days each time, since the data lived in three places and had to be reconciled manually. There was no way to segment donors for targeted outreach beyond a few broad, manually maintained mailing lists, and major donor relationship history — the kind of detail that actually matters for cultivating a significant gift — was tracked informally, often living only in one staff member's inbox and memory.</p>
HTML,

                'existing_challenges' => [
                    ["text" => "Donor records were split across an aging CRM, various spreadsheets, and a separate online donation tool that didn't sync data back."],
                    ['text' => 'Recurring monthly donations had a high silent failure rate (expired cards, etc.) with no automated way to recover them.'],
                    ['text' => 'Compiling donor and impact reports for grant applications and board meetings took the development team days each time.'],
                    ['text' => 'There was no way to segment donors for targeted outreach beyond a few broad, manually maintained mailing lists.'],
                    ['text' => "Major donor relationship history was tracked informally, often just in an individual staff member's email and memory."],
                ],

                'solution' => <<<'HTML'
<p>Consolidating three disconnected data sources into one unified donor CRM was the necessary first step, and also the riskiest — donor giving history is not something a nonprofit can afford to get wrong or lose during a migration. We ran a careful, staged consolidation, reconciling records across all three legacy sources and manually resolving conflicts (like the same donor appearing under slightly different names or addresses) before considering the migration complete.</p>
<p>With clean, unified data in place, we built automated recurring-donation recovery: when a card fails, the system automatically retries on a sensible schedule and, if needed, reaches out to the donor with a simple, low-friction way to update their payment method — recovering donations that used to just quietly stop with nobody noticing.</p>
<p>Reporting automation meant grant and board reports that used to take the development team days now generate in minutes, pulling directly from the unified donor and donation data rather than requiring manual reconciliation across systems. Donor segmentation tools let the team build targeted outreach campaigns based on actual giving behavior and history, rather than the same broad mailing list going to everyone regardless of relevance.</p>
<p>For major donor relationships specifically, we built a relationship-tracking module that captures interaction history, giving patterns, and relevant notes in one place — replacing the "it's in someone's inbox" system with something the whole development team can actually see and act on, which matters a great deal when a key staff member eventually moves on.</p>
HTML,

                'goals' => [
                    ['title' => 'Unify Donor Records', 'desc' => 'Consolidate three disconnected data sources into one donor CRM.', 'icon' => 'fas fa-database', 'color' => '#1a73e8'],
                    ['title' => 'Recover Lapsed Recurring Donations', 'desc' => 'Automatically retry and recover recurring donations that silently fail.', 'icon' => 'fas fa-rotate', 'color' => '#00c896'],
                    ['title' => 'Automate Reporting', 'desc' => 'Generate grant and board reports in minutes instead of days.', 'icon' => 'fas fa-file-lines', 'color' => '#ffb830'],
                    ['title' => 'Enable Targeted Outreach', 'desc' => 'Segment donors by real giving behavior for more relevant campaigns.', 'icon' => 'fas fa-bullseye', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Unified Donor CRM', 'desc' => 'One donor record consolidating history from 3 previously disconnected systems.', 'icon' => 'fas fa-address-book'],
                    ['name' => 'Recurring Donation Recovery Engine', 'desc' => 'Automated retry and donor outreach when a recurring payment fails.', 'icon' => 'fas fa-rotate'],
                    ['name' => 'Automated Impact & Grant Reporting', 'desc' => 'Self-service reports for grant applications and board meetings, generated in minutes.', 'icon' => 'fas fa-file-lines'],
                    ['name' => 'Donor Segmentation & Campaign Tools', 'desc' => 'Targeted outreach based on real giving behavior and history.', 'icon' => 'fas fa-bullseye'],
                    ['name' => 'Major Donor Relationship Tracking', 'desc' => 'Interaction history and notes for significant donor relationships, visible to the whole team.', 'icon' => 'fas fa-handshake'],
                    ['name' => 'Online Donation Portal', 'desc' => 'A modern, mobile-friendly donation flow feeding directly into the unified CRM.', 'icon' => 'fas fa-hand-holding-heart'],
                ],

                'kpis' => [
                    ['label' => 'Recurring Donation Recovery Rate', 'value' => '+64%'],
                    ['label' => 'Reporting Time', 'value' => 'Days → Minutes'],
                    ['label' => 'Donor Retention Rate', 'value' => '+21%'],
                    ['label' => 'Campaign Response Rate', 'value' => '+2.1x'],
                ],

                'technologies' => 'Laravel, Vue.js, MySQL, Stripe, Mailgun, AWS',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel, MySQL'],
                    ['category' => 'Frontend', 'items' => 'Vue.js'],
                    ['category' => 'Payments', 'items' => 'Stripe (with automated dunning/retry)'],
                    ['category' => 'Email & Outreach', 'items' => 'Mailgun'],
                    ['category' => 'Infrastructure', 'items' => 'AWS'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Donor Data Consolidation Planning', 'desc' => 'Mapped donor records across all 3 legacy systems and planned a careful, staged consolidation.'],
                    ['badge' => '02', 'title' => 'Unified CRM Migration', 'desc' => 'Migrated and reconciled donor records, resolving duplicate and conflicting entries by hand where needed.'],
                    ['badge' => '03', 'title' => 'Recurring Donation Recovery Logic Build', 'desc' => 'Built automated retry and donor outreach for failed recurring payments.'],
                    ['badge' => '04', 'title' => 'Reporting Automation', 'desc' => 'Built self-service grant and board reporting pulling directly from the unified data.'],
                    ['badge' => '05', 'title' => 'Segmentation & Campaign Tools Rollout', 'desc' => 'Delivered donor segmentation tools for targeted outreach based on real giving behavior.'],
                    ['badge' => '06', 'title' => 'Staff Training', 'desc' => 'Trained the development team across the organization on the new unified system.'],
                ],

                'achievements' => [
                    ['title' => 'Unified 220,000+ Donor Records', 'desc' => 'Consolidated three disconnected systems into one donor CRM with reconciled, accurate history.'],
                    ['title' => 'Recovered the Majority of Lapsed Recurring Donations', 'desc' => 'Automated recovery reclaimed 64% more previously-lapsed recurring donations.'],
                    ['title' => 'Reporting Time Cut from Days to Minutes', 'desc' => 'Grant and board reports that used to take days now generate on demand.'],
                ],

                'before_after' => [
                    ['before' => 'Donor data split across 3 disconnected systems', 'after' => 'One unified donor CRM'],
                    ['before' => 'High silent recurring-donation failure rate', 'after' => 'Automated recovery reclaiming most lapses'],
                    ['before' => 'Days to compile reports for grants/board', 'after' => 'Self-service reports in minutes'],
                    ['before' => 'Broad, manually maintained mailing lists', 'after' => 'Targeted, data-driven donor segmentation'],
                ],

                'compliance_items' => [
                    ['title' => 'PCI-DSS Compliant Donations', 'desc' => 'All online donation processing meets PCI-DSS payment security standards.', 'icon' => 'fas fa-credit-card'],
                    ['title' => 'Donor Data Privacy', 'desc' => 'Donor personal and giving data is encrypted and access-controlled.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'Nonprofit Financial Reporting Alignment', 'desc' => 'Reporting outputs align with standard nonprofit financial and grant reporting requirements.', 'icon' => 'fas fa-file-invoice'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-address-book', 'title' => 'Unified Donor CRM', 'desc' => 'One consolidated record for every donor across all channels.'],
                    ['icon' => 'fas fa-rotate', 'title' => 'Recurring Donation Recovery', 'desc' => 'Automated retry and outreach for failed recurring payments.'],
                    ['icon' => 'fas fa-file-lines', 'title' => 'Automated Impact Reporting', 'desc' => 'Grant and board reports generated on demand.'],
                    ['icon' => 'fas fa-bullseye', 'title' => 'Donor Segmentation', 'desc' => 'Targeted outreach based on real giving behavior.'],
                    ['icon' => 'fas fa-handshake', 'title' => 'Major Donor Tracking', 'desc' => 'Shared relationship history for significant donors.'],
                    ['icon' => 'fas fa-hand-holding-heart', 'title' => 'Online Donation Portal', 'desc' => 'A modern, mobile-friendly giving experience.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How did you migrate 220,000 donor records without losing giving history?', 'answer' => 'We ran a staged consolidation across all three legacy sources, manually reconciling duplicate or conflicting donor entries before considering any record fully migrated — giving history is too important to a nonprofit to risk on a one-shot migration.'],
                    ['question' => 'How does recurring donation recovery work without annoying donors?', 'answer' => "The system retries a failed payment automatically on a sensible schedule first, and only reaches out to the donor directly with a simple update-payment-method link if the automated retries don't resolve it."],
                    ['question' => 'How is donor data privacy protected?', 'answer' => 'Donor personal and financial data is encrypted at rest and in transit, with role-based access so only appropriate staff can view sensitive donor information.'],
                    ['question' => 'Can reporting be customized for different grant requirements?', 'answer' => 'Yes — the reporting module supports custom report templates so different grants or board reporting formats can pull from the same underlying unified data.'],
                ],

                'testimonial_quote' => "We had donor history scattered across a CRM nobody fully trusted, spreadsheets only certain staff knew how to read, and an online donation tool that felt like its own island. Bringing all of that into one place, and then having failed recurring donations actually get recovered automatically instead of just disappearing, has meaningfully changed our fundraising capacity — not through some flashy new campaign, just through not losing donors we'd already earned.",
                'testimonial_name' => 'Nathaniel Cross',
                'testimonial_role' => 'Director of Development, Horizon Give Foundation',
            ],

            // ══════════════════════════════════════════════════════════════
            // 10. EV Fleet & Charging Network (Sweden — Gothenburg)
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'fjordlight-mobility-ev-fleet-charging-case-study',
                'title'            => 'Managing 3,200+ EVs and a Growing Charging Network for Fjordlight Mobility',
                'is_featured'      => false,
                'sort_order'       => 15,
                'author_id'        => 2,
                'client_name'      => 'Fjordlight Mobility AB',
                'client_industry'  => 'Electric Vehicle Fleet & Charging Networks',
                'business_size'    => 'Nordic EV fleet operator, 3,200+ vehicles',
                'location'         => 'Gothenburg, Sweden',
                'business_model'   => 'EV Fleet & Charging Network Operator',
                'project_duration' => '9 months',
                'completion_date'  => 'June 2026',
                'focus_keyword'    => 'EV fleet management software',
                'meta_title'       => 'EV Fleet Management Case Study — Fjordlight Mobility | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a real-time fleet telematics and smart charging platform for Fjordlight Mobility, managing 3,200+ EVs across the Nordics.',
                'tags'             => 'EV fleet management, charging network software, fleet telematics, sustainability reporting',

                'challenge' => <<<'HTML'
<p>Fjordlight Mobility grew its Nordic electric vehicle fleet from a few hundred vehicles to more than 3,200 in a few years — a genuine growth success story that had completely outpaced the spreadsheet-based fleet tracking the company started with. What worked for a few hundred vehicles simply couldn't scale to thousands, and the cracks were showing everywhere.</p>
<p>Charging station faults or downtime were often only discovered the hard way: a driver would arrive at a station expecting to charge and find it wasn't working, with no advance warning to reroute them elsewhere. Vehicles charged whenever they happened to be plugged in, with no coordination across the fleet, which meant charging costs spiked during peak electricity demand periods that smarter scheduling could easily have avoided.</p>
<p>Corporate fleet clients — a growing and increasingly important part of Fjordlight's business — were asking for emissions and sustainability reports as standard due diligence, and Fjordlight had no automated way to produce them; compiling one meant manual data-pulling that didn't scale as the client roster grew. Route and vehicle assignment planning didn't account for real-time battery range or charging station availability either, occasionally assigning a route a vehicle couldn't comfortably complete without an unplanned charging stop.</p>
HTML,

                'existing_challenges' => [
                    ['text' => "Fleet tracking was still managed in spreadsheets, which hadn't scaled past a few hundred vehicles let alone 3,200+."],
                    ["text" => "Charging station faults or downtime were often only discovered when a driver arrived and couldn't charge."],
                    ['text' => 'Vehicles charged whenever plugged in with no coordination, driving up costs during peak electricity demand periods.'],
                    ['text' => 'Corporate fleet clients increasingly asked for emissions and sustainability reports Fjordlight had no automated way to produce.'],
                    ['text' => "Route and vehicle assignment planning didn't account for real-time battery range or charging station availability."],
                ],

                'solution' => <<<'HTML'
<p>The foundational piece was real-time fleet telematics — pulling live location, battery state, and vehicle health data from all 3,200+ vehicles into one platform, replacing spreadsheets that had never been designed for this scale in the first place. That same telemetry approach extended to the charging network itself, so station faults surface as an alert within minutes rather than being discovered by a stranded driver.</p>
<p>Smart charge scheduling was the piece with the clearest direct cost impact: rather than every vehicle charging the moment it's plugged in, the system coordinates charging across the fleet to shift load toward off-peak electricity pricing windows wherever a vehicle's schedule allows the flexibility, meaningfully reducing per-vehicle charging costs without requiring drivers to think about electricity pricing at all.</p>
<p>For corporate clients, we built an automated sustainability and emissions reporting module that pulls directly from real fleet telematics data — actual distance driven, actual charging sourced, not estimates — turning what used to be a manual, multi-day report-building exercise into something Fjordlight's account managers can generate on demand for any client, for any time period.</p>
<p>Range-aware route and vehicle assignment closed the loop: the system now factors in each vehicle's actual current battery range and nearby charging station availability before assigning it to a route, preventing the awkward situation of a vehicle being assigned a route it can't comfortably complete. We piloted the full platform on a 400-vehicle sub-fleet, deliberately including a mix of vehicle models and route types, before extending it across the full fleet and charging network.</p>
HTML,

                'goals' => [
                    ['title' => 'Scale Fleet Visibility Beyond Spreadsheets', 'desc' => 'Track 3,200+ vehicles in real time instead of manual spreadsheets.', 'icon' => 'fas fa-car-side', 'color' => '#1a73e8'],
                    ['title' => 'Monitor Charging Network Health', 'desc' => 'Detect charging station faults within minutes, not after a driver is stranded.', 'icon' => 'fas fa-charging-station', 'color' => '#00c896'],
                    ['title' => 'Optimize Charging Costs', 'desc' => 'Shift charging load to off-peak periods wherever possible.', 'icon' => 'fas fa-bolt', 'color' => '#ffb830'],
                    ['title' => 'Automate Sustainability Reporting', 'desc' => 'Generate real, data-backed emissions reports for corporate clients on demand.', 'icon' => 'fas fa-leaf', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Real-Time Fleet Telematics', 'desc' => 'Live location, battery state, and vehicle health data for 3,200+ vehicles.', 'icon' => 'fas fa-satellite-dish'],
                    ['name' => 'Charging Station Health Monitoring', 'desc' => 'Proactive fault alerts before a driver arrives at a non-functional station.', 'icon' => 'fas fa-charging-station'],
                    ['name' => 'Smart Charge Scheduling', 'desc' => 'Coordinates fleet charging to shift load toward off-peak electricity pricing.', 'icon' => 'fas fa-bolt'],
                    ['name' => 'Sustainability & Emissions Reporting', 'desc' => 'Automated, data-backed reports generated directly from real telematics data.', 'icon' => 'fas fa-leaf'],
                    ['name' => 'Range-Aware Route Assignment', 'desc' => 'Assigns vehicles to routes accounting for real battery range and charging availability.', 'icon' => 'fas fa-route'],
                    ['name' => 'Corporate Client Reporting Portal', 'desc' => 'On-demand sustainability and usage reports for corporate fleet clients.', 'icon' => 'fas fa-file-lines'],
                ],

                'kpis' => [
                    ['label' => 'Charging Cost per Vehicle', 'value' => '-19%'],
                    ['label' => 'Station Downtime Detection', 'value' => 'Hours → Minutes'],
                    ['label' => 'Fleet Utilization', 'value' => '+23%'],
                    ['label' => 'Sustainability Report Generation', 'value' => 'Days → Automated'],
                ],

                'technologies' => 'Python, Django, TimescaleDB, MQTT, React, Azure',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Django, TimescaleDB (telematics data)'],
                    ['category' => 'Data Ingestion', 'items' => 'MQTT from vehicles and charging stations'],
                    ['category' => 'Frontend', 'items' => 'React'],
                    ['category' => 'Infrastructure', 'items' => 'Azure, EU data residency'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Fleet & Charging Network Audit', 'desc' => 'Assessed existing telemetry availability across vehicles and charging stations.'],
                    ['badge' => '02', 'title' => 'Telematics Data Pipeline Build', 'desc' => 'Built the MQTT-based ingestion pipeline feeding a central time-series platform.'],
                    ['badge' => '03', 'title' => 'Smart Charge Scheduling Algorithm Design', 'desc' => 'Designed scheduling logic to shift charging load off-peak wherever vehicle schedules allow.'],
                    ['badge' => '04', 'title' => 'Sustainability Reporting Framework', 'desc' => 'Built automated emissions and usage reporting directly from telematics data.'],
                    ['badge' => '05', 'title' => 'Pilot on 400-Vehicle Sub-Fleet', 'desc' => 'Validated the platform on a representative mix of vehicle models and route types.'],
                    ['badge' => '06', 'title' => 'Full Fleet & Network Rollout', 'desc' => 'Extended the platform across all 3,200+ vehicles and the full charging network.'],
                ],

                'achievements' => [
                    ['title' => 'Real-Time Visibility for 3,200+ Vehicles', 'desc' => 'Fleet tracking scaled from spreadsheets to real-time telematics across the entire fleet.'],
                    ['title' => 'Charging Costs Cut Per Vehicle', 'desc' => 'Smart charge scheduling reduced per-vehicle charging costs by 19%.'],
                    ['title' => 'Sustainability Reporting Fully Automated', 'desc' => 'Corporate clients now receive data-backed emissions reports on demand instead of waiting days.'],
                ],

                'before_after' => [
                    ['before' => 'Spreadsheet fleet tracking', 'after' => 'Real-time telematics for 3,200+ vehicles'],
                    ['before' => 'Charging faults discovered by stranded drivers', 'after' => 'Proactive station health monitoring'],
                    ['before' => 'Uncoordinated peak-hour charging', 'after' => 'Smart scheduling shifting load off-peak'],
                    ['before' => 'Manual sustainability reports', 'after' => 'Automated corporate reporting portal'],
                ],

                'compliance_items' => [
                    ['title' => 'EU Data Residency', 'desc' => 'Fleet and driver data is stored and processed within EU-based infrastructure.', 'icon' => 'fas fa-earth-europe'],
                    ['title' => 'GDPR-Compliant Driver Data', 'desc' => 'Driver and vehicle usage data handling complies with GDPR requirements.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'Sustainability Reporting Standards', 'desc' => 'Emissions reports align with recognized corporate sustainability reporting standards.', 'icon' => 'fas fa-leaf'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-satellite-dish', 'title' => 'Real-Time Fleet Telematics', 'desc' => 'Live tracking of location, battery, and vehicle health.'],
                    ['icon' => 'fas fa-charging-station', 'title' => 'Charging Station Health Monitoring', 'desc' => 'Proactive fault detection before drivers are affected.'],
                    ['icon' => 'fas fa-bolt', 'title' => 'Smart Charge Scheduling', 'desc' => 'Shifts fleet charging toward off-peak pricing windows.'],
                    ['icon' => 'fas fa-leaf', 'title' => 'Automated Sustainability Reporting', 'desc' => 'Data-backed emissions reports generated on demand.'],
                    ['icon' => 'fas fa-route', 'title' => 'Range-Aware Route Assignment', 'desc' => 'Vehicle assignment that accounts for real battery range.'],
                    ['icon' => 'fas fa-file-lines', 'title' => 'Corporate Client Portal', 'desc' => 'On-demand usage and sustainability reports for clients.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How did you scale telematics tracking to thousands of vehicles?', 'answer' => 'We built the ingestion pipeline on a time-series database specifically designed for high-volume telemetry data, and validated it on a 400-vehicle pilot fleet before extending to the full 3,200+ vehicle fleet.'],
                    ['question' => 'Does smart charging scheduling reduce driver flexibility?', 'answer' => 'Only within limits the driver\'s own schedule allows — the system shifts charging to off-peak windows when a vehicle has flexibility, but never delays charging a vehicle that needs to be ready sooner.'],
                    ['question' => 'How accurate is the sustainability reporting?', 'answer' => 'Reports are generated directly from real telematics data — actual distance driven and actual energy consumed — rather than estimates, which is specifically what corporate clients wanted when they started requesting these reports.'],
                    ['question' => 'How is driver and vehicle data protected under GDPR?', 'answer' => 'All fleet and driver data is stored on EU-based infrastructure with access controls and data handling practices designed to meet GDPR requirements from the start.'],
                ],

                'testimonial_quote' => "We grew our fleet faster than our own tools could keep up with, and it showed — drivers stranded at broken chargers, charging costs creeping up for no clear reason, corporate clients asking for sustainability numbers we couldn't easily produce. Real-time visibility across 3,200 vehicles fixed all of that at once, and the automated sustainability reports have actually become something our sales team leads with now instead of scrambling to produce after the fact.",
                'testimonial_name' => 'Erik Lindqvist',
                'testimonial_role' => 'Head of Fleet Operations, Fjordlight Mobility AB',
            ],

        ];
    }
}
