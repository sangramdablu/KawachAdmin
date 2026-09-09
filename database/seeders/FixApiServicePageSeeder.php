<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * SEO PHASE 18/31 — fixes two real issues found on the API service pages
 * during keyword-mapping / duplicate-content review:
 *
 * 1. custom-api-development-integration-solutions had an ALL-CAPS title
 *    and a meta_title that describes AI, not API (a copy-paste error from
 *    whichever page it was originally drafted alongside) — both fixed here
 *    to accurately describe the actual page content.
 *
 * 2. kawach-provides-api-development-services is a genuine duplicate of
 *    the page above — same search intent ("API development"), same
 *    audience, live at a separate URL, causing keyword cannibalization.
 *    Rather than delete it (its content isn't fabricated, just redundant),
 *    it's set to draft here so it drops out of the sitemap and published-page
 *    scopes; routes/web.php adds a permanent redirect from its old URL to
 *    the canonical page so any existing inbound links/ranking signal
 *    transfers instead of 404ing.
 */
class FixApiServicePageSeeder extends Seeder
{
    public function run(): void
    {
        $canonical = Page::where('slug', 'custom-api-development-integration-solutions')->first();

        if ($canonical) {
            $canonical->fill([
                'title' => 'Custom API Development & Integration Solutions',
                'meta_title' => 'Custom API Development & Integration Services | Kawach Technology',
                'meta_description' => $canonical->meta_description ?: 'Kawach Technology builds custom REST APIs and integrates third-party APIs so your software solution connects seamlessly with the other apps, devices and business systems you rely on.',
                'meta_keywords' => $canonical->meta_keywords ?: 'custom api development, api integration services, rest api development, third-party api integration',
                'focus_keyword' => $canonical->focus_keyword ?: 'api development',
            ])->save();
        }

        $duplicate = Page::where('slug', 'kawach-provides-api-development-services')->first();

        if ($duplicate && $duplicate->status !== 'draft') {
            $duplicate->status = 'draft';
            $duplicate->save();
        }
    }
}
