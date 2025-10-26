@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="welcome-section fade-in text-center">
        <div class="welcome-content">
            <h1>✏️ Edit Scan</h1>
            <p>Update scan settings or target details.</p>
        </div>
    </section>

    <!-- Edit Form -->
    <section class="stats-section mt-12">
        <div class="stat-card fade-in">
            <form method="POST" action="{{ route('scans.update', $scan) }}">
                @csrf
                @method('PUT')

                <!-- Target URL -->
                <div class="form-group">
                    <label for="target_url" class="form-label">Target URL</label>
                    <input 
                        type="url" 
                        id="target_url" 
                        name="target_url" 
                        value="{{ old('target_url', $scan->target_url) }}" 
                        required
                        class="form-control">
                    @error('target_url')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Scan Depth -->
                <div class="form-group mt-4">
                    <label for="scan_depth" class="form-label">Scan Depth</label>
                    <select id="scan_depth" name="scan_depth" class="form-control">
                        <option value="quick" {{ $scan->scan_depth === 'quick' ? 'selected' : '' }}>Quick</option>
                        <option value="standard" {{ $scan->scan_depth === 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deep" {{ $scan->scan_depth === 'deep' ? 'selected' : '' }}>Deep</option>
                    </select>
                    @error('scan_depth')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn btn-accent">💾 Save Changes</button>
                    <a href="{{ route('scans.show', $scan) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
