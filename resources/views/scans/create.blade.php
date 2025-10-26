{{-- resources/views/scans/create.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="welcome-section fade-in text-center">
    <div class="welcome-content">
        @if (!empty($isGuest))
            <h1>🔍 Quick Website Scan</h1>
            <p>Enter your website URL to perform a quick vulnerability scan — no login required.</p>
        @else
            <h1>➕ New Security Scan</h1>
            <p>Enter your target details and start a scan to check for vulnerabilities.</p>
        @endif
    </div>
</section>

<!-- Scan Form -->
<section class="stats-section mt-12">
    <div class="stat-card fade-in">
        <form method="POST" 
              action="{{ !empty($isGuest) ? route('guest.scan.run') : route('scans.store') }}">
            @csrf

            <!-- Target URL -->
            <div class="form-group">
                <label for="target_url" class="form-label">Target URL</label>
                <input type="url" 
                       id="target_url" 
                       name="target_url" 
                       value="{{ old('target_url') }}" 
                       required 
                       placeholder="https://example.com"
                       class="form-control">
                @error('target_url')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Scan Depth -->
            <div class="form-group mt-4">
                <label for="scan_depth" class="form-label">Scan Depth</label>
                <select id="scan_depth" name="scan_depth" class="form-control">
                    <option value="quick" {{ old('scan_depth') === 'quick' ? 'selected' : '' }}>Quick</option>

                    @if (!empty($isGuest))
                        <option value="standard" disabled title="Login required for Standard scan">
                            Standard 
                        </option>
                        <option value="deep" disabled title="Login required for Deep scan">
                            Deep 
                        </option>
                    @else
                        <option value="standard" {{ old('scan_depth') === 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deep" {{ old('scan_depth') === 'deep' ? 'selected' : '' }}>Deep</option>
                    @endif
                </select>
                @error('scan_depth')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tooltip for guests -->
            @if (!empty($isGuest))
                <p class="text-sm text-gray-500 mt-2 italic">
                    ⚠️ To access <strong>Standard</strong> or <strong>Deep</strong> scans, please 
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">log in</a> or 
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">create an account</a>.
                </p>
            @endif

            <!-- Submit -->
            <div class="flex gap-4 mt-6 justify-center">
                <button type="submit" class="btn btn-accent">
                    🚀 {{ !empty($isGuest) ? 'Run Quick Scan' : 'Start Scan' }}
                </button>

                @if (empty($isGuest))
                    <a href="{{ route('scans.index') }}" class="btn btn-secondary">Cancel</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-secondary">Back to Home</a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- Inline Styling (optional enhancement) -->

@endsection
