<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SEO PHASE 26 — Country Content.
 *
 * The brief lists 13 candidate topics across USA/UK/Germany/Europe. Most
 * duplicate content that already exists and is already strong:
 *   - "Software development cost in USA/UK" — the market pages' own FAQ
 *     sections already answer this; a separate article would be a thinner
 *     rehash of the general pricing post (Phase 22).
 *   - "How to hire/choose a software development company in USA/UK" — this
 *     is the exact topic of how-to-choose-a-custom-software-development-
 *     company (Phase 25), just with a country label stapled on. Same
 *     checklist, no new value — textbook thin/duplicate content.
 *   - "Software development companies in Germany" / "Software outsourcing
 *     UK" / "Custom software development for European businesses" — these
 *     are literally what the germany/uk/europe market pages themselves
 *     already target as their primary keyword.
 *   - "Industry 4.0 software development" / "Software modernization for
 *     German manufacturing" — the Germany market page already has a
 *     substantial Mittelstand/Industry 4.0 section; a separate post would
 *     duplicate it rather than add anything.
 *
 * "Only publish country-specific content that provides genuine value" is
 * the brief's own instruction — that rules out ~11 of the 13 topics here.
 * The two that are genuinely new, not covered anywhere else on the site,
 * and reflect real search intent:
 *   - Offshore vs Nearshore Software Development (a real decision US/UK
 *     buyers face, honestly written from Kawach's actual position as an
 *     offshore India-based team — not invented positioning)
 *   - GDPR Considerations for Software Development (factual regulatory
 *     content relevant to UK/EU clients, nothing invented or claimed as
 *     certified)
 */
class CountryContentBlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::find(1) ?? User::first();

        $this->upsert(
            slug: 'offshore-vs-nearshore-software-development',
            title: 'Offshore vs Nearshore Software Development',
            excerpt: 'Offshore and nearshore both mean outsourcing — the real difference is time zone overlap, cost structure and talent pool. Here\'s how to actually decide.',
            metaDescription: 'Offshore vs nearshore software development compared honestly: cost, time zone overlap and talent pool — plus how to make either model actually work.',
            focusKeyword: 'offshore vs nearshore software development',
            author: $author,
            content: <<<'HTML'
<p>Both offshore and nearshore mean working with a development team outside your own country — the real difference is time zone distance, and that difference shapes almost everything else: cost, communication style, and how real-time your collaboration can be.</p>

<h2>What Each Term Actually Means</h2>
<ul>
<li><strong>Offshore</strong> typically means a team in a distant time zone — a US or UK company working with a team in India or elsewhere in Asia, often 8-13+ hours apart.</li>
<li><strong>Nearshore</strong> means a team in a similar or overlapping time zone — a US company working with a Latin American team, or a UK company working with an Eastern European team, often just 1-4 hours apart.</li>
</ul>

<h2>The Real Trade-Offs</h2>
<h3>Cost</h3>
<p>Offshore engagements generally offer more cost-effective rates, reflecting genuine differences in cost of living and market rates between regions — not a difference in work quality. Nearshore sits between offshore and fully local rates.</p>

<h3>Time Zone Overlap</h3>
<p>This is the real trade-off. Nearshore gives you several hours of real-time overlap for daily standups and quick back-and-forth. Offshore overlap is narrower — often just a couple of hours — which means more of the collaboration has to happen asynchronously: written updates, recorded demos, and a clear plan for what needs a live conversation versus what doesn't.</p>

<h3>Talent Pool</h3>
<p>Going offshore, particularly to India, opens access to a very large, mature software engineering talent pool — genuinely useful when you need specific expertise or need to scale a team without a multi-month local hiring process.</p>

<h3>Communication Style</h3>
<p>Nearshore's tighter overlap suits teams that want frequent real-time syncs. Offshore works well when the engagement is structured around it: scheduled overlap-window meetings for anything that needs live discussion, daily written updates, and a dedicated point of contact who's reachable throughout your working day even outside the overlap window.</p>

<h2>Making Offshore Actually Work</h2>
<p>We're an offshore team ourselves — based in India, working with clients across the <a href="/markets/usa/software-development">USA</a>, <a href="/markets/uk/software-development">UK</a> and <a href="/markets/europe/software-development">Europe</a> — so this isn't theoretical for us. What makes it work in practice:</p>
<ul>
<li>A recurring meeting scheduled in the actual overlap window (typically early morning for US/UK clients, evening for us) for sprint planning, demos and anything that needs real-time discussion.</li>
<li>Written daily updates so you always know what happened and what's next, without needing to be online at the same time we are.</li>
<li>A single named point of contact who owns communication with you, rather than routing through whoever happens to be available.</li>
<li>A shared project board so status is visible any time, not just during syncs.</li>
</ul>

<h2>Which Model Fits You?</h2>
<p>If your team needs frequent real-time collaboration and a few hours of overlap makes a meaningful difference to how you work, nearshore may fit better. If you're comfortable with structured async communication in exchange for a larger talent pool and more cost-effective rates, offshore is a genuinely strong option — which is exactly the model behind our own <a href="/services/custom-software-development">custom software development</a> engagements.</p>
HTML
        );

        $this->upsert(
            slug: 'gdpr-considerations-for-software-development',
            title: 'GDPR Considerations for Software Development',
            excerpt: 'GDPR isn\'t just a privacy policy checkbox — it shapes real architecture decisions in any software that touches EU or UK user data. Here\'s what actually matters.',
            metaDescription: 'What GDPR actually means for custom software: data minimization, consent, the right to erasure and security by design — practical, not just legal theory.',
            focusKeyword: 'gdpr considerations for software development',
            author: $author,
            content: <<<'HTML'
<p>GDPR (the EU's General Data Protection Regulation) is often treated as a legal/privacy-policy problem, but for software that actually handles personal data, it shapes real architecture and product decisions — not just a document your legal team signs off on.</p>

<p><strong>This is general technical guidance, not legal advice</strong> — GDPR compliance ultimately depends on your specific data flows and business context, and should be confirmed with your legal counsel.</p>

<h2>What GDPR Actually Asks of Software</h2>
<ul>
<li><strong>Data minimization.</strong> Collect only the personal data your feature genuinely needs, not everything that might be useful someday. This is a design decision made at the schema and form-field level, not an afterthought.</li>
<li><strong>Lawful basis and consent.</strong> Where consent is the basis for processing, it needs to be freely given, specific, and as easy to withdraw as it was to give — which has real UI implications, not just a checkbox buried in terms and conditions.</li>
<li><strong>Right to access and erasure.</strong> Users can request their data or request deletion. Software needs an actual mechanism to locate and act on a specific user's data across every table and system it touches — much harder to retrofit than to design in from the start.</li>
<li><strong>Data portability.</strong> Users can request their data in a usable, transferable format — which affects how you structure exports.</li>
<li><strong>Data residency and transfers.</strong> Where data is stored and processed matters, particularly when using cloud infrastructure or third-party services outside the EU/UK.</li>
<li><strong>Security by design.</strong> Encryption at rest and in transit, role-based access controls, and audit logging aren't optional extras for software touching EU personal data — they're part of complying with GDPR's security requirements.</li>
<li><strong>Data processing agreements.</strong> Any third-party service that processes personal data on your behalf (analytics, email, hosting) needs a data processing agreement in place — which affects which vendors and integrations make sense.</li>
</ul>

<h2>Why This Matters at the Architecture Stage</h2>
<p>Most of these requirements are far cheaper to design in from the start than to retrofit later. A user-deletion flow is straightforward if the data model was built with it in mind; it's a genuinely difficult, error-prone project if personal data is scattered informally across a system that was never designed to locate or remove it on request.</p>

<h2>What to Ask Your Development Partner</h2>
<ul>
<li>How do you approach data minimization during the design phase, not just at launch?</li>
<li>Is encryption at rest and in transit standard, or an add-on?</li>
<li>Can the system actually locate and delete a specific user's data on request?</li>
<li>What's your approach to data processing agreements with third-party services you integrate?</li>
</ul>
<p>We build with these considerations in mind for clients across the <a href="/markets/uk/software-development">UK</a> and <a href="/markets/europe/software-development">Europe</a> as standard practice on <a href="/services/custom-software-development">custom software development</a> projects — encryption, access controls and audit logging aren't something we bolt on afterward. If your project has specific compliance requirements beyond this, we work directly with your legal or compliance team to confirm what applies.</p>
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
