{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
    <!-- Welcome -->
    <section class="welcome-section fade-in text-center">
        <div class="welcome-content">
            <h1>
                @auth 
                    👋 Welcome back, {{ Auth::user()->name }} 
                @else 
                    🚀 Welcome to SecureAudit 
                @endauth
            </h1>
            <p class="mt-2 text-gray-600">
                @auth 
                    Here’s a quick overview of your security dashboard and recent activity. 
                @else 
                    Your simple, powerful tool for running free security scans and managing vulnerabilities. 
                @endauth
            </p>
            <div class="flex justify-center gap-4 flex-wrap mt-4">
                @auth
                    <a href="{{ route('scans.create') }}" class="btn btn-accent">➕ New Scan</a>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">📊 View Reports</a>
                @else
                    <a href="{{ route('guest.scan') }}" class="btn btn-accent">🔍 Try Free Scan</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endauth
            </div>
        </div>
    </section>

    @auth
        <!-- Stats -->
        <section class="stats-section mt-12">
            <div class="stats-grid">
                <div class="stat-card fade-in">
                    <div class="stat-icon">📊</div>
                    <div class="stat-number">12</div>
                    <div class="stat-label">Scans Completed</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">📄</div>
                    <div class="stat-number">5</div>
                    <div class="stat-label">Reports Generated</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-number">3</div>
                    <div class="stat-label">Active Vulnerabilities</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number">98%</div>
                    <div class="stat-label">System Health</div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="actions-section mt-12">
            <h2 class="section-title">⚡ Quick Actions</h2>
            <div class="actions-grid">
                <div class="action-card fade-in">
                    <div class="action-icon">🔍</div>
                    <h3>Run Security Scan</h3>
                    <p>Start a comprehensive vulnerability scan.</p>
                    <a href="{{ route('scans.create') }}" class="btn btn-primary">🚀 Start Scan</a>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">📊</div>
                    <h3>Generate Report</h3>
                    <p>Create detailed reports with actionable insights.</p>
                    <a href="{{ route('reports.create') }}" class="btn btn-accent">📊 Generate Report</a>
                </div>
                <div class="action-card fade-in">
                    <div class="action-icon">👀</div>
                    <h3>View Audit Logs</h3>
                    <p>Monitor activities and maintain compliance.</p>
                    <a href="{{ route('logs.index') }}" class="btn btn-secondary">👀 View Logs</a>
                </div>
            </div>
        </section>
    @else
        <!-- Guest Features -->
        <section class="actions-section fade-in mt-12">
            <h2 class="section-title">✨ Why SecureAudit?</h2>
            <div class="actions-grid">
                <div class="action-card">
                    <div class="action-icon">⚡</div>
                    <h3>Fast & Easy</h3>
                    <p>Run vulnerability scans in just a few clicks.</p>
                </div>
                <div class="action-card">
                    <div class="action-icon">🔒</div>
                    <h3>Secure by Design</h3>
                    <p>Your data is encrypted end-to-end.</p>
                </div>
                <div class="action-card">
                    <div class="action-icon">📈</div>
                    <h3>Actionable Insights</h3>
                    <p>Clear, prioritized recommendations.</p>
                </div>
            </div>
        </section>

        <!-- Demo Activity -->
        <section class="activity-section fade-in mt-12">
            <h2 class="section-title">📋 See SecureAudit in Action</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon scan">🔍</div>
                    <div class="activity-content">
                        <div class="activity-title">Website Scan Completed</div>
                        <div class="activity-description">Found 5 vulnerabilities on example.com</div>
                    </div>
                    <div class="activity-time">2 mins ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon report">📊</div>
                    <div class="activity-content">
                        <div class="activity-title">Report Generated</div>
                        <div class="activity-description">Detailed risk analysis available</div>
                    </div>
                    <div class="activity-time">10 mins ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon vulnerability">⚠️</div>
                    <div class="activity-content">
                        <div class="activity-title">Vulnerability Detected</div>
                        <div class="activity-description">Outdated SSL configuration</div>
                    </div>
                    <div class="activity-time">15 mins ago</div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="welcome-section fade-in mt-12 text-center">
            <div class="welcome-content">
                <h2>🔐 Ready to Secure Your Systems?</h2>
                <p>Join SecureAudit today and take control of your digital security.</p>
                <div class="flex justify-center gap-4 flex-wrap mt-4">
                    <a href="{{ route('register') }}" class="btn btn-primary">🚀 Get Started Free</a>
                    <a href="{{ route('guest.scan') }}" class="btn btn-accent">🔍 Try Free Scan</a>
                </div>
            </div>
        </section>
    @endauth
@endsection
