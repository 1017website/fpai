<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\BrochurePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = BrochurePage::query()->orderBy('position')->get();
        return view('cms.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $nextPosition = min(255, BrochurePage::query()->count() + 1);

        return view('cms.pages.create', compact('nextPosition'));
    }

    public function store(Request $request): RedirectResponse
    {
        $maxPosition = min(255, BrochurePage::query()->count() + 1);
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('brochure_pages')],
            'position' => ['required', 'integer', 'min:1', 'max:'.$maxPosition],
            'theme' => ['required', Rule::in(['dark', 'light'])],
            'alt_text' => ['required', 'string', 'max:255'],
            'navigation_label' => ['nullable', 'string', 'max:80'],
            'image' => ['required', 'image', 'mimes:webp,jpg,jpeg,png', 'max:20480'],
        ]);

        $nextPageNumber = ((int) BrochurePage::query()->max('page_number')) + 1;
        abort_if($nextPageNumber > 255, 422, 'Jumlah halaman sudah mencapai batas maksimum.');

        $storedPath = $request->file('image')->store('brochure-pages', 'public');
        unset($data['image']);

        try {
            DB::transaction(function () use ($data, $request, $nextPageNumber, $storedPath) {
                BrochurePage::query()->where('position', '>=', $data['position'])->increment('position');

                BrochurePage::query()->create([
                    ...$data,
                    'page_number' => $nextPageNumber,
                    'image_path' => 'storage/'.$storedPath,
                    'show_in_navigation' => $request->boolean('show_in_navigation'),
                    'is_active' => $request->boolean('is_active'),
                ]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($storedPath);
            throw $exception;
        }

        return redirect()->route('cms.pages.index')->with('success', 'Halaman frontend baru berhasil ditambahkan.');
    }

    public function edit(BrochurePage $page): View
    {
        return view('cms.pages.edit', compact('page'));
    }

    public function update(Request $request, BrochurePage $page): RedirectResponse
    {
        $maxPosition = max(1, BrochurePage::query()->count());
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('brochure_pages')->ignore($page)],
            'position' => ['required', 'integer', 'min:1', 'max:'.$maxPosition],
            'theme' => ['required', Rule::in(['dark', 'light'])],
            'alt_text' => ['required', 'string', 'max:255'],
            'navigation_label' => ['nullable', 'string', 'max:80'],
            'image' => ['nullable', 'image', 'mimes:webp,jpg,jpeg,png', 'max:20480'],
        ]);

        $data['show_in_navigation'] = $request->boolean('show_in_navigation');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $oldPath = $page->image_path;
            $data['image_path'] = 'storage/'.$request->file('image')->store('brochure-pages', 'public');
            if (str_starts_with($oldPath, 'storage/brochure-pages/')) {
                Storage::disk('public')->delete(substr($oldPath, 8));
            }
        }

        unset($data['image']);

        DB::transaction(function () use ($page, $data) {
            $oldPosition = $page->position;
            $newPosition = (int) $data['position'];

            if ($newPosition < $oldPosition) {
                BrochurePage::query()->where('id', '!=', $page->id)
                    ->whereBetween('position', [$newPosition, $oldPosition - 1])->increment('position');
            } elseif ($newPosition > $oldPosition) {
                BrochurePage::query()->where('id', '!=', $page->id)
                    ->whereBetween('position', [$oldPosition + 1, $newPosition])->decrement('position');
            }

            $page->update($data);
        });

        return redirect()->route('cms.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }
}
