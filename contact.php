
<?php
    $page = "contact";
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
    	<div class="content" style="background-image: url(assets/images/resource/contact.jpg)">
			<div class="auto-container">
				<h1>Contactez Nous</h1>
			</div>
		</div>
		<ul class="page-breadcrumb">
			<li><a href="vacancies">Accueil</a></li>
			<li>Contact</li>
		</ul>
    </section>
    <!-- End Main Slider Section -->
    	<!-- Contact Page Section -->
	<section class="contact-page-section">
        <div class="image-layer" style="background-image:url(assets/images/background/4.jpg)"></div>
        <div class="auto-container">
            <!-- Titre principal -->
            <div class="title-box">
                <h2>Une question ?</h2>
                <div class="text">
                    Merci de l'intérêt que vous portez à Ray Globals. Veuillez remplir le formulaire ci-dessous ou nous écrire à l’adresse indiquée.<br>
                    Nous vous répondrons dans les plus brefs délais.
                </div>
            </div>

            <div class="row clearfix">
                <div class="info-column col-lg-2 col-md-2 col-sm-12"></div>

                <!-- Adresse email -->
                <div class="info-column col-lg-4 col-md-6 col-sm-12">
                    <div class="inner-column">
                        <span class="icon flaticon-email-3"></span>
                        <div class="text">
                            info@rayglobals.org<br>
                            support@rayglobals.org
                        </div>
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="info-column col-lg-4 col-md-6 col-sm-12">
                    <div class="inner-column">
                        <span class="icon flaticon-telephone"></span>
                        <div class="text">
                            <a href="tel:+243814676307">+243 814 676 307</a><br>
                            <a href="tel:+243895954301">+243 895 954 301</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Google Map -->
            <div class="map-boxed">
                <div class="map-outer">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3977.265799781083!2d15.290365174985454!3d-4.317037946797999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a33fa69be13a5%3A0xf3ccbd8a0619aee2!2sGombe%2C%20Kinshasa%2C%20Democratic%20Republic%20of%20the%20Congo!5e0!3m2!1sfr!2s!4v1721054932982!5m2!1sfr!2s" allowfullscreen=""></iframe>
                </div>
            </div>

            <!-- Section inférieure -->
            <div class="lower-section" id="contact">
                <div class="row clearfix">

                    <!-- Présentation entreprise -->
                    <div class="title-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <h2>Chez Ray Globals</h2>
                            <div class="text">
                                Nous croyons que la diversité des compétences est une force. Notre équipe rassemble des experts passionnés et qualifiés, déterminés à proposer des solutions adaptées aux défis actuels. Nous nous engageons à offrir un service personnalisé et conforme aux normes internationales.
                            </div>
                            <!-- Bouton À propos -->
                            <div class="button-box">
                                <a class="btn-style-two theme-btn" href="about.html"><span class="txt">En savoir plus</span></a>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de contact -->
                    <div class="form-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="title-box">
                                <h3>Demande de devis</h3>
                                <div class="text">Obtenez une réponse rapide à vos besoins spécifiques</div>
                            </div>

                            <!-- Formulaire -->
                            <div class="default-form contact-form">
                                <form method="post" action="https://uniqthemes.com/html/bricks/sendemail.php" id="contact-form">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Nom" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="phone" placeholder="Téléphone" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="subject" placeholder="Objet" required>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" class="theme-btn btn-style-four">
                                            <span class="txt">Nous contacter</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Fin formulaire -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


	<!-- End Contact Page Section -->

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