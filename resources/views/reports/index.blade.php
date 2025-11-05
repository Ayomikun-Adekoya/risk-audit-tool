@extends('layouts.app')

@section('title', 'Security Reports')

@section('content')
<div class="main-content container fade-in">

    <!-- Page Title -->
    <h1 class="section-title mb-8">📊 Security Reports Overview</h1>

    <!-- Summary Stats -->
    <div class="stats-grid mb-8">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-number">{{ $summary['total'] ?? 0 }}</div>
            <div class="stat-label">Total Reports</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number text-green-600">{{ $summary['passed'] ?? 0 }}</div>
            <div class="stat-label">Passed Scans</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-number text-yellow-600">{{ $summary['warnings'] ?? 0 }}</div>
            <div class="stat-label">Warnings</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number text-red-600">{{ $summary['failed'] ?? 0 }}</div>
            <div class="stat-label">Failed Scans</div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Recent Reports</h2>
            <a href="{{ route('reports.create') }}" class="btn btn-accent">
                <i class="fas fa-plus"></i> New Report
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-left">
                        <th class="px-4 py-3 font-semibold text-slate-700">#</th>
                        <th class="px-4 py-3 font-semibold text-slate-700">Target URL</th>
                        <th class="px-4 py-3 font-semibold text-slate-700">Risk Score</th>
                        <th class="px-4 py-3 font-semibold text-slate-700">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-700">Completed</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $report)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('reports.show', $report->id) }}" class="text-blue-600 hover:underline">
                                    {{ $report->target_url }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold 
                                    {{ $report->risk_score > 80 ? 'text-red-600' : 
                                       ($report->risk_score > 50 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $report->risk_score }}%
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($report->status === 'completed') bg-green-100 text-green-700
                                    @elseif($report->status === 'running') bg-yellow-100 text-yellow-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $report->completed_at ? $report->completed_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-secondary text-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('reports.download', $report->id) }}" class="btn btn-primary text-sm">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-500">
                                No reports found yet. Start a scan to generate your first report.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
