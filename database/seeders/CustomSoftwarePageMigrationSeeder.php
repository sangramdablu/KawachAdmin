<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageService;
use Illuminate\Database\Seeder;

/**
 * SEO PHASE 2 + 3 — single source of truth for the /services/custom-
 * software-development page.
 *
 * Phase 2: migrates the old indexed service page
 * (custom-software-development-services-for-businesses) onto the new
 * canonical slug (custom-software-development), preserving the page's
 * database identity (same row, same id, same published_at) rather than
 * creating a second competing page. The matching 301 redirect for the old
 * URL lives in Kawawch_view's routes/web.php.
 *
 * Phase 3: the short_description/content copy below is written to cover
 * the primary keyword ("custom software development") plus its commercial
 * variants (company, services, business software development, bespoke
 * software development, custom application development) and startup/
 * small-business/cost-effective intent — naturally, not stuffed. USA/UK/
 * Germany/Europe geo-variants are deliberately NOT repeated here; they're
 * already owned by the dedicated market pages this page cross-links to
 * (see the "Software Development Services by Market" block in
 * sevice_details.blade.php), which avoids keyword cannibalization.
 *
 * Phase 4: positioning pass. The hero subtitle names all four segments
 * ("startups, small businesses, growing companies and enterprises")
 * explicitly rather than conflating them, the Overview opens with the
 * brief's anchor message near-verbatim ("We help businesses build the
 * right software without paying for unnecessary complexity"), and the
 * approved vocabulary (flexible, transparent, practical, budget-conscious,
 * value-focused, phased development, efficient development) replaces any
 * generic filler — "cheap"/"cheapest"/"lowest price" are never used
 * anywhere on this page.
 *
 * Safe to re-run: if the old slug is already gone (e.g. already migrated),
 * it falls back to updateOrCreate on the new slug so nothing breaks.
 */
class CustomSoftwarePageMigrationSeeder extends Seeder
{
    private const OLD_SLUG = 'custom-software-development-services-for-businesses';
    private const NEW_SLUG = 'custom-software-development';

    public function run(): void
    {
        $page = Page::where('slug', self::OLD_SLUG)->first()
            ?? Page::where('slug', self::NEW_SLUG)->first()
            ?? new Page();

        $page->fill([
            'page_type'          => 'service',
            'title'              => 'Custom Software Development',
            'slug'               => self::NEW_SLUG,
            'status'             => 'published',
            'published_at'       => $page->published_at ?? now(),
            'focus_keyword'      => 'custom software development',
            'meta_title'         => 'Custom Software Development Company | Kawach Technology',
            'meta_description'  => 'Build custom software that fits your business and budget — scalable web, mobile, SaaS and AI-powered solutions for startups, small businesses and enterprises.',
            'meta_keywords'      => 'custom software development, custom software development company, custom software development services, custom application development, bespoke software development, business software development, software development for startups, software development for small businesses, cost-effective software development',
            'robots'             => 'index, follow',
            'sitemap_priority'   => 0.9,
            'sitemap_changefreq' => 'weekly',
        ]);
        $page->save();

        $service = PageService::firstOrNew(['page_id' => $page->id]);
        $service->fill([
            'page_id'            => $page->id,
            'short_description'  => 'Kawach Technology is a custom software development company helping startups, small businesses, growing companies and enterprises build software around their specific requirements — with a flexible, transparent process that stays practical about cost.',
            'content'            => '<p><strong>We help businesses build the right software without paying for unnecessary complexity.</strong> Off-the-shelf tools rarely fit every workflow, so as a custom software development company we partner with you from discovery through deployment to build custom applications that match how your business actually operates — not the other way around.</p>'
                . '<p>Every engagement starts with requirement mapping and system architecture planning, followed by agile development sprints so you see working software early and often. The result is scalable, secure custom software built on modern engineering practices and designed to grow with your business.</p>'
                . '<p>Whether you need bespoke business software to replace manual processes, a customer-facing SaaS platform, or an internal tool that connects your existing systems, our custom application development approach stays flexible around your goals rather than forcing you into a one-size-fits-all product.</p>'
                . '<p>We work with startups validating a new idea, small businesses outgrowing spreadsheets and disconnected tools, and established enterprises modernizing legacy systems — scoping every engagement to be cost-effective, budget-conscious and value-focused. Where it makes sense, we recommend phased development and an MVP-first roadmap, so you invest in what moves your business forward first and expand from there. That\'s efficient development: building what matters, without the unnecessary complexity.</p>',
            'features'           => [
                ['title' => 'Requirement & Discovery Workshops', 'description' => 'In-depth workshops to map business goals, user workflows, and technical constraints before a single line of code is written.'],
                ['title' => 'Scalable Architecture Design', 'description' => 'Systems designed to handle growth in users, data, and features without costly rewrites down the line.'],
                ['title' => 'Agile Development Sprints', 'description' => '2-week sprint cycles with working demos, so you can track progress and adjust priorities in real time.'],
                ['title' => 'Third-Party System Integration', 'description' => 'Seamless integration with your existing CRMs, ERPs, payment gateways, and internal tools.'],
                ['title' => 'Post-Launch Support', 'description' => 'Ongoing maintenance, monitoring, and feature updates after your software goes live.'],
            ],
            'technologies'       => 'PHP, Laravel, Node.js, Python, React, PostgreSQL, MySQL, AWS',
        ]);
        $service->save();

        $this->command->info("Custom software page migrated to /services/{$page->slug} (page id {$page->id}).");
    }
}
