<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\VisitorEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $days = in_array((int) $request->input('days', 30), [7, 30, 90], true)
            ? (int) $request->input('days', 30)
            : 30;
        $since = now()->subDays($days - 1)->startOfDay();
        $events = VisitorEvent::query()->where('visited_at', '>=', $since)->get();
        $pageviews = $events->where('event_type', 'pageview');

        $daily = collect(range(0, $days - 1))->mapWithKeys(function (int $offset) use ($since, $pageviews) {
            $date = $since->copy()->addDays($offset)->toDateString();
            return [$date => $pageviews->filter(fn ($event) => $event->visited_at->toDateString() === $date)->count()];
        });

        $topSections = $events->where('event_type', 'section')
            ->groupBy('section_slug')->map->count()->sortDesc()->take(10);

        $topReferrers = $pageviews->map(function ($event) {
            if (! $event->referrer) return 'Langsung';
            return parse_url($event->referrer, PHP_URL_HOST) ?: 'Lainnya';
        })->countBy()->sortDesc()->take(8);

        $browsers = $pageviews->map(fn ($event) => $this->browser($event->user_agent ?? ''))
            ->countBy()->sortDesc();

        $stats = [
            'views' => $pageviews->count(),
            'visitors' => $pageviews->pluck('session_id')->filter()->unique()->count(),
            'today' => $pageviews->filter(fn ($event) => $event->visited_at->isToday())->count(),
            'avg' => round($pageviews->count() / max($days, 1), 1),
        ];

        return view('cms.analytics.index', compact(
            'days', 'daily', 'topSections', 'topReferrers', 'browsers', 'stats'
        ));
    }

    private function browser(string $agent): string
    {
        return match (true) {
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Lainnya',
        };
    }
}
