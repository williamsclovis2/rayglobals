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
            <div class="content" style="background-image: url(assets/images/background/16.jpg)">
               <div class="auto-container">
                  <h1>Témoignages</h1>
               </div>
            </div>
            <ul class="page-breadcrumb">
               <li><a href="index">Accueil</a></li>
               <li>Témoignages</li>
            </ul>
         </section>
         <!-- End Page Title -->
         <!-- Testimonial Page Section -->
         <section class="testimonial-page-section">
            <div class="auto-container">
               <div class="row clearfix">
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-1.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>Michelle Yeoh</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-2.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>Polard</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-1.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>John Adam</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-1.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>Michelle Yeoh</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-2.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>Polard</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
                  <!-- Testimonial Block Four -->
                  <div class="testimonial-block-four col-lg-4 col-md-6 col-sm-12">
                     <div class="inner-box">
                        <div class="quote flaticon-right-quote"></div>
                        <div class="image">
                           <img src="assets/images/resource/author-1.jpg" alt="" />
                        </div>
                        <div class="text">Pellentesque fermentum mauris tellus, et vehicula lacus semper in. Suspendisse potenti. Proin at magna massa.</div>
                        <h6>John Adam</h6>
                        <div class="designation">Admin</div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- End Testimonial Page Section -->
         <!-- Projects Section Three -->
		<section class="projects-section-three" style="background-image:url(assets/images/background/pattern-2.png)" id="testimonial-video">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title light centered">
				<span class="icon flaticon-telephone"></span>
				<h2>Clients <span>Testimonials</span></h2>
				<div class="text">Découvrez ce que nos partenaires disent de nous à travers leurs témoignages vidéo</div>
			</div>
			<div class="project-carousel-two owl-carousel owl-theme">
				<!-- Gallery Block Three -->
				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/166.jpg" alt="Témoignage 1" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Optimisation du Transport <br> International</a></h4>
							<div class="designation">Témoignage de : <span>M. Pascal, TransLog</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal1"></a>
						</div>
					</div>
					</div>
				</div>

				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/177.jpg" alt="Témoignage 2" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Innovation dans la Fabrication <br> de Blocs</a></h4>
							<div class="designation">Témoignage de : <span>Mme Aline, Bâtir Plus</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal2"></a>
						</div>
					</div>
					</div>
				</div>

				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/188.jpg" alt="Témoignage 3" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Campagne Digitale <br> à Impact Social</a></h4>
							<div class="designation">Témoignage de : <span>David, Agence Digitale RW</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal3"></a>
						</div>
					</div>
					</div>
				</div>

				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/166.jpg" alt="Témoignage 4" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Importation de Matériaux <br> Innovants</a></h4>
							<div class="designation">Témoignage de : <span>Hassan, Construct Ltd.</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal4"></a>
						</div>
					</div>
					</div>
				</div>

				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/177.jpg" alt="Témoignage 5" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Distribution d’Équipements <br> Technologiques</a></h4>
							<div class="designation">Témoignage de : <span>Claire, TechDistrib</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal5"></a>
						</div>
					</div>
					</div>
				</div>

				<div class="gallery-block-three">
					<div class="inner-box">
					<div class="image">
						<a href="#"><img src="assets/images/gallery/188.jpg" alt="Témoignage 6" /></a>
						<div class="overlay-content">
							<h4><a href="project-detail.html">Simplification des Processus <br> Douaniers</a></h4>
							<div class="designation">Témoignage de : <span>Jean-Claude, LogiTrade</span></div>
							<a class="plus fa fa-play" href="#" data-bs-toggle="modal" data-bs-target="#videoModal6"></a>
						</div>
					</div>
					</div>
				</div>
			</div>

			<div class="lower-text">
				En complément de ces témoignages vidéo, restez à l’écoute de notre actualité, de nos projets phares et des retours d’expérience de nos partenaires.<br>
				N’oubliez pas de consulter notre <strong>podcast</strong> dans la section ci-dessous pour des échanges enrichissants et des discussions inspirantes. <a href="#podcast-sec">Voir nos podcasts</a>
			</div>
		</div>
		</section>
		<!-- End Projects Section Three -->

         <!-- End Projects Section Three -->
         <section class="projects-section-two py-5 bg-light" id="podcast-sec">
            <div class="container">
               <div class="text-center mb-4">
                  <h2><i class="fas fa-podcast"></i> Nos <span>Podcasts</span></h2>
               </div>
               <div class="row">
                  <div class="col-md-1"></div>
                  <div class="col-md-10">
                     <p class="text-muted  text-center mb-4">Depuis 2005, Ray Globals façonne le paysage de la région sud-centre grâce à son expertise multisectorielle. À travers nos podcasts, nous vous emmenons au cœur de nos activités, de nos histoires inspirantes et des enjeux majeurs dans les domaines du transport, de la construction, du commerce international, de l’électronique et de la communication d’entreprise.</p>
                  </div>
                  <div class="col-md-1"></div>
               </div>
               <div class="row">
                  <!-- Video Card -->
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod1.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Repeat for Video 2 -->
                  <!-- Repeat for Video 2 -->
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod2.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod2.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Repeat for Video 3 -->
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod1.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Repeat for Video 2 -->
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod2.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Repeat for Video 3 -->
                  <div class="col-md-4">
                     <div class="video-container">
                        <video class="custom-video" poster="assets/images/pod3.png" src="assets/images/video.mp4"></video>
                        <div class="controls-overlay">
                           <div class="top-bar">
                              <div class="user-info">
                                 <div class="pod-profile">
                                    <img src="assets/images/profile.png" alt="User">
                                 </div>
                                 <div>
                                    <strong>Video Title 1</strong><br><small>Description</small>
                                 </div>
                              </div>
                              <div class="action-buttons">
                                 <button><i class="fa fa-heart"></i></button>
                                 <button><i class="fa fa-share"></i></button>
                                 <button><i class="fa fa-ellipsis-v"></i></button>
                              </div>
                           </div>
                           <div class="center-play">
                              <button class="play-btn"><i class="fa fa-play"></i></button>
                           </div>
                           <div class="bottom-controls">
                              <span class="time start">00:00</span>
                              <input type="range" class="progress-bar form-range" min="0" value="0" step="1">
                              <span class="time end">00:00</span>
                              <button class="fullscreen-btn"><i class="fa fa-expand"></i></button>
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
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/6.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/7.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/8.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/9.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/10.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/6.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/7.png" alt=""></a></figure>
                        </li>
                        <li class="slide-item">
                           <figure class="image-box"><a href="#"><img src="assets/images/clients/8.png" alt=""></a></figure>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </section>
         <!-- End Clients Section Two -->
         <!-- Bootstrap Modals  -->
         <!-- Modal for Video 1 -->
         <div class="modal fade" id="videoModal1" tabindex="-1" aria-labelledby="videoModal1Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Video 2 -->
         <div class="modal fade" id="videoModal2" tabindex="-1" aria-labelledby="videoModal2Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Video 3 -->
         <div class="modal fade" id="videoModal3" tabindex="-1" aria-labelledby="videoModal3Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Video 4 -->
         <div class="modal fade" id="videoModal4" tabindex="-1" aria-labelledby="videoModal4Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Video 5 -->
         <div class="modal fade" id="videoModal5" tabindex="-1" aria-labelledby="videoModal5Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Video 6 -->
         <div class="modal fade" id="videoModal6" tabindex="-1" aria-labelledby="videoModal6Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-body p-0">
                     <video class="modal-video" controls>
                        <source src="assets/images/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                     </video>
                  </div>
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
      <script src="assets/js/bootstrap.bundle.min.js"></script>
      <!-- Custom JS -->
      <script>
         // Pause video when modal is closed
         document.querySelectorAll('.modal').forEach(modal => {
             modal.addEventListener('hidden.bs.modal', function () {
                 const video = this.querySelector('video');
                 if (video) {
                     video.pause();
                     video.currentTime = 0;
                 }
             });
         });
      </script>
   </body>
   <!-- Scroll To Top -->
   <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
   <?php include("includes/script.php")?>
   </body>
</html>