<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Sekolah - SMKN 4 BOGOR</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        @media (min-width: 1400px) {
            .container {
                padding: 0 60px;
            }
        }
        
        /* Header Section - Modern Navbar Style */
        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        .branding {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .brand-icon {
            width: 60px;
            height: 60px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .brand-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        
        .brand-text p {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin: 0;
            line-height: 1;
        }
        
        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-links li a:hover {
            color: #3b82f6;
            background: #f1f5f9;
        }
        
        .nav-links li a.active {
            color: white;
            background: #3b82f6;
        }

        .login-btn {
            background: #3b82f6;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .login-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* User profile dropdown (sama seperti dashboard) */
        .user-profile { position: relative; }
        .user-profile-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }
        .user-profile-toggle:hover { background: #eef2ff; border-color: #c7d2fe; }
        .user-avatar {
            width: 28px; height: 28px; border-radius: 999px; background: #3b82f6; color: #fff;
            display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:600;
        }
        .user-name { max-width: 120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .user-profile-toggle i { font-size:11px; color:#6b7280; }
        .user-dropdown {
            position:absolute; right:0; top:120%; background:#fff; border-radius:14px;
            box-shadow:0 10px 25px rgba(15,23,42,0.18); border:1px solid #e5e7eb;
            padding:12px 14px; width:230px; z-index:50; display:none;
        }
        .user-dropdown.show { display:block; }
        .user-info-name { font-size:0.95rem; font-weight:600; color:#111827; }
        .user-info-email { font-size:0.8rem; color:#6b7280; margin-top:2px; word-break:break-all; }
        .user-dropdown-divider { height:1px; background:#e5e7eb; margin:10px 0; }
        .user-logout-btn {
            width:100%; border:none; border-radius:8px; padding:9px 10px; background:#ef4444;
            color:#fff; font-size:0.9rem; font-weight:600; display:inline-flex; align-items:center;
            justify-content:center; gap:6px; cursor:pointer; transition:all 0.2s ease;
        }
        .user-logout-btn:hover { background:#dc2626; box-shadow:0 6px 14px rgba(220,38,38,0.4); }

        .login-btn {
            background: #3b82f6;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .login-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Main Content */
        .main-content {
            padding: 0 0 60px;
        }
        
        .page-header {
            position: relative;
            text-align: center;
            margin-bottom: 40px; /* disamakan dengan galeri */
            padding: 60px 20px;  /* ditambah agar tidak terlalu tipis */
            border-radius: 0 0 20px 20px;
            color: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../images/DJI_0148.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        .page-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.65), rgba(30, 64, 175, 0.65));
            z-index: -1;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 15px;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: #e5e7eb;
        }
        
        .agenda-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .agenda-card {
            background: white;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            min-height: 210px;
        }
        
        .agenda-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .agenda-date {
            background: transparent;
            color: #6b7280;
            padding: 0;
            border-radius: 0;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .agenda-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .agenda-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* tampilkan ringkas dulu */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .agenda-time {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #3b82f6;
            font-weight: 500;
        }

        /* Saat kartu diklik (expanded), tampilkan deskripsi penuh */
        .agenda-card.expanded .agenda-description {
            -webkit-line-clamp: unset;
            overflow: visible;
        }
        
        .agenda-time i {
            font-size: 16px;
        }
        
        /* Footer Styles */
        .footer {
            background: #1e293b;
            color: #cbd5e1;
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-branding .branding {
            margin-bottom: 20px;
        }
        
        .footer-branding .brand-text h3 {
            color: white;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .footer-branding .brand-text p {
            color: #94a3b8;
            font-size: 14px;
        }
        
        .footer-description {
            line-height: 1.6;
            max-width: 350px;
        }
        
        .footer-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
        }
        
        .footer-section h4 {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: #3b82f6;
            border-radius: 2px;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 12px;
        }
        
        .footer-section ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .footer-section ul li a:hover {
            color: #3b82f6;
            transform: translateX(5px);
        }
        
        .footer-section ul li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: #3b82f6;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .agenda-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .container {
                padding: 0 20px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .brand-text h1 {
                font-size: 22px;
            }
            
            .brand-text p {
                font-size: 14px;
            }
            
            .brand-icon {
                width: 60px;
                height: 70px;
            }
            
            .navbar {
                padding: 0 20px;
            }
            
            .footer {
                padding: 40px 0 20px;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-links {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .agenda-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .container {
                padding: 0 20px;
            }
            
            .page-title {
                font-size: 1.3rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .brand-text h1 {
                font-size: 20px;
            }
            
            .brand-text p {
                font-size: 12px;
            }
            
            .footer-links {
                grid-template-columns: 1fr;
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <nav class="navbar">
                <div class="branding">
                    <div class="brand-icon">
                        <img src="{{ asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div class="brand-text">
                        <h1>SMKN 4</h1>
                        <p>Bogor</p>
                    </div>
                </div>
                
                <div class="nav-menu">
                    <ul class="nav-links">
                        <li><a href="{{ route('user.dashboard') }}">Beranda</a></li>
                        <li><a href="{{ route('user.gallery') }}">Galeri</a></li>
                        <li><a href="{{ route('user.informasi') }}">Informasi</a></li>
                        <li><a href="{{ route('user.agenda') }}" class="active">Agenda</a></li>
                    </ul>
                    @guest
                        <a href="{{ route('login') }}" class="login-btn">Login</a>
                    @else
                        <div class="user-profile">
                            <div class="user-profile-toggle" style="cursor: default;">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                            </div>
                        </div>
                    @endguest
                </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">{{ \App\Models\SiteSetting::get('agenda_title', 'Agenda Sekolah') }}</h1>
                <p class="page-subtitle">{{ \App\Models\SiteSetting::get('agenda_description', 'Jadwal kegiatan dan acara SMKN 4 Bogor') }}</p>
            </div>
            
            <div class="agenda-grid">
                @forelse($agendaItems as $item)
                <div class="agenda-card">
                    <div class="agenda-date">{{ $item->date_label }}</div>
                    <h3 class="agenda-title">{{ $item->title }}</h3>
                    <p class="agenda-description">{{ $item->description }}</p>
                    <div class="agenda-time">
                        <i class="fas fa-clock"></i>
                        <span>{{ $item->time }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500">Belum ada agenda tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-branding">
                    <div class="branding">
                        <div class="brand-icon">
                            <img src="{{ asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo">
                        </div>
                        <div class="brand-text">
                            <h3>SMKN 4</h3>
                            <p>Bogor</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Sekolah Menengah Kejuruan Negeri 4 Bogor adalah lembaga pendidikan kejuruan yang berkomitmen untuk menghasilkan lulusan yang kompeten dan berkarakter.
                    </p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="{{ route('user.dashboard') }}">Beranda</a></li>
                            <li><a href="{{ route('user.gallery') }}">Galeri</a></li>
                            <li><a href="{{ route('user.informasi') }}">Informasi</a></li>
                            <li><a href="{{ route('user.agenda') }}">Agenda</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Kontak</h4>
                        <ul>
                            <li><i class="fas fa-map-marker-alt"></i> Jl. Raya Tajur No. 84, Kota Bogor</li>
                            <li><i class="fas fa-phone"></i> (0251) 1234567</li>
                            <li><i class="fas fa-envelope"></i> info@smkn4bogor.sch.id</li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Ikuti Kami</h4>
                        <div class="social-links">
                            <a href="{{ \App\Models\SiteSetting::get('social_facebook', '#') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('social_instagram', '#') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('social_youtube', '#') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SMKN 4 Bogor. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Script interaksi agenda: klik kartu untuk melihat deskripsi lengkap -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var cards = document.querySelectorAll('.agenda-card');
            cards.forEach(function (card) {
                card.addEventListener('click', function () {
                    card.classList.toggle('expanded');
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>