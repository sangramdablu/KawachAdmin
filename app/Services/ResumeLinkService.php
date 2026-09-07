<?php

namespace App\Services;

use App\Models\JobApplication;

/**
 * Resumes are uploaded through the public site's (Kawawch_view) Careers
 * apply form and live on that app's own disk — this admin panel has no
 * filesystem access to them. Instead of duplicating the file or building
 * a full API integration, this signs a short-lived download link that
 * Kawawch_view verifies itself (see its CareerController::downloadResume),
 * using a secret shared between both apps' .env files (JOBS_SHARED_SECRET).
 */
class ResumeLinkService
{
    private const TTL_MINUTES = 30;

    public function build(JobApplication $application): ?string
    {
        if (!$application->resume_path) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.public_site.url'), '/');
        $secret  = (string) config('services.public_site.shared_secret');

        if (!$baseUrl || !$secret) {
            return null;
        }

        $expires = now()->addMinutes(self::TTL_MINUTES)->timestamp;
        $signature = hash_hmac('sha256', $application->id . '|' . $expires, $secret);

        return "{$baseUrl}/careers/applications/{$application->id}/resume?expires={$expires}&sig={$signature}";
    }
}
