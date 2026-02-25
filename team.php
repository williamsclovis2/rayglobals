
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
	
	<!-- Page Title -->
    <section class="page-title">
    	<div class="content" style="background-image:url(assets/images/main-slider/image-3.jpg)">
			<div class="auto-container">
				<h1>Notre équipe</h1>
			</div>
		</div>
		<ul class="page-breadcrumb">
			<li><a href="index">Accueil</a></li>
			<li>équipe</li>
		</ul>
    </section>
    <!-- End Page Title -->
	
	<!-- Team Section Two -->
	<section class="team-section-two style-two">
		<div class="auto-container">
			
			<!-- Sec Title -->
			<div class="sec-title centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Les Membres de <span>Notre Entreprise</span></h2>
				<div class="text">Une équipe dynamique d’experts qui portent l’innovation dans chacun de nos secteurs d’activité.</div>
			</div>


			<div class="row clearfix">
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-6.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Aaron Palmer</a></h5>
							<div class="designation">Company Manager</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-7.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Virginia Dunn</a></h5>
							<div class="designation">Cad Designer</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-8.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Carig McDonald</a></h5>
							<div class="designation">Attorney</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-9.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Lesiy Coruug</a></h5>
							<div class="designation">CEO &t Founder</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-6.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Aaron Palmer</a></h5>
							<div class="designation">Company Manager</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-7.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Virginia Dunn</a></h5>
							<div class="designation">Cad Designer</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-8.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Carig McDonald</a></h5>
							<div class="designation">Attorney</div>
						</div>
					</div>
				</div>
				
				<!-- Team Block -->
				<div class="team-block col-lg-3 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="team-detail.html"><img src="assets/images/resource/team-9.jpg" alt="" /></a>
							<!-- Social Box -->
							<ul class="social-box">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
								<li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
								<li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
								<li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
							</ul>
						</div>
						<div class="lower-content">
							<h5><a href="team-detail.html">Lesiy Coruug</a></h5>
							<div class="designation">CEO &t Founder</div>
						</div>
					</div>
				</div>
				
			</div>
			
			<!-- Button Box -->
			<div class="button-box text-center">
				<a class="btn-style-two theme-btn" href="projects.html"><span class="txt">View More</span></a>
			</div>
			
		</div>
	</section>
	<!-- End Team Section Two -->

  

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