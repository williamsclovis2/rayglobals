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
      /* TABLET — max 991px */
      @media (max-width: 991px) {
         #channel-sec { padding: 50px 0; }
         #channel-sec .section-title { font-size: 26px; }
         #channel-sec .section-desc { font-size: 14px; margin-bottom: 30px; }
         .rg-live-wrap { grid-template-columns: 1fr; gap: 28px; }
         .rg-episodes-panel { margin-top: 0; }
      }
      /* MOBILE — max 767px */
      @media (max-width: 767px) {
         #channel-sec { padding: 40px 0; }
         #channel-sec .section-badge { font-size: 10px; padding: 4px 12px; }
         #channel-sec .section-title { font-size: 22px; margin-bottom: 8px; }
         #channel-sec .section-desc { font-size: 13px; margin-bottom: 24px; padding: 0 8px; }
         .rg-live-wrap { gap: 20px; }
         .rg-player-box { border-radius: 10px; }
         .rg-nolive .icon-ring i { font-size: 20px; }
         .rg-nolive h5 { font-size: 15px; }
         .rg-nolive p { font-size: 12px; padding: 0 16px; }
         .rg-nolive a.rg-yt-btn { font-size: 12px; padding: 8px 16px; }
         .rg-episodes-header span { font-size: 14px; }
         .rg-ep-card { padding: 8px; gap: 10px; }
         .rg-ep-thumb { width: 90px; height: 54px; }
         .rg-ep-thumb .play-icon i { font-size: 16px; }
         .rg-ep-info strong { font-size: 12px; }
         .rg-ep-info small { font-size: 10px; }
         .rg-yt-subscribe { font-size: 12px; padding: 9px; }
      }
      /* SMALL MOBILE — max 480px */
      @media (max-width: 480px) {
         #channel-sec { padding: 30px 0; }
         #channel-sec .section-title { font-size: 20px; }
         .rg-live-wrap { gap: 16px; }
         .rg-ep-card { flex-direction: column; align-items: flex-start; }
         .rg-ep-thumb { width: 100%; height: 180px; border-radius: 8px; }
         .rg-ep-info { padding-top: 0; padding-left: 2px; }
         .rg-ep-info strong { font-size: 13px; -webkit-line-clamp: 3; }
         .rg-ep-info small { font-size: 11px; }
         .rg-nolive { gap: 12px; padding: 16px; }
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
      .rg-live-label.is-live { background: #c0392b; color: #fff; }
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
      .rg-nolive {
         position: absolute; inset: 0;
         display: flex; flex-direction: column;
         align-items: center; justify-content: center;
         gap: 18px;
         background: #111827;
      }
      .rg-nolive .icon-ring {
         display: flex; align-items: center; justify-content: center;
      }
      .rg-nolive .icon-ring i { font-size: 36px; color: #c0392b; }
      .rg-nolive h5 {
         color: #e5e7eb; font-size: 17px; font-weight: 600;
         margin: 0 0 6px; text-align: center;
      }
      .rg-nolive p {
         color: #9ca3af; font-size: 13px; text-align: center;
         max-width: 300px; margin: 0 0 16px; line-height: 1.6;
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
      .rg-episodes-header {
         display: flex;
         align-items: center;
         justify-content: space-between;
         margin-bottom: 14px;
      }
      .rg-episodes-header span { font-size: 15px; font-weight: 600; color: #1a1a2e; }
      .rg-episodes-header a {
         font-size: 12px; color: #f2c22f; text-decoration: none;
         display: flex; align-items: center; gap: 4px;
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
      .rg-ep-card:hover { border-color: #c0392b; box-shadow: 0 2px 12px rgba(192,57,43,0.1); }
      .rg-ep-thumb {
         position: relative;
         flex-shrink: 0;
         width: 110px; height: 66px;
         border-radius: 8px;
         overflow: hidden;
         background: #1f2937;
      }
      .rg-ep-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
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
         font-size: 13px; font-weight: 600; color: #1a1a2e;
         line-height: 1.4; margin-bottom: 5px;
      }
      .rg-ep-info small { font-size: 11px; color: #aaa; }
      .rg-yt-subscribe {
         display: flex;
         align-items: center; justify-content: center;
         gap: 8px;
         margin-top: 4px;
         padding: 10px;
         border-radius: 10px;
         background: rgba(192,57,43,0.06);
         border: 1px solid rgba(192,57,43,0.2);
         color: #c0392b; font-size: 13px; font-weight: 600;
         text-decoration: none;
         transition: background 0.2s;
      }
      .rg-yt-subscribe:hover { background: rgba(192,57,43,0.12); color: #c0392b; }

      /* ======================================================
         RAY GLOBALS FM — Radio Player
         ====================================================== */
      #rg-fm-section {
         background: #1a1a2e;
         padding: 28px 0;
         border-bottom: 1px solid rgba(255,255,255,0.06);
      }
      .rg-fm-card {
         display: flex;
         align-items: center;
         gap: 24px;
         background: #ffffff;
         border: 1px solid rgba(0,0,0,0.08);
         border-radius: 16px;
         padding: 22px 28px;
         box-shadow: 0 4px 32px rgba(0,0,0,0.3);
         max-width: 860px;
         margin: 0 auto;
         width: 100%;
      }
      .rg-fm-brand { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
      .rg-fm-logo-ring {
         width: 70px; height: 70px;
         border-radius: 12px; overflow: hidden;
         background: #000;
         display: flex; align-items: center; justify-content: center;
         box-shadow: 0 0 0 3px rgba(0,0,0,0.15);
         flex-shrink: 0;
      }
      .rg-fm-logo-ring img { width: 100%; height: 100%; object-fit: cover; display: block; }
      .rg-fm-brand-text { display: flex; flex-direction: column; }
      .rg-fm-tag {
         font-size: 9px; font-weight: 700; letter-spacing: 0.12em;
         color: #e05a00; text-transform: uppercase; margin-bottom: 2px;
      }
      .rg-fm-name {
         font-size: 20px; font-weight: 800; color: #111;
         margin: 0 0 2px; line-height: 1.1; white-space: nowrap;
      }
      .rg-fm-name img { width: 150px; }
      .rg-fm-name span { color: #e05a00; font-size: 28px; }
      .rg-fm-slogan { font-size: 11px; color: #666; margin: 0; white-space: nowrap; }
      .rg-fm-center {
         flex: 1; display: flex; flex-direction: column;
         align-items: center; gap: 10px; min-width: 0;
      }
      .rg-fm-wave { display: flex; align-items: flex-end; gap: 3px; height: 36px; }
      .rg-fm-wave span {
         display: block; width: 4px; border-radius: 4px;
         background: #ddd; height: 6px;
         transform-origin: bottom; transition: background 0.3s;
      }
      .rg-fm-wave.playing span { animation: rgWave 0.9s ease-in-out infinite alternate; background: #e05a00; }
      .rg-fm-wave.playing span:nth-child(1)  { animation-duration: 0.60s; }
      .rg-fm-wave.playing span:nth-child(2)  { animation-duration: 0.80s; }
      .rg-fm-wave.playing span:nth-child(3)  { animation-duration: 0.50s; }
      .rg-fm-wave.playing span:nth-child(4)  { animation-duration: 0.70s; }
      .rg-fm-wave.playing span:nth-child(5)  { animation-duration: 1.00s; }
      .rg-fm-wave.playing span:nth-child(6)  { animation-duration: 0.65s; }
      .rg-fm-wave.playing span:nth-child(7)  { animation-duration: 0.85s; }
      .rg-fm-wave.playing span:nth-child(8)  { animation-duration: 0.55s; }
      .rg-fm-wave.playing span:nth-child(9)  { animation-duration: 0.75s; }
      .rg-fm-wave.playing span:nth-child(10) { animation-duration: 0.95s; }
      .rg-fm-wave.playing span:nth-child(11) { animation-duration: 0.62s; }
      .rg-fm-wave.playing span:nth-child(12) { animation-duration: 0.82s; }
      @keyframes rgWave { 0% { height: 5px; } 100% { height: 34px; } }
      .rg-fm-playbtn {
         width: 52px; height: 52px;
         border-radius: 50%;
         background: linear-gradient(135deg, #e05a00, #ff7a1f);
         border: none; color: #fff; font-size: 18px;
         display: flex; align-items: center; justify-content: center;
         cursor: pointer;
         box-shadow: 0 4px 18px rgba(224,90,0,0.4);
         transition: transform 0.15s, box-shadow 0.15s, background 0.3s;
         flex-shrink: 0;
      }
      .rg-fm-playbtn:hover { transform: scale(1.08); box-shadow: 0 6px 24px rgba(224,90,0,0.55); }
      .rg-fm-playbtn:active { transform: scale(0.97); }
      .rg-fm-btn-playing {
         background: linear-gradient(135deg, #c44f00, #e05a00) !important;
         box-shadow: 0 0 0 6px rgba(224,90,0,0.2), 0 4px 18px rgba(224,90,0,0.45) !important;
         animation: rgPulse 1.8s ease-in-out infinite;
      }
      @keyframes rgPulse {
         0%, 100% { box-shadow: 0 0 0 6px rgba(224,90,0,0.2), 0 4px 18px rgba(224,90,0,0.45); }
         50%       { box-shadow: 0 0 0 12px rgba(224,90,0,0.06), 0 4px 18px rgba(224,90,0,0.45); }
      }
      .rg-fm-nowplaying { font-size: 11px; color: #888; margin: 0; text-align: center; min-height: 16px; }
      .rg-fm-nowplaying.live { color: #e05a00; font-weight: 600; }
      .rg-fm-right { display: flex; flex-direction: column; align-items: center; gap: 12px; flex-shrink: 0; }
      .rg-fm-vol-wrap { display: flex; align-items: center; gap: 8px; }
      .rg-fm-vol-icon { color: #aaa; font-size: 14px; transition: color 0.25s, transform 0.2s; cursor: pointer; }
      .rg-fm-vol-icon:hover { color: #333; transform: scale(1.15); }
      .rg-fm-muted { color: #ef4444 !important; animation: rgShake 0.3s ease; }
      @keyframes rgShake {
         0%,100% { transform: translateX(0); }
         25%      { transform: translateX(-3px); }
         75%      { transform: translateX(3px); }
      }
      .rg-fm-vol-track { position: relative; width: 90px; height: 6px; border-radius: 6px; background: #e5e7eb; }
      .rg-fm-vol-fill {
         position: absolute; left: 0; top: 0;
         height: 100%; border-radius: 6px; width: 80%;
         background: #22c55e; pointer-events: none; transition: width 0.05s, background 0.3s;
      }
      .rg-fm-vol {
         position: absolute; top: 50%; left: 0;
         transform: translateY(-50%);
         width: 100%; height: 100%;
         -webkit-appearance: none; appearance: none;
         background: transparent; outline: none; cursor: pointer; margin: 0;
      }
      .rg-fm-vol::-webkit-slider-thumb {
         -webkit-appearance: none;
         width: 16px; height: 16px; border-radius: 50%;
         background: #fff; border: 2px solid #e05a00; cursor: pointer;
         box-shadow: 0 0 0 3px rgba(224,90,0,0.2);
         transition: border-color 0.2s, transform 0.15s;
      }
      .rg-fm-vol::-webkit-slider-thumb:hover { border-color: #111; transform: scale(1.2); }
      .rg-fm-vol::-moz-range-thumb {
         width: 16px; height: 16px; border-radius: 50%;
         background: #fff; border: 2px solid #e05a00; cursor: pointer;
      }
      .rg-fm-ext-link {
         font-size: 11px; color: #aaa; text-decoration: none;
         display: flex; align-items: center; gap: 5px;
         transition: color 0.2s; white-space: nowrap;
      }
      .rg-fm-ext-link:hover { color: #e05a00; }
      .rg-fm-share-btn {
         display: flex; align-items: center; gap: 6px;
         background: transparent; border: 1.5px solid #e05a00; color: #e05a00;
         font-size: 12px; font-weight: 600; padding: 6px 14px;
         border-radius: 999px; cursor: pointer;
         transition: background 0.2s, color 0.2s, transform 0.15s; white-space: nowrap;
      }
      .rg-fm-share-btn:hover { background: #e05a00; color: #fff; transform: scale(1.05); }
      .rg-fm-share-btn:active { transform: scale(0.97); }
      .rg-fm-share-btn.copied { background: #22c55e; border-color: #22c55e; color: #fff; }
      .rg-fm-share-btn i { font-size: 13px; }
      /* FM Responsive */
      @media (max-width: 991px) {
         .rg-fm-card { gap: 18px; padding: 18px 20px; }
         .rg-fm-name { font-size: 17px; }
         .rg-fm-slogan { display: none; }
      }
      @media (max-width: 767px) {
         #rg-fm-section { padding: 22px 0; }
         .rg-fm-card { flex-direction: column; align-items: center; padding: 22px 16px; gap: 18px; text-align: center; }
         .rg-fm-brand { flex-direction: column; align-items: center; gap: 8px; }
         .rg-fm-brand-text { align-items: center; }
         .rg-fm-slogan { display: block; }
         .rg-fm-center { width: 100%; }
         .rg-fm-right { width: 100%; flex-direction: row; justify-content: center; flex-wrap: wrap; gap: 14px; }
         .rg-fm-vol-track { width: 130px; }
      }
      @media (max-width: 480px) {
         #rg-fm-section { padding: 16px 0; }
         .rg-fm-logo-ring { width: 58px; height: 58px; }
         .rg-fm-name { font-size: 18px; }
         .rg-fm-vol-track { width: 110px; }
      }
      /* Focus Mode Banner */
      #rg-fm-focus-banner { font-family: inherit; }
      .rg-focus-inner { display: flex; align-items: center; gap: 12px; max-width: 860px; margin: 0 auto; flex-wrap: wrap; }
      .rg-focus-dot { width: 10px; height: 10px; border-radius: 50%; background: #f2c22f; flex-shrink: 0; animation: rgBlink 1.2s infinite; }
      .rg-focus-msg { flex: 1; font-size: 13px; color: #d1d5db; min-width: 0; }
      .rg-focus-msg strong { color: #fff; font-weight: 700; }
      .rg-focus-close {
         display: inline-flex; align-items: center; gap: 7px;
         background: #f2c22f; color: #fff; border: none;
         padding: 8px 18px; border-radius: 999px; font-size: 12px; font-weight: 600;
         cursor: pointer; white-space: nowrap;
         transition: background 0.2s, transform 0.15s; flex-shrink: 0;
      }
      .rg-focus-close:hover { background: #f2c22f; transform: scale(1.04); }
      .rg-focus-close i { font-size: 12px; }
      @media (max-width: 480px) {
         .rg-focus-inner { gap: 8px; }
         .rg-focus-msg { font-size: 11px; width: 100%; }
         .rg-focus-close { font-size: 11px; padding: 7px 14px; width: 100%; justify-content: center; }
      }
   </style>

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

      <script>
      (function () {
         var CHANNEL_ID = 'UCOwcs6zPiOfhBiG2IHWqSVQ';

         var EPISODES = [
            { id: 'h8UWASEjsk4', title: 'Diesel Mwata',  meta: 'New Music Kiu' },
            { id: 'yBWQ3WDtAZ4', title: 'ARCHIP REGEP',  meta: 'Remember' },
            { id: 'dpy-8M7nIjo', title: 'Archip Regep',  meta: 'Remember ( officiel audio )' }
         ];

         var iframe     = document.getElementById('rg-player-iframe');
         var nolive     = document.getElementById('rg-nolive-screen');
         var liveBadge  = document.getElementById('rg-live-badge');
         var offBadge   = document.getElementById('rg-offline-badge');
         var epList     = document.getElementById('rg-episodes-list');
         var watchingEp = false;

         /* Build episode cards */
         EPISODES.forEach(function (ep) {
            var thumb = 'https://img.youtube.com/vi/' + ep.id + '/mqdefault.jpg';
            var card  = document.createElement('div');
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
            card.addEventListener('click', function () { watchingEp = true; playEpisode(ep.id); });
            epList.appendChild(card);
         });

         function showOffline() {
            iframe.style.display = 'none';
            iframe.src = '';
            nolive.style.display = 'flex';
            liveBadge.style.display = 'none';
            offBadge.style.display = 'inline-flex';
         }

         function loadLive() {
            watchingEp = false;
            var liveUrl = 'https://www.youtube.com/embed/live_stream?channel=' + CHANNEL_ID + '&autoplay=1&rel=0';
            if (iframe.src !== liveUrl) iframe.src = liveUrl;
            iframe.style.display = 'block';
            nolive.style.display = 'none';
            liveBadge.style.display = 'inline-flex';
            offBadge.style.display = 'none';
         }

         function playEpisode(videoId) {
            iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            iframe.style.display = 'block';
            nolive.style.display = 'none';
            liveBadge.style.display = 'none';
            offBadge.style.display = 'inline-flex';
         }

         function checkLiveStatus() {
            if (watchingEp) return;
            fetch('live_check.php?_=' + Date.now())
               .then(function(res) { return res.json(); })
               .then(function(data) { data.live ? loadLive() : showOffline(); })
               .catch(function() { showOffline(); });
         }

         checkLiveStatus();
         setInterval(checkLiveStatus, 30000);
      })();
      </script>

      <script>
      (function () {
         var STREAM_URL = 'https://stream.zeno.fm/0r0xa792kwzuv';
         var SHARE_URL  = 'https://www.rayglobals.org/channel#rg-fm-section';
         var SHARE_META = {
            title : 'Global+ FM — Radio en ligne | RayGlobals',
            text  : '🎙️ Écoutez Global+ FM en direct — actualités, musique & culture 24h/24 sur RayGlobals.',
            url   : SHARE_URL
         };

         var audio    = new Audio(STREAM_URL);
         audio.volume = 0.8;
         audio.preload = 'none';
         var playing  = false;
         var muted    = false;
         var lastVol  = 0.8;

         var btn        = document.getElementById('rg-fm-playbtn');
         var icon       = document.getElementById('rg-fm-icon');
         var status     = document.getElementById('rg-fm-status');
         var wave       = document.getElementById('rg-fm-wave');
         var volSlider  = document.getElementById('rg-fm-vol');
         var volIconL   = document.getElementById('rg-fm-vol-left');
         var volIconR   = document.getElementById('rg-fm-vol-right');
         var volFill    = document.getElementById('rg-fm-vol-fill');
         var shareBtn   = document.getElementById('rg-fm-share-btn');
         var shareLabel = document.getElementById('rg-fm-share-label');
         var shareIcon  = document.getElementById('rg-fm-share-icon');

         function activateFocusMode() {
            var section = document.getElementById('rg-fm-section');
            if (!section) return;
            var overlay = document.createElement('div');
            overlay.id = 'rg-fm-overlay';
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(10,10,20,0.82);z-index:9998;backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);opacity:0;transition:opacity 0.5s ease';
            document.body.appendChild(overlay);
            section.style.cssText += 'position:relative;z-index:9999;transition:box-shadow 0.5s ease';
            var banner = document.createElement('div');
            banner.id = 'rg-fm-focus-banner';
            banner.innerHTML =
               '<div class="rg-focus-inner">' +
                  '<span class="rg-focus-dot"></span>' +
                  '<span class="rg-focus-msg"><strong>Global+ FM</strong> — Partagé avec vous via RayGlobals</span>' +
                  '<button class="rg-focus-close" id="rg-focus-close-btn"><i class="fa fa-th-large"></i> Voir la page complète</button>' +
               '</div>';
            banner.style.cssText = 'position:fixed;top:0;left:0;right:0;z-index:10000;background:linear-gradient(90deg,#1a1a2e 0%,#16213e 100%);border-bottom:2px solid #f2c22f;padding:10px 20px;transform:translateY(-100%);transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1);box-shadow:0 4px 24px rgba(0,0,0,0.4)';
            document.body.appendChild(banner);
            requestAnimationFrame(function () {
               requestAnimationFrame(function () {
                  overlay.style.opacity = '1';
                  banner.style.transform = 'translateY(0)';
                  section.style.boxShadow = '0 0 0 4px #f2c22f, 0 0 60px rgba(224,90,0,0.3)';
                  section.scrollIntoView({ behavior: 'smooth', block: 'center' });
               });
            });
            function exitFocusMode() {
               overlay.style.opacity = '0';
               banner.style.transform = 'translateY(-100%)';
               section.style.boxShadow = '';
               section.style.zIndex = '';
               setTimeout(function () {
                  if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
                  if (banner.parentNode) banner.parentNode.removeChild(banner);
               }, 500);
            }
            document.getElementById('rg-focus-close-btn').addEventListener('click', exitFocusMode);
            overlay.addEventListener('click', exitFocusMode);
         }

         function updateSliderUI(val) {
            var pct   = val * 100;
            var color = pct === 0 ? '#ef4444' : pct < 40 ? '#f59e0b' : '#22c55e';
            volFill.style.width      = pct + '%';
            volFill.style.background = color;
            volIconR.style.color     = pct === 0 ? '#ef4444' : color;
         }

         function toggleMute() {
            if (!muted) {
               lastVol = audio.volume || 0.8;
               audio.volume = 0; volSlider.value = 0; muted = true;
               volIconL.className = 'fa fa-volume-off rg-fm-vol-icon rg-fm-muted';
               volIconL.title = 'Réactiver le son';
            } else {
               audio.volume = lastVol; volSlider.value = lastVol; muted = false;
               volIconL.className = 'fa fa-volume-down rg-fm-vol-icon';
               volIconL.title = 'Couper le son';
            }
            updateSliderUI(audio.volume);
         }

         function startPlay() {
            audio.src = STREAM_URL;
            audio.play().then(function () {
               playing = true;
               icon.className = 'fa fa-stop';
               btn.classList.add('rg-fm-btn-playing');
               wave.classList.add('playing');
               status.textContent = '● EN DIRECT — Global+ FM';
               status.classList.add('live');
            }).catch(function () {
               status.textContent = 'Erreur de connexion. Réessayez.';
            });
         }

         function stopPlay() {
            audio.pause(); audio.currentTime = 0; playing = false;
            icon.className = 'fa fa-play';
            btn.classList.remove('rg-fm-btn-playing');
            wave.classList.remove('playing');
            status.textContent = 'Cliquez pour écouter';
            status.classList.remove('live');
         }

         function showCopied() {
            shareBtn.classList.add('copied');
            shareIcon.className = 'fa fa-check';
            shareLabel.textContent = 'Lien copié !';
            setTimeout(function () {
               shareBtn.classList.remove('copied');
               shareIcon.className = 'fa fa-share-alt';
               shareLabel.textContent = 'Partager';
            }, 2500);
         }

         function fallbackCopy() {
            var tmp = document.createElement('input');
            tmp.value = SHARE_URL;
            tmp.style.cssText = 'position:fixed;opacity:0;top:0;left:0;';
            document.body.appendChild(tmp);
            tmp.focus(); tmp.select();
            try { document.execCommand('copy'); showCopied(); } catch(e) {}
            document.body.removeChild(tmp);
         }

         function doShare() {
            if (navigator.share) { navigator.share(SHARE_META).catch(function(){}); return; }
            if (navigator.clipboard && navigator.clipboard.writeText) {
               navigator.clipboard.writeText(SHARE_URL).then(showCopied).catch(fallbackCopy);
            } else { fallbackCopy(); }
         }

         btn.addEventListener('click', function () { playing ? stopPlay() : startPlay(); });
         volSlider.addEventListener('input', function () {
            var val = parseFloat(this.value);
            audio.volume = val; muted = (val === 0);
            if (val > 0) lastVol = val;
            volIconL.className = val === 0 ? 'fa fa-volume-off rg-fm-vol-icon rg-fm-muted' : 'fa fa-volume-down rg-fm-vol-icon';
            volIconL.title = val === 0 ? 'Réactiver le son' : 'Couper le son';
            updateSliderUI(val);
         });
         volIconL.addEventListener('click', toggleMute);
         shareBtn.addEventListener('click', doShare);

         audio.addEventListener('waiting', function () { if (playing) status.textContent = 'Chargement…'; });
         audio.addEventListener('playing', function () { if (playing) status.textContent = '● EN DIRECT — Global+ FM'; });
         audio.addEventListener('error',   function () { stopPlay(); status.textContent = 'Stream indisponible pour le moment.'; });

         updateSliderUI(0.8);
         if (window.location.hash === '#rg-fm-section') setTimeout(activateFocusMode, 600);
      })();
      </script>

      <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
      <?php include("includes/script.php")?>
   </body>
</html>