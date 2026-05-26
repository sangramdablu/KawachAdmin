<?php

namespace App\Services;

/**
 * BillingCalculator
 *
 * ALL monetary arithmetic lives here — the browser sends raw inputs,
 * this service re-derives every figure. The JS on the frontend only
 * fetches calculated values from /billing/calculate and displays them.
 * Users cannot tamper with totals.
 */
class BillingCalculator
{
    public const WORK_DAYS_PER_MONTH = 22;

    /** Accepted hourly rates keyed by technology name (cents) */
    public const TECH_RATES = [
        'PHP'          => 2400,
        'Laravel'      => 2800,
        'JavaScript'   => 3000,
        'React.js'     => 3500,
        'Vue.js'       => 3200,
        'Node.js'      => 3300,
        'Python'       => 3600,
        'Django'       => 3800,
        'Java'         => 4800,
        'Spring Boot'  => 5200,
        'Kotlin'       => 4600,
        '.NET / C#'    => 4400,
        'Flutter'      => 4000,
        'React Native' => 3800,
        'Swift / iOS'  => 5000,
        'DevOps'       => 5500,
        'AWS / Cloud'  => 5800,
        'UI/UX Design' => 3000,
        'QA / Testing' => 2200,
        'Data Science' => 6000,
    ];

    public const TECH_ICONS = [
        'PHP'          => '🐘', 'Laravel'      => '🔴', 'JavaScript'   => '🟡',
        'React.js'     => '⚛️', 'Vue.js'        => '💚', 'Node.js'      => '🟢',
        'Python'       => '🐍', 'Django'        => '🎯', 'Java'         => '☕',
        'Spring Boot'  => '🍃', 'Kotlin'        => '🟣', '.NET / C#'    => '🔷',
        'Flutter'      => '📱', 'React Native'  => '📲', 'Swift / iOS'  => '🍎',
        'DevOps'       => '⚙️', 'AWS / Cloud'   => '☁️', 'UI/UX Design' => '🎨',
        'QA / Testing' => '🧪', 'Data Science'  => '📊',
    ];

    /**
     * Validate that the submitted technology is in our whitelist
     * and return its canonical rate in cents.
     *
     * @throws \InvalidArgumentException
     */
    public static function resolveRate(string $technology): int
    {
        if (!array_key_exists($technology, self::TECH_RATES)) {
            throw new \InvalidArgumentException("Unknown technology: {$technology}");
        }
        return self::TECH_RATES[$technology];
    }

    /**
     * Calculate full billing from validated inputs.
     *
     * @param array $developers  Each: ['technology' => string, 'quantity' => int, 'label' => string]
     * @param int   $dailyHours  4 | 6 | 8 | 10
     * @param int   $months      1..60
     * @param float $taxPct      0..50
     * @param float $advancePct  0..100
     */
    public static function calculate(
        array $developers,
        int   $dailyHours,
        int   $months,
        float $taxPct,
        float $advancePct
    ): array {
        $monthlyHours = self::WORK_DAYS_PER_MONTH * $dailyHours;

        $devLines       = [];
        $subtotalCents  = 0;

        foreach ($developers as $i => $dev) {
            $rateCents    = self::resolveRate($dev['technology']);
            $qty          = max(1, min(20, (int) $dev['quantity']));
            $costCents    = $rateCents * $monthlyHours * $qty;
            $subtotalCents += $costCents;

            $devLines[] = [
                'sort_order'         => $i,
                'developer_label'    => substr(trim($dev['label'] ?? ''), 0, 120) ?: ($dev['technology'] . ' Developer'),
                'technology'         => $dev['technology'],
                'tech_icon'          => self::TECH_ICONS[$dev['technology']] ?? '💻',
                'hourly_rate_cents'  => $rateCents,
                'quantity'           => $qty,
                'monthly_hours'      => $monthlyHours,
                'monthly_cost_cents' => $costCents,
            ];
        }

        $taxCents         = (int) round($subtotalCents * ($taxPct / 100));
        $monthlyTotal     = $subtotalCents + $taxCents;
        $advanceCents     = (int) round($monthlyTotal * ($advancePct / 100));
        $grandTotalCents  = $monthlyTotal * $months;

        return [
            'monthly_hours'        => $monthlyHours,
            'dev_lines'            => $devLines,
            'subtotal_cents'       => $subtotalCents,
            'tax_cents'            => $taxCents,
            'monthly_total_cents'  => $monthlyTotal,
            'advance_cents'        => $advanceCents,
            'balance_cents'        => $monthlyTotal - $advanceCents,
            'contract_pre_cents'   => $subtotalCents * $months,
            'contract_tax_cents'   => $taxCents * $months,
            'grand_total_cents'    => $grandTotalCents,
        ];
    }

    /** Format cents to currency string */
    public static function fmt(int $cents, string $symbol = '$'): string
    {
        return $symbol . number_format($cents / 100, 2);
    }
}