<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureAudit - Security Made Simple</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>

         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #0ea5e9;
            --accent-color: #10b981;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --text-light: #94a3b8;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: var(--bg-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Navigation - Same as landing page */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Buttons - Same as landing page */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-accent {
            background: var(--accent-color);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-accent:hover {
            background: var(--success-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 2rem 0;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            border-radius: var(--border-radius);
            padding: 3rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Stats Grid */
        .stats-section {
            margin-bottom: 3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-gray);
            font-weight: 500;
        }

        /* Quick Actions Section */
        .actions-section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
            text-align: center;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .action-card {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .action-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .action-card p {
            color: var(--text-gray);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* Recent Activity Section */
        .activity-section {
            margin-bottom: 3rem;
        }

        .activity-list {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .activity-item {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: background 0.3s ease;
        }

        .activity-item:hover {
            background: var(--bg-light);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--white);
            flex-shrink: 0;
        }

        .activity-icon.scan {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .activity-icon.report {
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
        }

        .activity-icon.vulnerability {
            background: linear-gradient(135deg, var(--warning-color), var(--danger-color));
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .activity-description {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .activity-time {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .welcome-section {
                padding: 2rem;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .activity-item {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Mobile menu */
        .mobile-menu {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
        }

        .mobile-menu span {
            width: 25px;
            height: 3px;
            background: var(--text-dark);
            border-radius: 2px;
            transition: 0.3s;
        }

        @media (max-width: 768px) {
            .mobile-menu {
                display: flex;
            }
        }
  
        /* ======= Your Existing Styles ======= */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #0ea5e9;
            --accent-color: #10b981;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --text-light: #94a3b8;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: var(--bg-light);
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }

        /* --- Navbar --- */
        .navbar { position: fixed; top: 0; left: 0; right: 0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.2); z-index: 1000; transition: all 0.3s ease; }
        .nav-content { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .logo { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .nav-links { display: flex; list-style: none; gap: 2rem; align-items: center; }
        .nav-links a { text-decoration: none; color: var(--text-dark); font-weight: 500; transition: color 0.3s ease; position: relative; }
        .nav-links a:hover { color: var(--primary-color); }
        .nav-links a::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: var(--primary-color); transition: width 0.3s ease; }
        .nav-links a:hover::after { width: 100%; }

        /* --- Buttons --- */
        .btn { padding: 0.75rem 1.5rem; border-radius: var(--border-radius); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; border: none; cursor: pointer; font-size: 0.9rem; }
        .btn-primary { background: var(--primary-color); color: var(--white); box-shadow: var(--shadow); }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .btn-secondary { background: var(--white); color: var(--primary-color); border: 2px solid var(--primary-color); }
        .btn-secondary:hover { background: var(--primary-color); color: var(--white); transform: translateY(-2px); }
        .btn-accent { background: var(--accent-color); color: var(--white); box-shadow: var(--shadow); }
        .btn-accent:hover { background: var(--success-color); transform: translateY(-2px); box-shadow: var(--shadow-lg); }

        /* --- Sections --- */
        .main-content { margin-top: 100px; padding: 2rem 0; }
        .welcome-section { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: var(--white); border-radius: var(--border-radius); padding: 3rem; margin-bottom: 3rem; position: relative; overflow: hidden; }
        .welcome-content { position: relative; z-index: 2; text-align: center; }
        .welcome-section h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .welcome-section p { font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem; }

        .stats-section, .actions-section, .activity-section { margin-bottom: 3rem; }
        .section-title { font-size: 2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2rem; text-align: center; }

        .stats-grid, .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .stat-card, .action-card { background: var(--white); padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow); transition: all 0.3s ease; border: 1px solid rgba(0, 0, 0, 0.05); text-align: center; }
        .stat-card:hover, .action-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }

        .activity-list { background: var(--white); border-radius: var(--border-radius); box-shadow: var(--shadow); overflow: hidden; }
        .activity-item { padding: 1.5rem 2rem; border-bottom: 1px solid rgba(0, 0, 0, 0.05); display: flex; align-items: center; gap: 1rem; transition: background 0.3s ease; }
        .activity-item:hover { background: var(--bg-light); }
        .activity-item:last-child { border-bottom: none; }

        /* Responsive + Animations */
        @media (max-width: 768px) { .container { padding: 0 1rem; } .welcome-section { padding: 2rem; } .welcome-section h1 { font-size: 2rem; } .stats-grid { grid-template-columns: repeat(2, 1fr); } .actions-grid { grid-template-columns: 1fr; } .activity-item { padding: 1rem; } .nav-links { display: none; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }
        .fade-in { animation: fadeInUp 0.6s ease forwards; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <a href="{{ route('home') }}" class="logo">SecureAudit</a>
                <ul class="nav-links">
                    @auth
                        <li><a href="{{ route('scans.index') }}">Scans</a></li>
                        <li><a href="{{ route('reports.index') }}">Reports</a></li>
                        <li><a href="{{ route('logs.index') }}">Audit Logs</a></li>
                        <li><a href="{{ route('vulnerabilities.index') }}">Vulnerabilities</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="btn btn-secondary">Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('guest.scan') }}">Try Free Scan</a></li>
                        <li><a href="{{ route('login') }}" class="btn btn-secondary">Login</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-primary">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Section -->
            <section class="welcome-section fade-in">
                <div class="welcome-content">
                    <h1>
                        @auth
                            Welcome back, {{ Auth::user()->name }} 
                        @else
                            Welcome to SecureAudit 
                        @endauth
                    </h1>
                    <p>
                        @auth
                            Here's a quick overview of your security dashboard and recent activity.
                        @else
                            Your simple, powerful tool for running free security scans and managing vulnerabilities.
                        @endauth
                    </p>

                    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                        @auth
                            <a href="{{ route('scans.create') }}" class="btn btn-accent"> New Scan</a>
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">📊 View Reports</a>
                        @else
                            <a href="{{ route('guest.scan') }}" class="btn btn-accent"> Try Free Scan</a>
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        @endauth
                    </div>
                </div>
            </section>

            @auth
                <!-- Stats Section -->
                <section class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card fade-in"><div class="stat-icon">📊</div><div class="stat-number">12</div><div class="stat-label">Scans Completed</div></div>
                        <div class="stat-card fade-in"><div class="stat-icon">📄</div><div class="stat-number">5</div><div class="stat-label">Reports Generated</div></div>
                        <div class="stat-card fade-in"><div class="stat-icon">⚠️</div><div class="stat-number">3</div><div class="stat-label">Active Vulnerabilities</div></div>
                        <div class="stat-card fade-in"><div class="stat-icon">✅</div><div class="stat-number">98%</div><div class="stat-label">System Health</div></div>
                    </div>
                </section>

                <!-- Quick Actions -->
                <section class="actions-section">
                    <h2 class="section-title">Quick Actions</h2>
                    <div class="actions-grid">
                        <div class="action-card fade-in"><div class="action-icon">🔍</div><h3>Run Security Scan</h3><p>Start a comprehensive vulnerability scan.</p><a href="{{ route('scans.create') }}" class="btn btn-primary">Start Scan</a></div>
                        <div class="action-card fade-in"><div class="action-icon">📊</div><h3>Generate Report</h3><p>Create detailed reports with actionable insights.</p><a href="{{ route('reports.create') }}" class="btn btn-accent">Generate Report</a></div>
                        <div class="action-card fade-in"><div class="action-icon">👀</div><h3>View Audit Logs</h3><p>Monitor activities and maintain compliance.</p><a href="{{ route('logs.index') }}" class="btn btn-secondary">View Logs</a></div>
                    </div>
                </section>
            @else
                <!-- Guest Features -->
                <section class="actions-section fade-in">
                    <h2 class="section-title">Why SecureAudit?</h2>
                    <div class="actions-grid">
                        <div class="action-card"><div class="action-icon">⚡</div><h3>Fast & Easy</h3><p>Run vulnerability scans in just a few clicks.</p></div>
                        <div class="action-card"><div class="action-icon">🔒</div><h3>Secure by Design</h3><p>Your data is encrypted end-to-end.</p></div>
                        <div class="action-card"><div class="action-icon">📈</div><h3>Actionable Insights</h3><p>Clear, prioritized recommendations.</p></div>
                    </div>
                </section>

                <!-- Guest Demo Activity -->
                <section class="activity-section fade-in">
                    <h2 class="section-title">See SecureAudit in Action</h2>
                    <div class="activity-list">
                        <div class="activity-item"><div class="activity-icon scan">🔍</div><div class="activity-content"><div class="activity-title">Website Scan Completed</div><div class="activity-description">Found 5 vulnerabilities on example.com</div></div><div class="activity-time">2 mins ago</div></div>
                        <div class="activity-item"><div class="activity-icon report">📊</div><div class="activity-content"><div class="activity-title">Report Generated</div><div class="activity-description">Detailed risk analysis available</div></div><div class="activity-time">10 mins ago</div></div>
                        <div class="activity-item"><div class="activity-icon vulnerability">⚠️</div><div class="activity-content"><div class="activity-title">Vulnerability Detected</div><div class="activity-description">Outdated SSL configuration</div></div><div class="activity-time">15 mins ago</div></div>
                    </div>
                </section>

                <!-- Call to Action -->
                <section class="welcome-section fade-in" style="margin-top:2rem;">
                    <div class="welcome-content">
                        <h2>Ready to Secure Your Systems?</h2>
                        <p>Join SecureAudit today and take control of your digital security.</p>
                        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('register') }}" class="btn btn-primary">🚀 Get Started Free</a>
                            <a href="{{ route('guest.scan') }}" class="btn btn-accent">🔍 Try Free Scan</a>
                        </div>
                    </div>
                </section>
            @endauth
        </div>
    </main>
</body>
</html>
