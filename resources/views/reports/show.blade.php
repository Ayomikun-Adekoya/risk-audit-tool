@extends('layouts.app')

@section('content')
<section class="main-content fade-in container">
    <!-- Header -->
    <div class="welcome-section mb-10">
        <div class="welcome-content">
            <h1 class="text-4xl font-bold mb-2">📊 Security Report Summary</h1>
            <p class="text-lg opacity-90">Detailed insights for <span class="font-semibold">{{ $report->target_url }}</span></p>
            <p class="mt-2 text-sm opacity-80">
                Completed on <strong>{{ $report->completed_at ? $report->completed_at->format('M d, Y H:i') : 'N/A' }}</strong>
            </p>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="stat-card mb-10 text-left">
        <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            <span class="text-primary">📋</span> General Summary
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <p class="text-gray-500 text-sm">Scan ID</p>
                <p class="font-semibold text-lg text-primary">#{{ $report->id }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Depth</p>
                <p class="font-semibold text-lg">{{ ucfirst($report->scan_depth) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Risk Score</p>
                <p class="font-semibold text-lg text-{{ $report->risk_score >= 70 ? 'danger' : ($report->risk_score >= 40 ? 'warning' : 'success') }}">
                    {{ $report->risk_score ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Status</p>
                <p class="font-semibold text-lg">{{ ucfirst($report->status) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">HTTPS Enabled</p>
                <p class="font-semibold text-lg">
                    {{ is_null($report->uses_https) ? 'Unknown' : ($report->uses_https ? '✅ Yes' : '❌ No') }}
                </p>
            </div>
        </div>
    </div>

    <!-- OWASP Top 10 -->
    <div class="action-card mb-10 text-left">
        <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            <span class="text-accent">🛡️</span> OWASP Top 10 Analysis
        </h2>
        <div class="space-y-4">
            @foreach ($owaspResults as $code => $result)
                <div class="p-4 border rounded-lg shadow-sm transition hover:shadow-md {{ ($result['status'] ?? '') === 'Passed' ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50' }}">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg">{{ $code }} — {{ $result['title'] }}</h3>
                        <span class="text-sm px-3 py-1 rounded-full {{ ($result['status'] ?? '') === 'Passed' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ $result['status'] ?? 'Unknown' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 mt-2"><strong>Recommendation:</strong> {{ $result['recommendation'] ?? '—' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Raw Findings -->
    @if(!empty($rawResults['raw_results']))
        <div class="action-card mb-10 text-left">
            <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
                <span class="text-warning">🧩</span> Raw Findings
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border border-gray-200 text-left">Title / Category</th>
                            <th class="px-4 py-2 border border-gray-200 text-left">Description</th>
                            <th class="px-4 py-2 border border-gray-200 text-left">Evidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawResults['raw_results'] as $finding)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border border-gray-200 font-semibold text-primary">
                                    {{ $finding['title'] ?? $finding['category'] }}
                                </td>
                                <td class="px-4 py-2 border border-gray-200 text-gray-700">
                                    {{ $finding['description'] ?? '—' }}
                                </td>
                                <td class="px-4 py-2 border border-gray-200 text-gray-500 text-xs">
                                    {{ !empty($finding['evidence']) ? json_encode($finding['evidence']) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Buttons -->
    <div class="flex gap-4 mt-10 justify-center">
        <a href="{{ route('reports.download', $report->id) }}" class="btn btn-primary shadow-md">
            ⬇️ Download PDF
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            ⬅️ Back to Reports
        </a>
    </div>
</section>
@endsection
