<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorPageview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VisitorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $includeBots = $request->boolean('include_bots');

        $visitorsBase = Visitor::query()->when(!$includeBots, fn ($q) => $q->where('is_bot', false));
        $pageviewsBase = VisitorPageview::query()
            ->whereHas('visitor', fn ($q) => $q->when(!$includeBots, fn ($q2) => $q2->where('is_bot', false)));

        $now = now();
        $stats = [
            'total_visitors'    => (clone $visitorsBase)->count(),
            'today'             => (clone $pageviewsBase)->whereDate('created_at', $now->toDateString())->distinct('visitor_id')->count('visitor_id'),
            'this_week'         => (clone $pageviewsBase)->where('created_at', '>=', $now->copy()->startOfWeek())->distinct('visitor_id')->count('visitor_id'),
            'this_month'        => (clone $pageviewsBase)->where('created_at', '>=', $now->copy()->startOfMonth())->distinct('visitor_id')->count('visitor_id'),
            'returning'         => (clone $visitorsBase)->where('visit_count', '>', 1)->count(),
            'avg_time_seconds'  => (int) round((clone $visitorsBase)->where('total_time_seconds', '>', 0)->avg('total_time_seconds') ?? 0),
            'bot_count'         => Visitor::where('is_bot', true)->count(),
        ];

        $topPages = (clone $pageviewsBase)
            ->select('path', DB::raw('count(*) as hits'))
            ->groupBy('path')
            ->orderByDesc('hits')
            ->limit(10)
            ->get();

        $deviceBreakdown = (clone $visitorsBase)
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        $countryTotals = (clone $visitorsBase)
            ->select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        // Uppercased ISO-2 code => count, for the world-map choropleth.
        $countryTraffic = $countryTotals
            ->mapWithKeys(fn ($row) => [strtoupper($row->country) => (int) $row->total])
            ->all();

        $topCountries = $countryTotals->take(10);

        $topSources = (clone $visitorsBase)
            ->select('utm_source', 'referrer', DB::raw('count(*) as total'))
            ->groupBy('utm_source', 'referrer')
            ->get()
            ->groupBy(fn ($v) => $this->sourceLabel($v->utm_source, $v->referrer))
            ->map(fn ($group, $label) => ['label' => $label, 'total' => $group->sum('total')])
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $visitors = (clone $visitorsBase)
            ->orderByDesc('last_seen_at')
            ->paginate(25)
            ->withQueryString();

        return view('visitors.index', [
            'stats'           => $stats,
            'topPages'        => $topPages,
            'deviceBreakdown' => $deviceBreakdown,
            'topCountries'    => $topCountries,
            'countryTraffic'  => $countryTraffic,
            'topSources'      => $topSources,
            'visitors'        => $visitors,
            'includeBots'     => $includeBots,
        ]);
    }

    public function pageviews(Visitor $visitor): JsonResponse
    {
        $pageviews = $visitor->pageviews()
            ->orderByDesc('created_at')
            ->limit(200)
            ->get()
            ->map(fn ($pv) => [
                'path'                => $pv->path,
                'route_name'          => $pv->route_name,
                'time_spent_seconds'  => $pv->time_spent_seconds,
                'visited_at'          => $pv->created_at->format('d M Y, h:i A'),
            ]);

        return response()->json([
            'success'    => true,
            'visitor_id' => $visitor->id,
            'pageviews'  => $pageviews,
        ]);
    }

    private function sourceLabel(?string $utmSource, ?string $referrer): string
    {
        if ($utmSource) {
            return ucfirst($utmSource);
        }

        if (!$referrer) {
            return 'Direct';
        }

        $host = parse_url($referrer, PHP_URL_HOST) ?: $referrer;
        return preg_replace('/^www\./', '', $host);
    }
}
