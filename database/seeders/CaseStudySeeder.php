<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageCaseStudy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CaseStudySeeder extends Seeder
{
    // 5 fully hand-written case studies. Featured/OG images are intentionally
    // left null — the client is adding real project imagery separately, and
    // Page::getFeaturedImageUrlAttribute() / the front-end views already
    // handle a missing featured_image gracefully (@if($page->featured_image)).
    public function run(): void
    {
        foreach ($this->caseStudies() as $data) {
            $page = Page::where('slug', $data['slug'])->first();

            // Only mint fresh random 2025-2026 timestamps the first time this
            // case study is seeded — re-running the seeder must not shuffle
            // dates on records that already exist.
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
                'category_id'        => 2, // "Case study" category
                'author_id'          => $data['author_id'],
                'published_at'       => $createdAt,
                // Branded cover graphic — lives in public/avatars/{slug}.png
                // (not the usual page_images/case_study upload folder; this
                // batch was generated and placed there directly).
                'featured_image'     => 'avatars/' . $data['slug'] . '.png',
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
                'og_image'           => 'avatars/' . $data['slug'] . '.png',
                'twitter_card'       => 'summary_large_image',
                'hreflang'           => 'en',
                'sitemap_priority'   => 0.8,
                'sitemap_changefreq' => 'monthly',
                'custom_head_script' => null,
                'tags'               => $data['tags'],
            ]);

            // Full control over timestamps — bypass Eloquent's auto-touch so
            // the random 2025/2026 dates actually stick.
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

        $this->command->info('✅ 5 case studies seeded (Page + PageCaseStudy).');
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
            // 1. EDUCATION — School ERP
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'bright-horizons-school-group-erp-case-study',
                'title'            => 'How Bright Horizons School Group Digitized Admissions, Attendance, and Fees Across 3 Campuses',
                'is_featured'      => true,
                'sort_order'       => 1,
                'author_id'        => 1,
                'client_name'      => 'Bright Horizons School Group',
                'client_industry'  => 'Education',
                'business_size'    => '3 campuses, 4,200+ students',
                'location'         => 'Bengaluru, India',
                'business_model'   => 'Private K-12 Education Group',
                'project_duration' => '7 months',
                'completion_date'  => 'March 2026',
                'focus_keyword'    => 'school ERP software development',
                'meta_title'       => 'School ERP Case Study — Bright Horizons School Group | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a unified school ERP for Bright Horizons, cutting admission time by 70% and giving 3 campuses one system for attendance, fees, and grades.',
                'tags'             => 'school ERP, education technology, student management system, custom software development',

                'challenge' => <<<'HTML'
<p>Bright Horizons School Group runs three campuses across Bengaluru, but until early last year, those three campuses operated like three completely separate businesses. Each one kept its own paper admission files, its own Excel sheets for attendance, and its own fee ledger — and none of it talked to each other. When the group's leadership asked a simple question like "how many seats are open across all campuses this term?", getting an answer meant phone calls to three different front-office coordinators and a few hours of manually reconciling spreadsheets.</p>
<p>The pain was sharpest during admissions season. Every application arrived as a physical form, was manually entered into a campus-specific spreadsheet, and then cross-checked by hand against available seats — a process that stretched to nearly three weeks per intake cycle. Attendance wasn't much better: 140+ teachers marked paper registers every morning, which admin staff then re-typed into Excel at the end of each day, introducing errors and a 24-hour lag before anyone could act on an attendance concern.</p>
<p>Parents felt the disconnect most directly. There was no way to check a child's attendance, see term grades, or confirm a fee payment without calling the school office directly — and during peak admission or exam season, those calls could mean a 15-20 minute hold. Report cards, meanwhile, were compiled manually in Word documents by teachers, consuming two to three full working days every term that could have gone toward actual teaching.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Admissions relied on physical forms and manual data entry, taking up to 3 weeks per intake cycle.'],
                    ['text' => 'Attendance was marked on paper registers by 140+ teachers, then re-typed into Excel by admin staff — a process prone to errors and delays.'],
                    ['text' => 'Fee collection and reconciliation were tracked in separate spreadsheets per campus, making group-level financial reporting nearly impossible.'],
                    ['text' => "Parents had no way to check a child's attendance, grades, or fee dues without calling the school office."],
                    ['text' => 'Report cards were compiled manually in Word documents, taking teachers 2-3 full days per term.'],
                ],

                'solution' => <<<'HTML'
<p>We started by spending three weeks physically embedded across all three campuses — sitting with front-office staff during live admissions, watching teachers mark attendance, and following a fee payment from collection to reconciliation. That groundwork mattered more than any line of code: it surfaced edge cases (sibling discounts, mid-term transfers between campuses, partial fee waivers) that a requirements document alone would have missed.</p>
<p>The result was a single, unified school ERP built around six core modules: online admissions with automated seat allocation, RFID-based attendance capture that syncs in real time, structured fee management with an integrated payment gateway, a digital gradebook that generates report cards automatically, a parent-facing mobile portal, and a group-wide dashboard for school leadership.</p>
<p>Rather than launching everything at once, we shipped in priority order — admissions first, timed to be ready ahead of the next intake cycle, followed by attendance and fees, and finally the parent portal. We piloted the complete system on the flagship campus for six weeks, running it in parallel with the old paper process, before rolling it out group-wide. That parallel-run caught a handful of edge cases in fee proration that would have been painful to fix after a full launch.</p>
<p>Training was deliberately hands-on and small-batch: rather than one big rollout session, we trained front-office staff and teachers at each campus in small groups, backed by a dedicated support hotline for the first month. That investment paid off — the system carried the school group through its first full admission and exam cycle without a single reported data-loss incident.</p>
HTML,

                'goals' => [
                    ['title' => 'Centralize Operations', 'desc' => 'Bring admissions, attendance, fees, and academics onto one platform accessible across all 3 campuses.', 'icon' => 'fas fa-building', 'color' => '#1a73e8'],
                    ['title' => 'Reduce Administrative Load', 'desc' => 'Automate repetitive manual work so front-office and teaching staff could focus on students, not paperwork.', 'icon' => 'fas fa-clock', 'color' => '#00c896'],
                    ['title' => 'Improve Parent Communication', 'desc' => 'Give parents real-time, self-service visibility into attendance, grades, and fee status.', 'icon' => 'fas fa-comments', 'color' => '#ffb830'],
                    ['title' => 'Enable Data-Driven Decisions', 'desc' => 'Provide school leadership with group-wide dashboards for enrollment, attendance trends, and revenue.', 'icon' => 'fas fa-chart-line', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Admissions & Enrollment', 'desc' => 'Online application forms, document upload, seat allocation, and automated waitlist management.', 'icon' => 'fas fa-user-graduate'],
                    ['name' => 'Attendance Management', 'desc' => 'RFID card-based attendance capture at classroom entry points, synced instantly to the central system.', 'icon' => 'fas fa-clipboard-check'],
                    ['name' => 'Fee Management', 'desc' => 'Structured fee plans per grade, online payment gateway integration, and automated reminders for overdue payments.', 'icon' => 'fas fa-file-invoice-dollar'],
                    ['name' => 'Gradebook & Report Cards', 'desc' => 'Digital markbook for teachers with auto-generated, brandable report cards exported as PDF.', 'icon' => 'fas fa-award'],
                    ['name' => 'Parent Portal & App', 'desc' => 'A mobile-friendly portal where parents view attendance, grades, timetables, and pay fees online.', 'icon' => 'fas fa-mobile-alt'],
                    ['name' => 'Admin Dashboard', 'desc' => 'Group-level dashboards comparing enrollment, attendance, and revenue across all 3 campuses.', 'icon' => 'fas fa-chart-pie'],
                ],

                'kpis' => [
                    ['label' => 'Admission Processing Time', 'value' => '-70%'],
                    ['label' => 'Fee Collection Turnaround', 'value' => '-45%'],
                    ['label' => 'Teacher Admin Hours Saved / Week', 'value' => '6 hrs'],
                    ['label' => 'Parent Portal Adoption', 'value' => '92%'],
                ],

                'technologies' => 'Laravel, Vue.js, MySQL, AWS, Razorpay, RFID Integration',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, PHP 8.2, MySQL 8'],
                    ['category' => 'Frontend', 'items' => 'Vue.js 3, Tailwind CSS, Chart.js'],
                    ['category' => 'Infrastructure', 'items' => 'AWS EC2, S3, CloudFront, RDS'],
                    ['category' => 'Integrations', 'items' => 'Razorpay Payment Gateway, RFID Hardware SDK, Twilio SMS'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Discovery & Process Mapping', 'desc' => 'Spent 3 weeks on-site across all 3 campuses observing existing admissions, attendance, and fee workflows to map every edge case before writing a line of code.'],
                    ['badge' => '02', 'title' => 'UI/UX Design', 'desc' => 'Designed low-clutter interfaces for non-technical front-office staff and teachers, validated through 2 rounds of usability testing with real school staff.'],
                    ['badge' => '03', 'title' => 'Phased Development', 'desc' => 'Built and shipped modules in priority order — admissions first, ahead of the new academic year, then attendance and fees, then the parent portal.'],
                    ['badge' => '04', 'title' => 'Pilot on One Campus', 'desc' => 'Ran the system in parallel with the old paper process on the flagship campus for 6 weeks to catch issues before a full rollout.'],
                    ['badge' => '05', 'title' => 'Group-Wide Rollout & Training', 'desc' => 'Trained 140+ teachers and front-office staff across all campuses in small batches, backed by a dedicated support hotline during the first month.'],
                    ['badge' => '06', 'title' => 'Post-Launch Support', 'desc' => 'Provided 3 months of on-call support to carry the school group through its first full admission and exam cycle on the new system.'],
                ],

                'achievements' => [
                    ['title' => 'Zero-Downtime Exam Season', 'desc' => 'The gradebook and report card module handled the first full exam cycle for 4,200+ students without a single reported data-loss incident.'],
                    ['title' => 'Full Group Rollout in 7 Months', 'desc' => "All 3 campuses were live on the unified system before the start of the new academic year, meeting the client's hard deadline."],
                    ['title' => '92% Parent App Adoption', 'desc' => 'Nearly all parents actively use the portal within the first term, dramatically cutting phone calls to the front office.'],
                ],

                'before_after' => [
                    ['before' => '3-week manual admissions cycle with physical forms', 'after' => 'Fully online admissions completed in 4-5 days'],
                    ['before' => 'Attendance re-typed manually from paper registers', 'after' => 'Real-time RFID attendance synced instantly campus-wide'],
                    ['before' => 'Fee reconciliation done separately per campus in Excel', 'after' => 'Single group-wide fee dashboard updated in real time'],
                    ['before' => 'Parents called the office for attendance/fee updates', 'after' => 'Parents self-serve everything through the portal/app'],
                ],

                'compliance_items' => [
                    ['title' => 'Student Data Privacy', 'desc' => "Role-based access controls ensure only authorized staff can view a student's personal and academic records.", 'icon' => 'fas fa-user-shield'],
                    ['title' => 'Payment Data Security', 'desc' => 'All fee payments are processed through a PCI-DSS compliant gateway — no card data ever touches our servers.', 'icon' => 'fas fa-lock'],
                    ['title' => 'Data Backups', 'desc' => 'Automated daily encrypted backups with a 30-day retention window protect against accidental data loss.', 'icon' => 'fas fa-database'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-user-graduate', 'title' => 'Online Admissions', 'desc' => 'Digital application forms with document upload and automated seat allocation.'],
                    ['icon' => 'fas fa-clipboard-check', 'title' => 'RFID Attendance', 'desc' => 'Tap-in attendance capture synced instantly to the central database.'],
                    ['icon' => 'fas fa-file-invoice-dollar', 'title' => 'Online Fee Payments', 'desc' => 'Parents pay fees online with automatic receipt generation and reminders.'],
                    ['icon' => 'fas fa-mobile-alt', 'title' => 'Parent Mobile Portal', 'desc' => 'Real-time visibility into attendance, grades, and dues from any device.'],
                    ['icon' => 'fas fa-chart-pie', 'title' => 'Management Dashboards', 'desc' => 'Group-wide reporting across all campuses for school leadership.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How long did the full rollout take?', 'answer' => 'The complete project — from discovery through group-wide rollout across all 3 campuses — took 7 months, timed to be ready before the new academic year began.'],
                    ['question' => 'Did the school need to replace its existing hardware?', 'answer' => 'No. We integrated with RFID readers already installed at two campuses and supplied compatible readers for the third, avoiding unnecessary hardware costs.'],
                    ['question' => 'How was staff training handled?', 'answer' => 'We ran small-batch, hands-on training sessions for front-office staff and teachers at each campus, backed by a dedicated support hotline for the first month after go-live.'],
                    ['question' => 'Can the system scale if Bright Horizons opens new campuses?', 'answer' => 'Yes — the platform is built to onboard additional campuses without any architectural changes, just new campus records and user accounts.'],
                ],

                'testimonial_quote' => "Before Kawach built our ERP, running three campuses meant three different spreadsheets, three different headaches every fee season. Now our principals see everything — enrollment, attendance, collections — on one screen. Our front-office staff finally have time to actually help parents instead of drowning in paperwork.",
                'testimonial_name' => 'Meenakshi Iyer',
                'testimonial_role' => 'Group Director of Operations, Bright Horizons School Group',
            ],

            // ══════════════════════════════════════════════════════════════
            // 2. HEALTHCARE — Telemedicine / Patient Management
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'medcare-health-network-telemedicine-case-study',
                'title'            => 'Building a Patient Management & Telemedicine Platform for MedCare Health Network',
                'is_featured'      => false,
                'sort_order'       => 2,
                'author_id'        => 2,
                'client_name'      => 'MedCare Health Network',
                'client_industry'  => 'Healthcare',
                'business_size'    => '12 clinics, 60+ doctors',
                'location'         => 'Mumbai, India',
                'business_model'   => 'Multi-Specialty Clinic Chain',
                'project_duration' => '9 months',
                'completion_date'  => 'January 2026',
                'focus_keyword'    => 'healthcare software development',
                'meta_title'       => 'Telemedicine Platform Case Study — MedCare Health Network | Kawach Technology',
                'meta_description' => 'How Kawach Technology unified 12 clinics onto one patient record platform and launched video consultations for MedCare Health Network.',
                'tags'             => 'telemedicine platform, patient management system, healthcare software, HL7 integration',

                'challenge' => <<<'HTML'
<p>MedCare Health Network operates 12 clinics across Mumbai, but for years each clinic ran on a different legacy scheduling tool — some on old desktop software, others on nothing more than a diary and a phone line. The consequence was that patient history simply didn't travel with the patient. If someone visited clinic A for a check-up and then clinic C for a follow-up, the doctor at clinic C had no idea what had already been prescribed or tested.</p>
<p>Booking an appointment meant calling in, and during peak hours patients routinely sat on hold for over 15 minutes. There was no option at all for a remote consultation — every follow-up, even a simple "how are the new medications working," required an in-person visit, which frustrated patients and quietly turned away demand MedCare could have served. Prescription and lab report history lived on paper at whichever clinic issued it, invisible to any doctor at another location.</p>
<p>Perhaps the most telling symptom of the problem: patients filled out the same intake form by hand, every single visit, regardless of how many times they'd already been seen within the MedCare network. It was a system built around individual clinics, not around the patient — and MedCare's leadership knew that had to change before it started costing them patients to competitors offering a more modern experience.</p>
HTML,

                'existing_challenges' => [
                    ['text' => "Each of the 12 clinics ran a different legacy scheduling tool, so patient records didn't follow patients between locations."],
                    ['text' => 'Appointment booking was phone-only, with patients often on hold for 15+ minutes during peak hours.'],
                    ['text' => 'Doctors had no way to consult patients remotely, turning away demand for follow-ups that didn\'t need an in-person visit.'],
                    ['text' => 'Prescription and lab report history was kept on paper at the clinic where treatment happened, invisible to doctors elsewhere.'],
                    ['text' => 'Patients filled out the same intake form by hand at every single visit, regardless of clinic.'],
                ],

                'solution' => <<<'HTML'
<p>We began by shadowing front-desk staff and doctors across four representative clinics — a mix of high-volume and smaller locations — to understand how scheduling and record-keeping actually happened day to day, not just how MedCare's leadership assumed it happened. That discovery phase directly shaped the data migration plan: consolidating 12 separate, inconsistent datasets into one unified patient record schema without losing a single patient's history.</p>
<p>The platform we built centers on one unified patient record accessible securely across all 12 clinics, backed by an online appointment engine, a browser-based WebRTC video consultation module, digital e-prescriptions, a lab report portal, and consolidated billing that handles both in-person and video visits. We built the patient record and appointment systems first, since every other module depended on them being solid.</p>
<p>Video consultation was the feature MedCare was most nervous about, so we piloted it with just three doctors over four weeks before opening it network-wide — watching closely for call quality issues and gathering doctor feedback on the interface. Once that pilot proved stable, we migrated the remaining 12 clinics onto the new platform in small batches over eight weeks, deliberately keeping any single clinic's disruption to a minimum rather than attempting a single "big bang" cutover.</p>
<p>In-person training for front-desk and clinical staff at each clinic, paired with two months of dedicated post-launch support, meant the platform was fully embedded into daily operations well before MedCare's seasonal patient volume peak — a deadline that mattered because a rocky rollout right before peak season could have been genuinely damaging.</p>
HTML,

                'goals' => [
                    ['title' => 'Unify Patient Records', 'desc' => "Give every doctor in the network secure access to a patient's full history regardless of which clinic they visit.", 'icon' => 'fas fa-file-medical', 'color' => '#1a73e8'],
                    ['title' => 'Enable Remote Consultations', 'desc' => "Launch a secure video consultation feature so doctors can see follow-up patients without requiring an in-clinic visit.", 'icon' => 'fas fa-video', 'color' => '#00c896'],
                    ['title' => 'Cut Appointment Wait Times', 'desc' => 'Replace phone-only booking with an online scheduling system patients can use anytime.', 'icon' => 'fas fa-calendar-check', 'color' => '#ffb830'],
                    ['title' => 'Improve Clinical Decision-Making', 'desc' => "Surface a patient's prescription and lab history to the treating doctor at the point of care.", 'icon' => 'fas fa-stethoscope', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Unified Patient Records', 'desc' => 'A single patient profile shared across all 12 clinics, covering visit history, prescriptions, and lab results.', 'icon' => 'fas fa-file-medical-alt'],
                    ['name' => 'Online Appointment Booking', 'desc' => 'Patients book, reschedule, or cancel appointments by doctor, specialty, or clinic location in under a minute.', 'icon' => 'fas fa-calendar-alt'],
                    ['name' => 'Video Consultation', 'desc' => 'Encrypted, browser-based video calls between doctor and patient with no app download required.', 'icon' => 'fas fa-video'],
                    ['name' => 'E-Prescriptions', 'desc' => 'Doctors issue digital prescriptions patients can view, download, and share directly with pharmacies.', 'icon' => 'fas fa-prescription'],
                    ['name' => 'Lab Report Portal', 'desc' => "Lab results are uploaded directly to the patient's profile, viewable by both the patient and any treating doctor.", 'icon' => 'fas fa-vial'],
                    ['name' => 'Billing & Insurance', 'desc' => 'Consolidated billing across in-person and video visits, with support for insurance claim documentation.', 'icon' => 'fas fa-file-invoice'],
                ],

                'kpis' => [
                    ['label' => 'Avg. Appointment Booking Time', 'value' => '< 60 sec'],
                    ['label' => 'Phone Hold Time Reduction', 'value' => '-80%'],
                    ['label' => 'Monthly Video Consultations', 'value' => '1,900+'],
                    ['label' => 'Doctor Time Saved on Record Lookup', 'value' => '~20 min/day'],
                ],

                'technologies' => 'Laravel, React, PostgreSQL, WebRTC, AWS, Twilio',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, PHP 8.2, PostgreSQL'],
                    ['category' => 'Frontend', 'items' => 'React 18, Redux, Tailwind CSS'],
                    ['category' => 'Real-Time / Video', 'items' => 'WebRTC, Twilio Video API'],
                    ['category' => 'Infrastructure', 'items' => 'AWS EC2, RDS, S3 (encrypted at rest), CloudFront'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Clinical Workflow Discovery', 'desc' => 'Shadowed front-desk staff and doctors across 4 representative clinics to understand real scheduling and record-keeping workflows.'],
                    ['badge' => '02', 'title' => 'Data Migration Planning', 'desc' => 'Designed a phased migration plan to consolidate 12 separate legacy datasets into one unified patient record schema without losing historical data.'],
                    ['badge' => '03', 'title' => 'Core Platform Build', 'desc' => 'Developed the unified patient record system and appointment engine first, since every other module depended on them.'],
                    ['badge' => '04', 'title' => 'Video Consultation Rollout', 'desc' => 'Added the WebRTC-based video module and ran a 4-week pilot with 3 doctors before opening it network-wide.'],
                    ['badge' => '05', 'title' => 'Clinic-by-Clinic Go-Live', 'desc' => "Migrated clinics onto the new platform in small batches over 8 weeks to keep any single clinic's disruption to a minimum."],
                    ['badge' => '06', 'title' => 'Staff Training & Support', 'desc' => 'Delivered in-person training for front-desk and clinical staff at each clinic, followed by 2 months of dedicated post-launch support.'],
                ],

                'achievements' => [
                    ['title' => '12 Clinics Unified Without Data Loss', 'desc' => 'Successfully migrated 12 separate legacy systems into one unified patient record platform with zero reported record mismatches.'],
                    ['title' => 'Video Consultations Adopted Within Weeks', 'desc' => 'Video consultation volume crossed 1,900 sessions/month within the first two months of network-wide rollout.'],
                    ['title' => 'Delivered Ahead of Peak Season', 'desc' => "The platform went fully live one month before MedCare's seasonal patient volume peak, avoiding the usual booking bottleneck."],
                ],

                'before_after' => [
                    ['before' => 'Patient records isolated per clinic on paper/legacy tools', 'after' => 'One unified patient profile accessible network-wide'],
                    ['before' => 'Phone-only booking with 15+ minute hold times', 'after' => 'Self-service online booking in under a minute'],
                    ['before' => 'No option for remote follow-up consultations', 'after' => '1,900+ video consultations completed monthly'],
                    ['before' => 'Patients re-filled intake forms at every visit', 'after' => 'Intake history auto-populates from prior visits'],
                ],

                'compliance_items' => [
                    ['title' => 'Patient Data Encryption', 'desc' => 'All patient health records and video sessions are encrypted both in transit and at rest.', 'icon' => 'fas fa-shield-alt'],
                    ['title' => 'Role-Based Clinical Access', 'desc' => "Access to a patient's records is restricted to doctors and staff directly involved in that patient's care.", 'icon' => 'fas fa-user-md'],
                    ['title' => 'Audit Logging', 'desc' => 'Every access to a patient record is logged for accountability and internal compliance review.', 'icon' => 'fas fa-clipboard-list'],
                    ['title' => 'Secure Video Infrastructure', 'desc' => 'Video consultations run over encrypted WebRTC channels with no session recordings stored by default.', 'icon' => 'fas fa-video-slash'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-file-medical-alt', 'title' => 'Unified Patient Records', 'desc' => 'One patient profile shared securely across all 12 clinic locations.'],
                    ['icon' => 'fas fa-video', 'title' => 'Video Consultations', 'desc' => 'Browser-based, encrypted video visits with no app download needed.'],
                    ['icon' => 'fas fa-calendar-check', 'title' => 'Online Appointment Booking', 'desc' => 'Book, reschedule, or cancel appointments in under a minute.'],
                    ['icon' => 'fas fa-prescription', 'title' => 'E-Prescriptions', 'desc' => 'Digital prescriptions patients can access and share instantly.'],
                    ['icon' => 'fas fa-vial', 'title' => 'Digital Lab Reports', 'desc' => "Lab results delivered straight to the patient's profile."],
                ],

                'cs_faqs' => [
                    ['question' => 'How was patient data migrated without disruption?', 'answer' => 'We migrated clinics in small batches over 8 weeks, running the old and new systems in parallel at each clinic briefly to verify data accuracy before fully switching over.'],
                    ['question' => 'Is the video consultation feature secure?', 'answer' => 'Yes — video sessions run over encrypted WebRTC connections via the Twilio Video API, and no session is recorded or stored by default.'],
                    ['question' => 'Did doctors need special equipment for video consultations?', 'answer' => 'No. The video module runs entirely in the browser, so doctors and patients only need a device with a camera and a stable internet connection.'],
                    ['question' => 'How does the platform handle patients without smartphones?', 'answer' => "Front-desk staff can book, manage, and check in appointments on a patient's behalf, so the platform doesn't require patients to have their own device to benefit from it."],
                ],

                'testimonial_quote' => "We used to lose valuable consultation time just trying to piece together a patient's history from whichever clinic they last visited. Now every doctor in our network sees the same complete picture the moment a patient walks in — or logs into a video call. It's changed how quickly we can actually treat people.",
                'testimonial_name' => 'Dr. Rajiv Menon',
                'testimonial_role' => 'Chief Medical Officer, MedCare Health Network',
            ],

            // ══════════════════════════════════════════════════════════════
            // 3. RETAIL / E-COMMERCE — AI Recommendations + Scale
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'urban-threads-apparel-ecommerce-ai-case-study',
                'title'            => "Scaling Urban Threads Apparel's Online Store for 5x Festive Traffic with AI-Powered Recommendations",
                'is_featured'      => true,
                'sort_order'       => 3,
                'author_id'        => 1,
                'client_name'      => 'Urban Threads Apparel',
                'client_industry'  => 'Retail & E-commerce',
                'business_size'    => '₹15 Cr+ annual online revenue',
                'location'         => 'Delhi NCR, India',
                'business_model'   => 'Direct-to-Consumer (D2C) Fashion Retail',
                'project_duration' => '5 months',
                'completion_date'  => 'November 2025',
                'focus_keyword'    => 'ecommerce platform development',
                'meta_title'       => 'E-Commerce AI Case Study — Urban Threads Apparel | Kawach Technology',
                'meta_description' => 'How Kawach Technology rebuilt Urban Threads\' online store to survive 5x festive traffic and lift conversion 42% with AI-powered product recommendations.',
                'tags'             => 'ecommerce development, AI product recommendations, D2C platform, retail technology',

                'challenge' => <<<'HTML'
<p>Urban Threads Apparel had built a genuinely loyal D2C fashion customer base, but their online store had a problem that only showed up at the worst possible moment: every major flash sale or festive campaign — precisely the events driving the most revenue of the year — the site slowed to a crawl or crashed outright. Customers gave up and left, and the team could only watch it happen in real time.</p>
<p>Beyond the crashes, the storefront treated every visitor identically. A first-time browser and a five-time repeat customer saw the exact same homepage and product listings, with no attempt to surface what either of them was actually likely to buy. Inventory counts were only updated manually every few hours, which meant overselling during high-traffic sales — a customer service nightmare that meant refunding orders the warehouse simply couldn't fulfill.</p>
<p>Cart abandonment sat above 78%, with no automated way to bring those customers back. And because 85% of Urban Threads' traffic came from mobile, the storefront's average mobile page load time of over 6 seconds was quietly costing sales on every single visit, not just during sale events.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'The storefront repeatedly slowed down or crashed during flash sales and festive campaigns — the exact moments driving the most revenue.'],
                    ['text' => 'Every customer saw the same generic homepage and product listings, regardless of browsing or purchase history.'],
                    ['text' => 'Inventory counts were updated manually every few hours, leading to overselling during high-traffic sales events.'],
                    ['text' => 'Cart abandonment sat above 78%, with no automated way to re-engage customers who left without checking out.'],
                    ['text' => 'Average mobile page load time — where 85% of traffic came from — was over 6 seconds.'],
                ],

                'solution' => <<<'HTML'
<p>We opened with a performance audit, running load tests against the existing storefront to pin down exactly which parts of the stack buckled under pressure — it turned out to be a mix of an undersized database connection pool and no caching layer at all in front of product pages. That diagnosis shaped everything that followed: we re-architected the platform around auto-scaling cloud infrastructure and a Redis caching layer built specifically to absorb sudden traffic spikes.</p>
<p>For personalization, we trained a recommendation model on two years of Urban Threads' own historical browsing and purchase data — deliberately avoiding third-party data brokers — before deploying it live. Real-time inventory sync and automated abandoned-cart email/SMS sequences shipped first, since they delivered value immediately; AI-driven recommendations followed once the core platform had proven stable.</p>
<p>Before trusting any of it with a live festive campaign, we simulated 5x Urban Threads' expected peak traffic in a staging environment and tuned auto-scaling rules until the platform absorbed that simulated load without a single error. Image optimization and CDN delivery were layered in specifically to address the mobile load-time problem, since that's where the overwhelming majority of visitors actually were.</p>
<p>We stayed on real-time monitoring and on-call support throughout Urban Threads' biggest sales campaign of the year — the actual moment of truth the whole rebuild had been aimed at.</p>
HTML,

                'goals' => [
                    ['title' => 'Survive Peak Traffic', 'desc' => 'Rebuild the platform to reliably handle 5x normal traffic during festive sales without downtime.', 'icon' => 'fas fa-bolt', 'color' => '#1a73e8'],
                    ['title' => 'Personalize the Shopping Experience', 'desc' => 'Show each customer product recommendations based on their own browsing and purchase behavior.', 'icon' => 'fas fa-magic', 'color' => '#00c896'],
                    ['title' => 'Fix Inventory Accuracy', 'desc' => 'Sync inventory across the storefront in real time to eliminate overselling.', 'icon' => 'fas fa-boxes', 'color' => '#ffb830'],
                    ['title' => 'Recover Abandoned Carts', 'desc' => 'Automatically re-engage customers who leave without completing checkout.', 'icon' => 'fas fa-shopping-cart', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'AI Recommendation Engine', 'desc' => 'A machine-learning model that ranks product recommendations per visitor using browsing history, purchase patterns, and real-time trending items.', 'icon' => 'fas fa-brain'],
                    ['name' => 'Real-Time Inventory Sync', 'desc' => 'Inventory updates propagate to the storefront within seconds of a sale, across all sales channels.', 'icon' => 'fas fa-boxes'],
                    ['name' => 'Abandoned Cart Automation', 'desc' => 'Automated email and SMS sequences triggered when a cart is left inactive, with time-limited discount nudges.', 'icon' => 'fas fa-envelope-open-text'],
                    ['name' => 'Auto-Scaling Storefront', 'desc' => 'Cloud infrastructure that automatically scales server capacity up during traffic spikes and back down afterward.', 'icon' => 'fas fa-server'],
                    ['name' => 'Image & CDN Optimization', 'desc' => 'Automatic image compression and global CDN delivery to cut mobile page load times.', 'icon' => 'fas fa-tachometer-alt'],
                ],

                'kpis' => [
                    ['label' => 'Mobile Page Load Time', 'value' => '1.8s (from 6s+)'],
                    ['label' => 'Festive Season Uptime', 'value' => '99.98%'],
                    ['label' => 'Cart Abandonment', 'value' => '-31%'],
                    ['label' => 'Conversion Rate', 'value' => '+42%'],
                ],

                'technologies' => 'Laravel, React, Redis, Elasticsearch, AWS, Python (ML)',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, PHP 8.2, MySQL, Redis'],
                    ['category' => 'Frontend', 'items' => 'React 18, Next.js, Tailwind CSS'],
                    ['category' => 'Search & Recommendations', 'items' => 'Elasticsearch, Python, scikit-learn'],
                    ['category' => 'Infrastructure', 'items' => 'AWS Auto Scaling, CloudFront CDN, S3'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Performance Audit', 'desc' => 'Ran load tests against the existing storefront to identify the exact bottlenecks that caused crashes during past sales events.'],
                    ['badge' => '02', 'title' => 'Architecture Redesign', 'desc' => 'Re-architected the platform around auto-scaling infrastructure and a caching layer to absorb sudden traffic spikes.'],
                    ['badge' => '03', 'title' => 'Recommendation Model Training', 'desc' => 'Trained the recommendation model on 2 years of historical browsing and purchase data before deploying it live.'],
                    ['badge' => '04', 'title' => 'Phased Feature Rollout', 'desc' => 'Shipped inventory sync and cart automation first, then layered in AI recommendations once the core platform was stable.'],
                    ['badge' => '05', 'title' => 'Load Testing at Scale', 'desc' => 'Simulated 5x expected festive traffic in a staging environment before the live campaign to validate auto-scaling behavior.'],
                    ['badge' => '06', 'title' => 'Live Festive Season Monitoring', 'desc' => "Provided real-time monitoring and on-call support throughout the client's biggest sales campaign of the year."],
                ],

                'achievements' => [
                    ['title' => 'Zero Downtime During Peak Sale', 'desc' => 'The platform handled 5x normal traffic during the festive campaign with 99.98% uptime and no checkout failures.'],
                    ['title' => '42% Conversion Rate Increase', 'desc' => 'AI-driven product recommendations lifted overall conversion rate by 42% within the first full quarter after launch.'],
                    ['title' => 'Mobile Load Time Cut by Over 70%', 'desc' => 'Image optimization and CDN delivery brought mobile page load time down from over 6 seconds to under 2.'],
                ],

                'before_after' => [
                    ['before' => 'Storefront crashed during high-traffic sales events', 'after' => '99.98% uptime during 5x festive traffic'],
                    ['before' => 'Same generic homepage shown to every visitor', 'after' => 'AI-personalized recommendations per visitor'],
                    ['before' => 'Manual inventory updates every few hours', 'after' => 'Real-time inventory sync across all channels'],
                    ['before' => '78%+ cart abandonment with no follow-up', 'after' => '31% reduction in cart abandonment via automation'],
                ],

                'compliance_items' => [
                    ['title' => 'Payment Data Security', 'desc' => "Checkout is processed through PCI-DSS compliant payment gateways — no raw card data is stored on Urban Threads' servers.", 'icon' => 'fas fa-credit-card'],
                    ['title' => 'Customer Data Protection', 'desc' => 'Personal and browsing data used for recommendations is encrypted and access-controlled per internal data policy.', 'icon' => 'fas fa-user-shield'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-brain', 'title' => 'AI Product Recommendations', 'desc' => 'Personalized product suggestions based on real browsing and purchase behavior.'],
                    ['icon' => 'fas fa-bolt', 'title' => 'Auto-Scaling Infrastructure', 'desc' => 'Automatically handles traffic spikes during flash sales without downtime.'],
                    ['icon' => 'fas fa-boxes', 'title' => 'Real-Time Inventory Sync', 'desc' => 'Prevents overselling by updating stock instantly across channels.'],
                    ['icon' => 'fas fa-envelope-open-text', 'title' => 'Abandoned Cart Recovery', 'desc' => 'Automated email/SMS sequences that bring shoppers back to checkout.'],
                    ['icon' => 'fas fa-tachometer-alt', 'title' => 'Optimized Mobile Performance', 'desc' => 'Sub-2-second load times even on average mobile connections.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How did you prepare the platform for festive-season traffic specifically?', 'answer' => "We simulated 5x the client's expected peak traffic in a staging environment weeks before the actual sale, tuning auto-scaling rules until the platform handled the simulated load with zero errors."],
                    ['question' => 'How does the AI recommendation engine work without being invasive?', 'answer' => "It only uses first-party behavioral data — pages viewed, items purchased, items added to cart — collected directly on Urban Threads' own store, with no third-party data brokers involved."],
                    ['question' => "Did the redesign require Urban Threads to change their existing product catalog structure?", 'answer' => "No. We built the new platform to migrate the existing product catalog as-is, so the merchandising team didn't need to re-enter or restructure any product data."],
                ],

                'testimonial_quote' => "Every festive season used to be a nail-biter — we knew a big sale meant a good chance the site would slow to a crawl right when we needed it most. This time, traffic was five times normal and the site didn't even blink. And the personalized recommendations alone paid for the project within the first quarter.",
                'testimonial_name' => 'Ananya Kapoor',
                'testimonial_role' => 'Head of E-Commerce, Urban Threads Apparel',
            ],

            // ══════════════════════════════════════════════════════════════
            // 4. FINTECH — Automated Loan Underwriting
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'quickfund-financial-services-lending-platform-case-study',
                'title'            => 'How QuickFund Financial Services Automated Loan Underwriting and Cut Approval Time from Days to Minutes',
                'is_featured'      => false,
                'sort_order'       => 4,
                'author_id'        => 2,
                'client_name'      => 'QuickFund Financial Services',
                'client_industry'  => 'FinTech / NBFC Lending',
                'business_size'    => 'Mid-size NBFC, ₹200 Cr+ loan book',
                'location'         => 'Pune, India',
                'business_model'   => 'Digital Lending / NBFC',
                'project_duration' => '10 months',
                'completion_date'  => 'June 2026',
                'focus_keyword'    => 'loan management system development',
                'meta_title'       => 'FinTech Lending Case Study — QuickFund Financial Services | Kawach Technology',
                'meta_description' => 'How Kawach Technology built an automated loan origination and credit scoring system for QuickFund, cutting approval time from days to minutes.',
                'tags'             => 'fintech software development, loan origination system, digital lending platform, RBI compliance',

                'challenge' => <<<'HTML'
<p>QuickFund Financial Services had grown its loan book past ₹200 crore largely on the strength of its underwriting team's judgment — but that same manual process had become the ceiling on how much further the business could grow. Loan applications were reviewed by hand, and even straightforward cases took five to seven business days to get a decision, mostly because underwriters were manually cross-referencing bureau reports, income documents, and bank statements one application at a time.</p>
<p>KYC verification relied on physically collected documents, which meant delays before an application could even enter the review queue. Worse, credit risk assessment varied meaningfully between underwriters — there was no standardized, data-driven scoring model, just individual judgment shaped by each underwriter's own experience. That inconsistency was a real risk, not just an efficiency problem.</p>
<p>Leadership had no real-time view into the health of the loan book. NPAs, disbursement trends, and collections were compiled by hand into monthly reports, meaning problems could go unnoticed for weeks. And every quarter, the finance team spent days manually assembling RBI-mandated regulatory reports from a patchwork of spreadsheets — time that could have gone toward actually managing risk instead of documenting it after the fact.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Loan applications were reviewed manually by underwriters, taking 5-7 business days per decision even for straightforward cases.'],
                    ['text' => 'KYC verification relied on physically collected documents, creating bottlenecks and a poor applicant experience.'],
                    ['text' => 'Credit risk assessment varied significantly between underwriters, since there was no standardized, data-driven scoring model.'],
                    ['text' => 'Leadership had no real-time dashboard of loan book health — NPAs, disbursement trends, and collections were compiled manually every month.'],
                    ['text' => "RBI-mandated regulatory reports were assembled by hand from multiple spreadsheets every quarter, consuming days of the finance team's time."],
                ],

                'solution' => <<<'HTML'
<p>Because this was a regulated lending business, we started with QuickFund's risk and compliance teams, not the engineering backlog — working through RBI's digital lending guidelines together before designing a single workflow. That sequencing mattered: the credit scoring model we eventually built came directly from translating QuickFund's own underwriters' existing manual assessment logic into a structured, data-driven scoring system, rather than importing a generic off-the-shelf model.</p>
<p>The platform integrates Aadhaar and PAN verification APIs so identity checks that used to take days now complete in minutes, alongside a hybrid rules-based and machine-learning credit scoring engine, e-signature for loan agreements, a real-time portfolio dashboard, and automated RBI regulatory reporting generated directly from live loan book data.</p>
<p>We didn't let the automated scoring engine make a single live decision until it had earned that trust: for six weeks, it ran in parallel with manual underwriting, and we compared every automated recommendation against what an experienced underwriter actually decided. Only once that comparison showed consistent accuracy did QuickFund's compliance team sign off on a phased go-live — starting with smaller loan tickets and expanding to larger ones only after the system built a track record.</p>
<p>The result respects a principle QuickFund was firm about from day one: automation should handle the clear-cut cases, and route anything ambiguous to a human underwriter rather than force a decision either way.</p>
HTML,

                'goals' => [
                    ['title' => 'Automate Underwriting', 'desc' => 'Replace manual document review with an automated, data-driven credit decisioning engine.', 'icon' => 'fas fa-robot', 'color' => '#1a73e8'],
                    ['title' => 'Speed Up KYC', 'desc' => 'Verify applicant identity digitally in minutes instead of days.', 'icon' => 'fas fa-id-card', 'color' => '#00c896'],
                    ['title' => 'Standardize Risk Scoring', 'desc' => 'Apply a consistent, auditable credit scoring model to every application.', 'icon' => 'fas fa-balance-scale', 'color' => '#ffb830'],
                    ['title' => 'Give Leadership Real-Time Visibility', 'desc' => 'Replace manual monthly reporting with a live portfolio health dashboard.', 'icon' => 'fas fa-chart-line', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'Digital Loan Origination', 'desc' => 'End-to-end online application flow from form submission through disbursement, replacing the paper-based process.', 'icon' => 'fas fa-file-signature'],
                    ['name' => 'Automated KYC Verification', 'desc' => 'Integrated Aadhaar and PAN verification APIs to confirm applicant identity in minutes.', 'icon' => 'fas fa-id-card'],
                    ['name' => 'Credit Scoring Engine', 'desc' => 'A rules-based and machine-learning hybrid model that scores applications using bureau data, income signals, and repayment history.', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'E-Sign Loan Agreements', 'desc' => 'Legally compliant digital signing of loan agreements, eliminating physical paperwork and courier delays.', 'icon' => 'fas fa-pen-fancy'],
                    ['name' => 'Portfolio Dashboard', 'desc' => 'Real-time visibility into disbursements, collections, and NPA trends for leadership and the risk team.', 'icon' => 'fas fa-chart-pie'],
                    ['name' => 'Regulatory Reporting', 'desc' => 'Automated generation of RBI-mandated reports directly from live loan book data.', 'icon' => 'fas fa-file-alt'],
                ],

                'kpis' => [
                    ['label' => 'Loan Approval Time', 'value' => '5-7 days → 12 min'],
                    ['label' => 'KYC Verification Time', 'value' => '< 3 min'],
                    ['label' => 'Underwriting Team Capacity', 'value' => '+3x applications/day'],
                    ['label' => 'Regulatory Report Prep Time', 'value' => '-90%'],
                ],

                'technologies' => 'Laravel, Python, PostgreSQL, AWS, Aadhaar eKYC API, ML',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, PHP 8.2, PostgreSQL'],
                    ['category' => 'Credit Scoring', 'items' => 'Python, scikit-learn, credit bureau APIs'],
                    ['category' => 'Integrations', 'items' => 'Aadhaar eKYC API, PAN verification API, e-Sign gateway'],
                    ['category' => 'Infrastructure', 'items' => 'AWS (isolated VPC), encrypted RDS, S3'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Regulatory & Risk Discovery', 'desc' => "Worked closely with QuickFund's risk and compliance teams to understand RBI lending guidelines before designing any workflow."],
                    ['badge' => '02', 'title' => 'Credit Model Design', 'desc' => "Collaborated with QuickFund's underwriters to translate their existing manual assessment logic into a structured, data-driven scoring model."],
                    ['badge' => '03', 'title' => 'Core LOS Development', 'desc' => 'Built the loan origination workflow and KYC integrations first, since every other module depended on a verified applicant record.'],
                    ['badge' => '04', 'title' => 'Parallel-Run Validation', 'desc' => 'Ran the automated scoring engine alongside manual underwriting for 6 weeks, comparing outcomes before trusting it with live decisions.'],
                    ['badge' => '05', 'title' => 'Compliance Review & Sign-Off', 'desc' => "Had the full system, including data handling and audit logging, reviewed by QuickFund's compliance team before go-live."],
                    ['badge' => '06', 'title' => 'Phased Go-Live', 'desc' => 'Rolled out automated underwriting for smaller loan tickets first, expanding to larger ticket sizes only after building a track record of accuracy.'],
                ],

                'achievements' => [
                    ['title' => 'Approval Time Cut from Days to Minutes', 'desc' => 'Straightforward loan applications now receive a decision in about 12 minutes instead of 5-7 business days.'],
                    ['title' => '3x Underwriting Capacity Without New Hires', 'desc' => 'The existing underwriting team now handles roughly 3x the application volume by focusing only on edge cases the automated engine flags for review.'],
                    ['title' => 'Passed Compliance Review on First Submission', 'desc' => "The new regulatory reporting module passed QuickFund's internal compliance audit on its first submission, with no findings."],
                ],

                'before_after' => [
                    ['before' => '5-7 business days to approve a loan application', 'after' => '~12 minutes for automated decisions on standard applications'],
                    ['before' => 'Physical KYC document collection', 'after' => 'Digital Aadhaar/PAN verification in under 3 minutes'],
                    ['before' => 'Underwriter judgment varied case to case', 'after' => 'Standardized, auditable credit scoring for every application'],
                    ['before' => 'Manual monthly portfolio reports', 'after' => 'Real-time portfolio dashboard for leadership'],
                ],

                'compliance_items' => [
                    ['title' => 'RBI Lending Guidelines', 'desc' => "The credit decisioning and disclosure workflows were designed in direct consultation with QuickFund's compliance team to align with RBI digital lending guidelines.", 'icon' => 'fas fa-university'],
                    ['title' => 'Data Localization & Encryption', 'desc' => 'All applicant and financial data is stored on infrastructure within India, encrypted at rest and in transit.', 'icon' => 'fas fa-shield-alt'],
                    ['title' => 'Audit Trail', 'desc' => 'Every underwriting decision, automated or manually reviewed, is logged with a full audit trail for regulatory inspection.', 'icon' => 'fas fa-clipboard-list'],
                    ['title' => 'Consent-Based Data Use', 'desc' => 'Applicant data is only used for credit assessment with explicit consent captured during the application flow.', 'icon' => 'fas fa-check-double'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-robot', 'title' => 'Automated Underwriting', 'desc' => 'Data-driven credit decisions in minutes instead of days.'],
                    ['icon' => 'fas fa-id-card', 'title' => 'Digital KYC', 'desc' => 'Aadhaar and PAN verification completed in under 3 minutes.'],
                    ['icon' => 'fas fa-pen-fancy', 'title' => 'E-Sign Agreements', 'desc' => 'Legally compliant digital signing, no physical paperwork.'],
                    ['icon' => 'fas fa-chart-pie', 'title' => 'Real-Time Portfolio Dashboard', 'desc' => 'Live visibility into disbursements, collections, and NPAs.'],
                    ['icon' => 'fas fa-file-alt', 'title' => 'Automated Regulatory Reports', 'desc' => 'RBI-mandated reports generated directly from live data.'],
                ],

                'cs_faqs' => [
                    ['question' => 'How did you validate the automated credit scoring model before trusting it with real decisions?', 'answer' => "We ran the model in parallel with QuickFund's manual underwriting process for 6 weeks, comparing every automated decision against what an experienced underwriter would have decided, before allowing it to make live decisions."],
                    ['question' => 'Does the system still involve human underwriters?', 'answer' => "Yes. The automated engine handles clear-cut approvals and rejections, and routes borderline or high-value applications to human underwriters — it's designed to augment the team, not replace judgment on edge cases."],
                    ['question' => 'How is applicant data kept compliant with RBI regulations?', 'answer' => "All data handling, consent capture, and disclosure workflows were built in direct consultation with QuickFund's compliance team and reviewed against RBI's digital lending guidelines before go-live."],
                    ['question' => 'What happens if the automated system is unsure about an application?', 'answer' => "Applications that don't clearly meet or fail the scoring criteria are automatically flagged and routed to a human underwriter rather than being auto-approved or auto-rejected."],
                ],

                'testimonial_quote' => "Lending is a business of trust and speed, and we were struggling on both fronts with a manual process. Kawach didn't just automate our underwriting — they built it in a way our compliance team could actually stand behind. We went from a 5-7 day approval cycle to minutes, without cutting a single corner on due diligence.",
                'testimonial_name' => 'Vikram Deshmukh',
                'testimonial_role' => 'Chief Risk Officer, QuickFund Financial Services',
            ],

            // ══════════════════════════════════════════════════════════════
            // 5. LOGISTICS — Fleet & Shipment Tracking
            // ══════════════════════════════════════════════════════════════
            [
                'slug'             => 'swiftcargo-logistics-fleet-tracking-case-study',
                'title'            => 'Giving SwiftCargo Logistics Real-Time Fleet Visibility with a Custom Shipment Tracking Platform',
                'is_featured'      => false,
                'sort_order'       => 5,
                'author_id'        => 1,
                'client_name'      => 'SwiftCargo Logistics',
                'client_industry'  => 'Logistics & Supply Chain',
                'business_size'    => '180+ vehicle fleet, pan-India operations',
                'location'         => 'Chennai, India',
                'business_model'   => 'B2B Freight & Logistics',
                'project_duration' => '8 months',
                'completion_date'  => 'August 2025',
                'focus_keyword'    => 'logistics software development',
                'meta_title'       => 'Fleet Tracking Case Study — SwiftCargo Logistics | Kawach Technology',
                'meta_description' => 'How Kawach Technology built a real-time GPS fleet tracking and shipment platform for SwiftCargo Logistics, cutting support calls by 65%.',
                'tags'             => 'fleet management software, shipment tracking platform, logistics technology, supply chain SaaS',

                'challenge' => <<<'HTML'
<p>SwiftCargo Logistics runs a fleet of more than 180 vehicles across India, but for a company built on moving things reliably, they had remarkably little visibility into where those things actually were at any given moment. Dispatch planning happened entirely over phone calls and WhatsApp messages between the control room and individual drivers — functional, but with no central record anyone could refer back to.</p>
<p>Customers felt this gap directly: with no way to track their own shipment, they called the support team repeatedly for status updates on a single delivery, straining a support team that had no better answer than calling the driver themselves. Delivery proof — a signed receipt — was collected on paper and often didn't reach the office for days after the actual delivery, which delayed invoicing and, by extension, cash flow.</p>
<p>Route planning was left entirely to individual driver judgment, which meant fuel costs and delivery times varied inconsistently across routes that should have looked similar. And because none of this generated structured data, SwiftCargo had no way to identify recurring delays, chronically problematic routes, or underperforming vehicles — the kind of patterns that, once visible, are usually the easiest problems to fix.</p>
HTML,

                'existing_challenges' => [
                    ['text' => 'Dispatch planning was coordinated entirely over phone calls and WhatsApp between the control room and 180+ drivers, with no central record.'],
                    ['text' => 'Customers had no way to track their shipment and called support multiple times per shipment for status updates.'],
                    ['text' => 'Delivery proof (signed receipts) was collected on paper and only reached the office days after delivery, delaying invoicing.'],
                    ['text' => 'Route planning was left to individual driver judgment, leading to inconsistent fuel costs and delivery times across similar routes.'],
                    ['text' => 'There was no historical data to identify recurring delays, problem routes, or underperforming vehicles.'],
                ],

                'solution' => <<<'HTML'
<p>We spent time directly in SwiftCargo's control room and rode along with drivers on actual delivery runs before designing anything — the goal was to understand dispatch and delivery exactly as it happened, not as an org chart described it. One early, practical decision came out of that time: rather than requiring a costly fleet-wide hardware swap, we integrated directly with the GPS devices SwiftCargo already had installed in most vehicles.</p>
<p>The platform we built gives the control room live GPS tracking across the entire fleet, gives customers a self-service tracking link for their own shipments, and gives drivers a lightweight Flutter mobile app for status updates and digital proof-of-delivery capture — photo and e-signature, synced instantly rather than sitting in a driver's bag for days. A route optimization engine layers Google's traffic and directions data on top of SwiftCargo's own historical delivery times to suggest better routes.</p>
<p>We built the fleet tracking dashboard and customer tracking portal first, since dispatch visibility was the most urgent pain point, then piloted the driver app with a group of 20 drivers on a single route before expanding further. That pilot mattered — driver feedback led us to simplify the app's interface twice before it was ready for the remaining 160+ drivers, who we onboarded in batches over six weeks with in-person training at each depot.</p>
<p>The route optimization model kept improving after launch too: we spent two months tuning it against real delivery data before it consistently outperformed driver-only route judgment.</p>
HTML,

                'goals' => [
                    ['title' => 'Real-Time Shipment Visibility', 'desc' => 'Give both the control room and customers live GPS tracking for every shipment in transit.', 'icon' => 'fas fa-map-marker-alt', 'color' => '#1a73e8'],
                    ['title' => 'Reduce Support Load', 'desc' => 'Let customers self-serve shipment status instead of calling in for updates.', 'icon' => 'fas fa-headset', 'color' => '#00c896'],
                    ['title' => 'Digitize Proof of Delivery', 'desc' => 'Capture signed delivery confirmation instantly through a driver mobile app.', 'icon' => 'fas fa-signature', 'color' => '#ffb830'],
                    ['title' => 'Optimize Routes & Fuel Costs', 'desc' => 'Use data-driven route suggestions to cut fuel consumption and delivery times.', 'icon' => 'fas fa-route', 'color' => '#7c3aed'],
                ],

                'solution_modules' => [
                    ['name' => 'GPS Fleet Tracking', 'desc' => 'Live location tracking for all 180+ vehicles, visible on a control-room dashboard.', 'icon' => 'fas fa-satellite-dish'],
                    ['name' => 'Customer Tracking Portal', 'desc' => 'A public tracking link customers use to see real-time shipment status without calling support.', 'icon' => 'fas fa-search-location'],
                    ['name' => 'Driver Mobile App', 'desc' => 'A lightweight Android/iOS app for drivers to update delivery status and capture digital proof of delivery.', 'icon' => 'fas fa-mobile-alt'],
                    ['name' => 'Route Optimization', 'desc' => 'Suggests optimized delivery routes based on traffic, distance, and historical delivery time data.', 'icon' => 'fas fa-route'],
                    ['name' => 'Digital Proof of Delivery', 'desc' => 'Photo and e-signature capture at the point of delivery, synced instantly to the office for same-day invoicing.', 'icon' => 'fas fa-file-signature'],
                    ['name' => 'Analytics Dashboard', 'desc' => 'Historical reporting on delivery times, fuel usage, and vehicle performance to spot recurring problem routes.', 'icon' => 'fas fa-chart-line'],
                ],

                'kpis' => [
                    ['label' => 'Customer Support Calls', 'value' => '-65%'],
                    ['label' => 'On-Time Delivery Rate', 'value' => '+28%'],
                    ['label' => 'Invoicing Delay', 'value' => 'Days → Same-day'],
                    ['label' => 'Fuel Cost per Route', 'value' => '-14%'],
                ],

                'technologies' => 'Laravel, Flutter, PostgreSQL, Google Maps API, AWS, MQTT',

                'tech_stack' => [
                    ['category' => 'Backend', 'items' => 'Laravel 10, PHP 8.2, PostgreSQL'],
                    ['category' => 'Driver Mobile App', 'items' => 'Flutter, MQTT (real-time location updates)'],
                    ['category' => 'Mapping & Routing', 'items' => 'Google Maps API, Google Directions API'],
                    ['category' => 'Infrastructure', 'items' => 'AWS EC2, RDS, IoT Core (GPS device ingestion)'],
                ],

                'cs_process_steps' => [
                    ['badge' => '01', 'title' => 'Control Room & Driver Discovery', 'desc' => "Spent time in SwiftCargo's control room and rode along with drivers to understand real dispatch and delivery workflows."],
                    ['badge' => '02', 'title' => 'GPS Hardware Integration Planning', 'desc' => "Evaluated and integrated with SwiftCargo's existing in-vehicle GPS devices instead of requiring a costly hardware swap."],
                    ['badge' => '03', 'title' => 'Core Tracking Platform Build', 'desc' => 'Built the fleet tracking dashboard and customer tracking portal first, since dispatch visibility was the most urgent pain point.'],
                    ['badge' => '04', 'title' => 'Driver App Pilot', 'desc' => 'Piloted the driver mobile app with a group of 20 drivers on one route before rolling it out fleet-wide.'],
                    ['badge' => '05', 'title' => 'Fleet-Wide Rollout', 'desc' => 'Onboarded the remaining 160+ drivers in batches over 6 weeks, with in-person app training at each depot.'],
                    ['badge' => '06', 'title' => 'Route Optimization Tuning', 'desc' => 'Refined the route optimization model over 2 months using real delivery data to improve its suggestions.'],
                ],

                'achievements' => [
                    ['title' => '65% Fewer Support Calls Within First Quarter', 'desc' => 'Customer-initiated status check calls dropped by 65% once the self-service tracking portal launched.'],
                    ['title' => 'Same-Day Invoicing Became Possible', 'desc' => "Digital proof of delivery let SwiftCargo's finance team start invoicing the same day a shipment was delivered, instead of waiting days for paper receipts."],
                    ['title' => '28% Improvement in On-Time Delivery', 'desc' => 'Route optimization and better dispatch visibility lifted the on-time delivery rate by 28% within 4 months of full rollout.'],
                ],

                'before_after' => [
                    ['before' => 'Dispatch coordinated over phone calls and WhatsApp', 'after' => 'Centralized dispatch dashboard with live GPS tracking'],
                    ['before' => 'Customers called repeatedly for shipment status', 'after' => 'Customers self-track shipments via a live tracking link'],
                    ['before' => 'Paper delivery receipts reaching office days later', 'after' => 'Digital proof of delivery synced instantly, same-day invoicing'],
                    ['before' => 'Route planning left to individual driver judgment', 'after' => 'Data-driven route suggestions cutting fuel costs by 14%'],
                ],

                'compliance_items' => [
                    ['title' => 'Driver Data Privacy', 'desc' => 'Location tracking is limited to on-duty hours and used strictly for dispatch and delivery purposes.', 'icon' => 'fas fa-user-shield'],
                    ['title' => 'Customer Data Protection', 'desc' => "Shipment tracking links expose only shipment status to customers — no access to other customers' data or internal operational details.", 'icon' => 'fas fa-lock'],
                ],

                'cs_features' => [
                    ['icon' => 'fas fa-satellite-dish', 'title' => 'Live GPS Fleet Tracking', 'desc' => 'Real-time location visibility for the entire 180+ vehicle fleet.'],
                    ['icon' => 'fas fa-search-location', 'title' => 'Customer Tracking Portal', 'desc' => 'Self-service shipment tracking with no login required.'],
                    ['icon' => 'fas fa-file-signature', 'title' => 'Digital Proof of Delivery', 'desc' => 'Photo and e-signature capture synced instantly to the office.'],
                    ['icon' => 'fas fa-route', 'title' => 'Route Optimization', 'desc' => 'Data-driven route suggestions to cut fuel costs and delays.'],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Fleet Analytics Dashboard', 'desc' => 'Historical insights into delivery performance and vehicle usage.'],
                ],

                'cs_faqs' => [
                    ['question' => 'Did SwiftCargo need to install new GPS hardware in their vehicles?', 'answer' => 'No. We integrated directly with the GPS devices already installed across the fleet, which avoided a costly and time-consuming hardware replacement.'],
                    ['question' => 'How do customers access shipment tracking?', 'answer' => 'Each shipment gets a unique tracking link sent via SMS/email — customers open it directly in their browser with no account or app download required.'],
                    ['question' => 'How was the driver app adopted across 180+ drivers with varying smartphone comfort levels?', 'answer' => 'We piloted the app with 20 drivers first, simplified the interface based on their feedback, then rolled it out fleet-wide in batches with in-person training at each depot.'],
                    ['question' => 'Does the route optimization account for real-world traffic conditions?', 'answer' => "Yes — it factors in live traffic data from the Google Directions API alongside SwiftCargo's own historical delivery time data for each route."],
                ],

                'testimonial_quote' => "We were flying blind before this — a shipment left the depot and we genuinely didn't know where it was until the driver called in or the customer complained. Now our control room sees every vehicle in real time, customers track their own shipments, and we invoice the same day instead of waiting on paper receipts to arrive. It's completely changed how we operate.",
                'testimonial_name' => 'Karthik Subramaniam',
                'testimonial_role' => 'Head of Operations, SwiftCargo Logistics',
            ],

        ];
    }
}
