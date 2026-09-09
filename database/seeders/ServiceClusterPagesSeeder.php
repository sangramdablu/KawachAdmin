<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageService;
use Illuminate\Database\Seeder;

/**
 * SEO PHASE 9 — new service cluster pages.
 *
 * Of the 14 URLs listed in the brief, 9 already exist under a different
 * (often better) slug and already serve the same search intent:
 *   web-application-development, mobile-app-development,
 *   dedicated-development-teams, cloud-devops-solutions,
 *   quality-assurance-software-testing, ai-machine-learning-development,
 *   plus 2 existing (overlapping) API pages.
 * Creating parallel pages for those would be exactly the kind of keyword
 * cannibalization / duplicate-page problem this whole project started by
 * fixing (see Phase 1/18/31) — so this seeder only creates the 5 that were
 * genuinely missing: SaaS, Enterprise Software, Software Modernization,
 * CRM, and ERP development.
 *
 * Each page links inline to one real, already-published case study that
 * genuinely matches (verified present in both the local dev DB and the
 * live production sitemap — not the local-only "Meridian Flow" or
 * production-only "real-estate-property-management-platform" case
 * studies flagged as diverged in earlier phases), plus to
 * custom-software-development per Phase 9's linking requirement.
 */
class ServiceClusterPagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsert('saas-development', [
            'title' => 'SaaS Development',
            'meta_title' => 'SaaS Development Company | Kawach Technology',
            'meta_description' => 'Kawach Technology builds multi-tenant SaaS platforms — from a focused MVP to a product supporting thousands of teams — with billing and onboarding built in.',
            'meta_keywords' => 'saas development, saas development company, saas mvp development, custom saas platform',
            'focus_keyword' => 'saas development',
            'short_description' => 'We design and build multi-tenant SaaS platforms — architected from day one to onboard customers, scale with usage, and support the subscription model your business runs on.',
            'content' => '<p>SaaS development means building a platform your customers subscribe to, not a one-off application for a single company. That changes the engineering priorities: multi-tenancy, per-customer data isolation, subscription billing, and an architecture that stays reliable as your customer count grows from ten to ten thousand.</p>'
                . '<p>We build SaaS products end to end — onboarding flows that get new customers to value quickly, role-based access and account management, integrations with the billing and analytics tools you already use, and infrastructure that scales horizontally instead of needing a rewrite at your next growth stage.</p>'
                . '<p>Our own product team has been on both sides of this: our internal project management platform, <a href="/case-studies/orbit-real-time-project-management-case-study">Orbit</a>, is a real-time SaaS product we built and run ourselves, alongside client SaaS platforms. If you\'re earlier in the journey, our <a href="/services/custom-software-development">custom software development</a> approach applies the same MVP-first thinking to get your SaaS off the ground without over-building on day one.</p>',
            'features' => [
                ['title' => 'Multi-Tenant Architecture', 'description' => 'Data isolation and account structure designed for many customers on one platform, without cross-tenant risk.'],
                ['title' => 'Subscription & Billing Integration', 'description' => 'Plans, trials, upgrades and usage-based billing wired into your platform, not bolted on after launch.'],
                ['title' => 'Onboarding & User Management', 'description' => 'Self-serve onboarding, role-based permissions, and account administration built for how SaaS customers actually sign up.'],
                ['title' => 'API-First Design', 'description' => 'A well-documented API from the start, so integrations and future mobile or partner apps aren\'t an afterthought.'],
                ['title' => 'Scalable Cloud Infrastructure', 'description' => 'Infrastructure that scales horizontally as usage grows, with monitoring in place before you need it.'],
            ],
            'technologies' => 'Laravel, Node.js, React, PostgreSQL, Redis, AWS, Stripe',
        ]);

        $this->upsert('enterprise-software-development', [
            'title' => 'Enterprise Software Development',
            'meta_title' => 'Enterprise Software Development Company | Kawach Technology',
            'meta_description' => 'Kawach Technology builds enterprise software that holds up at scale — connecting departments and legacy systems with the security enterprise operations demand.',
            'meta_keywords' => 'enterprise software development, enterprise software development company, enterprise application development',
            'focus_keyword' => 'enterprise software development',
            'short_description' => 'We build enterprise software that connects departments, integrates with existing systems, and holds up under real operational scale — not a prototype that breaks past a few hundred users.',
            'content' => '<p>Enterprise software development is different from building a single-team tool: the system has to serve multiple departments, integrate with systems that already exist, meet security and compliance requirements, and stay reliable for thousands of users rather than dozens.</p>'
                . '<p>We design enterprise platforms around your actual organizational structure — role-based access across departments, audit trails where they matter, and integration with the systems you\'re not replacing. <a href="/case-studies/lakeshore-mutual-insurance-claims-automation-case-study">Our work automating claims intake for a 300,000-policyholder insurer</a> is a good example of what that looks like at real enterprise scale.</p>'
                . '<p>Enterprise projects still benefit from the same <a href="/services/custom-software-development">custom software development</a> discipline we apply everywhere — phased delivery and clear architecture decisions, just scoped for organizational complexity rather than a single team.</p>',
            'features' => [
                ['title' => 'Cross-Department Workflows', 'description' => 'Systems designed around how information actually needs to move between teams, not a single department\'s view of the process.'],
                ['title' => 'Legacy System Integration', 'description' => 'Connecting new software to the systems you\'re not replacing, instead of forcing an all-or-nothing migration.'],
                ['title' => 'Enterprise-Grade Security', 'description' => 'Role-based access, audit trails and data handling built to the standard enterprise operations require.'],
                ['title' => 'High-Volume Reliability', 'description' => 'Architecture tested for real operational load — thousands of concurrent users, not a demo environment.'],
                ['title' => 'Compliance-Aware Design', 'description' => 'Built with your specific regulatory and data-handling requirements factored in from the architecture stage.'],
            ],
            'technologies' => 'Laravel, Node.js, PostgreSQL, MySQL, AWS, Docker, Redis',
        ]);

        $this->upsert('software-modernization', [
            'title' => 'Software Modernization',
            'meta_title' => 'Software Modernization Services | Kawach Technology',
            'meta_description' => 'Kawach Technology modernizes legacy systems — reducing technical debt and migrating to maintainable architecture without disrupting the business that depends on it.',
            'meta_keywords' => 'software modernization, legacy software modernization, legacy system migration, application modernization',
            'focus_keyword' => 'software modernization',
            'short_description' => 'We modernize legacy systems that have become slow, brittle or risky to extend — migrating to current architecture and technology without disrupting the operations that depend on them every day.',
            'content' => '<p>Software modernization means taking a system that\'s still functional but increasingly hard to maintain — outdated technology, undocumented workarounds, a codebase nobody wants to touch — and bringing it onto architecture your team can actually extend safely.</p>'
                . '<p>We start by understanding what the existing system actually does (including the undocumented parts), then modernize in stages rather than a risky big-bang rewrite — keeping the business running throughout. <a href="/case-studies/horizon-give-foundation-donor-platform-case-study">Modernizing donor management for a foundation serving 220,000 donors</a> is a real example of replacing a legacy platform without disrupting the operation depending on it.</p>'
                . '<p>Where a full rebuild does make sense, we scope it the same way we approach any <a href="/services/custom-software-development">custom software development</a> project — phased, with the highest-risk legacy components addressed first.</p>',
            'features' => [
                ['title' => 'Legacy System Audits', 'description' => 'Understanding what the current system actually does — including undocumented behavior — before changing anything.'],
                ['title' => 'Phased Migration', 'description' => 'Modernizing in stages so the business keeps running throughout, instead of a risky all-at-once cutover.'],
                ['title' => 'Technical Debt Reduction', 'description' => 'Replacing brittle, hard-to-maintain code with current, well-documented architecture your team can extend.'],
                ['title' => 'Platform & Framework Upgrades', 'description' => 'Moving off outdated languages, frameworks or infrastructure that are becoming a hiring and security risk.'],
                ['title' => 'Data Migration', 'description' => 'Moving existing data across safely, with validation, rather than treating it as an afterthought.'],
            ],
            'technologies' => 'PHP, Laravel, Node.js, MySQL, PostgreSQL, AWS, Docker',
        ]);

        $this->upsert('crm-development', [
            'title' => 'Custom CRM Development',
            'meta_title' => 'Custom CRM Development Services | Kawach Technology',
            'meta_description' => 'Kawach Technology builds custom CRM software around your actual sales and client-relationship process, instead of a generic, one-size-fits-all CRM.',
            'meta_keywords' => 'crm development, custom crm development, custom crm software, crm development company',
            'focus_keyword' => 'crm development',
            'short_description' => 'We build custom CRM software around your actual sales, client-relationship or case-management process — not a generic pipeline your team has to bend their workflow to fit.',
            'content' => '<p>Off-the-shelf CRMs are built to serve as many businesses as possible, which means most teams end up customizing around the parts that don\'t fit, or paying for modules they don\'t need. Custom CRM development flips that — the software is built around your actual sales process, client relationships, or case-management workflow from the start.</p>'
                . '<p>We\'ve built this for businesses where relationship tracking isn\'t just sales — <a href="/case-studies/sterling-cross-legal-partners-case-management-case-study">modernizing case management for a legal partnership across 5 offices</a> is fundamentally a custom CRM problem: tracking clients, matters and communication in one connected system built for how the firm actually works.</p>'
                . '<p>A custom CRM is one part of a larger system for most clients — it often connects to the broader <a href="/services/custom-software-development">custom software development</a> work we do around it, from reporting dashboards to client portals.</p>',
            'features' => [
                ['title' => 'Pipeline Built Around Your Process', 'description' => 'Stages, fields and workflows that match how your team actually sells or manages relationships — not a generic template.'],
                ['title' => 'Third-Party Integrations', 'description' => 'Connect your CRM to email, calendars, accounting software and the other tools your team already uses daily.'],
                ['title' => 'Custom Reporting & Dashboards', 'description' => 'The specific metrics your sales or account management team needs to see, without digging through generic reports.'],
                ['title' => 'Role-Based Access', 'description' => 'Give each team — sales, support, management — exactly the view and permissions they need.'],
                ['title' => 'Automation & Reminders', 'description' => 'Automated follow-ups, task assignment and notifications so nothing falls through the cracks.'],
            ],
            'technologies' => 'Laravel, Node.js, React, MySQL, PostgreSQL, AWS',
        ]);

        $this->upsert('erp-development', [
            'title' => 'Custom ERP Development',
            'meta_title' => 'Custom ERP Development Services | Kawach Technology',
            'meta_description' => 'Kawach Technology builds custom ERP software connecting finance, inventory, operations and reporting — replacing spreadsheets with one platform built for your business.',
            'meta_keywords' => 'erp development, custom erp development, custom erp software, erp development company',
            'focus_keyword' => 'erp development',
            'short_description' => 'We build custom ERP software that connects finance, inventory, operations and reporting into one system — replacing spreadsheets and disconnected tools with a platform built around how your business runs.',
            'content' => '<p>ERP development means bringing the core operational functions of your business — finance, inventory, scheduling, reporting — into one connected system, instead of maintaining them separately across spreadsheets and disconnected software.</p>'
                . '<p>Off-the-shelf ERP systems are built for a generic version of your industry; custom ERP development is built around how your specific organization actually operates. <a href="/case-studies/bright-horizons-school-group-erp-case-study">Digitizing admissions, attendance and fees across 3 campuses for a school group</a> is a direct example — a single ERP system replacing manual, campus-by-campus processes.</p>'
                . '<p>Most ERP projects are scoped and delivered the way we approach any <a href="/services/custom-software-development">custom software development</a> engagement — starting with the highest-impact modules first, rather than trying to replace every system on day one.</p>',
            'features' => [
                ['title' => 'Unified Operations', 'description' => 'Finance, inventory, scheduling and reporting in one system instead of several disconnected tools.'],
                ['title' => 'Custom Modules', 'description' => 'Build only the modules your business actually needs — not a generic suite with features you\'ll never use.'],
                ['title' => 'Real-Time Reporting', 'description' => 'Operational and financial reporting drawn from live data, not a manually assembled monthly spreadsheet.'],
                ['title' => 'Multi-Location Support', 'description' => 'A single system that works across multiple branches, campuses or locations, with the right data visibility at each level.'],
                ['title' => 'Role-Based Workflows', 'description' => 'Each department — finance, operations, admin — gets the specific workflow and permissions their role needs.'],
            ],
            'technologies' => 'Laravel, PHP, MySQL, PostgreSQL, Vue.js, AWS',
        ]);
    }

    private function upsert(string $slug, array $data): void
    {
        $page = Page::firstOrNew(['slug' => $slug]);

        $page->fill([
            'page_type' => 'service',
            'title' => $data['title'],
            'slug' => $slug,
            'status' => 'published',
            'published_at' => $page->published_at ?? now(),
            'focus_keyword' => $data['focus_keyword'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keywords' => $data['meta_keywords'],
            'robots' => 'index, follow',
            'sitemap_priority' => 0.8,
            'sitemap_changefreq' => 'monthly',
        ]);
        $page->save();

        $service = PageService::firstOrNew(['page_id' => $page->id]);
        $service->fill([
            'page_id' => $page->id,
            'short_description' => $data['short_description'],
            'content' => $data['content'],
            'features' => $data['features'],
            'technologies' => $data['technologies'],
        ]);
        $service->save();

        $this->command->info("Service page ready: /services/{$slug} (page id {$page->id}).");
    }
}
