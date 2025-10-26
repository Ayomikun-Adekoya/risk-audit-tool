@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="welcome-section fade-in text-center">
        <div class="welcome-content">
            <h1>🔍 Security Scan Report</h1>
            <p>Comprehensive analysis and recommendations for {{ $scan->target_url }}</p>
        </div>
    </section>

    <!-- Scan Overview Card -->
    <section class="stats-section mt-8">
        <div class="stat-card fade-in">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ $scan->target_url }}</h2>
                    <div class="flex gap-4 text-sm text-gray-600">
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
                <div>
                    <span id="scan-status" class="inline-block px-4 py-2 rounded-full font-semibold text-sm">
                        @switch($scan->status)
                            @case('completed') 
                                <span class="bg-green-100 text-green-800">✅ Completed</span>
                                @break
                            @case('running') 
                                <span class="bg-blue-100 text-blue-800">⏳ Running</span>
                                @break
                            @case('pending') 
                                <span class="bg-yellow-100 text-yellow-800">⏳ Pending</span>
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
        <!-- Risk Score Overview -->
        <section class="stats-section mt-8">
            <div class="stat-card fade-in">
                <h3 class="text-xl font-bold mb-4">Risk Assessment</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Overall Risk Score -->
                    <div class="text-center p-6 border rounded-lg">
                        <div class="text-4xl font-bold text-gray-900">
                            {{ $scan->risk_score ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Overall Risk Score</div>
                        @php
                            $riskLevel = $scan->risk_score >= 75 ? 'Critical' : 
                                        ($scan->risk_score >= 50 ? 'High' : 
                                        ($scan->risk_score >= 25 ? 'Medium' : 'Low'));
                        @endphp
                        <div class="text-xs font-semibold mt-1 text-gray-700">{{ $riskLevel }} Risk</div>
                    </div>

                    <!-- Vulnerabilities -->
                    <div class="text-center p-6 border rounded-lg">
                        <div class="text-4xl font-bold text-gray-900">
                            {{ $scan->vulnerabilities_count ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Vulnerabilities Found</div>
                    </div>

                    <!-- OWASP Pass Rate -->
                    @php
                        $owaspResults = [
                            ['code' => 'A01', 'title' => 'Broken Access Control', 'status' => 'pass', 'severity' => 'high'],
                            ['code' => 'A02', 'title' => 'Cryptographic Failures', 'status' => 'fail', 'severity' => 'critical'],
                            ['code' => 'A03', 'title' => 'Injection', 'status' => 'pass', 'severity' => 'critical'],
                            ['code' => 'A04', 'title' => 'Insecure Design', 'status' => 'pass', 'severity' => 'medium'],
                            ['code' => 'A05', 'title' => 'Security Misconfiguration', 'status' => 'fail', 'severity' => 'high'],
                            ['code' => 'A06', 'title' => 'Vulnerable Components', 'status' => 'pass', 'severity' => 'high'],
                            ['code' => 'A07', 'title' => 'Authentication Failures', 'status' => 'pass', 'severity' => 'critical'],
                            ['code' => 'A08', 'title' => 'Data Integrity Failures', 'status' => 'fail', 'severity' => 'medium'],
                            ['code' => 'A09', 'title' => 'Security Logging Failures', 'status' => 'pass', 'severity' => 'low'],
                            ['code' => 'A10', 'title' => 'Server-Side Request Forgery', 'status' => 'pass', 'severity' => 'medium'],
                        ];
                        $passCount = collect($owaspResults)->where('status', 'pass')->count();
                        $totalCount = count($owaspResults);
                        $passRate = round(($passCount / $totalCount) * 100);
                    @endphp
                    <div class="text-center p-6 border rounded-lg">
                        <div class="text-4xl font-bold text-gray-900">
                            {{ $passRate }}%
                        </div>
                        <div class="text-sm text-gray-600 mt-2">OWASP Pass Rate</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $passCount }}/{{ $totalCount }} checks passed</div>
                    </div>

                    <!-- Scan Duration -->
                    <div class="text-center p-6 border rounded-lg">
                        <div class="text-4xl font-bold text-gray-900">
                            @if($scan->started_at && $scan->completed_at)
                                {{ round(\Carbon\Carbon::parse($scan->started_at)->diffInMinutes(\Carbon\Carbon::parse($scan->completed_at)), 1) }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Scan Duration (min)</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vulnerability Breakdown -->
        <section class="stats-section mt-8">
            <div class="stat-card fade-in">
                <h3 class="text-xl font-bold mb-4">🔴 Vulnerability Breakdown</h3>
                @php
                    // Mock vulnerability data - replace with actual data
                    $vulnerabilities = [
                        ['severity' => 'critical', 'count' => 2],
                        ['severity' => 'high', 'count' => 5],
                        ['severity' => 'medium', 'count' => 8],
                        ['severity' => 'low', 'count' => 3],
                    ];
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($vulnerabilities as $vuln)
                        @php
                            $colors = [
                                'critical' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '🔴'],
                                'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-300', 'icon' => '🟠'],
                                'medium' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '🟡'],
                                'low' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => '🔵'],
                            ];
                            $color = $colors[$vuln['severity']];
                        @endphp
                        <div class="p-4 {{ $color['bg'] }} {{ $color['text'] }} border-2 {{ $color['border'] }} rounded-lg text-center">
                            <div class="text-3xl font-bold">{{ $vuln['count'] }}</div>
                            <div class="text-sm font-semibold mt-1">{{ $color['icon'] }} {{ ucfirst($vuln['severity']) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- OWASP Top 10 Detailed Analysis -->
        <section class="stats-section mt-8">
            <div class="stat-card fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">🛡️ OWASP Top 10 Analysis</h3>
                    <div class="text-sm">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full mr-2">
                            ✅ {{ $passCount }} Passed
                        </span>
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full">
                            ❌ {{ $totalCount - $passCount }} Failed
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($owaspResults as $result)
                        @php
                            $isPassed = $result['status'] === 'pass';
                            $severityColors = [
                                'critical' => 'bg-red-50 border-red-200',
                                'high' => 'bg-orange-50 border-orange-200',
                                'medium' => 'bg-yellow-50 border-yellow-200',
                                'low' => 'bg-blue-50 border-blue-200',
                            ];
                            $cardColor = $severityColors[$result['severity']];
                            
                            $recommendations = [
                                'A01' => 'Implement role-based access control (RBAC) and ensure all routes have proper authorization checks.',
                                'A02' => 'Enable HTTPS site-wide, use bcrypt/Argon2 for passwords, and encrypt sensitive data at rest.',
                                'A03' => 'Use Eloquent ORM exclusively, validate all inputs, and avoid raw SQL queries.',
                                'A04' => 'Follow secure design principles, implement threat modeling, and use security design patterns.',
                                'A05' => 'Disable APP_DEBUG in production, restrict .env access, and keep software updated.',
                                'A06' => 'Regularly run composer audit, keep dependencies updated, and remove unused packages.',
                                'A07' => 'Implement MFA, use secure session management, and enforce strong password policies.',
                                'A08' => 'Use digital signatures, validate serialized data, and implement integrity checks.',
                                'A09' => 'Enable comprehensive logging, monitor security events, and set up alerting.',
                                'A10' => 'Validate and sanitize all URLs, use allowlists for remote resources, and disable HTTP redirects.',
                            ];
                        @endphp
                        <div class="border-2 {{ $cardColor }} rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-bold text-gray-700">{{ $result['code'] }}</span>
                                        <h4 class="font-semibold text-gray-900">{{ $result['title'] }}</h4>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($result['severity'] === 'critical') bg-red-200 text-red-800
                                            @elseif($result['severity'] === 'high') bg-orange-200 text-orange-800
                                            @elseif($result['severity'] === 'medium') bg-yellow-200 text-yellow-800
                                            @else bg-blue-200 text-blue-800
                                            @endif">
                                            {{ ucfirst($result['severity']) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-2">
                                        <strong>Recommendation:</strong> {{ $recommendations[$result['code']] }}
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

        <!-- Top Priority Actions -->
        <section class="stats-section mt-8">
            <div class="stat-card fade-in bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200">
                <h3 class="text-xl font-bold mb-4 text-red-900">⚠️ Priority Actions Required</h3>
                <div class="space-y-3">
                    @php
                        $failedChecks = collect($owaspResults)->where('status', 'fail');
                    @endphp
                    @foreach($failedChecks as $check)
                        <div class="bg-white p-4 rounded-lg border-l-4 border-red-500">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $check['code'] }}: {{ $check['title'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">Immediate attention required</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($check['severity'] === 'critical') bg-red-200 text-red-800
                                    @elseif($check['severity'] === 'high') bg-orange-200 text-orange-800
                                    @else bg-yellow-200 text-yellow-800
                                    @endif">
                                    {{ ucfirst($check['severity']) }} Priority
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Action Buttons -->
    <section class="stats-section mt-8 mb-12">
        <div class="stat-card fade-in">
            <div class="flex flex-wrap gap-4">
                @if($scan->status === 'completed')
                    <a href="{{ route('reports.create', ['scan_id' => $scan->id]) }}" 
                       class="btn btn-primary flex-1 md:flex-none">
                        📊 Generate Full Report
                    </a>
                    <a href="{{ route('scans.index') }}" 
                       class="btn btn-accent flex-1 md:flex-none">
                        🔄 Run New Scan
                    </a>
                @endif
                <a href="{{ route('scans.edit', $scan) }}" 
                   class="btn btn-accent flex-1 md:flex-none">
                    ✏️ Edit Scan
                </a>
                <form method="POST" action="{{ route('scans.destroy', $scan) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this scan? This action cannot be undone.')" 
                      class="flex-1 md:flex-none">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">
                        🗑️ Delete Scan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Polling Script for Live Status Updates -->
    <script>
        const scanId = {{ $scan->id }};
        const statusEl = document.getElementById('scan-status');

        async function pollStatus() {
            try {
                const res = await fetch(`/scans/${scanId}/status`);
                const data = await res.json();
                
                let statusHTML = '';
                switch(data.status) {
                    case 'pending': 
                        statusHTML = '<span class="bg-yellow-100 text-yellow-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">⏳ Pending</span>';
                        break;
                    case 'running': 
                        statusHTML = '<span class="bg-blue-100 text-blue-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">⏳ Running</span>';
                        break;
                    case 'completed': 
                        statusHTML = '<span class="bg-green-100 text-green-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">✅ Completed</span>';
                        location.reload(); // Reload to show results
                        break;
                    case 'failed': 
                        statusHTML = '<span class="bg-red-100 text-red-800 inline-block px-4 py-2 rounded-full font-semibold text-sm">❌ Failed</span>';
                        break;
                }
                
                statusEl.innerHTML = statusHTML;
                
                if(['pending', 'running'].includes(data.status)) {
                    setTimeout(pollStatus, 3000);
                }
            } catch(err) {
                console.error('Error fetching scan status:', err);
                setTimeout(pollStatus, 5000);
            }
        }

        @if(!in_array($scan->status, ['completed', 'failed']))
            pollStatus();
        @endif
    </script>
@endsection