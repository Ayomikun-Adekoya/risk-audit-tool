{{-- resources/views/scans/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="welcome-section fade-in text-center">
        <div class="welcome-content">
            <h1>🔍 My Security Scans</h1>
            <p>Track and manage all your past and ongoing scans.</p>
            <a href="{{ route('scans.create') }}" class="btn btn-accent">➕ New Scan</a>
        </div>
    </section>

    <!-- Scan List -->
    <section class="stats-section fade-in mt-12">
        @if($scans->isEmpty())
            <div class="stat-card text-center">
                <p>No scans found. 🚀 Start your first scan now.</p>
            </div>
        @else
            <div class="stats-grid">
                @foreach($scans as $scan)
                    <div class="stat-card fade-in">
                        <div class="stat-icon">🌐</div>
                        <h3>{{ $scan->target_url }}</h3>
                        <p class="stat-label">Depth: {{ ucfirst($scan->scan_depth) }}</p>
                        <p class="stat-label">
                            Status:
                            @if($scan->status === 'completed')
                                ✅ Completed
                            @elseif($scan->status === 'running')
                                ⏳ Running
                            @else
                                ❌ Failed
                            @endif
                        </p>
                        <div class="mt-4 flex gap-2 justify-center flex-wrap">
                            <a href="{{ route('scans.show', $scan) }}" class="btn btn-secondary">📄 View</a>
                            <a href="{{ route('reports.create', ['scan_id' => $scan->id]) }}" class="btn btn-primary">📊 Generate Report</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
