{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
    <!-- Welcome Section -->
    <section class="welcome-section text-center py-10 fade-in relative z-10">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">
                @auth
                    👋 Welcome back, {{ Auth::user()->name }}
                @else
                    🚀 Welcome to SecureAudit
                @endauth
            </h1>

            <p class="mt-3 text-gray-600 text-lg">
                @auth
                    Here’s a quick overview of your security dashboard and recent activity.
                @else
                    Your simple, powerful tool for running free security scans and managing vulnerabilities.
                @endauth
            </p>

            <div class="flex justify-center flex-wrap gap-4 mt-6 z-20 relative">
                @auth
                    <a href="{{ route('scans.create') }}" class="btn btn-accent">➕ New Scan</a>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">📊 View Reports</a>
                @else
                    <a href="{{ route('guest.scan') }}" class="btn btn-accent" style="position: relative; z-index: 20;">🔍 Try Free Scan</a>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="position: relative; z-index: 20;">🔐 Login</a>
                @endauth
            </div>
        </div>
    </section>

    @auth
        <!-- Dashboard Stats -->
        <section class="stats-section mt-12">
            <h2 class="section-title text-center mb-6">📈 Your Security Overview</h2>
            <div class="stats-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                <div class="stat-card fade-in">
                    <div class="stat-icon">📊</div>
                    <div class="stat-number">{{ $totalScans }}</div>
                    <div class="stat-label">Total Scans</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number">{{ $completedScans }}</div>
                    <div class="stat-label">Completed Scans</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-number">{{ $totalVulnerabilities }}</div>
                    <div class="stat-label">Total Vulnerabilities</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">💡</div>
                    <div class="stat-number">{{ $health }}%</div>
                    <div class="stat-label">System Health</div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="actions-section mt-16">
            <h2 class="section-title text-center mb-8">⚡ Quick Actions</h2>
            <div class="actions-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <div class="action-card fade-in">
                    <div class="action-icon">🔍</div>
                    <h3>Run Security Scan</h3>
                    <p>Start a comprehensive vulnerability scan.</p>
                    <a href="{{ route('scans.create') }}" class="btn btn-primary mt-3">🚀 Start Scan</a>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">📊</div>
                    <h3>Generate Report</h3>
                    <p>Create detailed reports with actionable insights.</p>
                    <a href="{{ route('reports.create') }}" class="btn btn-accent mt-3">📑 Generate Report</a>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">👀</div>
                    <h3>View Audit Logs</h3>
                    <p>Monitor activities and maintain compliance.</p>
                    <a href="{{ route('logs.index') }}" class="btn btn-secondary mt-3">👀 View Logs</a>
                </div>
            </div>
        </section>
    @else
        <!-- Guest Features -->
        <section class="features-section mt-16">
            <h2 class="section-title text-center mb-8">✨ Why Choose SecureAudit?</h2>
            <div class="actions-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <div class="action-card fade-in">
                    <div class="action-icon">⚡</div>
                    <h3>Fast & Easy</h3>
                    <p>Run vulnerability scans in just a few clicks.</p>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">🔒</div>
                    <h3>Secure by Design</h3>
                    <p>Your data is encrypted end-to-end.</p>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">📈</div>
                    <h3>Actionable Insights</h3>
                    <p>Get prioritized recommendations to strengthen your security.</p>
                </div>
            </div>
        </section>

        <!-- Demo Activity -->
        <section class="activity-section mt-16">
            <h2 class="section-title text-center mb-8">📋 See SecureAudit in Action</h2>
            <div class="activity-list max-w-4xl mx-auto">
                <div class="activity-item fade-in">
                    <div class="activity-icon">🔍</div>
                    <div class="activity-content">
                        <div class="activity-title">Website Scan Completed</div>
                        <div class="activity-description">Found 5 vulnerabilities on example.com</div>
                    </div>
                    <div class="activity-time text-sm text-gray-500">2 mins ago</div>
                </div>
                <div class="activity-item fade-in">
                    <div class="activity-icon">📊</div>
                    <div class="activity-content">
                        <div class="activity-title">Report Generated</div>
                        <div class="activity-description">Detailed risk analysis available</div>
                    </div>
                    <div class="activity-time text-sm text-gray-500">10 mins ago</div>
                </div>
                <div class="activity-item fade-in">
                    <div class="activity-icon">⚠️</div>
                    <div class="activity-content">
                        <div class="activity-title">Vulnerability Detected</div>
                        <div class="activity-description">Outdated SSL configuration</div>
                    </div>
                    <div class="activity-time text-sm text-gray-500">15 mins ago</div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section text-center mt-16 fade-in">
            <h2 class="text-2xl font-semibold">🔐 Ready to Secure Your Systems?</h2>
            <p class="mt-2 text-gray-600">Join SecureAudit today and take control of your digital security.</p>
            <div class="flex justify-center gap-4 mt-6">
                <a href="{{ route('register') }}" class="btn btn-primary" style="position: relative; z-index: 20;">🚀 Get Started Free</a>
                <a href="{{ route('guest.scan') }}" class="btn btn-accent" style="position: relative; z-index: 20;">🔍 Try Free Scan</a>
            </div>
        </section>
    @endauth
@endsection
