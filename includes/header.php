<!-- Header Top Two -->
<div class="header-top-two">
    <div class="auto-container">
        <div class="inner-container clearfix">
            <!-- Top Left -->
            <div class="top-left clearfix">
                <div class="button-box">
                    <a class="theme-btn contact-btn" href="#">Demander un devis</a>
                </div>
                <ul class="info-links">
                    <li><span class="icon flaticon-call"></span> Téléphone: <a href="tel:+243814676307">+243 814 676 307 | +243 895 954 301</a></li>
                    <li><span class="icon flaticon-email-2"></span>Email: <a href="mailto:info@rayglobals.org">info@rayglobals.org</a></li>
                </ul>
            </div>
            <!-- Top Right -->
            <div class="top-right pull-right clearfix">
                <!-- Social Box -->
                <ul class="social-box">
                    <li class="follow">Suivez-nous :</li>
                    <li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
                    <li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
                    <li><a href="https://www.linkedin.com/" class="fa fa-instagram"></a></li>
                    <li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Header Upper -->
<div class="header-upper">
    <div class="auto-container">
        <div class="clearfix">
            <div class="pull-left logo-box">
                <div class="logo"><a href="<?= DN ?>/index"> 
                <img src="<?= DN ?>/assets/images/logo-3.png"  alt="Ray Globals Logo" title=""></a></div>
            </div>
            <div class="nav-outer clearfix">
                <!-- Mobile Navigation Toggler -->
                <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
                <!-- Main Menu -->
                <nav class="main-menu navbar-expand-md">
                    <div class="navbar-header">
                        <!-- Toggle Button -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                        <ul class="navigation clearfix">
                            <li class="<?php echo ($page == "index" ? "current" : "")?>"><a href="<?= DN ?>/index">Accueil</a></li>
                            <li class="dropdown <?php echo ($page == "about" ? "current" : "")?>"><a href="<?= DN ?>/about">À Propos</a>
                                <ul>
                                    <li><a href="about">À Propos de Nous</a></li>
                                    <li><a href="team">Équipe</a></li>
                                    <li><a href="testimonials">Témoignages</a></li>
                                    <li><a href="#">Pourquoi Nous Choisir</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#">Nos Services</a>
                                <ul>
                                    <li><a href="#">Transport</a></li>
                                    <li><a href="#">Matériaux de Construction & Blocs</a></li>
                                    <li><a href="#">Appareils Électroniques</a></li>
                                    <li><a href="#">Import-Export</a></li>
                                    <li><a href="#">Communication d’Entreprise</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#">Nos Projets</a>
                                <ul>
                                    <li><a href="#">Projets</a></li>
                                    <li><a href="#">Détails du Projet</a></li>
                                </ul>
                            </li>
                            <li class="<?php echo ($page == "vacancies" ? "current" : "")?>"><a href="<?= DN ?>/vacancies">offres d'emploi</a></li>
                            <li class="dropdown"><a href="#">Blog</a>
                                <ul>
                                    
                                    <li><a href="#">Les prix de nos produits</a></li>
                                    <li><a href="#">Nos Actualités</a></li>
                                </ul>
                            </li>
                            <li  class="<?php echo ($page == "contact" ? "current" : "")?>"><a href="<?= DN ?>/contact">Contact</a></li>
                        </ul>
                    </div>
                </nav>
                <!-- Main Menu End -->
                <div class="outer-box clearfix">
                    <!-- Cart Box -->
                    <!-- <div class="cart-box">
                        <div class="dropdown">
                            <button class="cart-box-btn dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flaticon-shopping-bag-1"></span><span class="total-cart">2</span></button>
                            <div class="dropdown-menu pull-right cart-panel" aria-labelledby="dropdownMenu">
                                <div class="cart-product">
                                    <div class="inner">
                                        <div class="cross-icon"><span class="icon fa fa-remove"></span></div>
                                        <div class="image"><img src="assets/images/resource/post-thumb-1.jpg" alt="" /></div>
                                        <h3><a href="#">Produit 01</a></h3>
                                        <div class="quantity-text">Quantité 01</div>
                                        <div class="price">99,00 €</div>
                                    </div>
                                </div>
                                <div class="cart-product">
                                    <div class="inner">
                                        <div class="cross-icon"><span class="icon fa fa-remove"></span></div>
                                        <div class="image"><img src="assets/images/resource/post-thumb-2.jpg" alt="" /></div>
                                        <h3><a href="#">Produit 02</a></h3>
                                        <div class="quantity-text">Quantité 01</div>
                                        <div class="price">99,00 €</div>
                                    </div>
                                </div>
                                <div class="cart-total">Sous-total : <span>198,00 €</span></div>
                                <ul class="btns-boxed">
                                    <li><a href="#">Voir le Panier</a></li>
                                    <li><a href="#">Commander</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                    <!-- Nav Btn -->
                    <div class="nav-btn navSidebar-button"><span class="icon flaticon-menu-2"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Upper -->

<!-- Sticky Header -->
<div class="sticky-header">
    <div class="auto-container clearfix">
        <!-- Logo -->
        <div class="logo pull-left">
            <a href="#" title=""><img class="sticky-logo" src="<?= DN ?>/assets/images/logo-3.png" alt="Ray Globals Logo" title=""></a>
        </div>
        <!-- Right Col -->
        <div class="pull-right">
            <!-- Main Menu -->
            <nav class="main-menu">
                <!-- Menu will come through Javascript -->
            </nav>
            <!-- Main Menu End -->
            <!-- Mobile Navigation Toggler -->
            <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
        </div>
    </div>
</div>
<!-- End Sticky Menu -->

<!-- Mobile Menu -->
<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <div class="close-btn"><span class="icon flaticon-multiply"></span></div>
    <nav class="menu-box">
        <div class="nav-logo"><a href="#"><img src="<?= DN ?>/assets/images/logo-small-new.png" alt="Ray Globals Logo" title=""></a></div>
        <div class="menu-outer"></div>
    </nav>
</div>
<!-- End Mobile Menu -->