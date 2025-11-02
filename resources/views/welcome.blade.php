<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Université Sidi Mohamed Ben Abdellah - Plateforme de gestion</title>
    <meta name="description" content="Plateforme de gestion des enseignements et emplois du temps de la Faculté des Lettres et des Sciences Humaines Dhar El Mahraz">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d2b56;
            --primary-light: #1a3a6b;
            --primary-dark: #051a38;
            --secondary-color: #8B0000;
            --secondary-light: #A52A2A;
            --secondary-dark: #660000;
            --accent-color: #2ecc71;
            --accent-light: #4cdb87;
            --light-bg: #ffffff;
            --light-card: #f8f9fa;
            --dark-color: #2c3e50;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --red-glow: rgba(139, 0, 0, 0.3);
            --blue-glow: rgba(13, 43, 86, 0.2);
            --transition-fast: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-medium: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            --radius-lg: 25px;
            --radius-sm: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #ffffff 100%);
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
            position: relative;
            min-height: 100vh;
        }

        /* ============ ANIMATED BACKGROUND ELEMENTS ============ */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            animation: float 25s infinite ease-in-out;
            filter: blur(1px);
        }

        .shape-1 {
            width: 120px;
            height: 120px;
            background: var(--primary-color);
            top: 10%;
            left: 5%;
            animation-delay: 0s;
            animation-duration: 25s;
        }

        .shape-2 {
            width: 180px;
            height: 180px;
            background: var(--secondary-color);
            top: 70%;
            left: 80%;
            animation-delay: 3s;
            animation-duration: 30s;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            background: var(--accent-color);
            top: 40%;
            left: 90%;
            animation-delay: 7s;
            animation-duration: 22s;
        }

        .shape-4 {
            width: 150px;
            height: 150px;
            background: var(--primary-light);
            top: 80%;
            left: 10%;
            animation-delay: 5s;
            animation-duration: 28s;
        }

        .shape-5 {
            width: 80px;
            height: 80px;
            background: var(--secondary-light);
            top: 20%;
            left: 70%;
            animation-delay: 10s;
            animation-duration: 20s;
        }

        .shape-6 {
            width: 130px;
            height: 130px;
            background: var(--accent-light);
            top: 60%;
            left: 20%;
            animation-delay: 8s;
            animation-duration: 26s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            25% {
                transform: translate(30px, 40px) rotate(90deg) scale(1.05);
            }
            50% {
                transform: translate(-20px, 60px) rotate(180deg) scale(0.95);
            }
            75% {
                transform: translate(-40px, 20px) rotate(270deg) scale(1.02);
            }
        }

        /* ============ PARTICLES EFFECT ============ */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 50%;
            opacity: 0.2;
            animation: particleFloat 12s infinite linear;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.2;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* ============ LOADER ============ */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--light-bg), #e9ecef);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            text-align: center;
            color: var(--text-dark);
        }

        .university-logo-loader {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseGlow 2s ease-in-out infinite;
            box-shadow: 0 0 20px var(--red-glow);
        }

        .university-logo-loader i {
            font-size: 2.5rem;
            color: white;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px var(--red-glow); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px var(--red-glow); }
        }

        .loader-text {
            font-size: 1rem;
            font-weight: 300;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-dark);
            animation: fadeInOut 2s ease-in-out infinite;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        .loader-progress {
            width: 150px;
            height: 3px;
            background: rgba(13, 43, 86, 0.2);
            border-radius: 10px;
            margin: 0.8rem auto 0;
            overflow: hidden;
        }

        .loader-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            border-radius: 10px;
            animation: loading 2s ease-in-out infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0); }
            100% { transform: translateX(100%); }
        }

        /* ============ HEADER ============ */
        .header {
            background: linear-gradient(135deg, 
                rgba(13, 43, 86, 0.95) 0%, 
                rgba(2, 29, 58, 0.98) 50%,
                rgba(13, 43, 86, 0.95) 100%);
            color: white;
            text-align: center;
            padding: 3rem 1rem 2.5rem;
            position: relative;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-bottom: 3px solid rgba(139, 0, 0, 0.4);
            z-index: 10;
            overflow: hidden;
            animation: slideDown 1s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(139, 0, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(13, 43, 86, 0.2) 0%, transparent 50%);
            opacity: 0.6;
            z-index: -1;
        }

        .header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--secondary-color), 
                #DC143C, 
                var(--secondary-color), 
                transparent);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }

        @keyframes gradientMove {
            0% { background-position: -200% 50%; }
            100% { background-position: 200% 50%; }
        }

        .header-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

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

        .header h3 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .header h4 {
            font-weight: 600;
            font-size: 1.3rem;
            margin-top: 0.8rem;
            text-shadow: 0 1px 5px rgba(0,0,0,0.2);
            color: rgba(255,255,255,0.95);
        }

        .header p {
            opacity: 0.9;
            font-size: 0.95rem;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
            margin: 0.3rem 0;
        }

        /* ============ MAIN CONTENT ============ */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 10;
        }

        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 3rem;
            align-items: center;
            justify-content: space-between;
        }

        .left-content {
            flex: 1;
            min-width: 300px;
            animation: fadeInLeft 1s ease-out 0.5s both;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .right-content {
            flex: 1;
            display: flex;
            justify-content: center;
            min-width: 300px;
            animation: fadeInRight 1s ease-out 0.5s both;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .title {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--dark-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            position: relative;
            line-height: 1.3;
            font-family: 'Playfair Display', serif;
        }

        .title::after {
            content: "";
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), #DC143C, var(--secondary-color));
            border-radius: 4px;
            margin-top: 10px;
            box-shadow: 0 0 10px var(--red-glow);
            animation: expandWidth 1.5s ease-out 1s both;
        }

        @keyframes expandWidth {
            from { width: 0; }
            to { width: 80px; }
        }

        .sub-title {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 95%;
            font-weight: 400;
            line-height: 1.6;
            animation: fadeIn 1s ease-out 0.8s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ============ BUTTONS ============ */
        .buttons-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
            animation: fadeInUp 1s ease-out 1.6s both;
        }

        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 5px 15px rgba(13, 43, 86, 0.3);
            transition: all var(--transition-fast);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.7s;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 43, 86, 0.4);
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--secondary-light), var(--secondary-color));
            box-shadow: 0 8px 20px rgba(139, 0, 0, 0.4);
        }

        /* ============ UNIVERSITY LOGO CARD ============ */
        .university-logo-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all var(--transition-fast);
            width: 380px;
            height: 380px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid rgba(13, 43, 86, 0.1);
            position: relative;
            overflow: hidden;
        }

        .university-logo-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
        }

        .university-logo-card:hover::before {
            transform: rotate(45deg) translate(50%, 50%);
        }

        .university-logo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-color: var(--secondary-color);
        }

        .logo-image {
            width: 220px;
            height: 220px;
            object-fit: contain;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            transition: all var(--transition-fast);
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
            animation: bounceIn 1s ease-out 0.7s both;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .university-logo-card:hover .logo-image {
            transform: scale(1.05);
        }

        .logo-text h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Playfair Display', serif;
        }

        .logo-text p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* ============ FOOTER ============ */
        footer {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 1rem 1.5rem;
            margin-top: 4rem;
            position: relative;
            border-top: 3px solid rgba(139, 0, 0, 0.4);
            animation: fadeIn 1s ease-out 1.8s both;
        }

        footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                var(--secondary-color), 
                #DC143C, 
                var(--secondary-color), 
                transparent);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2rem;
            max-width: 1200px;
            margin: auto;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
            animation: fadeInUp 0.8s ease-out both;
        }

        .footer-section:nth-child(1) { animation-delay: 2s; }
        .footer-section:nth-child(2) { animation-delay: 2.2s; }

        .footer-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            color: white;
            font-family: 'Playfair Display', serif;
        }

        .footer-section h3::after {
            content: "";
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--secondary-color), transparent);
            position: absolute;
            left: 0;
            bottom: -5px;
            border-radius: 3px;
            animation: expandWidth 1s ease-out 2.5s both;
        }

        .footer-section p {
            color: rgba(255,255,255,0.9);
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .footer-contact div {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            padding: 0.4rem;
            border-radius: 6px;
            transition: all var(--transition-fast);
        }

        .footer-contact div:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .footer-contact i {
            color: var(--secondary-color);
            margin-right: 10px;
            width: 16px;
            font-size: 1rem;
            transition: all var(--transition-fast);
        }

        .footer-contact div:hover i {
            transform: scale(1.1);
            color: #DC143C;
        }

        .footer-contact span {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }

        .social-links {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all var(--transition-fast);
            border: 1px solid rgba(255,255,255,0.2);
            animation: fadeIn 0.5s ease-out both;
            position: relative;
            overflow: hidden;
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .social-link:hover::before {
            left: 100%;
        }

        .social-link:nth-child(1) { animation-delay: 2.4s; }
        .social-link:nth-child(2) { animation-delay: 2.5s; }
        .social-link:nth-child(3) { animation-delay: 2.6s; }
        .social-link:nth-child(4) { animation-delay: 2.7s; }

        .social-link:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(139, 0, 0, 0.4);
        }

        .footer-bottom {
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: 2rem;
            padding-top: 1.5rem;
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeIn 1s ease-out 2.8s both;
        }

        /* ============ RESPONSIVE DESIGN ============ */
        @media (max-width: 992px) {
            .content-wrapper {
                flex-direction: column;
                text-align: center;
                gap: 2.5rem;
            }
            .title::after {
                margin: 10px auto 0;
            }
            .university-logo-card {
                width: 340px;
                height: 340px;
            }
        }

        @media (max-width: 768px) {
            .title { font-size: 1.8rem; }
            .sub-title { 
                font-size: 1rem;
                white-space: normal;
                animation: fadeIn 1s ease-out 0.8s both;
            }
            .btn-custom { 
                width: 100%; 
                justify-content: center; 
                padding: 0.9rem 1.5rem;
            }
            .university-logo-card { 
                padding: 1.5rem; 
                width: 300px;
                height: 300px;
            }
            .main-container { padding: 2rem 1rem; }
            .header { padding: 2rem 1rem 1.5rem; }
            .header h3 { font-size: 1.4rem; }
            .header h4 { font-size: 1.1rem; }
            .logo-image {
                width: 180px;
                height: 180px;
            }
        }

        @media (max-width: 576px) {
            .header h3 { font-size: 1.2rem; }
            .header h4 { font-size: 1rem; }
            .title { font-size: 1.5rem; }
            .header { padding: 1.5rem 1rem 1rem; }
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            .footer-section h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            .university-logo-card {
                width: 260px;
                height: 260px;
                padding: 1rem;
            }
            .logo-image {
                width: 150px;
                height: 150px;
            }
            .logo-text h3 {
                font-size: 1.4rem;
            }
            .logo-text p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>

    <!-- Particles Effect -->
    <div class="particles-container" id="particles"></div>

    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="loader-content">
            <div class="university-logo-loader">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="loader-text">Chargement...</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- En-tête -->
    <header class="header">
        <div class="header-content">
            <h3>جامعة سيدي محمد بن عبد الله بفاس</h3>
            <h4>كلية الآداب والعلوم الإنسانية - ظهر المهرار</h4>
            <p>Faculté des Lettres et des Sciences Humaines Dhar El Mahraz</p>
            <p>Université Sidi Mohamed Ben Abdellah de Fès</p>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main-container">
        <div class="content-wrapper">
            <div class="left-content">
                <h1 class="title">Plateforme de gestion des enseignements et emplois du temps</h1>
                <p class="sub-title">Optimisez la planification de vos cours grâce à une gestion moderne, intuitive et performante.</p>

                <div class="buttons-container">
                    <a href="{{ route('login') }}" class="btn-custom">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </a>
                    <a href="{{ route('emplois.consulter1') }}" class="btn-custom btn-secondary">
                        <i class="fas fa-calendar-alt"></i> Consulter emploi
                    </a>
                </div>
            </div>

            <div class="right-content">
                <div class="university-logo-card">
                    <img src="/images/logo.png" alt="Logo Université Sidi Mohamed Ben Abdellah" class="logo-image">
                    <div class="logo-text">
                        <h3>USMBA</h3>
                        <p>Université Sidi Mohamed Ben Abdellah</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>Plateforme de gestion des emplois du temps de la Faculté des Lettres et des Sciences Humaines Dhar El Mahraz, Université Sidi Mohamed Ben Abdellah de Fès.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Contact</h3>
                <div class="footer-contact">
                    <div>
                        <i class="fas fa-map-marker-alt"></i> 
                        <span>Dhar El Mahraz, Fès, Maroc</span>
                    </div>
                    <div>
                        <i class="fas fa-phone"></i> 
                        <span>+212 5 35 00 00 00</span>
                    </div>
                    <div>
                        <i class="fas fa-envelope"></i> 
                        <span>contact@usmba.ac.ma</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; 2025 Université Sidi Mohamed Ben Abdellah de Fès. Tous droits réservés.
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('hidden');
            }, 1500);
        });

        // Particles Effect
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 25;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random properties
                const size = Math.random() * 3 + 1;
                const left = Math.random() * 100;
                const animationDuration = Math.random() * 10 + 8;
                const animationDelay = Math.random() * 5;
                const color = Math.random() > 0.5 ? 'var(--primary-color)' : 'var(--secondary-color)';
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${left}%`;
                particle.style.background = color;
                particle.style.animationDuration = `${animationDuration}s`;
                particle.style.animationDelay = `${animationDelay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
        });
    </script>
</body>
</html>