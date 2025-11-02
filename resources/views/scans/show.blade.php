{{-- resources/views/scans/show.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="welcome-section fade-in text-center bg-gradient-to-r from-teal-50 via-blue-50 to-white py-10">
    <div class="welcome-content">
        <h1 class="text-3xl md:text-4xl font-bold text-teal-700">🔍 Security Scan Report</h1>
        <p class="text-gray-600 mt-2">Comprehensive analysis and recommendations for 
            <span class="text-blue-700 font-semibold">{{ $scan->target_url }}</span>
        </p>
    </div>
</section>

<!-- Scan Overview Card -->
<section class="stats-section mt-8">
    <div class="stat-card fade-in bg-white shadow-md border border-blue-100 rounded-2xl p-6">
        <div class="flex justify-between items-start mb-6 flex-wrap">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $scan->target_url }}</h2>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <span><strong>Scan ID:</strong> #{{ $scan->id }}</span>
                    <span><strong>Depth:</strong> {{ ucfirst($scan->scan_depth) }}</span>
                    <span><strong>Started:</strong>
                        @if($scan->started_at)
                            {{ \Carbon\Carbon::parse($scan->started_at)->format('M d, Y H:i') }}
                        @else
                            Not started
                        @endif
                    </span>
                </div>
            </div>
            <div class="mt-3 md:mt-0">
                <span id="scan-status" class="inline-block px-4 py-2 rounded-full font-semibold text-sm shadow-sm">
                    @switch($scan->status)
                        @case('completed')
                            <span class="bg-green-100 text-green-800">✅ Completed</span>
                            @break
                        @case('running')
                            <span class="bg-blue-100 text-blue-800">⏳ Running</span>
                            @break
                        @case('pending')
                            <span class="bg-yellow-100 text-yellow-800">🕒 Pending</span>
                            @break
                        @default
                            <span class="bg-red-100 text-red-800">❌ Failed</span>
                    @endswitch
                </span>
            </div>
        </div>
    </div>
</section>

@if($scan->status === 'completed')
    @php
        // Use the casted array directly (no json_decode)
        $results = $scan->results ?? [];
        $owaspResults = $results['owasp_results'] ?? [];
        $summary = $results['summary'] ?? [];
        $passCount = $summary['passed'] ?? 0;
        $failCount = $summary['failed'] ?? 0;
        $totalCount = $passCount + $failCount;
        $passRate = $summary['score'] ?? 0;
    @endphp

    <!-- Risk Assessment -->
    <section class="stats-section mt-8">
        <div class="stat-card fade-in bg-gradient-to-br from-white via-blue-50 to-teal-50 rounded-2xl shadow-md p-6">
            <h3 class="text-xl font-bold mb-4 text-teal-700">🧮 Risk Assessment Overview</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-6 bg-white border border-blue-100 rounded-xl shadow-sm">
                    <div class="text-4xl font-bold text-blue-700">{{ $scan->risk_score ?? 'N/A' }}</div>
                    <p class="text-sm text-gray-600 mt-1">Overall Risk Score</p>
                    @php
                        $riskLevel = $scan->risk_score >= 75 ? 'Critical' :
                                    ($scan->risk_score >= 50 ? 'High' :
                                    ($scan->risk_score >= 25 ? 'Medium' : 'Low'));
                    @endphp
                    <div class="mt-1 text-xs font-semibold text-gray-700">{{ $riskLevel }} Risk</div>
                </div>

                <div class="p-6 bg-white border border-blue-100 rounded-xl shadow-sm">
                    <div class="text-4xl font-bold text-gray-900">{{ $scan->sql_injections_detected ?? 0 }}</div>
                    <p class="text-sm text-gray-600 mt-1">SQL Injections Detected</p>
                </div>

                <div class="p-6 bg-white border border-blue-100 rounded-xl shadow-sm">
                    @if($scan->uses_https)
                        <div class="text-4xl font-bold text-green-700">✅</div>
                        <p class="text-sm text-gray-600 mt-1">HTTPS Enabled</p>
                    @else
                        <div class="text-4xl font-bold text-red-700">❌</div>
                        <p class="text-sm text-gray-600 mt-1">No HTTPS</p>
                    @endif
                </div>

                <div class="p-6 bg-white border border-blue-100 rounded-xl shadow-sm">
                    <div class="text-4xl font-bold text-gray-900">
                        @if($scan->started_at && $scan->completed_at)
                            {{ round(\Carbon\Carbon::parse($scan->started_at)->diffInMinutes(\Carbon\Carbon::parse($scan->completed_at)), 1) }}
                        @else
                            N/A
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Scan Duration (min)</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Scan Metrics -->
    <section class="stats-section mt-8">
        <div class="stat-card fade-in bg-white rounded-2xl shadow-md border border-teal-100 p-6">
            <h3 class="text-xl font-bold mb-4 text-blue-700">🧠 Technical Scan Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div><strong>Open Ports:</strong> {{ $scan->open_ports_count ?? 0 }}</div>
                <div><strong>Access Control Issues:</strong> {{ $scan->access_control_issues ?? 0 }}</div>
                <div><strong>Weak Passwords Detected:</strong> {{ $scan->weak_passwords_detected ?? 0 }}</div>
                <div><strong>Logging Enabled:</strong> {{ $scan->has_logging_enabled ? 'Yes' : 'No' }}</div>
                <div><strong>SSRF Detected:</strong> {{ $scan->ssrf_detected ? 'Yes' : 'No' }}</div>
            </div>
        </div>
    </section>

    <!-- OWASP Top 10 -->
    <section class="stats-section mt-8 mb-12">
        <div class="stat-card fade-in bg-gradient-to-br from-white via-teal-50 to-blue-50 rounded-2xl shadow-md border border-blue-100 p-6">
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                <h3 class="text-xl font-bold text-teal-800">🛡️ OWASP Top 10 Analysis</h3>
                <div class="text-sm">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full mr-2">
                        ✅ {{ $passCount }} Passed
                    </span>
                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full">
                        ❌ {{ $failCount }} Failed
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($owaspResults as $code => $result)
                    @php
                        $isPassed = $result['status'] === 'Passed';
                        $severityColors = [
                            'A01' => 'border-red-300 bg-red-50',
                            'A02' => 'border-orange-300 bg-orange-50',
                            'A03' => 'border-yellow-300 bg-yellow-50',
                            'A05' => 'border-blue-300 bg-blue-50',
                            'A09' => 'border-green-300 bg-green-50',
                        ];
                        $cardColor = $severityColors[$code] ?? 'border-gray-300 bg-gray-50';
                    @endphp

                    <div class="border-2 {{ $cardColor }} rounded-xl p-4 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-bold text-gray-700">{{ $code }}</span>
                                    <h4 class="font-semibold text-gray-900">{{ $result['title'] }}</h4>
                                </div>
                                <p class="text-sm text-gray-700 mt-2">
                                    <strong>Recommendation:</strong> {{ $result['recommendation'] }}
                                </p>
                            </div>
                            <div class="ml-4">
                                @if($isPassed)
                                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold text-sm">
                                        ✅ Passed
                                    </span>
                                @else
                                    <span class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold text-sm">
                                        ❌ Failed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Action Buttons -->
<section class="stats-section mt-10 mb-12">
    <div class="stat-card fade-in bg-white border border-blue-100 shadow-md rounded-2xl p-6 flex flex-wrap gap-4">
        @if($scan->status === 'completed')
            <a href="{{ route('reports.create', ['scan_id' => $scan->id]) }}" 
               class="btn btn-primary flex-1 md:flex-none bg-teal-600 hover:bg-teal-700 text-white shadow-md">
                📊 Generate Full Report
            </a>
            <a href="{{ route('scans.index') }}" 
               class="btn btn-accent flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white shadow-md">
                🔄 Run New Scan
            </a>
        @endif
        <a href="{{ route('scans.edit', $scan) }}" 
           class="btn btn-accent flex-1 md:flex-none bg-sky-500 hover:bg-sky-600 text-white shadow-md">
            ✏️ Edit Scan
        </a>
        <form method="POST" action="{{ route('scans.destroy', $scan) }}" 
              onsubmit="return confirm('Are you sure you want to delete this scan? This action cannot be undone.')" 
              class="flex-1 md:flex-none">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger w-full bg-red-600 hover:bg-red-700 text-white shadow-md">
                🗑️ Delete Scan
            </button>
        </form>
    </div>
</section>

<!-- Polling Script -->
<script>
    const scanId = {{ $scan->id }};
    const statusEl = document.getElementById('scan-status');

    async function pollStatus() {
        try {
            const res = await fetch(`/scans/${scanId}/status`);
            const data = await res.json();

            let statusHTML = '';
            switch (data.status) {
                case 'pending':
                    statusHTML = '<span class="bg-yellow-100 text-yellow-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">🕒 Pending</span>';
                    break;
                case 'running':
                    statusHTML = '<span class="bg-blue-100 text-blue-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">⏳ Running</span>';
                    break;
                case 'completed':
                    statusHTML = '<span class="bg-green-100 text-green-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">✅ Completed</span>';
                    location.reload();
                    break;
                case 'failed':
                    statusHTML = '<span class="bg-red-100 text-red-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">❌ Failed</span>';
                    break;
            }
            statusEl.innerHTML = statusHTML;

            if (['pending', 'running'].includes(data.status)) {
                setTimeout(pollStatus, 3000);
            }
        } catch (err) {
            console.error('Error fetching scan status:', err);
            setTimeout(pollStatus, 5000);
        }
    }

    @if(!in_array($scan->status, ['completed', 'failed']))
        pollStatus();
    @endif
</script>
@endsection
