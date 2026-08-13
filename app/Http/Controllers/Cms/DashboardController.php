<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\BrochurePage;
use App\Models\User;
use App\Models\VisitorEvent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $pageviews = VisitorEvent::query()->where('event_type', 'pageview');
        $stats = [
            'today' => (clone $pageviews)->where('visited_at', '>=', today())->count(),
            'week' => (clone $pageviews)->where('visited_at', '>=', now()->subDays(7))->count(),
            'month' => (clone $pageviews)->where('visited_at', '>=', now()->subDays(30))->count(),
            'pages' => BrochurePage::query()->where('is_active', true)->count(),
            'users' => User::query()->count(),
        ];

        $recent = VisitorEvent::query()->where('event_type', 'pageview')->latest('visited_at')->limit(8)->get();

        return view('cms.dashboard', compact('stats', 'recent'));
    }
}
