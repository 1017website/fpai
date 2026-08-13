<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ToolController extends Controller
{
    public function index(): View
    {
        return view('cms.tools.index');
    }

    public function run(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'command' => ['required', Rule::in(['migrate', 'optimize:clear', 'storage:link'])],
        ]);

        try {
            $parameters = $data['command'] === 'migrate' ? ['--force' => true] : [];
            $exitCode = Artisan::call($data['command'], $parameters);
            $output = trim(Artisan::output()) ?: 'Perintah selesai tanpa keluaran.';

            return back()->with($exitCode === 0 ? 'success' : 'error', $output);
        } catch (Throwable $exception) {
            report($exception);
            return back()->with('error', 'Perintah gagal: '.$exception->getMessage());
        }
    }
}
