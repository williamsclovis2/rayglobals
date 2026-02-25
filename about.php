
<?php
    $page = "about";
	require_once "admin/core/init.php";
    $_display = Input::get('page','get');
 ?>
<!DOCTYPE html>
<html>
<head>
	<?php include("includes/head.php")?>
</head>

<body class="hidden-bar-wrapper">

<div class="page-wrapper">
 
 	
 	<!-- Header Style One / Two -->
    <header class="main-header header-style-two">
		<?php include("includes/header.php")?>
    </header>
    <!-- End Main Header -->
	
	<!-- Sidebar Cart Item -->
	<div class="xs-sidebar-group info-group">
		<div class="xs-overlay xs-bg-black"></div>
		<?php include("includes/sidebar.php")?>
	</div>
	<!-- END sidebar widget item -->
	
	<!-- Main Slider Section Two -->
    <section class="page-title">
    	<div class="content" style="background-image: url(assets/images/main-slider/image-1.jpg)">
			<div class="auto-container">
				<h1>À propos de nous</h1>
			</div>
		</div>
		<ul class="page-breadcrumb">
			<li><a href="index.html">Accueil</a></li>
			<li>À propos</li>
		</ul>
    </section>
    <!-- End Main Slider Section -->
	
	<section class="experiance-section-two style-two ">
		<div class="auto-container">
			<div class="row clearfix">
			
				<!-- Content Column -->
				<div class="content-column col-lg-12 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="sec-title">
							<div class="title">Multisectorielle depuis la fondation</div>
							<h2>Excellence et <span>Innovation</span> <br> au service de vos besoins.</h2>
							<div class="text">Chez Ray Globals, nous croyons que la diversité des compétences est une force qui nous permet d’apporter des solutions adaptées aux défis actuels. Forte d’une vision résolument tournée vers l’excellence, notre entreprise rassemble des experts qualifiés et passionnés, prêts à répondre aux attentes de nos clients avec professionnalisme et engagement. Nous nous distinguons par une expertise multisectorielle, une approche client personnalisée et un respect rigoureux des normes internationales.</div>
                            <div class="text">Sed vehicula ac sapien ac tincidunt. Etiam nec semper ex, sit amet maximus nulla. Donec sed dictum enim. Vestibulum condimentum posuere mi, vel fringilla purus finibus a. Nullam ornare turpis sit amet orci luctus, nec posuere leo vulputate. Nulla dignissim euismod congue. Sed luctus ac lectus ut volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dignissim elit eros, id convallis magna aliquam ac. Mauris congue elit eget sagittis ornare. Proin laoreet arcu eu eros vehicula commodo. Suspendisse gravida, nibh eu rhoncus consequat, lectus justo tempus felis, vel ornare urna lacus et mi.</div>
						</div>
						<!-- Fact Counter / Style Three -->
						<div class="fact-counter style-three">
							<div class="row clearfix">
								
								<!-- Column -->
								<div class="column counter-column col-lg-4 col-md-4 col-sm-12">
									<div class="inner wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
										<div class="content">
											<div class="icon flaticon-helmet"></div>
											<div class="count-outer count-box alternate">
												<span class="count-text" data-speed="5000" data-stop="500">0</span>+
											</div>
											<h4 class="counter-title">Projets Réalisés</h4>
										</div>
									</div>
								</div>

								<!-- Column -->
								<div class="column counter-column col-lg-4 col-md-4 col-sm-12">
									<div class="inner wow fadeInLeft" data-wow-delay="600ms" data-wow-duration="1500ms">
										<div class="content">
											<div class="icon flaticon-blueprint"></div>
											<div class="count-outer count-box">
												<span class="count-text" data-speed="3000" data-stop="10">0</span>K
											</div>
											<h4 class="counter-title">Clients Satisfaits</h4>
										</div>
									</div>
								</div>

                                <div class="column counter-column col-lg-4 col-md-4 col-sm-12">
									<div class="inner wow fadeInLeft" data-wow-delay="600ms" data-wow-duration="1500ms">
										<div class="content">
											<div class="icon flaticon-group"></div>
											<div class="count-outer count-box">
												<span class="count-text" data-speed="3000" data-stop="50">0</span>
											</div>
											<h4 class="counter-title">Nombre d'employés</h4>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						
					</div>
				</div>
				
				
			</div>
		</div>
	</section>
	<!-- End Experiance Section Two -->

    <!-- Services Section Two -->
	<section class="services-section-two">
		<div class="auto-container">
			
			<!-- Sec Title -->
			<div class="sec-title centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Ce que nous <span>faisons</span></h2>
				<div class="text">Solutions Multisectorielles pour vos besoins</div>
			</div>
			
			<div class="services-carousel owl-carousel owl-theme">
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<span class="icon fa fa-truck"></span>
						<h5><a href="service-detail.html">Transport</a></h5>
						<div class="text">Services de transport fiables pour vos marchandises...</div>
						<a href="service-detail.html" class="read-more">Lire Plus <span class="arrow fa fa-caret-right"></span></a>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<span class="icon fa fa-cubes"></span>
						<h5><a href="service-detail.html">Matériaux & Blocs</a></h5>
						<div class="text">Fourniture de matériaux de construction de qualité...</div>
						<a href="service-detail.html" class="read-more">Lire Plus <span class="arrow fa fa-caret-right"></span></a>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<span class="icon fa fa-plug"></span>
						<h5><a href="service-detail.html">Électronique</a></h5>
						<div class="text">Solutions électroniques innovantes pour tous vos besoins...</div>
						<a href="service-detail.html" class="read-more">Lire Plus <span class="arrow fa fa-caret-right"></span></a>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<span class="icon fa fa-train"></span>
						<h5><a href="service-detail.html">Import-Export</a></h5>
						<div class="text">Gestion efficace des opérations d'importation et d'exportation...</div>
						<a href="service-detail.html" class="read-more">Lire Plus <span class="arrow fa fa-caret-right"></span></a>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<span class="icon flaticon-megaphone"></span>
						<h5><a href="service-detail.html">Communication</a></h5>
						<div class="text">Services de communication pour une connectivité optimale...</div>
						<a href="service-detail.html" class="read-more">Lire Plus <span class="arrow fa fa-caret-right"></span></a>
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Services Section Two -->

    <!-- Video Section -->
    <section class="video-section" style="background-image:url(assets/images/background/5.jpg)">
        <div class="auto-container">
            <div class="content">
                <h2>Transformez Vos Projets<br>en Réalité avec Ray Globals</h2>
                <div class="text">Votre partenaire multisectoriel de confiance pour des solutions innovantes et performantes</div>
                <a href="https://www.youtube.com/watch?v=kxPCFljwJws" class="lightbox-image video-box">
                    <span class="flaticon-play-button"><i class="ripple"></i></span>
                </a>
            </div>
        </div>
    </section>
    <!-- End Video Section -->



    <!-- Clients Section Two -->
    <section class="clients-section-two style-two"  id="partners">
        <div class="auto-container">
			<!-- Title Box -->
			<div class="title-box">
				<div class="row clearfix">
					<!-- Column -->
					<div class="column col-lg-6 col-md-12 col-sm-12">
						<div class="icon flaticon-email"></div>
						<h2>Nos Meilleurs <br> Partenaires Corporatifs</h2>
					</div>
					<!-- Column -->
					<div class="column col-lg-6 col-md-12 col-sm-12">
						<div class="text">Nous collaborons avec le secteur public pour bâtir des communautés prospères.</div>
					</div>
				</div>
			</div>
            <div class="inner-container">
				<div class="sponsors-outer">
					<!-- Sponsors Carousel -->
					<ul class="sponsors-carousel owl-carousel owl-theme">
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/6.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/7.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/8.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/9.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/10.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/6.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/7.png" alt=""></a></figure></li>
						<li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/8.png" alt=""></a></figure></li>
					</ul>
				</div>
            </div>
        </div>
    </section>
    <!-- End Clients Section Two -->

    <!-- <section class="solution-section">
	<div class="side-image wow fadeInRight" data-wow-delay="300ms" data-wow-duration="1500ms">
		<img src="assets/images/resource/solution.png" alt="" />
	</div>
	<div class="auto-container">
		<div class="row clearfix">
			<div class="title-column col-lg-8 col-md-12 col-sm-12">
				<div class="inner-column">
					<div class="sec-title">
						<h2>Des Solutions Multisectorielles Sur Mesure</h2>
						<div class="text">Des services fiables et innovants pour répondre à vos besoins variés</div>
					</div>
					<div class="inner-column">
					<div class="feature-block">
						<div class="inner-box">
							<span class="icon flaticon-transportation"></span>
							<h4><a href="#">Transport Logistique</a></h4>
							<div class="text">Des solutions de transport sécurisées et efficaces, appuyées par une flotte moderne et un suivi avancé.</div>
							<a href="#" class="read-more">En savoir plus <i class="fa fa-caret-right"></i></a>
						</div>
					</div>
					<div class="feature-block">
						<div class="inner-box">
							<span class="icon flaticon-brick-wall"></span>
							<h4><a href="#">Matériaux & Blocs Ciment</a></h4>
							<div class="text">Matériaux de construction de qualité et blocs ciment vibrés conformes aux normes de durabilité.</div>
							<a href="#" class="read-more">En savoir plus <i class="fa fa-caret-right"></i></a>
						</div>
					</div>
					<div class="feature-block">
						<div class="inner-box">
							<span class="icon flaticon-import"></span>
							<h4><a href="#">Import & Export</a></h4>
							<div class="text">Accompagnement personnalisé pour vos échanges commerciaux à l’international, logistique et douanes.</div>
							<a href="#" class="read-more">En savoir plus <i class="fa fa-caret-right"></i></a>
						</div>
					</div>
					
				</div>
				</div>
			</div>
			<div class="content-column col-lg-4 col-md-12 col-sm-12">
				
			</div>
			
		</div>
	</div>
</section> -->
<!-- End Solution Section -->
	

	<!-- Main Footer -->
    <footer class="footer-style-two" style="background-image:url(assets/images/background/10.jpg)">
		<?php include("includes/footer.php")?>
	</footer>
	<!-- End Main Footer -->
	
	
</div>
<!-- End PageWrapper -->





<!-- Scroll To Top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>

<?php include("includes/script.php")?>

</body>

<!-- Mirrored from uniqthemes.com/html/bricks/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 25 Feb 2025 14:54:23 GMT -->
</html>