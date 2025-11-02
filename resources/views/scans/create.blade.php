{{-- resources/views/scans/create.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- 🌐 Page Header -->
<section class="welcome-section fade-in text-center bg-gradient-to-r from-sky-50 to-blue-50 py-10 rounded-lg shadow-sm">
    <div class="welcome-content">
        @if (!empty($isGuest))
            <h1 class="text-3xl font-bold text-slate-800">🔍 Quick Website Scan</h1>
            <p class="text-slate-600 mt-2">Enter your website URL to perform a quick vulnerability scan — no login required.</p>
        @else
            <h1 class="text-3xl font-bold text-slate-800">➕ New Security Scan</h1>
            <p class="text-slate-600 mt-2">Enter your target details and start a scan to check for vulnerabilities.</p>
        @endif
    </div>
</section>

<!-- 🧭 Scan Form -->
<section class="mt-10">
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-2xl p-8 border border-slate-100">
        <form method="POST" 
              action="{{ !empty($isGuest) ? route('guest.scan.run') : route('scans.store') }}">
            @csrf

            <!-- 🌍 Target URL -->
            <div class="mb-5">
                <label for="target_url" class="block text-slate-700 font-medium mb-1">Target URL</label>
                <input type="url" 
                       id="target_url" 
                       name="target_url" 
                       value="{{ old('target_url') }}" 
                       required 
                       placeholder="https://example.com"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                @error('target_url')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ⚙️ Scan Depth -->
            <div class="mb-5">
                <label for="scan_depth" class="block text-slate-700 font-medium mb-1">Scan Depth</label>
                <select id="scan_depth" name="scan_depth" 
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    <option value="quick" {{ old('scan_depth') === 'quick' ? 'selected' : '' }}>Quick</option>

                    @if (!empty($isGuest))
                        <option value="standard" disabled class="text-slate-400">
                            Standard (Login required)
                        </option>
                        <option value="deep" disabled class="text-slate-400">
                            Deep (Login required)
                        </option>
                    @else
                        <option value="standard" {{ old('scan_depth') === 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deep" {{ old('scan_depth') === 'deep' ? 'selected' : '' }}>Deep</option>
                    @endif
                </select>
                @error('scan_depth')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 💡 Tooltip for guests -->
            @if (!empty($isGuest))
                <p class="text-sm text-slate-500 mt-1 italic">
                    ⚠️ To access <strong>Standard</strong> or <strong>Deep</strong> scans, please 
                    <a href="{{ route('login') }}" class="text-sky-600 hover:underline">log in</a> or 
                    <a href="{{ route('register') }}" class="text-sky-600 hover:underline">create an account</a>.
                </p>
            @endif

            <!-- ✅ Legal Reminder & Consent -->
            <div class="mt-8">
                <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-400 rounded-lg shadow-sm">
                    <p class="text-sm text-amber-800 mb-2">
                        <strong>Important — Legal Reminder:</strong><br>
                        Only scan systems you own or have explicit permission to test. Even non-intrusive scans can be considered unauthorized access.
                    </p>

                    <!-- Checkbox -->
                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" name="consent_given" value="1" required class="mr-2 rounded text-sky-500 border-slate-300 focus:ring-sky-400">
                        <span class="text-sm text-slate-700">I confirm I have permission to scan the target.</span>
                    </label>

                    @error('consent_given')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <!-- Modal trigger -->
                    <p class="text-xs text-slate-500 mt-3">
                        <button type="button" id="openConsentModal" class="text-sky-600 underline hover:text-sky-800">
                            Read full legal notice
                        </button>
                    </p>
                </div>
            </div>

            <!-- 🪩 Modal -->
            <div id="consentModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-xl shadow-2xl max-w-md mx-auto border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Legal Reminder</h3>
                    <p class="text-sm text-slate-600 mb-4">
                        By proceeding, you confirm that you are the owner of the target system or have explicit written
                        permission to perform this scan. Unauthorized scanning may violate law or service terms.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="closeConsentModal" class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg hover:bg-slate-200">
                            Close
                        </button>
                        <button type="button" id="acceptConsentModal" class="bg-sky-600 text-white px-3 py-1 rounded-lg hover:bg-sky-700">
                            I Understand & Accept
                        </button>
                    </div>
                </div>
            </div>

            <!-- 🚀 Submit Buttons -->
            <div class="flex gap-4 mt-8 justify-center">
                <button type="submit" 
                        class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2 rounded-lg shadow hover:from-sky-600 hover:to-sky-700 transition">
                    🚀 {{ !empty($isGuest) ? 'Run Quick Scan' : 'Start Scan' }}
                </button>

                @if (empty($isGuest))
                    <a href="{{ route('scans.index') }}" class="bg-slate-100 text-slate-700 px-5 py-2 rounded-lg hover:bg-slate-200 transition">Cancel</a>
                @else
                    <a href="{{ route('home') }}" class="bg-slate-100 text-slate-700 px-5 py-2 rounded-lg hover:bg-slate-200 transition">Back to Home</a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- ⚡ Modal Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('consentModal');
        const openBtn = document.getElementById('openConsentModal');
        const closeBtn = document.getElementById('closeConsentModal');
        const acceptBtn = document.getElementById('acceptConsentModal');

        const toggleModal = (show) => {
            modal.classList.toggle('hidden', !show);
        };

        openBtn?.addEventListener('click', () => toggleModal(true));
        closeBtn?.addEventListener('click', () => toggleModal(false));
        acceptBtn?.addEventListener('click', () => toggleModal(false));
    });
</script>
@endsection
