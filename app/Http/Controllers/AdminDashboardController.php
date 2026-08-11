<?php

namespace App\Http\Controllers;

use App\Models\ContactSource;
use App\Models\ContactSubmission;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $statuses = ['pending', 'sent', 'failed', 'in_progress', 'resolved'];
        $statusCounts = ContactSubmission::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $dailyCounts = ContactSubmission::query()
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->get()
            ->groupBy(fn (ContactSubmission $submission) => $submission->created_at->toDateString())
            ->map(fn ($submissions) => $submissions->count());

        $trend = collect(range(6, 0))->map(function (int $daysAgo) use ($dailyCounts): array {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('D'),
                'total' => $dailyCounts->get($date->toDateString(), 0),
            ];
        })->all();

        $totalSubmissions = ContactSubmission::count();
        $sentSubmissions = (int) ($statusCounts['sent'] ?? 0);
        $failedSubmissions = (int) ($statusCounts['failed'] ?? 0);
        $activeSources = ContactSource::query()->where('is_active', true)->count();
        $statusCounts = collect($statuses)->mapWithKeys(fn (string $status) => [$status => (int) ($statusCounts[$status] ?? 0)]);
        $topOrigins = ContactSubmission::query()
                ->selectRaw('website_origin, count(*) as total')
                ->groupBy('website_origin')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        $recentSubmissions = ContactSubmission::query()->latest()->limit(5)->get();

        $dashboard = compact(
            'totalSubmissions',
            'sentSubmissions',
            'failedSubmissions',
            'activeSources',
            'statusCounts',
            'trend',
            'topOrigins',
            'recentSubmissions',
        );

        return view('admin.dashboard', ['dashboard' => $dashboard]);
    }
}
