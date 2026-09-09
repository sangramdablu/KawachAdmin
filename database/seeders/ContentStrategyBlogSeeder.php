<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SEO PHASE 25 — Content Strategy.
 *
 * The brief lists 15 candidate topics across Commercial/Pricing/Technical
 * buckets. Most of them already have a dedicated commercial page targeting
 * that exact keyword — SaaS Development Guide (saas-development), Custom
 * CRM/ERP Development (crm-development, erp-development), How to Modernize
 * Legacy Software (software-modernization), and several cost variants
 * already covered by how-much-does-custom-software-development-cost-in-2026
 * (Phase 22). Writing parallel blog posts for those would create exactly
 * the keyword cannibalization the brief itself warns against (Phase 18/31),
 * and "affordable"/"for startups"/"for small businesses" positioning is
 * already the CSD page's own targeted content.
 *
 * That leaves two genuinely distinct, comparison/decision-intent topics
 * with zero existing page coverage — real search intent, not duplicated
 * anywhere else on the site:
 *   - Custom Software vs Off-the-Shelf Software (comparison intent)
 *   - How to Choose a Custom Software Development Company (vendor-selection intent)
 * Both are written in full and link naturally to the relevant commercial
 * pages, per the brief's explicit requirement — not two more thin posts
 * padding out a content-volume quota.
 */
class ContentStrategyBlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::find(1) ?? User::first();

        $this->upsert(
            slug: 'custom-software-vs-off-the-shelf-software',
            title: 'Custom Software vs Off-the-Shelf Software',
            excerpt: "Off-the-shelf tools get you moving fast and cheap. Custom software costs more upfront but fits your business instead of the other way around. Here's how to actually decide.",
            metaDescription: 'Off-the-shelf vs custom software: a practical comparison of cost, speed and long-term fit, plus the signs that tell you which one your business actually needs.',
            focusKeyword: 'custom software vs off-the-shelf software',
            author: $author,
            content: <<<'HTML'
<p>Almost every software decision starts with this fork: buy something that already exists, or build something that doesn't. Off-the-shelf software (Salesforce, a generic e-commerce platform, a template project-management tool) gets you moving in days. Custom software takes longer and costs more upfront — but it's built around how your business actually works, instead of the other way around. Neither is universally "better." The right answer depends on where your business is right now.</p>

<h2>Off-the-Shelf Software: The Real Trade-Offs</h2>
<p><strong>What you get:</strong> fast setup, lower upfront cost, proven functionality, and someone else handling maintenance and updates.</p>
<p><strong>What it costs you over time:</strong></p>
<ul>
<li><strong>Workflow compromise.</strong> You adapt your process to fit the software, not the other way around — which works fine until your process is actually a competitive advantage.</li>
<li><strong>Subscription costs that compound.</strong> Per-seat pricing that felt trivial at 10 users can become a serious line item at 200.</li>
<li><strong>Feature bloat or feature gaps.</strong> You pay for modules you'll never use, while the one feature your business actually needs isn't on the roadmap.</li>
<li><strong>Integration limits.</strong> You're constrained to whatever APIs and integrations the vendor decided to build.</li>
<li><strong>Vendor lock-in.</strong> Pricing changes, feature deprecations and shutdown risk are the vendor's decisions, not yours.</li>
</ul>

<h2>Custom Software: The Real Trade-Offs</h2>
<p><strong>What you get:</strong> software built around your actual workflow, full ownership of the code and data, no per-seat licensing, and the ability to extend it however your business grows.</p>
<p><strong>What it costs you upfront:</strong></p>
<ul>
<li><strong>Higher initial investment</strong> than a subscription, though it typically pays back over time versus compounding per-seat costs.</li>
<li><strong>Longer time to launch</strong> than signing up for an existing tool — you're building, not subscribing.</li>
<li><strong>You own the maintenance</strong> going forward, whether that's in-house or through an ongoing support arrangement.</li>
</ul>

<h2>Signs You're Fine With Off-the-Shelf</h2>
<ul>
<li>Your process is genuinely standard — payroll, basic accounting, generic email marketing — where a mature tool already does exactly what you need.</li>
<li>You're pre-validation and need to move in days, not months.</li>
<li>The tool's limitations don't touch what actually differentiates your business.</li>
</ul>

<h2>Signs You've Outgrown It</h2>
<ul>
<li>You're paying for multiple tools that don't talk to each other, and someone's job has become manually reconciling data between them.</li>
<li>Your team has built workarounds — spreadsheets, manual steps — to compensate for what the software can't do.</li>
<li>Per-seat or usage-based costs are scaling faster than the value you're getting.</li>
<li>The workflow you're forcing into the software is actually how you compete, not just how you operate.</li>
</ul>

<h2>The Middle Path: Start Off-the-Shelf, Move to Custom When It Makes Sense</h2>
<p>This isn't always an all-or-nothing decision on day one. A common, sensible path is starting with off-the-shelf tools to validate the business, then commissioning <a href="/services/custom-software-development">custom software development</a> once you understand exactly what your business needs and the off-the-shelf costs or limitations start to bite. That's the same MVP-first, phased-development thinking behind most of the custom projects we build — start with what proves the concept, then build the parts that are actually worth owning.</p>

<h2>Making the Call</h2>
<p>If you're not sure which side of this you're on, that's a reasonable thing to talk through rather than guess at. A short conversation about your current tools, where they're breaking down, and what you're trying to build next is usually enough to see which path actually makes sense.</p>
HTML
        );

        $this->upsert(
            slug: 'how-to-choose-a-custom-software-development-company',
            title: 'How to Choose a Custom Software Development Company',
            excerpt: "Portfolio, process, ownership, support — here's what actually separates a development partner worth hiring from one that'll cost you a rebuild in two years.",
            metaDescription: 'A practical checklist for evaluating custom software development companies: portfolio fit, process, IP ownership, support, and the red flags to watch for.',
            focusKeyword: 'how to choose a custom software development company',
            author: $author,
            content: <<<'HTML'
<p>Choosing a development partner is a decision you'll live with for years, not months — the wrong choice usually shows up as a rebuild eighteen months later. Here's what's actually worth checking before you sign anything.</p>

<h2>1. Portfolio and Case Studies — Ask for Specifics, Not Logos</h2>
<p>A logo wall proves a company had a client, not that they did good work. Ask for real case studies: what was the problem, what did they build, and what changed as a result. Ideally, look for a project in your industry or a similar technical shape (SaaS, marketplace, internal tool) to your own. Our own <a href="/case-studies">case studies</a> page shows real projects with the actual challenge and outcome, not just a screenshot.</p>

<h2>2. Their Development Process</h2>
<p>Ask directly: how do you run a project? A credible answer covers a discovery phase before any code is written, a sprint cadence (typically 2 weeks) with working demos rather than a single "big reveal" at the end, and a clear point of contact who owns communication with you. If the answer is vague, that's usually a sign the process itself is vague.</p>

<h2>3. Technical Fit, Not Just Technical Skill</h2>
<p>A team can be technically excellent and still be the wrong fit if their expertise doesn't match your project — a team specialized in marketing websites isn't necessarily the right choice for a compliance-heavy fintech platform. Ask what they've built that's structurally similar to what you need, not just "are you good developers."</p>

<h2>4. Engagement Model Options</h2>
<p>A company that only offers one engagement model is fitting your project into their preferred structure, not the other way around. Look for flexibility across fixed-price (defined scope), dedicated team (ongoing product work), and time-and-materials (evolving scope) — and ask them to explain which one fits your situation and why.</p>

<h2>5. IP Ownership and NDAs</h2>
<p>This should be a non-negotiable, not a negotiation. Source code, documentation and any custom IP built for your project should belong to you as standard practice, confirmed in writing before work begins. A company that hesitates on IP ownership or won't sign an NDA before a detailed discussion is a real red flag.</p>

<h2>6. Post-Launch Support</h2>
<p>Launch is rarely the end of the work — bugs surface, performance needs tuning, and feature requests keep coming. Ask what happens after go-live: is there a support window included, and what do ongoing maintenance arrangements look like? A company with no answer here is planning to disappear after delivery.</p>

<h2>7. Talk to a Reference</h2>
<p>If a company has real, satisfied clients, they should be willing to connect you with at least one — even briefly. A flat refusal, versus a company that's happy to arrange it, tells you something real about how their past projects actually went.</p>

<h2>Red Flags Worth Walking Away From</h2>
<ul>
<li>No discovery process — they quote a fixed price before understanding your requirements.</li>
<li>Vague or absent contract terms around IP ownership and deliverables.</li>
<li>No mention of post-launch support until you ask.</li>
<li>Pressure to sign quickly, before you've had time to evaluate them properly.</li>
<li>Communication that's unclear or inconsistent even during the sales process — it rarely improves once the contract is signed.</li>
</ul>

<h2>Putting It Together</h2>
<p>None of these checks take long individually, but together they separate a partner who'll still be reliable eighteen months in from one who won't. If you're currently evaluating options, feel free to run this exact checklist against us — <a href="/about-us">read about how we work</a>, look through our <a href="/case-studies">case studies</a>, and <a href="/contact">reach out</a> with the questions above.</p>
HTML
        );
    }

    private function upsert(string $slug, string $title, string $excerpt, string $metaDescription, string $focusKeyword, ?User $author, string $content): void
    {
        $post = Blog::firstOrNew(['slug' => $slug]);

        $post->fill([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'meta_title' => $title,
            'meta_description' => $metaDescription,
            'focus_keyword' => $focusKeyword,
            'author_id' => $author?->id,
            'status' => 'published',
            'allow_comments' => true,
            'published_at' => $post->published_at ?? now(),
            'reading_time' => (int) ceil(str_word_count(strip_tags($content)) / 200),
        ]);

        $post->save();
    }
}
