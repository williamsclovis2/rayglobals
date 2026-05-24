<?php
   $page = "channel";
   require_once "admin/core/init.php";
   $_display = Input::get('page','get');
?>
<!DOCTYPE html>
<html>
   <head>
      <?php include("includes/head.php")?>
      <link rel="stylesheet" href="assets/css/channels.css">
   </head>
  

   <body class="hidden-bar-wrapper">
      <div class="page-wrapper">

         <header class="main-header header-style-two">
            <?php include("includes/header.php")?>
         </header>

         <div class="xs-sidebar-group info-group">
            <div class="xs-overlay xs-bg-black"></div>
            <?php include("includes/sidebar.php")?>
         </div>

         <section class="page-title">
            <div class="content" style="background-image: url(assets/images/podcast.jpg)">
               <div class="auto-container">
                  <h1>Notre chaîne YouTube <br> et notre radio FM</h1>
               </div>
            </div>
         </section>

         <!-- RAY GLOBALS FM — Radio Player Section -->
         <section id="rg-fm-section">
            <div class="container">
               <div class="rg-fm-card">
                  <div class="rg-fm-brand">
                     <div class="rg-fm-brand-text">
                        <span class="rg-fm-tag">RADIO EN LIGNE</span>
                        <h3 class="rg-fm-name"><img src="assets/images/globalplus.png"><span> - FM</span></h3>
                        <p class="rg-fm-slogan">L'info, la musique &amp; la culture — 24h/24</p>
                     </div>
                  </div>
                  <div class="rg-fm-center">
                     <div class="rg-fm-wave" id="rg-fm-wave">
                        <span></span><span></span><span></span><span></span>
                        <span></span><span></span><span></span><span></span>
                        <span></span><span></span><span></span><span></span>
                     </div>
                     <button class="rg-fm-playbtn" id="rg-fm-playbtn" aria-label="Écouter / Arrêter">
                        <i class="fa fa-play" id="rg-fm-icon"></i>
                     </button>
                     <p class="rg-fm-nowplaying" id="rg-fm-status">Cliquez pour écouter</p>
                  </div>
                  <div class="rg-fm-right">
                     <div class="rg-fm-vol-wrap">
                        <i class="fa fa-volume-down rg-fm-vol-icon" id="rg-fm-vol-left" title="Couper le son"></i>
                        <div class="rg-fm-vol-track">
                           <div class="rg-fm-vol-fill" id="rg-fm-vol-fill"></div>
                           <input type="range" class="rg-fm-vol" id="rg-fm-vol" min="0" max="1" step="0.01" value="0.8">
                        </div>
                        <i class="fa fa-volume-up rg-fm-vol-icon" id="rg-fm-vol-right"></i>
                     </div>
                     <a href="https://www.youtube.com/@Globalplus243" target="_blank" class="rg-fm-ext-link">
                        <i class="fa fa-external-link"></i> Écouter en ligne
                     </a>
                     <button class="rg-fm-share-btn" id="rg-fm-share-btn" title="Partager la radio">
                        <i class="fa fa-share-alt" id="rg-fm-share-icon"></i>
                        <span id="rg-fm-share-label">Partager</span>
                     </button>
                  </div>
               </div>
            </div>
         </section>

         <!-- Live Channel Section -->
         <section class="py-0 bg-light" id="channel-sec">
            <div class="container">
               <div class="text-left col-md-8">
                  <h2 class="section-title">Direct &amp; <span>Épisodes</span></h2>
                  <p class="section-desc">
                     Regardez nos émissions en direct directement depuis cette page et ne manquez aucun moment. Retrouvez également tous nos épisodes passés disponibles sur notre chaîne YouTube.
                  </p>
               </div>
               <div class="rg-live-wrap">
                  <!-- LEFT: Live Player -->
                  <div>
                     <div class="rg-live-label is-live" id="rg-live-badge">
                        <span class="dot"></span> EN DIRECT
                     </div>
                     <div class="rg-live-label is-offline" id="rg-offline-badge" style="display:none;">
                        <i class="fa fa-circle" style="font-size:8px;margin-right:4px;"></i> Hors ligne
                     </div>
                     <div class="rg-player-box" id="rg-player-box">
                        <div class="rg-nolive" id="rg-nolive-screen">
                           <div class="icon-ring">
                              <i class="fa fa-ban"></i>
                           </div>
                           <div>
                              <h5>Aucun direct en ce moment</h5>
                              <p>Revenez bientôt pour suivre nos émissions en direct. En attendant, découvrez nos épisodes passés sur notre chaîne.</p>
                              <div class="text-center">
                                 <a href="https://www.youtube.com/@Globalplus243" target="_blank" class="rg-yt-btn">
                                    <i class="fa fa-youtube-play"></i> Voir notre chaîne
                                 </a>
                              </div>
                           </div>
                        </div>
                        <iframe
                           id="rg-player-iframe"
                           allowfullscreen
                           allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                     </div>
                     <p class="text-muted mt-2" style="font-size:12px;">
                        <i class="fa fa-info-circle"></i>
                        Abonnez-vous à notre chaîne YouTube pour ne manquer aucun direct et partager nos contenus avec vos proches !
                     </p>
                  </div>
                  <!-- RIGHT: Past Episodes -->
                  <div class="rg-episodes-panel">
                     <div class="rg-episodes-header">
                        <span>Épisodes récents</span>
                        <a href="https://www.youtube.com/@Globalplus243" target="_blank">
                           Tout voir <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                     </div>
                     <div id="rg-episodes-list"></div>
                     <a href="https://www.youtube.com/@Globalplus243" target="_blank" class="rg-yt-subscribe">
                        <i class="fa fa-youtube-play" style="font-size:16px;"></i>
                        S'abonner à notre chaîne
                     </a>
                  </div>
               </div>
            </div>
         </section>

         <!-- Clients Section -->
         <section class="clients-section-two style-two" id="partners">
            <div class="auto-container">
               <div class="title-box">
                  <div class="row clearfix">
                     <div class="column col-lg-6 col-md-12 col-sm-12">
                        <div class="icon flaticon-email"></div>
                        <h2>Nos Meilleurs <br> Partenaires Corporatifs</h2>
                     </div>
                     <div class="column col-lg-6 col-md-12 col-sm-12">
                        <div class="text">Nous collaborons avec le secteur public pour bâtir des communautés prospères.</div>
                     </div>
                  </div>
               </div>
               <div class="inner-container">
                  <div class="sponsors-outer">
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

         <!-- Bootstrap Video Modals -->
         <?php foreach ([1,2,3,4,5,6] as $n): ?>
         <div class="modal fade" id="videoModal<?= $n ?>" tabindex="-1" aria-hidden="true">
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
         <?php endforeach; ?>

         <footer class="footer-style-two" style="background-image:url(assets/images/background/10.jpg)">
            <?php include("includes/footer.php")?>
         </footer>
      </div>
      <!-- End PageWrapper -->

      <script src="assets/js/bootstrap.bundle.min.js"></script>

      <script>
         // Pause video when modal is closed
         document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function () {
               var video = this.querySelector('video');
               if (video) { video.pause(); video.currentTime = 0; }
            });
         });
      </script>

     <script src="includes/channels.js"></script>

      <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
      <?php include("includes/script.php")?>
   </body>
</html>