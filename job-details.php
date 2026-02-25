<?php
    $page = "vacancies";
    require_once "admin/core/init.php";
    $_ID_ = Input::get('id','get');

    $_ID = Hash::decryptToken($_ID_);

    $storiSerieTable = new StoriSerie();
    $storiSerieTable->selectQuery(" SELECT * FROM `stori_serie` WHERE id='".$_ID."'");

     $stori_serie_data =  $storiSerieTable->first();

     if(! $stori_serie_data)
        Redirect::to(DNADMIN + "/blogs");
    
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
    	<div class="content" style="background-image: url(<?= DN ?>/assets/images/resource/hiring.jpg)">
			<div class="auto-container">
				<h1>Détails du travail</h1>
			</div>
		</div>
		<ul class="page-breadcrumb">
			<li><a href="vacancies">Nos offres</a></li>
			<li>Détails</li>
		</ul>
    </section>
    <!-- End Main Slider Section -->
	
	
  <section id="job-details">
    <!-- Début de l’offre d’emploi -->
    <div class="job-post-company pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-between">
                <!-- Contenu gauche -->
                <div class="col-xl-7 col-lg-8">
                    <!-- Offre d’emploi unique -->
                    <div class="single-job-items mb-50">
                        <div class="job-items">
                            <div class="company-img company-img-details">
                                <a href="#"><img src="assets/images/icon/job-list1.png" alt=""></a>
                            </div>
                            <div class="job-tittle">
                                <a href="#">
                                    <h4><?= $stori_serie_data->serie_title ?></h4>
                                </a>
                                <ul>
                                    <li><?= $stori_serie_data->company_name ?></li>
                                    <li><i class="fas fa-map-marker-alt"></i>Congo, Kinshasa</li>
                                    <li>$3500 - $4000</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Fin de l’offre d’emploi unique -->

                    <div class="job-post-details">
                        <div class="post-details1 mb-50">
                            <!-- Petit Titre -->
                            <div class="small-section-tittle">
                                <h4>Description du poste</h4> 
                            </div>
                            <p><?= $stori_serie_data->dtserie_description ?></p>
                        </div>

                        <div class="post-details2 mb-50">
                            <!-- Petit Titre -->
                            <div class="small-section-tittle">
                                <h4>Compétences et connaissances requises</h4>
                            </div>
                            <ul>
                                <li><?= $stori_serie_data->comp_1 ?></li>
                                <li><?= $stori_serie_data->comp_2 ?></li>
                                <li><?= $stori_serie_data->comp_3 ?></li>
                                <li><?= $stori_serie_data->comp_4 ?></li>
                                <li><?= $stori_serie_data->comp_5 ?></li>
                            </ul>
                        </div>

                        <div class="post-details2 mb-50">
                            <!-- Petit Titre -->
                            <div class="small-section-tittle">
                                <h4>Éducation + Expérience</h4>
                            </div>
                            <ul>
                                <li><?= $stori_serie_data->education ?></li>
                                <li><?= $stori_serie_data->exp_1 ?></li>
                                <li><?= $stori_serie_data->exp_2 ?></li>
                                <li><?= $stori_serie_data->exp_3 ?></li>
                                <li><?= $stori_serie_data->exp_4 ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contenu droit -->
                <div class="col-xl-4 col-lg-4">
                    <div class="post-details3 mb-50">
                        <!-- Petit Titre -->
                        <div class="small-section-tittle">
                            <h4>Aperçu de l’emploi</h4>
                        </div>
                        <ul>
                            <li>Date de publication : <span><?= $stori_serie_data->posting_date ?></span></li>
                            <li>Lieu : <span>RDC Congo</span></li>
                            <li>Postes vacants : <span>02</span></li>
                            <li>Type de contrat : <span><?= $stori_serie_data->job_type ?></span></li>
                            <li>Salaire : <span>$7,800 par an</span></li>
                            <li>Date limite de candidature : <span>12 Sep 2020</span></li>
                        </ul>
                        <div class="apply-btn2">
                            <button data-bs-toggle="modal" data-bs-target="#jobApplicationModal" class="btn">Postuler maintenant</button>
                        </div>
                    </div>

                    <div class="post-details4 mb-50">
                        <!-- Petit Titre -->
                        <div class="small-section-tittle">
                            <h4>Informations sur l’entreprise</h4>
                        </div>
                        <span>RayGlobal</span>
                        <p>Il est bien établi qu’un lecteur sera distrait par le contenu lisible d’une page lorsqu’il en examine la mise en page.</p>
                        <ul>
                            <li>Nom : <span>RayGlobal</span></li>
                            <li>Site web : <span>rayglobal.org</span></li>
                            <li>Email : <span>contact@rayglobal.org</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de l’offre d’emploi -->
</section>


  <!-- Job application modal -->

  <!-- Bootstrap Modal -->
<div class="modal fade" id="jobApplicationModal" tabindex="-1" aria-labelledby="jobApplicationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="jobApplicationModalLabel">Job Application Form</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form enctype="multipart/form-data">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="First name" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Last name" required>
              </div>
              <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Email" required>
              </div>
              <div class="col-md-6">
                <input type="tel" class="form-control" placeholder="Phone" required>
              </div>
              <div class="col-md-6">
                <select class="form-select" required>
                  <option selected disabled>Select country</option>
                  <option>Rwanda</option>
                  <option>Kenya</option>
                  <option>Uganda</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="City" required>
              </div>
              <div class="col-12">
                <input type="text" class="form-control" placeholder="Address" required>
              </div>
              <div class="col-12">
                <select class="form-select" required>
                  <option selected disabled>Choose desired position</option>
                  <option>Software Developer</option>
                  <option>Graphic Designer</option>
                  <option>Data Analyst</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-12">
                <textarea class="form-control" placeholder="Additional info" rows="3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Add your CV</label>
                <input type="file" class="form-control" accept=".pdf,.doc,.docx" required>
                <small class="text-muted">Only: pdf/doc | Max size: 1MB</small>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Application</button>
        </div>
      </form>
    </div>
  </div>
</div>

	

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