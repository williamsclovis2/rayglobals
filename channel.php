<?php
   $page = "channel";
   require_once "admin/core/init.php";
    $_display = Input::get('page','get');
   ?>
<!DOCTYPE html>
<html>
   <head>
      <?php include("includes/head.php")?>
   </head>
   <style>
      /* ---- Live Section ---- */

      /* ---- No-live screen text fix for mobile ---- */
.rg-nolive > div {
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}
.rg-nolive h5 {
  font-size: 15px;
  padding: 0 10px;
  word-break: break-word;
}
.rg-nolive p {
  font-size: 12px;
  max-width: 100%;
  padding: 0 10px;
  word-break: break-word;
}
.rg-nolive .icon-ring {
  flex-shrink: 0;
}

@media (max-width: 480px) {
  .rg-nolive {
    padding: 20px 16px;
    gap: 10px;
    justify-content: center;
  }
  .rg-nolive > div {
    padding: 0;
  }
  .rg-nolive h5 {
    font-size: 14px;
    margin-bottom: 6px;
  }
  .rg-nolive p {
    font-size: 11px;
    line-height: 1.5;
    margin-bottom: 12px;
  }
  .rg-nolive a.rg-yt-btn {
    font-size: 12px;
    padding: 8px 14px;
  }
}
      #channel-sec {
      padding: 70px 0 !important;
      background: #f8f8f8;
      }
      #channel-sec .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #c0392b;
      color: #fff;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.08em;
      padding: 5px 14px;
      border-radius: 999px;
      margin-bottom: 14px;
      text-transform: uppercase;
      }
      #channel-sec .section-title {
      font-size: 32px;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 10px;
      text-transform: uppercase;
      }
      #channel-sec .section-title span { color: #f2c22f; }
      #channel-sec .section-desc {
      color: #777;
      font-size: 15px;
      /* max-width: 580px; */
      margin: 0 auto 40px;
      line-height: 1.7;
      }
      /* ---- Live Player ---- */
      .rg-live-wrap {
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 24px;
      align-items: start;
      }
      /* =============================================
      TABLET — max 991px
      ============================================= */
      @media (max-width: 991px) {
      #channel-sec {
      padding: 50px 0;
      }
      #channel-sec .section-title {
      font-size: 26px;
      }
      #channel-sec .section-desc {
      font-size: 14px;
      margin-bottom: 30px;
      }
      .rg-live-wrap {
      grid-template-columns: 1fr;
      gap: 28px;
      }
      .rg-episodes-panel {
      margin-top: 0;
      }
      }
      /* =============================================
      MOBILE — max 767px
      ============================================= */
      @media (max-width: 767px) {
      #channel-sec {
      padding: 40px 0;
      }
      #channel-sec .section-badge {
      font-size: 10px;
      padding: 4px 12px;
      }
      #channel-sec .section-title {
      font-size: 22px;
      margin-bottom: 8px;
      }
      #channel-sec .section-desc {
      font-size: 13px;
      margin-bottom: 24px;
      padding: 0 8px;
      }
      .rg-live-wrap {
      gap: 20px;
      }
      .rg-player-box {
      border-radius: 10px;
      }
      .rg-nolive .icon-ring {
      width: 60px;
      height: 60px;
      }
      .rg-nolive .icon-ring i {
      font-size: 26px;
      }
      .rg-nolive h5 {
      font-size: 15px;
      }
      .rg-nolive p {
      font-size: 12px;
      padding: 0 16px;
      }
      .rg-nolive a.rg-yt-btn {
      font-size: 12px;
      padding: 8px 16px;
      }
      .rg-episodes-header span {
      font-size: 14px;
      }
      .rg-ep-card {
      padding: 8px;
      gap: 10px;
      }
      .rg-ep-thumb {
      width: 90px;
      height: 54px;
      }
      .rg-ep-thumb .play-icon i {
      font-size: 16px;
      }
      .rg-ep-info strong {
      font-size: 12px;
      }
      .rg-ep-info small {
      font-size: 10px;
      }
      .rg-yt-subscribe {
      font-size: 12px;
      padding: 9px;
      }
      }
      /* =============================================
      SMALL MOBILE — max 480px
      ============================================= */
      @media (max-width: 480px) {
      #channel-sec {
      padding: 30px 0;
      }
      #channel-sec .section-title {
      font-size: 20px;
      }
      .rg-live-wrap {
      gap: 16px;
      }
      .rg-ep-card {
      flex-direction: column;
      align-items: flex-start;
      }
      .rg-ep-thumb {
      width: 100%;
      height: 180px;
      border-radius: 8px;
      }
      .rg-ep-info {
      padding-top: 0;
      padding-left: 2px;
      }
      .rg-ep-info strong {
      font-size: 13px;
      -webkit-line-clamp: 3;
      }
      .rg-ep-info small {
      font-size: 11px;
      }
      .rg-nolive {
      gap: 12px;
      padding: 16px;
      }
      }
      .rg-live-label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 999px;
      margin-bottom: 10px;
      }
      .rg-live-label.is-live {
      background: #c0392b;
      color: #fff;
      }
      .rg-live-label.is-live .dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: #fff;
      animation: rgBlink 1.2s infinite;
      }
      .rg-live-label.is-offline {
      background: #e9ecef;
      color: #999;
      border: 1px solid #ddd;
      }
      @keyframes rgBlink { 0%,100%{opacity:1} 50%{opacity:0.25} }
      .rg-player-box {
      aspect-ratio: 16/9;
      border-radius: 14px;
      overflow: hidden;
      background: #111827;
      position: relative;
      }
      .rg-player-box iframe {
      width: 100%; height: 100%; border: 0;
      display: none;
      }
      /* No-live screen */
      .rg-nolive {
      position: absolute; inset: 0;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 18px;
      background: #111827;
      }
      .rg-nolive .icon-ring {
      width: 80px; height: 80px;
      border-radius: 50%;
      background: rgba(192,57,43,0.15);
      display: flex; align-items: center; justify-content: center;
      }
      .rg-nolive .icon-ring i {
      font-size: 36px;
      color: #c0392b;
      }
      .rg-nolive h5 {
      color: #e5e7eb;
      font-size: 17px;
      font-weight: 600;
      margin: 0 0 6px;
      text-align: center;
      }
      .rg-nolive p {
      color: #9ca3af;
      font-size: 13px;
      text-align: center;
      max-width: 300px;
      margin: 0 0 16px;
      line-height: 1.6;
      }
      .rg-nolive a.rg-yt-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #c0392b;
      color: #fff;
      text-decoration: none;
      padding: 9px 20px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      transition: background 0.2s;
      }
      .rg-nolive a.rg-yt-btn:hover { background: #a93226; color: #fff; }
      /* ---- Past Episodes Sidebar ---- */
      .rg-episodes-panel {}
      .rg-episodes-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
      }
      .rg-episodes-header span {
      font-size: 15px;
      font-weight: 600;
      color: #1a1a2e;
      }
      .rg-episodes-header a {
      font-size: 12px;
      color: #f2c22f;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 4px;
      }
      .rg-episodes-header a:hover { text-decoration: underline; }
      .rg-ep-card {
      display: flex;
      gap: 12px;
      padding: 10px;
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      background: #fff;
      cursor: pointer;
      margin-bottom: 12px;
      transition: box-shadow 0.2s, border-color 0.2s;
      text-decoration: none;
      }
      .rg-ep-card:hover {
      border-color: #c0392b;
      box-shadow: 0 2px 12px rgba(192,57,43,0.1);
      }
      .rg-ep-thumb {
      position: relative;
      flex-shrink: 0;
      width: 110px;
      height: 66px;
      border-radius: 8px;
      overflow: hidden;
      background: #1f2937;
      }
      .rg-ep-thumb img {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
      }
      .rg-ep-thumb .play-icon {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      background: rgba(0,0,0,0.35);
      transition: background 0.2s;
      }
      .rg-ep-card:hover .play-icon { background: rgba(192,57,43,0.5); }
      .rg-ep-thumb .play-icon i { font-size: 22px; color: #fff; }
      .rg-ep-info { flex: 1; min-width: 0; padding-top: 2px; }
      .rg-ep-info strong {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      font-size: 13px;
      font-weight: 600;
      color: #1a1a2e;
      line-height: 1.4;
      margin-bottom: 5px;
      }
      .rg-ep-info small { font-size: 11px; color: #aaa; }
      .rg-yt-subscribe {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 4px;
      padding: 10px;
      border-radius: 10px;
      background: rgba(192,57,43,0.06);
      border: 1px solid rgba(192,57,43,0.2);
      color: #c0392b;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s;
      }
      .rg-yt-subscribe:hover {
      background: rgba(192,57,43,0.12);
      color: #c0392b;
      }
   </style>
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
            <div class="content" style="background-image: url(assets/images/podcast.jpg)">
               <div class="auto-container">
                  <h1>Notre chaîne youtube</h1>
               </div>
            </div>
            <ul class="page-breadcrumb">
               <li><a href="index">Accueil</a></li>
               <li>channels </li>
            </ul>
         </section>
         <!-- End Page Title -->
         <!-- ======================================================
            LIVE CHANNEL SECTION  (replaces old podcast section)
            ====================================================== -->
         <!-- ======================================================
            LIVE CHANNEL SECTION  (replaces old podcast section)
            ====================================================== -->
         <section class="py-0 bg-light" id="channel-sec">
            <div class="container">
               <!-- Header -->
               <div class="text-left col-md-8">
                  <h2 class="section-title">Direct &amp; <span>Épisodes</span></h2>
                  <p class="section-desc">
                     Regardez nos émissions en direct directement depuis cette page et ne manquez aucun moment. Retrouvez également tous nos épisodes passés disponibles sur notre chaîne YouTube.
                  </p>
               </div>
               <!-- Main Grid -->
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
                        <!-- No-live placeholder (shown when not live) -->
                        <div class="rg-nolive" id="rg-nolive-screen">
                           <div class="icon-ring">
                              <i class="fa fa-ban"></i>
                           </div>
                           <div>
                              <h5>Aucun direct en ce moment</h5>
                              <p>Revenez bientôt pour suivre nos émissions en direct. En attendant, découvrez nos épisodes passés sur notre chaîne.</p>
                              <div class="text-center">
                                 <a href="https://www.youtube.com/@rayproduction243" target="_blank" class="rg-yt-btn">
                                 <i class="fa fa-youtube-play"></i> Voir notre chaîne
                                 </a>
                              </div>
                           </div>
                        </div>
                        <!-- Live iframe (shown when live OR when user clicks an episode) -->
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
                        <a href="https://www.youtube.com/@rayproduction243" target="_blank">
                        Tout voir <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                     </div>
                     <!-- Episode cards are injected here by JS -->
                     <div id="rg-episodes-list"></div>
                     <a href="https://www.youtube.com/@rayproduction243" target="_blank" class="rg-yt-subscribe">
                     <i class="fa fa-youtube-play" style="font-size:16px;"></i>
                     S'abonner à notre chaîne
                     </a>
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
      <script>
         (function () {
         
           var CHANNEL_ID = 'UCH1rSXDmXzRphOYrU5xPn3Q';
         
           var EPISODES = [
             {
               id: 'rdbkCbgYdwk',
               title: 'Transport & Logistique — Épisode 1',
               meta: 'Ray Globals · 12 min'
             },
             {
               id: 'rdbkCbgYdwk',
               title: 'Commerce International — Épisode 2',
               meta: 'Ray Globals · 18 min'
             },
             {
               id: 'rdbkCbgYdwk',
               title: 'Électronique & Innovation — Épisode 3',
               meta: 'Ray Globals · 24 min'
             }
           ];
         
           var iframe        = document.getElementById('rg-player-iframe');
           var nolive        = document.getElementById('rg-nolive-screen');
           var liveBadge     = document.getElementById('rg-live-badge');
           var offBadge      = document.getElementById('rg-offline-badge');
           var epList        = document.getElementById('rg-episodes-list');
           var watchingEp    = false;
         
           /* Build episode cards */
           EPISODES.forEach(function (ep) {
             var thumb = 'https://img.youtube.com/vi/' + ep.id + '/mqdefault.jpg';
             var card = document.createElement('div');
             card.className = 'rg-ep-card';
             card.innerHTML =
               '<div class="rg-ep-thumb">' +
                 '<img src="' + thumb + '" alt="' + ep.title + '" loading="lazy">' +
                 '<div class="play-icon"><i class="fa fa-play"></i></div>' +
               '</div>' +
               '<div class="rg-ep-info">' +
                 '<strong>' + ep.title + '</strong>' +
                 '<small>' + ep.meta + '</small>' +
               '</div>';
             card.addEventListener('click', function () {
               watchingEp = true;
               playEpisode(ep.id);
             });
             epList.appendChild(card);
           });
         
           /* Show offline screen */
           function showOffline() {
             iframe.style.display = 'none';
             iframe.src = '';
             nolive.style.display = 'flex';
             liveBadge.style.display = 'none';
             offBadge.style.display = 'inline-flex';
           }
         
           /* Load live stream */
          /* Load live stream */
           function loadLive() {
             watchingEp = false;
             var liveUrl = 'https://www.youtube.com/embed/live_stream?channel=' + CHANNEL_ID + '&autoplay=1&rel=0';
             if (iframe.src !== liveUrl) {
               iframe.src = liveUrl;
             }
             iframe.style.display = 'block';
             nolive.style.display = 'none';
             liveBadge.style.display = 'inline-flex';
             offBadge.style.display = 'none';
           }
         
           /* Check live status via our PHP proxy */
           function checkLiveStatus() {
             if (watchingEp) return;
             fetch('live_check.php?_=' + Date.now())
               .then(function(res) { return res.json(); })
               .then(function(data) {
                 if (data.live) {
                   loadLive();
                 } else {
                   showOffline();
                 }
               })
               .catch(function() {
                 /* On error don't touch the current state */
               });
           }
         
           /* Play a past episode */
           function playEpisode(videoId) {
             iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
             iframe.style.display = 'block';
             nolive.style.display = 'none';
             liveBadge.style.display = 'none';
             offBadge.style.display = 'inline-flex';
           }
         
           /* Check live status via our PHP proxy */
           function checkLiveStatus() {
             if (watchingEp) return;
             fetch('live_check.php')
               .then(function(res) { return res.json(); })
               .then(function(data) {
                 if (data.live) {
                   loadLive();
                 } else {
                   showOffline();
                 }
               })
               .catch(function() {
                 showOffline();
               });
           }
         
           /* Check on load, then every 30 seconds */
           checkLiveStatus();
           setInterval(checkLiveStatus, 30000);
         
         })();
      </script>
   </body>
   <!-- Scroll To Top -->
   <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
   <?php include("includes/script.php")?>
   </body>
</html>