<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Galeri Foto - SMKN 4 BOGOR</title>
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
            color: #1f2937;
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
        
        /* Header/Nav - unified */
        .header { background: white; padding: 20px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: sticky; top: 0; z-index: 1000; }
        .navbar { display: flex; align-items: center; justify-content: space-between; width: 100%; max-width: 1400px; margin: 0 auto; padding: 0 40px; }
        .branding { display: flex; align-items: center; gap: 16px; }
        .brand-icon { 
            width: 60px; 
            height: 60px; 
            position: relative;
            display: flex; 
            align-items: center; 
            justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 2px 4px rgba(30, 58, 138, 0.2));
        }
        .brand-text h1 { font-size: 24px; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.2; }
        .brand-text p { font-size: 13px; color: #64748b; font-weight: 500; margin: 0; line-height: 1; }
        .nav-menu { display: flex; align-items: center; gap: 20px; }
        .nav-links { display: flex; align-items: center; gap: 8px; list-style: none; margin: 0; padding: 0; }
        .nav-links a { color: #64748b; text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 20px; border-radius: 8px; transition: all 0.2s ease; white-space: nowrap; }
        .nav-links a:hover { color: #3b82f6; background: #f1f5f9; }
        .nav-links a.active { color: white; background: #3b82f6; }
        
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
        
        /* Main Content */
        .main-content { padding: 0 0 40px; background: #f8f9fa; min-height: calc(100vh - 100px); }
        
        .gallery-header {
            position: relative;
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 20px;
            border-radius: 0 0 20px 20px;
            color: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            background-image: linear-gradient(135deg, rgba(37, 99, 235, 0.65), rgba(30, 64, 175, 0.65)), url('../../images/DJI_0148.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .gallery-title { font-size: 2rem; font-weight: 700; color: #ffffff; margin-bottom: 15px; }
        
        .gallery-subtitle { font-size: 1.1rem; color: #e5e7eb; max-width: 600px; margin: 0 auto; }
        
        /* Gallery Controls */
        .gallery-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .search-filter {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input { padding: 12px 16px 12px 45px; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; color: #1f2937; font-size: 16px; width: 300px; transition: all 0.3s ease; }
        
        .search-box input:focus { outline: none; border-color: #3b82f6; background: #fff; }
        
        .search-box input::placeholder { color: #94a3b8; }
        
        .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        
        .filter-select { padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; color: #1f2937; font-size: 16px; cursor: pointer; }
        
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .view-toggle {
            display: flex;
            gap: 10px;
        }
        
        .view-btn { padding: 12px 16px; border: 1px solid #e5e7eb; background: #fff; color: #64748b; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; }
        
        .view-btn.active { background: #3b82f6; border-color: #3b82f6; color: #fff; }
        
        .view-btn:hover { border-color: #3b82f6; }
        
        .add-photo-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .add-photo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        
        /* Gallery Grid */
        .gallery-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 30px; 
            margin-bottom: 40px; 
        }
        
        /* Animation for filtered items */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .error-shake {
            animation: shake 0.4s ease-in-out;
        }
        
        /* Image Carousel Styles */
        .carousel-container {
            position: relative;
            width: 100%;
        }
        
        .carousel-image {
            max-width: 100%;
            height: auto;
            border-radius: 0 0 16px 16px;
            display: block;
        }
        
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            font-size: 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .carousel-nav:hover {
            background: rgba(0, 0, 0, 0.8);
        }
        
        .carousel-prev {
            left: 20px;
        }
        
        .carousel-next {
            right: 20px;
        }
        
        .carousel-counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        /* List View Specific Styles */
        .gallery-grid.list-view {
            grid-template-columns: 1fr;
        }

        .gallery-grid.list-view .gallery-item {
            display: grid;
            grid-template-columns: 320px 1fr;
            max-height: 260px;
        }
        
        .gallery-grid.list-view .gallery-image {
            height: 100%;
            max-height: 260px;
            border-radius: 20px 0 0 20px;
        }
        
        .gallery-grid.list-view .gallery-info {
            padding: 20px 24px;
        }
        
        .gallery-item { 
            background: #fff; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            border: 1px solid #e5e7eb; 
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .gallery-item:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 30px rgba(0,0,0,0.15); 
        }
        
        .gallery-image {
            width: 100%;
            height: 260px; /* foto lebih besar mendominasi kartu */
            object-fit: cover;
            object-position: center;
            cursor: pointer;
            display: block;
            background: #f3f4f6;
        }
        
        .gallery-info {
            padding: 16px 20px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .gallery-title { 
            font-size: 1.1rem; 
            font-weight: 700; 
            color: #1e293b; 
            margin-bottom: 8px; 
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.8em;
        }

        /* Pastikan teks di header galeri mengikuti style seperti halaman Informasi */
        .gallery-header .gallery-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .gallery-header .gallery-subtitle {
            font-size: 1.1rem;
            color: #e5e7eb;
        }
        
        .gallery-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .gallery-category {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .category-tag-icon {
            font-size: 0.85rem;
        }

        .gallery-photo-count {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .gallery-meta-separator {
            color: #9ca3af;
        }
        
        .gallery-description { 
            color: #64748b; 
            font-size: 0.9rem; 
            line-height: 1.5; 
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }
        
        .gallery-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .edit-btn {
            background: #f59e0b;
            color: white;
        }
        
        .edit-btn:hover {
            background: #d97706;
        }
        
        .delete-btn {
            background: #ef4444;
            color: white;
        }
        
        .delete-btn:hover {
            background: #dc2626;
        }
        
        /* Gallery Actions Container */
        .gallery-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        
        .gallery-action-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #e2e8f0;
            background: #ffffff;
        }
        
        .gallery-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-download-action {
            background: #ffffff;
            color: #1f2933;
            border-color: #cbd2e1;
        }
        
        .btn-download-action:hover {
            background: #f1f5f9;
            border-color: #9fb3c8;
        }
        
        .btn-like-action {
            background: #ffffff;
            color: #4b5563;
            border-color: #cbd5e1;
        }
        
        .btn-like-action:hover {
            background: #f1f5f9;
            border-color: #9ca3af;
        }
        
        .btn-like-action.liked {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #2563eb;
        }

        .btn-dislike-action {
            background: #ffffff;
            color: #4b5563;
            border-color: #fecaca;
        }

        .btn-dislike-action:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .btn-dislike-action.disliked {
            background: #fef2f2;
            border-color: #ef4444;
            color: #b91c1c;
        }
        
        .action-icon {
            font-size: 16px;
        }
        
        .action-count {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            overflow-y: auto;
            padding: 20px 0;
        }
        
        .modal-content { 
            background: #fff; 
            margin: 5% auto; 
            padding: 0; 
            border-radius: 16px; 
            width: 90%; 
            max-width: 600px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3); 
            animation: modalSlideIn 0.3s ease;
            position: relative;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 10px 18px; 
            border-radius: 16px 16px 0 0; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center;
            min-height: 40px;
        }
        
        .modal-title { 
            color: #ffffff; 
            font-size: 22px; 
            font-weight: 700;
            margin: 0;
        }
        
        .close { 
            color: #ffffff; 
            font-size: 32px; 
            font-weight: 300;
            cursor: pointer; 
            transition: all 0.3s ease;
            line-height: 1;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .close:hover { 
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 24px 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }

        /* Modal detail foto: layout dua kolom */
        .image-modal-layout {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(260px, 1.4fr);
            gap: 24px;
            align-items: flex-start;
        }

        .image-modal-left {
            position: relative;
        }

        .image-modal-right {
            text-align: left;
            padding-right: 4px;
        }

        .image-modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .image-modal-category {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .image-modal-meta {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .image-modal-description {
            font-size: 0.9rem;
            color: #4b5563;
            line-height: 1.6;
            white-space: pre-line;
        }
        
        .form-label { display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
        
        .form-input { 
            width: 100%; 
            padding: 14px 18px; 
            border: 2px solid #e5e7eb; 
            border-radius: 10px; 
            background: #fff; 
            color: #111827; 
            font-size: 18px; 
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-input:focus { 
            outline: none; 
            border-color: #3b82f6; 
            background: #fff; 
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        .form-input::placeholder {
            color: #94a3b8;
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .file-input {
            position: relative;
            display: inline-block;
            cursor: pointer;
            width: 100%;
        }
        
        .file-input input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label { display: block; padding: 12px 16px; border: 2px dashed #cbd5e1; border-radius: 10px; background: #fff; color: #64748b; text-align: center; transition: all 0.3s ease; cursor: pointer; }
        
        .file-input-label:hover { border-color: #3b82f6; background: #f8fafc; }
        
        .modal-footer { 
            padding: 20px 30px; 
            background: #f8f9fa; 
            border-radius: 0 0 16px 16px; 
            display: flex; 
            justify-content: flex-end; 
            gap: 12px; 
            border-top: 1px solid #e5e7eb;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-secondary {
            background: #64748b;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #475569;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        
        .btn-download {
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
        }
        
        .btn-download:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .captcha-box {
            background: linear-gradient(135deg, #3b82f615 0%, #2563eb15 100%);
            padding: 24px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            margin: 20px 0;
        }
        
        .captcha-question {
            font-size: 28px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 16px;
            text-align: center;
            letter-spacing: 2px;
        }
        
        .rate-limit-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fbbf24;
            color: #92400e;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            
            .gallery-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-filter {
                justify-content: center;
            }
            
            .search-box input {
                width: 100%;
                max-width: 300px;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }
            
            .gallery-image {
                height: 220px;
            }
            
            .gallery-item {
                border-radius: 16px;
            }
            
            .gallery-info {
                padding: 1.5rem;
            }
            
            .gallery-title {
                font-size: 1.15rem;
            }
            
            .gallery-description {
                font-size: 0.95rem;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
            
            .modal-header {
                padding: 20px;
            }
            
            .modal-title {
                font-size: 18px;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .modal-footer {
                padding: 16px 20px;
                gap: 10px;
            }
            
            .btn {
                flex: 1;
                min-width: 100px;
            }
            
            .captcha-question {
                font-size: 24px;
            }
            
            .carousel-nav {
                padding: 10px 12px;
                font-size: 16px;
            }
            
            .carousel-prev {
                left: 10px;
            }
            
            .carousel-next {
                right: 10px;
            }
            
            .carousel-counter {
                font-size: 12px;
                padding: 6px 12px;
                bottom: 10px;
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
                    <img src="{{ secure_asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div class="brand-text">
                    <h1>SMKN 4</h1>
                    <p>Bogor</p>
                </div>
            </div>
            <div class="nav-menu">
                <ul class="nav-links">
                    <li><a href="{{ route('user.dashboard') }}">Beranda</a></li>
                    <li><a href="{{ route('user.gallery') }}" class="active">Galeri</a></li>
                    <li><a href="{{ route('user.informasi') }}">Informasi</a></li>
                    <li><a href="{{ route('user.agenda') }}">Agenda</a></li>
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
            <!-- Gallery Header -->
            <div class="gallery-header">
                <h1 class="gallery-title">Galeri Foto Kegiatan</h1>
                <p class="gallery-subtitle">Lihat dokumentasi kegiatan dan prestasi sekolah SMKN 4 BOGOR</p>
            </div>

            <!-- Gallery Controls -->
            <div class="gallery-controls">
                <div class="search-filter">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari foto...">
                    </div>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->judul }}">{{ $kategori->judul }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="galleryGrid">
                @forelse($galeri as $item)
                <div class="gallery-item" 
                     data-category="{{ $item->post->kategori->judul ?? 'Umum' }}" 
                     data-gallery-id="{{ $item->id }}"
                     @if($item->fotos->count() > 0)
                        data-foto-id="{{ $item->fotos->first()->id }}"
                        data-foto-file="{{ $item->fotos->first()->file }}"
                        data-foto-likes="{{ $item->fotos->first()->likes }}"
                        data-foto-dislikes="{{ $item->fotos->first()->dislikes ?? 0 }}"
                     @endif>
                    @if($item->fotos->count() > 0)
                        <img src="{{ secure_asset('uploads/galeri/' . $item->fotos->first()->file) }}" 
                             alt="{{ $item->post->judul ?? 'Gallery Image' }}" 
                             class="gallery-image"
                             onclick="openGalleryModal('{{ $item->id }}')">
                    @else
                        <div class="gallery-image" style="display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                            <i class="fas fa-image" style="font-size: 64px;"></i>
                        </div>
                    @endif
                    <div class="gallery-info">
                        <h3 class="gallery-title">{{ $item->post->judul ?? 'Untitled' }}</h3>
                        <div class="gallery-meta-row">
                            <span class="gallery-category">
                                <span class="category-tag-icon">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <span class="category-text">{{ $item->post->kategori->judul ?? 'Umum' }}</span>
                            </span>
                            <span class="gallery-meta-separator">•</span>
                            <span class="gallery-photo-count">
                                <i class="fas fa-image"></i> {{ $item->fotos->count() }} foto
                            </span>
                        </div>
                        <p class="gallery-description">{{ $item->post->isi ?? 'No description available' }}</p>
                        @if($item->fotos->count() > 0)
                            <?php $defaultTitle = "Gallery Image"; ?>
                            <div class="gallery-actions">
                                <button class="gallery-action-btn btn-like-action" data-type="like" data-foto-id="{{ $item->fotos->first()->id }}" onclick="event.stopPropagation(); toggleLike('{{ $item->fotos->first()->id }}', this)">
                                    <i class="fas fa-thumbs-up action-icon"></i>
                                    <span class="action-count">{{ $item->fotos->first()->likes }}</span>
                                </button>

                                <button class="gallery-action-btn btn-dislike-action" data-type="dislike" data-foto-id="{{ $item->fotos->first()->id }}" onclick="event.stopPropagation(); toggleDislike('{{ $item->fotos->first()->id }}', this)">
                                    <i class="fas fa-thumbs-down action-icon"></i>
                                    <span class="action-count">{{ $item->fotos->first()->dislikes ?? 0 }}</span>
                                </button>

                                <button class="gallery-action-btn btn-download-action" onclick="event.stopPropagation(); openDownloadModal('{{ $item->fotos->first()->file }}', '{{ addslashes($item->post->judul ?? $defaultTitle) }}')">
                                    <i class="fas fa-download action-icon"></i>
                                    <span>Download</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Hidden data for all photos -->
                    <div class="gallery-photos-data" style="display: none;">
                        @foreach($item->fotos as $foto)
                            <span data-photo="{{ secure_asset('uploads/galeri/' . $foto->file) }}"></span>
                        @endforeach
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #64748b;">
                    <i class="fas fa-images" style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;"></i>
                    <h3 style="font-size: 24px; margin-bottom: 10px; color: #1e293b;">Tidak ada galeri tersedia</h3>
                    <p style="font-size: 16px;">Belum ada galeri foto yang dapat ditampilkan saat ini.</p>
                    <p style="font-size: 14px; margin-top: 10px; color: #94a3b8;">Debug: Gallery count = {{ $galeri->count() }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>
    

    <!-- Image Preview Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content" style="max-width: 980px;">
            <div class="modal-header">
                <h2 class="modal-title" id="imageModalTitle" style="display:none;">Preview Foto</h2>
                <span class="close" onclick="closeModal('imageModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="image-modal-layout">
                    <div class="image-modal-left">
                        <div class="carousel-container">
                            <img id="imageModalImg" src="" alt="Preview" class="carousel-image">
                            <button class="carousel-nav carousel-prev" onclick="changeImage(-1)" id="prevBtn" style="display: none;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-nav carousel-next" onclick="changeImage(1)" id="nextBtn" style="display: none;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <div class="carousel-counter" id="imageCounter" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="image-modal-right">
                        <div class="image-modal-title" id="imageModalDetailTitle">Judul Foto</div>
                        <div class="image-modal-category" id="imageModalDetailCategory" style="display:none;">
                            <span class="category-tag-icon">
                                <i class="fas fa-tags"></i>
                            </span>
                            <span class="category-text" id="imageModalDetailCategoryText"></span>
                        </div>
                        <div class="image-modal-meta" id="imageModalDetailMeta" style="display:none;"></div>
                        <div class="image-modal-description" id="imageModalDetailDescription"></div>

                        <div class="gallery-actions" style="margin-top:16px;">
                            <button class="gallery-action-btn btn-like-action" id="modalLikeButton" data-type="like">
                                <i class="fas fa-thumbs-up action-icon"></i>
                                <span class="action-count" id="modalLikeCount">0</span>
                            </button>

                            <button class="gallery-action-btn btn-dislike-action" id="modalDislikeButton" data-type="dislike">
                                <i class="fas fa-thumbs-down action-icon"></i>
                                <span class="action-count" id="modalDislikeCount">0</span>
                            </button>

                            <button class="gallery-action-btn btn-download-action" id="modalDownloadButton">
                                <i class="fas fa-download action-icon"></i>
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal with Captcha -->
    <div id="downloadModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2 class="modal-title">Download Foto</h2>
                <span class="close" onclick="closeModal('downloadModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="rate-limit-info">
                    <i class="fas fa-info-circle"></i> Batas download: 5 file per jam
                </div>
                <p style="margin-bottom: 15px; color: #64748b;">
                    Untuk mengunduh foto, silakan jawab pertanyaan berikut:
                </p>
                <div class="captcha-box">
                    <div class="captcha-question" id="captchaQuestion">Loading...</div>
                    <input type="number" id="captchaAnswer" class="form-input" placeholder="Masukkan jawaban" style="text-align: center; font-size: 18px;">
                </div>
                <div id="captchaError" style="color: #ef4444; margin-top: 10px; display: none; padding: 12px; background: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444; font-weight: 500;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('downloadModal')">Batal</button>
                <button class="btn btn-success" onclick="verifyAndDownload()">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentCaptchaSession = null;
        let currentFilePath = null;
        let currentImages = [];
        let currentImageIndex = 0;

        function openGalleryModal(galleryId) {
            // Find gallery item
            const galleryItem = document.querySelector(`[data-gallery-id="${galleryId}"]`);
            if (!galleryItem) return;
            
            // Get title & description from card
            const titleEl = galleryItem.querySelector('.gallery-title');
            const categoryEl = galleryItem.querySelector('.gallery-category');
            const descriptionEl = galleryItem.querySelector('.gallery-description');
            const photosDataContainer = galleryItem.querySelector('.gallery-photos-data');

            const title = titleEl ? titleEl.textContent.trim() : 'Preview Foto';
            const category = categoryEl ? categoryEl.textContent.trim() : '';
            const description = descriptionEl ? descriptionEl.textContent.trim() : '';

            // Foto utama (pertama) untuk aksi like/dislike/download
            const mainFotoId = galleryItem.getAttribute('data-foto-id');
            const mainFotoFile = galleryItem.getAttribute('data-foto-file');
            const mainFotoLikes = parseInt(galleryItem.getAttribute('data-foto-likes') || '0', 10);
            const mainFotoDislikes = parseInt(galleryItem.getAttribute('data-foto-dislikes') || '0', 10);

            // Get all photos
            const photoElements = photosDataContainer ? photosDataContainer.querySelectorAll('span[data-photo]') : [];
            currentImages = Array.from(photoElements).map(el => el.getAttribute('data-photo'));
            currentImageIndex = 0;

            // Hitung jumlah foto
            const totalPhotos = currentImages.length;

            // Show modal
            document.getElementById('imageModalTitle').textContent = title;
            document.getElementById('imageModal').style.display = 'block';

            // Isi panel deskripsi di samping foto
            const detailTitleEl = document.getElementById('imageModalDetailTitle');
            const detailCategoryEl = document.getElementById('imageModalDetailCategory');
            const detailCategoryTextEl = document.getElementById('imageModalDetailCategoryText');
            const detailMetaEl = document.getElementById('imageModalDetailMeta');
            const detailDescEl = document.getElementById('imageModalDetailDescription');
            const modalLikeButton = document.getElementById('modalLikeButton');
            const modalDislikeButton = document.getElementById('modalDislikeButton');
            const modalDownloadButton = document.getElementById('modalDownloadButton');
            const modalLikeCount = document.getElementById('modalLikeCount');
            const modalDislikeCount = document.getElementById('modalDislikeCount');

            if (detailTitleEl) detailTitleEl.textContent = title;

            if (detailCategoryEl) {
                if (category) {
                    if (detailCategoryTextEl) {
                        detailCategoryTextEl.textContent = category;
                    }
                    detailCategoryEl.style.display = 'inline-block';
                } else {
                    detailCategoryEl.style.display = 'none';
                }
            }

            if (detailMetaEl) {
                if (totalPhotos > 0) {
                    detailMetaEl.textContent = totalPhotos + ' foto dalam galeri ini';
                    detailMetaEl.style.display = 'block';
                } else {
                    detailMetaEl.style.display = 'none';
                }
            }

            if (detailDescEl) {
                detailDescEl.textContent = description || 'Tidak ada deskripsi untuk galeri ini.';
            }

            // Sinkronkan tombol aksi di modal dengan foto utama galeri
            if (mainFotoId) {
                if (modalLikeButton && modalLikeCount) {
                    modalLikeButton.dataset.fotoId = mainFotoId;
                    modalLikeCount.textContent = isNaN(mainFotoLikes) ? 0 : mainFotoLikes;
                    modalLikeButton.classList.remove('liked');
                    modalLikeButton.onclick = function (event) {
                        event.stopPropagation();
                        toggleLike(mainFotoId, modalLikeButton);
                    };
                }

                if (modalDislikeButton && modalDislikeCount) {
                    modalDislikeButton.dataset.fotoId = mainFotoId;
                    modalDislikeCount.textContent = isNaN(mainFotoDislikes) ? 0 : mainFotoDislikes;
                    modalDislikeButton.classList.remove('disliked');
                    modalDislikeButton.onclick = function (event) {
                        event.stopPropagation();
                        toggleDislike(mainFotoId, modalDislikeButton);
                    };
                }

                if (modalDownloadButton && mainFotoFile) {
                    modalDownloadButton.onclick = function (event) {
                        event.stopPropagation();
                        openDownloadModal(mainFotoFile, title);
                    };
                }
            }
            
            // Display first image
            updateCarousel();
        }
        
        function updateCarousel() {
            if (currentImages.length === 0) return;
            
            // Update image
            document.getElementById('imageModalImg').src = currentImages[currentImageIndex];
            
            // Show/hide navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const counter = document.getElementById('imageCounter');
            
            if (currentImages.length > 1) {
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'block';
                counter.style.display = 'block';
                counter.textContent = `${currentImageIndex + 1} / ${currentImages.length}`;
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                counter.style.display = 'none';
            }
        }
        
        function changeImage(direction) {
            currentImageIndex += direction;
            
            // Loop around
            if (currentImageIndex < 0) {
                currentImageIndex = currentImages.length - 1;
            } else if (currentImageIndex >= currentImages.length) {
                currentImageIndex = 0;
            }
            
            updateCarousel();
        }
        
        async function openDownloadModal(filePath, fileName) {
            currentFilePath = filePath;
            document.getElementById('downloadModal').style.display = 'block';
            document.getElementById('captchaAnswer').value = '';
            document.getElementById('captchaError').style.display = 'none';
            
            // Generate captcha
            try {
                const response = await fetch("{{ route('download.captcha') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                document.getElementById('captchaQuestion').textContent = data.question;
                currentCaptchaSession = data.session_id;
            } catch (error) {
                console.error('Error generating captcha:', error);
                document.getElementById('captchaQuestion').textContent = 'Error loading captcha';
            }
        }
        
        async function verifyAndDownload() {
            const answer = document.getElementById('captchaAnswer').value;
            const errorDiv = document.getElementById('captchaError');
            const captchaBox = document.querySelector('.captcha-box');
            
            if (!answer) {
                errorDiv.textContent = 'Silakan jawab pertanyaan captcha';
                errorDiv.style.display = 'block';
                captchaBox.classList.add('error-shake');
                setTimeout(() => captchaBox.classList.remove('error-shake'), 400);
                return;
            }
            
            try {
                const response = await fetch("{{ route('download.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        captcha_answer: answer,
                        captcha_session: currentCaptchaSession,
                        file_path: currentFilePath
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Close modal first
                    closeModal('downloadModal');
                    
                    // Then redirect to download after a short delay to ensure modal is closed
                    setTimeout(() => {
                        window.location.href = `/download/${data.download_token}`;
                    }, 100);
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.style.display = 'block';
                    captchaBox.classList.add('error-shake');
                    setTimeout(() => captchaBox.classList.remove('error-shake'), 400);
                    // Refresh captcha
                    openDownloadModal(currentFilePath, '');
                }
            } catch (error) {
                console.error('Error verifying captcha:', error);
                errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                errorDiv.style.display = 'block';
                captchaBox.classList.add('error-shake');
                setTimeout(() => captchaBox.classList.remove('error-shake'), 400);
            }
        }
        
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
        
        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(modal => {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                    }
                });
            }
            
            // Arrow keys for image navigation
            if (document.getElementById('imageModal').style.display === 'block') {
                if (event.key === 'ArrowLeft') {
                    changeImage(-1);
                } else if (event.key === 'ArrowRight') {
                    changeImage(1);
                }
            }
        });
        
        // Allow Enter key to submit download
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && document.getElementById('downloadModal').style.display === 'block') {
                verifyAndDownload();
            }
        });
        
        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', function() {
            filterGallery();
        });
        
        document.getElementById('categoryFilter').addEventListener('change', function() {
            filterGallery();
        });
        
        function filterGallery() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            galleryItems.forEach(item => {
                const title = item.querySelector('.gallery-title').textContent.toLowerCase();
                const categorySpan = item.querySelector('.gallery-category');
                const category = categorySpan ? categorySpan.textContent.trim() : '';
                
                const matchesSearch = title.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                
                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show count of filtered items
            const visibleItems = document.querySelectorAll('.gallery-item[style*="block"]').length;
            console.log(`Showing ${visibleItems} items for category: ${categoryFilter || 'All'}`);
        }
        
        // View Toggle
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                const grid = document.getElementById('galleryGrid');
                
                if (view === 'list') {
                    grid.classList.add('list-view');
                } else {
                    grid.classList.remove('list-view');
                }
            });
        });
        
        // Halaman galeri kini read-only; tidak ada form CRUD
        
        function showNotification(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'toast';
            if (type === 'error') toast.classList.add('toast-error');
            if (type === 'success') toast.classList.add('toast-success');

            const iconSpan = document.createElement('span');
            iconSpan.className = 'toast-icon';
            iconSpan.innerHTML = type === 'error' ? '<i class="fas fa-exclamation-circle text-red-500"></i>' : '<i class="fas fa-info-circle text-blue-500"></i>';

            const msgDiv = document.createElement('div');
            msgDiv.className = 'toast-message';
            msgDiv.textContent = message;

            const closeBtn = document.createElement('button');
            closeBtn.className = 'toast-close';
            closeBtn.innerHTML = '&times;';
            closeBtn.onclick = () => container.removeChild(toast);

            toast.appendChild(iconSpan);
            toast.appendChild(msgDiv);
            toast.appendChild(closeBtn);

            container.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement === container) {
                    container.removeChild(toast);
                }
            }, 3000);
        }

        // Like/Dislike Functionality
        async function toggleLike(fotoId, button) {
            try {
                const response = await fetch(`/foto/${fotoId}/toggle-like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.status === 401) {
                    const errorData = await response.json().catch(() => ({}));
                    showNotification(errorData.message || 'Silakan login terlebih dahulu untuk memberi like.', 'error');
                    return;
                }

                const data = await response.json();
                
                if (data.success) {
                    // Update semua tombol LIKE untuk foto ini (card & modal)
                    document.querySelectorAll('.btn-like-action[data-foto-id="' + fotoId + '"]').forEach(function (btn) {
                        const likeCountSpan = btn.querySelector('.action-count');
                        if (likeCountSpan) {
                            likeCountSpan.textContent = data.likes;
                        }

                        if (data.liked) {
                            btn.classList.add('liked');
                        } else {
                            btn.classList.remove('liked');
                        }
                    });

                    // Update semua tombol DISLIKE untuk foto ini (card & modal)
                    document.querySelectorAll('.btn-dislike-action[data-foto-id="' + fotoId + '"]').forEach(function (btn) {
                        const dislikeCountSpan = btn.querySelector('.action-count');
                        if (dislikeCountSpan && typeof data.dislikes !== 'undefined') {
                            dislikeCountSpan.textContent = data.dislikes;
                        }
                        if (data.liked) {
                            btn.classList.remove('disliked');
                        }
                    });
                } else {
                    showNotification('Gagal memperbarui like: ' + (data.message || 'Terjadi kesalahan.'), 'error');
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        }

        async function toggleDislike(fotoId, button) {
            try {
                const response = await fetch(`/foto/${fotoId}/toggle-dislike`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.status === 401) {
                    const errorData = await response.json().catch(() => ({}));
                    showNotification(errorData.message || 'Silakan login terlebih dahulu untuk memberi dislike.', 'error');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    // Update semua tombol DISLIKE untuk foto ini (card & modal)
                    document.querySelectorAll('.btn-dislike-action[data-foto-id="' + fotoId + '"]').forEach(function (btn) {
                        const dislikeCountSpan = btn.querySelector('.action-count');
                        if (dislikeCountSpan) {
                            dislikeCountSpan.textContent = data.dislikes;
                        }

                        if (data.disliked) {
                            btn.classList.add('disliked');
                        } else {
                            btn.classList.remove('disliked');
                        }
                    });

                    // Update semua tombol LIKE untuk foto ini (card & modal)
                    document.querySelectorAll('.btn-like-action[data-foto-id="' + fotoId + '"]').forEach(function (btn) {
                        const likeCountSpan = btn.querySelector('.action-count');
                        if (likeCountSpan && typeof data.likes !== 'undefined') {
                            likeCountSpan.textContent = data.likes;
                        }
                        if (data.disliked) {
                            btn.classList.remove('liked');
                        }
                    });
                } else {
                    showNotification('Gagal memperbarui dislike: ' + (data.message || 'Terjadi kesalahan.'), 'error');
                }
            } catch (error) {
                console.error('Error toggling dislike:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        }
    </script>
</body>
</html>

