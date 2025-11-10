@extends('layouts.app')

@section('title', 'FACIGA 2025 - Accueil')

@section('content')
<style>
    /* Masquer uniquement la navigation horizontale du layout principal */
    body > header:not(.contact-header),
    body > nav:not(.menu-nav),
    .navbar:not(.slide-menu *),
    .main-nav,
    .navigation:not(.slide-menu *),
    .header-menu:not(.slide-menu *),
    .menu-horizontal {
        display: none !important;
    }
    
    /* Contact Header Styles */
    .contact-header {
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        padding: 15px 0;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .contact-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .contact-info-item i {
        font-size: 16px;
    }
    
    @media (max-width: 768px) {
        .contact-info-item {
            font-size: 12px;
        }
    }

    /* Mobile Menu Styles */
    .mobile-menu-toggle {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 1001;
        background: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .mobile-menu-toggle:hover {
        transform: scale(1.05);
        background: var(--blue-gabon);
        color: white;
    }
    
    .mobile-menu-toggle:hover .hamburger-icon span {
        background: white;
    }
    
    .menu-text {
        font-weight: 600;
        color: var(--blue-gabon);
        transition: color 0.3s;
    }
    
    .mobile-menu-toggle:hover .menu-text {
        color: white;
    }
    
    .hamburger-icon {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .hamburger-icon span {
        width: 25px;
        height: 3px;
        background: var(--blue-gabon);
        border-radius: 2px;
        transition: all 0.3s;
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .slide-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 380px;
        height: 100vh;
        background: white;
        z-index: 2000;
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: -5px 0 20px rgba(0,0,0,0.3);
        overflow-y: auto;
    }
    
    .slide-menu.active {
        right: 0;
    }
    
    .menu-header {
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        padding: 30px 20px;
        color: white;
        position: relative;
    }
    
    .menu-close {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .menu-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }
    
    .menu-logo {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .menu-subtitle {
        font-size: 14px;
        opacity: 0.9;
    }
    
    .menu-nav {
        padding: 20px 0;
    }
    
    .nav-item {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .nav-link-mobile {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .nav-link-mobile:hover {
        background: linear-gradient(90deg, rgba(58, 117, 196, 0.1), transparent);
        padding-left: 30px;
        color: var(--blue-gabon);
    }
    
    .nav-link-mobile i {
        width: 30px;
        font-size: 20px;
        color: var(--blue-gabon);
        margin-right: 15px;
    }
    
    .menu-contact {
        padding: 20px;
        background: #f8f9fa;
    }
    
    .contact-title {
        font-weight: bold;
        color: var(--blue-gabon);
        margin-bottom: 15px;
        font-size: 16px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .contact-item i {
        width: 25px;
        font-size: 18px;
        color: var(--green-gabon);
        margin-right: 10px;
        margin-top: 2px;
    }
    
    .menu-social {
        padding: 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .social-links {
        display: flex;
        gap: 15px;
        justify-content: center;
    }
    
    .social-link {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--blue-gabon);
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .social-link:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(58, 117, 196, 0.3);
    }

    /* Hero Carousel Styles */
    .hero-carousel {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        margin-top: 0;
    }
    
    .carousel-item {
        height: 100vh;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        padding-top: 60px;
    }
    
    .carousel-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(58, 117, 196, 0.4), rgba(0, 158, 73, 0.3));
        animation: gradientShift 15s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% {
            background: linear-gradient(135deg, rgba(58, 117, 196, 0.4), rgba(0, 158, 73, 0.3));
        }
        50% {
            background: linear-gradient(135deg, rgba(0, 158, 73, 0.3), rgba(58, 117, 196, 0.4));
        }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s ease-out;
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
    
    .countdown-timer {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: bounceIn 1s ease-out 0.5s both;
    }
    
    @keyframes bounceIn {
        from {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            transform: scale(1.05);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .count-box {
        text-align: center;
        padding: 15px;
    }
    
    .count-number {
        font-size: 3rem;
        font-weight: bold;
        color: var(--blue-gabon);
        line-height: 1;
    }
    
    .stats-counter {
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        color: white;
        padding: 80px 0;
    }
    
    .counter-box h1 {
        font-size: 4rem;
        font-weight: bold;
        color: white;
    }
    
    .feature-box {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .feature-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: var(--blue-gabon);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }
    
    .wave-divider {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }
    
    .wave-divider svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 100px;
    }
    
    .video-section {
        position: relative;
        background: linear-gradient(135deg, rgba(58, 117, 196, 0.05), rgba(0, 158, 73, 0.05));
        padding: 100px 0;
    }
    
    .video-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .video-overlay:hover {
        background: rgba(0,0,0,0.5);
    }
    
    .play-button {
        width: 100px;
        height: 100px;
        background: var(--blue-gabon);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        transition: all 0.3s;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(58, 117, 196, 0.7);
        }
        70% {
            box-shadow: 0 0 0 40px rgba(58, 117, 196, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(58, 117, 196, 0);
        }
    }
    
    .sector-card {
        transition: all 0.3s;
        border-left: 4px solid var(--blue-gabon);
        padding: 20px;
        background: white;
        border-radius: 8px;
    }
    
    .sector-card:hover {
        transform: translateX(10px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    /* Timeline Horizontal 2 Lignes Styles */
    .timeline-container {
        position: relative;
        padding: 40px 0;
    }
    
    .timeline-day {
        margin-bottom: 80px;
        position: relative;
    }
    
    .timeline-day:last-child {
        margin-bottom: 0;
    }
    
    .timeline-line {
        position: absolute;
        left: 0;
        right: 0;
        top: 80px;
        height: 3px;
        background: linear-gradient(to right, var(--blue-gabon), var(--green-gabon));
    }
    
    .day-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .timeline-date {
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        padding: 12px 25px;
        border-radius: 30px;
        font-weight: bold;
        color: white;
        display: inline-block;
        font-size: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    .timeline-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        position: relative;
    }
    
    @media (min-width: 992px) {
        .timeline-wrapper {
            grid-template-columns: repeat(5, 1fr);
        }
        
        .timeline-wrapper.day-2 {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .timeline-item {
        position: relative;
    }
    
    .event-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        transition: all 0.3s;
        height: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .event-card::before {
        content: '';
        position: absolute;
        top: -32px;
        left: 50%;
        transform: translateX(-50%);
        width: 15px;
        height: 15px;
        background: var(--blue-gabon);
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px var(--blue-gabon);
    }
    
    .timeline-day:nth-child(2) .event-card::before {
        background: var(--green-gabon);
        box-shadow: 0 0 0 3px var(--green-gabon);
    }
    
    .event-time {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        color: white;
        border-radius: 50%;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .event-title {
        color: var(--blue-gabon);
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
    
    .event-description {
        color: #666;
        margin: 0;
        font-size: 14px;
    }
    
    .event-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 15px;
    }
    
    .timeline-day:nth-child(2) .event-icon {
        background: linear-gradient(135deg, var(--green-gabon), var(--orange-ci));
    }
    
    @media (max-width: 768px) {
        .timeline-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Contact Header Band -->
<div class="contact-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-4">
                    <div class="contact-info-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Libreville, Gabon</span>
                    </div>
                    <div class="contact-info-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>+241 (0)74 58 24 24</span>
                    </div>
                    <div class="contact-info-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>event@investingabon.ga</span>
                    </div>
                    <div class="contact-info-item">
                        <i class="bi bi-calendar-event"></i>
                        <span>09-10 Octobre 2025</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="https://www.facebook.com/investingabon" target="_blank" class="text-white"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.linkedin.com/company/invest-in-gabon" class="text-white ms-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" target="_blank" class="text-white ms-2"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-white ms-2"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Toggle Button -->
<button class="mobile-menu-toggle" onclick="toggleMenu()">
    <div class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <span class="menu-text">Menu</span>
</button>

<!-- Modal Overlay -->
<div class="modal-overlay" id="modalOverlay" onclick="closeMenu()"></div>

<!-- Slide Menu -->
<div class="slide-menu" id="slideMenu">
    <div class="menu-header">
        <button class="menu-close" onclick="closeMenu()">
            <i class="bi bi-x"></i>
        </button>
        <div class="menu-logo">FACIGA 2025</div>
        <div class="menu-subtitle">Forum d'Affaires Côte d'Ivoire - Gabon</div>
    </div>

    <nav class="menu-nav">
        <div class="nav-item">
            <a href="/" class="nav-link-mobile">
                <i class="bi bi-house-door"></i>
                <span>Accueil</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#apropos" class="nav-link-mobile">
                <i class="bi bi-info-circle"></i>
                <span>À propos</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#programme" class="nav-link-mobile">
                <i class="bi bi-calendar-event"></i>
                <span>Programme</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#intervenants" class="nav-link-mobile">
                <i class="bi bi-people"></i>
                <span>Intervenants</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#secteurs" class="nav-link-mobile">
                <i class="bi bi-briefcase"></i>
                <span>Secteurs</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('inscription') }}" class="nav-link-mobile">
                <i class="bi bi-ticket-perforated"></i>
                <span>Inscription</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('contact') }}" class="nav-link-mobile">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </div>
    </nav>

    <div class="menu-contact">
        <div class="contact-title">
            <i class="bi bi-telephone me-2"></i>
            Informations de Contact
        </div>
        <div class="contact-item">
            <i class="bi bi-geo-alt-fill"></i>
            <div>
                <strong>Lieu</strong><br>
                Radisson Blu, Libreville, Gabon
            </div>
        </div>
        <div class="contact-item">
            <i class="bi bi-phone-fill"></i>
            <div>
                <strong>Téléphone</strong><br>
                +241 (0)74 58 24 24
            </div>
        </div>
        <div class="contact-item">
            <i class="bi bi-envelope-fill"></i>
            <div>
                <strong>Email</strong><br>
                event@investingabon.ga
            </div>
        </div>
        <div class="contact-item">
            <i class="bi bi-calendar3"></i>
            <div>
                <strong>Date</strong><br>
                09-10 Octobre 2025
            </div>
        </div>
    </div>

    <div class="menu-social">
        <div class="social-links">
            <a href="#" class="social-link">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="social-link">
                <i class="bi bi-twitter"></i>
            </a>
            <a href="#" class="social-link">
                <i class="bi bi-linkedin"></i>
            </a>
            <a href="#" class="social-link">
                <i class="bi bi-instagram"></i>
            </a>
        </div>
    </div>
</div>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('images/faciga-banner.jpg') }}');">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="countdown-timer mt-4">
                            <div class="row text-center">
                                <div class="col-3">
                                    <div class="count-box">
                                        <div class="count-number" id="days">00</div>
                                        <small class="text-muted">Jours</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="count-box">
                                        <div class="count-number" id="hours">00</div>
                                        <small class="text-muted">Heures</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="count-box">
                                        <div class="count-number" id="minutes">00</div>
                                        <small class="text-muted">Minutes</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="count-box">
                                        <div class="count-number" id="seconds">00</div>
                                        <small class="text-muted">Secondes</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2">
                        <div class="hero-content text-white">
                            <p class="mb-3" style="color: var(--orange-ci); font-size: 1.2rem;">ÉVÉNEMENT 2025</p>
                            <h1 class="display-2 fw-bold mb-3">FORUM FACIGA</h1>
                            <h2 class="h3 mb-4">Côte d'Ivoire - Gabon</h2>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-calendar-event me-2"></i> 09-10 Octobre 2025</li>
                                <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Radisson Blu, Libreville</li>
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('inscription') }}" class="btn btn-lg px-5 py-3 me-3" style="background-color: var(--blue-gabon); color: white; border: none;">
                                    INSCRIPTION
                                </a>
                                <a href="#programme" class="btn btn-outline-light btn-lg px-5 py-3">
                                    VOIR LE PROGRAMME
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="carousel-item" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('images/faciga-banner.jpg') }}');">
            <div class="container h-100 d-flex align-items-center">
                <div class="hero-content text-center w-100 text-white">
                    <h1 class="display-3 fw-bold mb-4">Coopération Sud-Sud</h1>
                    <h2 class="h3 mb-4">Partenariats Stratégiques & Durables</h2>
                    <p class="lead mb-5">Plus de 200 opérateurs économiques | Rencontres B2B & B2G</p>
                    <a href="{{ route('inscription') }}" class="btn btn-light btn-lg px-5 py-3">
                        Rejoignez-nous
                    </a>
                </div>
            </div>
        </div>
        
        <div class="carousel-item" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('images/faciga-banner.jpg') }}');">
            <div class="container h-100 d-flex align-items-center">
                <div class="hero-content text-center w-100 text-white">
                    <h1 class="display-3 fw-bold mb-4">12 Secteurs Prioritaires</h1>
                    <h2 class="h3 mb-4">Opportunités d'Investissement Exceptionnelles</h2>
                    <p class="lead mb-5">Agro-industrie | Mines | Infrastructures | Technologies</p>
                    <a href="#secteurs" class="btn btn-light btn-lg px-5 py-3">
                        Découvrir les opportunités
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Stats Counter Section -->
<div class="stats-counter">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-box border-end border-white border-opacity-25">
                    <h1 class="mb-2">300<span>+</span></h1>
                    <p class="text-white">Participants attendus</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-box border-end border-white border-opacity-25">
                    <h1 class="mb-2">12</h1>
                    <p class="text-white">Secteurs prioritaires</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-box border-end border-white border-opacity-25">
                    <h1 class="mb-2">50<span>+</span></h1>
                    <p class="text-white">Décideurs & Gouv</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-box">
                    <h1 class="mb-2">2</h1>
                    <p class="text-white">Jours d'échanges</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<section id="apropos" class="py-5 my-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4">
                <p class="mb-2" style="color: var(--blue-gabon); font-weight: 600;">À PROPOS</p>
                <h2 class="mb-4" style="color: var(--blue-gabon);">Un tournant historique dans la <span style="color: var(--orange-ci);">coopération économique</span></h2>
                <p class="lead">Le FACIGA 2025 marque un moment décisif dans les relations économiques entre la Côte d'Ivoire et le Gabon.</p>
                <p>Organisé conjointement par le CEPICI et l'ANPI-Gabon, ce forum vise à poser les bases d'une coopération économique et commerciale profitable aux deux nations, dans le cadre de la consolidation des relations d'amitiés depuis 1973.</p>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-md-6">
                        <img src="{{ asset('images/group/1.jpg') }}" alt="FACIGA" class="img-fluid rounded shadow">
                    </div>
                    <div class="col-md-6 mt-md-5">
                        <img src="{{ asset('images/group/2.jpg') }}" alt="FACIGA" class="img-fluid rounded shadow mb-3">
                        <img src="{{ asset('images/group/3.jpg') }}" alt="FACIGA" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="feature-box text-center p-4 h-100 bg-white rounded shadow-sm">
                    <div class="feature-icon">
                        <i class="bi bi-handshake"></i>
                    </div>
                    <h5 style="color: var(--blue-gabon);">Partenariats Durables</h5>
                    <p class="text-muted">Créez des alliances stratégiques avec des entreprises gabonaises et ivoiriennes de premier plan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box text-center p-4 h-100 bg-white rounded shadow-sm">
                    <div class="feature-icon" style="background-color: var(--green-gabon);">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h5 style="color: var(--blue-gabon);">Opportunités d'Investissement</h5>
                    <p class="text-muted">Identifiez des projets porteurs dans 12 secteurs prioritaires à fort potentiel</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box text-center p-4 h-100 bg-white rounded shadow-sm">
                    <div class="feature-icon" style="background-color: var(--orange-ci);">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 style="color: var(--blue-gabon);">Networking Premium</h5>
                    <p class="text-muted">Rencontrez décideurs politiques, chefs d'entreprises et investisseurs influents</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Section -->
<section class="video-section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="mb-2" style="color: var(--blue-gabon); font-weight: 600;">VIDÉO PROMOTIONNELLE</p>
            <h2 style="color: var(--blue-gabon);">Découvrez le <span style="color: var(--orange-ci);">FACIGA 2025</span></h2>
            <p class="text-muted">Une vision unique de la coopération économique Côte d'Ivoire - Gabon</p>
        </div>
        
        <div class="video-container">
            <img src="{{ asset('images/faciga-banner.jpg') }}" alt="FACIGA Video" class="img-fluid">
            <div class="video-overlay" onclick="this.style.display='none'; document.getElementById('facigaVideo').play();">
                <div class="play-button">
                    <i class="bi bi-play-fill"></i>
                </div>
            </div>
            <video id="facigaVideo" class="w-100" controls style="display: none;">
                <source src="{{ asset('videos/faciga-promo.mp4') }}" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>
    </div>
</section>

<!-- Programme Section -->
<section id="programme" class="py-5" style="background: linear-gradient(135deg, rgba(58, 117, 196, 0.05), rgba(0, 158, 73, 0.05));">
    <div class="container">
        <div class="text-center mb-5">
            <p class="mb-2" style="color: var(--blue-gabon); font-weight: 600;">PROGRAMME</p>
            <h2 style="color: var(--blue-gabon);">Planning de l'<span style="color: var(--orange-ci);">événement</span></h2>
            <p class="text-muted">Un parcours de 2 jours pour renforcer la coopération économique</p>
        </div>
        
        <div class="timeline-container">
            <!-- JOUR 1 -->
            <div class="timeline-day">
                <div class="day-header">
                    <div class="timeline-date">
                        <i class="bi bi-calendar-check me-2"></i>
                        Jour 1 - 09 Octobre 2025
                    </div>
                </div>
                
                <div class="timeline-line"></div>
                
                <div class="timeline-wrapper">
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <div class="event-time">1</div>
                            <h5 class="event-title">Cérémonie d'ouverture</h5>
                            <p class="event-description">Allocutions des autorités gabonaises et ivoiriennes. Discours d'ouverture.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div class="event-time">2</div>
                            <h5 class="event-title">Présentations sectorielles</h5>
                            <p class="event-description">Panels thématiques sur les 12 secteurs prioritaires.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-cup-straw"></i>
                            </div>
                            <div class="event-time">3</div>
                            <h5 class="event-title">Déjeuner networking</h5>
                            <p class="event-description">Pause déjeuner conviviale entre participants.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="event-time">4</div>
                            <h5 class="event-title">Ateliers sectoriels</h5>
                            <p class="event-description">Sessions parallèles avec experts et praticiens.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-champagne"></i>
                            </div>
                            <div class="event-time">5</div>
                            <h5 class="event-title">Cocktail de bienvenue</h5>
                            <p class="event-description">Networking avec animations culturelles.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- JOUR 2 -->
            <div class="timeline-day">
                <div class="day-header">
                    <div class="timeline-date">
                        <i class="bi bi-calendar-event me-2"></i>
                        Jour 2 - 10 Octobre 2025
                    </div>
                </div>
                
                <div class="timeline-line"></div>
                
                <div class="timeline-wrapper day-2">
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-chat-quote"></i>
                            </div>
                            <div class="event-time">6</div>
                            <h5 class="event-title">Table ronde de haut niveau</h5>
                            <p class="event-description">Discussions avec ministres, ambassadeurs et dirigeants.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-handshake"></i>
                            </div>
                            <div class="event-time">7</div>
                            <h5 class="event-title">Sessions B2B personnalisées</h5>
                            <p class="event-description">Rencontres directes entreprises-investisseurs.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="event-card">
                            <div class="event-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="event-time">8</div>
                            <h5 class="event-title">Dîner de gala</h5>
                            <p class="event-description">Célébration avec signatures et spectacle culturel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Intervenants (placeholder pour futur développement) -->
<section id="intervenants" style="display: none;"></section>

<!-- Secteurs Prioritaires -->
<section id="secteurs" class="py-5 my-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="mb-2" style="color: var(--blue-gabon); font-weight: 600;">SECTEURS CLÉS</p>
            <h2 style="color: var(--blue-gabon);">12 Secteurs <span style="color: var(--orange-ci);">Prioritaires</span></h2>
        </div>
        
        <div class="row g-3">
            @php
            $secteurs = [
                ['icon' => 'tree', 'nom' => 'Agro-Industrie'],
                ['icon' => 'hammer', 'nom' => 'Infrastructures'],
                ['icon' => 'bank', 'nom' => 'Banque-Assurance'],
                ['icon' => 'gem', 'nom' => 'Mines'],
                ['icon' => 'lightning', 'nom' => 'Énergies renouvelables'],
                ['icon' => 'lightbulb', 'nom' => 'Startups & Innovation'],
                ['icon' => 'droplet', 'nom' => 'Aquaculture'],
                ['icon' => 'file-text', 'nom' => 'Production biométrique'],
                ['icon' => 'cpu', 'nom' => 'Projets Monétiques'],
                ['icon' => 'basket', 'nom' => 'Anacarde'],
                ['icon' => 'truck', 'nom' => 'Équipement routier'],
                ['icon' => 'egg', 'nom' => 'Élevage']
            ];
            @endphp
            
            @foreach($secteurs as $secteur)
            <div class="col-md-4">
                <div class="sector-card">
                    <i class="bi bi-{{ $secteur['icon'] }} me-2" style="color: var(--blue-gabon); font-size: 1.5rem;"></i> 
                    <strong>{{ $secteur['nom'] }}</strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Countries Info -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-5">
                        <h3 class="mb-4" style="color: var(--blue-gabon);">🇨🇮 Côte d'Ivoire</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Population:</strong> 32 millions d'habitants</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Capitale:</strong> Yamoussoukro</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Monnaie:</strong> Franc CFA</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Superficie:</strong> 322 463 km²</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Leader:</strong> Agriculture, Services</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-5">
                        <h3 class="mb-4" style="color: var(--blue-gabon);">🇬🇦 Gabon</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Population:</strong> 2,4 millions d'habitants</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Capitale:</strong> Libreville</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Monnaie:</strong> Franc CFA</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>PIB:</strong> 13 118 milliards FCFA</li>
                            <li class="mb-2"><i class="bi bi-check-circle me-2" style="color: var(--green-gabon);"></i> <strong>Leader:</strong> Pétrole, Bois, Mines</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="py-5 text-center" style="background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));">
    <div class="container">
        <div class="text-white py-5">
            <h2 class="mb-4 text-white">N'avez-vous pas encore réservé votre place ?</h2>
            <h1 class="display-4 fw-bold mb-4 text-white">RÉSERVEZ MAINTENANT</h1>
            <p class="lead mb-4">Rejoignez plus de 200 chefs d'entreprise pour cette première édition historique du FACIGA 2025</p>
            <a href="{{ route('inscription') }}" class="btn btn-light btn-lg px-5 py-3">
                <i class="bi bi-ticket-perforated"></i> S'INSCRIRE MAINTENANT
            </a>
        </div>
    </div>
</section>

<script>
// Mobile Menu Functions
function toggleMenu() {
    const menu = document.getElementById('slideMenu');
    const overlay = document.getElementById('modalOverlay');
    
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
    
    if (menu.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

function closeMenu() {
    const menu = document.getElementById('slideMenu');
    const overlay = document.getElementById('modalOverlay');
    
    menu.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = 'auto';
}

document.querySelectorAll('.nav-link-mobile').forEach(link => {
    link.addEventListener('click', closeMenu);
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeMenu();
    }
});

// Countdown Timer - Version améliorée
const eventDate = new Date('2025-10-09T09:00:00').getTime();
let countdownInterval;

function updateCountdown() {
    const now = new Date().getTime();
    const distance = eventDate - now;
    
    // Vérifier si les éléments existent
    const daysElement = document.getElementById('days');
    const hoursElement = document.getElementById('hours');
    const minutesElement = document.getElementById('minutes');
    const secondsElement = document.getElementById('seconds');
    
    if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
        console.error('Elements du countdown non trouvés');
        return;
    }
    
    if (distance < 0) {
        // L'événement est passé
        daysElement.textContent = '00';
        hoursElement.textContent = '00';
        minutesElement.textContent = '00';
        secondsElement.textContent = '00';
        clearInterval(countdownInterval);
        return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    daysElement.textContent = days.toString().padStart(2, '0');
    hoursElement.textContent = hours.toString().padStart(2, '0');
    minutesElement.textContent = minutes.toString().padStart(2, '0');
    secondsElement.textContent = seconds.toString().padStart(2, '0');
}

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    updateCountdown(); // Mise à jour initiale
    countdownInterval = setInterval(updateCountdown, 1000);
});
</script>
@endsection