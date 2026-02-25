
<?php
    $page = "vacancies";
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
    	<div class="content" style="background-image: url(assets/images/resource/hiring.jpg)">
			<div class="auto-container">
				<h1>Nos offres d'emploi</h1>
			</div>
		</div>
		<ul class="page-breadcrumb">
			<li><a href="index.html">Accueil</a></li>
			<li>Nos offres</li>
		</ul>
    </section>
    <!-- End Main Slider Section -->
	
	<section class="experiance-section-two style-two " style="padding: unset !important;">
	<div class="job-listing-area pt-120 pb-120">
        <div class="container vac-container">
            <div class="row">
                <!-- Contenu gauche -->
                <div class="col-xl-3 col-lg-3 col-md-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="small-section-tittle2 mb-45">
                                <div class="ion"> 
                                    <!-- SVG remains unchanged -->
                                </div>
                                <h4>Filtrer les offres</h4>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des catégories d’emploi début -->
                    <div class="job-category-listing mb-50">
                        <!-- première section -->
                        <div class="single-listing">
                           <div class="small-section-tittle2">
                                 <h4>Catégorie d'emploi</h4>
                           </div>
                            <div class="select-job-items2">
                                <select name="select">
                                    <option value="">Toutes les catégories</option>
                                    <option value="">Catégorie 1</option>
                                    <option value="">Catégorie 2</option>
                                    <option value="">Catégorie 3</option>
                                    <option value="">Catégorie 4</option>
                                </select>
                            </div>
                            <div class="select-Categories pt-80 pb-50">
                                <div class="small-section-tittle2">
                                    <h4>Type d'emploi</h4>
                                </div>
                                <label class="container">Temps plein
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Temps partiel
                                    <input type="checkbox" checked="checked active">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Télétravail
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Freelance
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <!-- deuxième section -->
                        <div class="single-listing">
                           <div class="small-section-tittle2">
                                 <h4>Localisation</h4>
                           </div>
                            <div class="select-job-items2">
                                <select name="select">
                                    <option value="">Partout</option>
                                    <option value="">Catégorie 1</option>
                                    <option value="">Catégorie 2</option>
                                    <option value="">Catégorie 3</option>
                                    <option value="">Catégorie 4</option>
                                </select>
                            </div>
                            <div class="select-Categories pt-80 pb-50">
                                <div class="small-section-tittle2">
                                    <h4>Expérience</h4>
                                </div>
                                <label class="container">1-2 ans
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">2-3 ans
                                    <input type="checkbox" checked="checked active">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">3-6 ans
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Plus de 6 ans
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <!-- troisième section -->
                        <div class="single-listing">
                            <div class="select-Categories pb-50">
                                <div class="small-section-tittle2">
                                    <h4>Publié dans</h4>
                                </div>
                                <label class="container">N’importe quand
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Aujourd’hui
                                    <input type="checkbox" checked="checked active">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Les 2 derniers jours
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Les 3 derniers jours
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Les 5 derniers jours
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Les 10 derniers jours
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des catégories d’emploi fin -->
                </div>
                <!-- Contenu droit -->
                <div class="col-xl-9 col-lg-9 col-md-8">
                    <section class="featured-job-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="count-job mb-35">
                                        <span>39 782 offres trouvées</span>
                                        <div class="select-job-items">
                                            <span>Trier par</span>
                                            <select name="select" id="nice-select">
                                                <option value="">Aucun</option>
                                                <option value="">Liste d’emplois</option>
                                                <option value="">Liste d’emplois</option>
                                                <option value="">Liste d’emplois</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                               <?php
            
                                    if($_display=="details"){
                                        $_ID = Input::get('id','get');
                                        $storiSerieTable = new StoriSerie();
                                        if($storiSerieTable->find($_ID)){
                                            StoriSerieController::addView($_ID);
                                            $stori_serie_data = $storiSerieTable->data();
                                            include 'includes/blog-details.php';
                                            
                                        }
                                    }
                                    
                                ?>

                            <!-- single-job-content -->
                              <?php
                                    $storiSerieTable = new StoriSerie();
                                    $storiSerieTable->selectQuery("SELECT * FROM `stori_serie` WHERE `state`='Published' ORDER BY pin_top DESC");

                                    if($storiSerieTable->count()){
                                        $i = 0;
                                        foreach($storiSerieTable->data() as $stori_serie_data){

                                            include 'includes/blog-item.php';
                                        }
                                    }
                            ?>
                            


                            <!-- Fin section emploi -->

                            <!-- Répétez autant de fois que nécessaire pour chaque offre -->
                            <!-- ... -->
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Zone de pagination -->
        <div class="pagination-area pb-115 text-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="single-wrap d-flex justify-content-center">
                            <nav aria-label="Exemple de navigation par page">
                                <ul class="pagination justify-content-start">
                                    <li class="page-item active"><a class="page-link" href="#">01</a></li>
                                    <li class="page-item"><a class="page-link" href="#">02</a></li>
                                    <li class="page-item"><a class="page-link" href="#">03</a></li>
                                    <li class="page-item"><a class="page-link" href="#"><span class="fa fa-arrow-right"></span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





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