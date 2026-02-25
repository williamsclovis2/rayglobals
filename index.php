
<?php
    $page = "index";
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
    <section class="main-slider-two">
		<?php include("includes/main-slide.php")?>
		<div class="play-box">
			<a href="assets/images/video.mp4" class="lightbox-image play-button"><span class="flaticon-play-arrow"><i class="ripple"></i></span></a>
		</div>
    </section>
    <!-- End Main Slider Section -->
	
	<section class="experiance-section-two style-two ">
		<div class="auto-container">
			<div class="row clearfix">
			
				<!-- Content Column -->
				<div class="content-column col-lg-6 col-md-12 col-sm-12 first-col">
					<div class="inner-column">
						<div class="sec-title">
							<div class="title">Multisectorielle depuis la fondation</div>
							<h2>Excellence et <span>Innovation</span> <br> au service de vos besoins.</h2>
							<div class="text">Chez Ray Globals, nous croyons que la diversité des compétences est une force qui nous permet d’apporter des solutions adaptées aux défis actuels. Forte d’une vision résolument tournée vers l’excellence, notre entreprise rassemble des experts qualifiés et passionnés, prêts à répondre aux attentes de nos clients avec professionnalisme et engagement. Nous nous distinguons par une expertise multisectorielle, une approche client personnalisée et un respect rigoureux des normes internationales.</div>
						</div>
						<!-- Fact Counter / Style Three -->
						<div class="fact-counter style-three">
							<div class="row clearfix">
								
								<!-- Column -->
								<div class="column counter-column col-lg-6 col-md-6 col-sm-12">
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
								<div class="column counter-column col-lg-6 col-md-6 col-sm-12">
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
								
							</div>
						</div>
						
						<!-- Button Box -->
						<div class="button-box">
							<a class="btn-style-three theme-btn" href="about"><span class="txt">En Savoir Plus</span></a>
						</div>
					</div>
				</div>
				
				<!-- Image Column -->
				<div class="image-column col-lg-6 col-md-12 col-sm-12 second-col">
					<div class="inner-column">
						<div class="year-box">
							<div class="count-box">
								0<span class="count-text" data-speed="5000" data-stop="5">0</span>
							</div>
							<span class="years">Années</span>
						</div>
						<div class="image">
							<img src="assets/images/resource/experiance22.jpg" alt="" />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Experiance Section Two -->
	
	<!-- Services Section Two -->
	<section class="services-section-four style-two" style="background-image:url(assets/images/background/pattern-1.png)">
		<div class="auto-container">
			
			<!-- Sec Title -->
			<div class="sec-title centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Nos <span>Services</span></h2>
				<div class="text">Solutions multisectorielles pour vos besoins</div>
			</div>
			
			<div class="row clearfix">
				
				<!-- Service Block Three -->
				<div class="service-block-three col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="H"><img src="assets/images/resource/service-11.jpg" alt="" /></a>
						</div>
						<div class="lower-content">
							<div class="content">
								<h5><a href="#">Transport</a></h5>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Service Block Three -->
				<div class="service-block-three col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="#"><img src="assets/images/resource/service-22.jpg" alt="" /></a>
						</div>
						<div class="lower-content">
							<div class="content">
								<h5><a href="#">Matériaux & Blocs</a></h5>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Service Block Three -->
				<div class="service-block-three col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInRight" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="#"><img src="assets/images/resource/service-33.jpg" alt="" /></a>
						</div>
						<div class="lower-content">
							<div class="content">
								<h5><a href="#">Les produits électroniques</a></h5>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Service Block Three -->
				<div class="service-block-three col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="#"><img src="assets/images/resource/service-44.jpg" alt="" /></a>
						</div>
						<div class="lower-content">
							<div class="content">
								<h5><a href="#">Import-Export</a></h5>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Service Block Three -->
				<div class="service-block-three col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="#"><img src="assets/images/resource/service-55.jpg" alt="" /></a>
						</div>
						<div class="lower-content">
							<div class="content">
								<h5><a href="#">Communication</a></h5>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
			<!-- Lower Text -->
			<div class="lower-text">
				<div class="text">Découvrez tous nos services... <a href="#">Voir Plus de Services</a></div>
			</div>
			
		</div>
	</section>
	<!-- End Services Section Two -->
	<section class="cta-section">
		<div class="auto-container">
			<div class="row clearfix">
			
				<!-- Title Column -->
				<div class="title-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<h3>Obtenez un Devis Facile</h3>
						<div class="text">Ray Globals, votre partenaire multisectoriel pour des solutions innovantes</div>					</div>
				</div>
				
				<!-- Info Column -->
				<div class="info-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="clearfix">
							<div class="phone-box">
							<span class="icon flaticon-call-2"></span>
							Contactez-nous à tout moment :<br>
								<a href="tel:+243814676307"> +243 814 676 307</a>
								<span class="or">ou</span>
							</div>
							<!-- Button Box -->
							<div class="button-box">
								<a class="btn-style-four theme-btn" href="#"><span class="txt"> contacter</span></a>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>

	<!-- Quality Section-->
	<section class="quality-section why-sec" style="background-image:url(assets/images/background/9.jpg)">
		<div class="auto-container">
			<div class="row clearfix">
			
				<!-- Content Column -->
				<div class="content-column col-lg-6 col-md-12 col-sm-12 first-col">
					<div class="inner-column">
						<div class="sec-title">
							<div class="title">Ray Globals, votre partenaire multisectoriel</div>
							<h2>Pourquoi Choisir <br> Ray Globals ?</h2>
						</div>
						<ul class="check-list">
							<li>Notre savoir-faire multisectoriel nous permet de répondre à une vaste gamme de besoins avec polyvalence et expertise.</li>
							<li>Nous garantissons des produits et services de haute qualité, conformes aux normes internationales les plus strictes.</li>
							<li>En tant que partenaire fiable, nous assurons la réussite de vos projets grâce à notre engagement et notre professionnalisme.</li>
							<li>Nos solutions innovantes intègrent des technologies modernes pour s’adapter à un monde en constante évolution.</li>
							<li>Nous offrons un accompagnement personnalisé, avec une écoute attentive pour proposer des services sur mesure à chaque client.</li>
						</ul>
					</div>
				</div>
				
				<!-- Image Column -->
				<div class="image-column col-lg-6 col-md-12 col-sm-12 second-col">
					<div class="inner-column">
						<div class="image">
							<img src="assets/images/resource/quality-11.jpg" alt="" />
							<div class="play-box">
								<a href="https://www.youtube.com/watch?v=kxPCFljwJws" class="lightbox-image play-button"><span class="flaticon-play-button-1"><i class="ripple"></i></span></a>
							</div>
						</div>
						<div class="image-two quality-2">
							<img src="assets/images/resource/quality-2.jpg" alt="" />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Quality Section-->
	
	<!-- FullWidth Section -->
	<section class="fullwidth-section">
		<div class="outer-container">
			<div class="row clearfix">
				<!-- Left Column -->
				<div class="left-column" style="background-image:url(assets/images/main-slider/image-1.jpg)">
					<div class="inner-column">
						<div class="title">Notre Vision</div>
						<h3>Transformer Vos Projets <br> avec des Solutions Multisectorielles</h3>
						<div class="text v-ms">Ray Globals, un partenaire dédié à l’excellence depuis sa fondation, s’engage à offrir des solutions innovantes, efficaces et adaptées aux besoins de ses clients. Forts d’une expertise multisectorielle, nous mettons la qualité, la rigueur et la satisfaction client au cœur de chacune de nos interventions.</div>
					</div>
				</div>
				<!-- Right Column -->
				<div class="right-column" style="background-image:url(assets/images/background/fullwidth-2.png)">
					<div class="inner-column">
						<h3>Concrétisez Vos Ambitions</h3>
						<h5>Innovation et Qualité</h5>
						<a href="#" class="theme-btn btn-style-four"><span class="txt">Contactez Nos Experts</span></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End FullWidth Section -->

	<!-- Clients Section Two -->
    <section class="clients-section-two" id="paterners">
        <div class="auto-container">
			<!-- Title Box -->
			<div class="title-box">
				<div class="row clearfix">
					<!-- Column -->
					<div class="column col-lg-6 col-md-12 col-sm-12">
						<div class="icon flaticon-helmet-2"></div>
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

	<!-- Projects Section Three -->
	<section class="projects-section-three" style="background-image:url(assets/images/background/pattern-2.png)">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title light centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Projets <span>Multisectoriels</span></h2>
				<div class="text">Ray Globals, des solutions innovantes pour vos ambitions</div>
			</div>
			<div class="project-carousel-two owl-carousel owl-theme">
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/166.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Logistique de Transport <br> Internationale</a></h4>
								<div class="designation">Notre Rôle : <span>Gestion Logistique</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/199.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Construction de Blocs <br> Ciment Vibrés</a></h4>
								<div class="designation">Notre Rôle : <span>Fabrication et Fourniture</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/188.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Campagne de <br> Communication Digitale</a></h4>
								<div class="designation">Notre Rôle : <span>Marketing Digital</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/166.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Importation de Matériaux <br> de Construction</a></h4>
								<div class="designation">Notre Rôle : <span>Import-Export</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/177.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Distribution d’Appareils <br> Électroniques</a></h4>
								<div class="designation">Notre Rôle : <span>Vente au Détail</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
						<div class="image">
							<a href="#"><img src="assets/images/gallery/188.jpg" alt="" /></a>
							<div class="overlay-content">
								<h4><a href=" ">Projet de Logistique <br> Douanière</a></h4>
								<div class="designation">Notre Rôle : <span>Accompagnement Douanier</span></div>
								<a class="plus fa fa-plus" href=" "></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="lower-text">
				Découvrez nos réalisations multisectorielles... <a href="projects.html">Voir Plus de Projets</a>
			</div>
		</div>
	</section>
	<!-- End Projects Section Three -->

	<!-- News Section -->
	<section class="news-section">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Nos Actualités <span>et Insights</span></h2>
				<div class="text">Ray Globals, votre partenaire multisectoriel depuis sa fondation</div>
			</div>
			
			<div class="row clearfix">
				<!-- News Block -->
				<div class="news-block-two col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="blog-detail.html"><img src="assets/images/resource/news-44.jpg" alt="" /></a>
							<div class="post-date">15 AVR 2025</div>
						</div>
						<div class="lower-content">
							<ul class="post-meta">
								<li><span class="icon fa fa-user"></span>Équipe Ray Globals</li>
								<li><span class="icon fa fa-comment"></span>3 Commentaires</li>
							</ul>
							<h4><a href="blog-detail.html">Nouveau service de logistique pour l’import-export</a></h4>
							<div class="text">Découvrez comment Ray Globals optimise vos échanges internationaux avec des solutions logistiques innovantes.</div>
						</div>
					</div>
				</div>
				
				<!-- News Block -->
				<div class="news-block-two col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="blog-detail.html"><img src="assets/images/resource/news-55.jpg" alt="" /></a>
							<div class="post-date">10 AVR 2025</div>
						</div>
						<div class="lower-content">
							<ul class="post-meta">
								<li><span class="icon fa fa-user"></span>Équipe Ray Globals</li>
								<li><span class="icon fa fa-comment"></span>5 Commentaires</li>
							</ul>
							<h4><a href="blog-detail.html">Lancement de blocs <br>ciment vibrés<br> écologiques</a></h4>
							<div class="text">Nos nouveaux blocs ciment vibrés allient durabilité et respect des normes environnementales.</div>
						</div>
					</div>
				</div>
				
				<!-- News Block -->
				<div class="news-block-two col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box wow fadeInRight" data-wow-delay="300ms" data-wow-duration="1500ms">
						<div class="image">
							<a href="blog-detail.html"><img src="assets/images/resource/news-66.jpg" alt="" /></a>
							<div class="post-date">05 AVR 2025</div>
						</div>
						<div class="lower-content">
							<ul class="post-meta">
								<li><span class="icon fa fa-user"></span>Équipe Ray Globals</li>
								<li><span class="icon fa fa-comment"></span>2 Commentaires</li>
							</ul>
							<h4><a href="blog-detail.html">Stratégies de communication digitale réussies</a></h4>
							<div class="text">Apprenez comment Ray Globals booste la visibilité des entreprises grâce à des stratégies modernes.</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Button Box -->
			<div class="button-box text-center">
				<a class="btn-style-two theme-btn" href="blog.html"><span class="txt">Voir Tous les Articles</span></a>
			</div>
		</div>
	</section>
	<!-- End News Section -->
	
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