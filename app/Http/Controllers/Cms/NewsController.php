<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $articles = NewsArticle::query()->latest('published_at')->latest()->paginate(15);

        return view('cms.news.index', compact('articles'));
    }

    public function create(): View
    {
        return view('cms.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['show_in_popup'] = $data['is_published'] && $request->boolean('show_in_popup');
        $data['published_at'] ??= $data['is_published'] ? now() : null;

        if ($request->hasFile('image')) {
            $data['image_path'] = 'storage/'.$request->file('image')->store('news', 'public');
        }
        unset($data['image']);

        DB::transaction(function () use ($data) {
            if ($data['show_in_popup']) {
                NewsArticle::query()->update(['show_in_popup' => false]);
            }
            NewsArticle::query()->create($data);
        });

        return redirect()->route('cms.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(NewsArticle $article): View
    {
        return view('cms.news.edit', compact('article'));
    }

    public function update(Request $request, NewsArticle $article): RedirectResponse
    {
        $oldImagePath = $article->image_path;
        $data = $this->validated($request, $article);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $article);
        $data['is_published'] = $request->boolean('is_published');
        $data['show_in_popup'] = $data['is_published'] && $request->boolean('show_in_popup');
        $data['published_at'] ??= $data['is_published'] ? now() : null;

        $newStoredPath = null;
        if ($request->hasFile('image')) {
            $newStoredPath = $request->file('image')->store('news', 'public');
            $data['image_path'] = 'storage/'.$newStoredPath;
        }
        unset($data['image']);

        try {
            DB::transaction(function () use ($article, $data) {
                if ($data['show_in_popup']) {
                    NewsArticle::query()->whereKeyNot($article->getKey())->update(['show_in_popup' => false]);
                }
                $article->update($data);
            });
        } catch (\Throwable $exception) {
            if ($newStoredPath) {
                Storage::disk('public')->delete($newStoredPath);
            }
            throw $exception;
        }

        if ($newStoredPath && str_starts_with((string) $oldImagePath, 'storage/news/')) {
            Storage::disk('public')->delete(substr($oldImagePath, 8));
        }

        return redirect()->route('cms.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(NewsArticle $article): RedirectResponse
    {
        $imagePath = $article->image_path;
        $article->delete();

        if (str_starts_with((string) $imagePath, 'storage/news/')) {
            Storage::disk('public')->delete(substr($imagePath, 8));
        }

        return redirect()->route('cms.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request, ?NewsArticle $article = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('news_articles')->ignore($article)],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'image' => [$article ? 'nullable' : 'required', 'image', 'mimes:webp,jpg,jpeg,png', 'max:10240'],
        ]);
    }

    private function uniqueSlug(string $source, ?NewsArticle $article = null): string
    {
        $base = Str::slug($source) ?: 'berita';
        $slug = $base;
        $suffix = 2;

        while (NewsArticle::query()
            ->when($article, fn ($query) => $query->whereKeyNot($article->getKey()))
            ->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
