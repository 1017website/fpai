<?php

namespace App\Http\Controllers;

use App\Models\BrochurePage;
use App\Models\NewsArticle;
use App\Models\SiteSetting;
use App\Models\VisitorEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(Request $request): View
    {
        $pages = BrochurePage::query()->where('is_active', true)->orderBy('position')->get();
        $settings = SiteSetting::values();
        $popupArticle = NewsArticle::query()->published()
            ->where('show_in_popup', true)
            ->latest('published_at')
            ->first();

        $this->record($request, 'pageview');

        return view('frontend.home', compact('pages', 'settings', 'popupArticle'));
    }

    public function section(Request $request): JsonResponse
    {
        $data = $request->validate(['section_slug' => ['required', 'string', 'max:255']]);
        $this->record($request, 'section', $data['section_slug']);

        return response()->json(['ok' => true]);
    }

    private function record(Request $request, string $type, ?string $section = null): void
    {
        VisitorEvent::query()->create([
            'event_type' => $type,
            'session_id' => $request->session()->getId(),
            'ip_hash' => hash('sha256', (string) $request->ip().'|'.config('app.key')),
            'path' => $request->path() === '/' ? '/' : '/'.$request->path(),
            'section_slug' => $section,
            'referrer' => $request->headers->get('referer'),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);
    }
}
