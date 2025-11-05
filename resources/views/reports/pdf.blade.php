<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Report #{{ $report->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1, h2 { color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background: #f7f7f7; }
        .section { margin-bottom: 16px; }
    </style>
</head>
<body>
    <h1>Security Scan Report — #{{ $report->id }}</h1>
    <p><strong>Target URL:</strong> {{ $report->target_url }}</p>
    <p><strong>Risk Score:</strong> {{ $report->risk_score ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $report->completed_at ? $report->completed_at->format('M d, Y H:i') : 'N/A' }}</p>

    <div class="section">
        <h2>OWASP Top 10 Analysis</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($owaspResults as $code => $result)
                    <tr>
                        <td>{{ $code }}</td>
                        <td>{{ $result['title'] }}</td>
                        <td>{{ $result['status'] ?? 'Unknown' }}</td>
                        <td>{{ $result['recommendation'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(!empty($rawResults['raw_results']))
        <div class="section">
            <h2>Raw Findings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rawResults['raw_results'] as $finding)
                        <tr>
                            <td>{{ $finding['category'] ?? '' }}</td>
                            <td>{{ $finding['title'] ?? '' }}</td>
                            <td>{{ $finding['description'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>
