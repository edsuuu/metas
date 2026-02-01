<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SocialService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    protected SocialService $socialService;

    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Reports/Index', [
            'reports' => $this->socialService->getPendingReports(),
        ]);
    }

    public function resolve(int $reportId, Request $request): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:resolved,dismissed',
            'delete_post' => 'required|boolean',
        ]);

        if ($this->socialService->resolveReport($reportId, $request->status, $request->delete_post)) {
            return back()->with('success', 'Denúncia resolvida!');
        }

        return back()->with('error', 'Erro ao resolver denúncia.');
    }
}
