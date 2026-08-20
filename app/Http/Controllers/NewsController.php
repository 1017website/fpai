<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\SiteSetting;
use App\Models\VisitorEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $articles = NewsArticle::query()->published()->latest('published_at')->paginate(9);
        $settings = SiteSetting::values();
        $this->record($request);

        return view('frontend.news.index', compact('articles', 'settings'));
    }

    public function show(Request $request, NewsArticle $article): View
    {
        abort_unless(
            $article->is_published && $article->published_at && $article->published_at->isPast(),
            404
        );

        $settings = SiteSetting::values();
        $otherArticles = NewsArticle::query()->published()
            ->whereKeyNot($article->getKey())
            ->latest('published_at')
            ->limit(3)
            ->get();
        $this->record($request);

        return view('frontend.news.show', compact('article', 'otherArticles', 'settings'));
    }

    private function record(Request $request): void
    {
        VisitorEvent::query()->create([
            'event_type' => 'pageview',
            'session_id' => $request->session()->getId(),
            'ip_hash' => hash('sha256', (string) $request->ip().'|'.config('app.key')),
            'path' => '/'.$request->path(),
            'referrer' => $request->headers->get('referer'),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);
    }
}
