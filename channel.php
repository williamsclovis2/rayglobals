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
      /* .rg-nolive > div {
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
      } */
      /* @media (max-width: 480px) {
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
      } */
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
      .rg-nolive .icon-ring i {
      font-size: 20px;
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
      /* width: 80px; height: 80px;
      border-radius: 50%;
      background: rgba(192,57,43,0.15); */
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
      /* RADIO STATION  */
      /* ======================================================
      RAY GLOBALS FM — Radio Player
      ====================================================== */
      #rg-fm-section {
      background: #0c2d62;
      padding: 28px 0;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      }
      .rg-fm-card {
      display: flex;
      align-items: center;
      gap: 24px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 22px 28px;
      box-shadow: 0 4px 32px rgba(0,0,0,0.3);
      /* max-width: 860px; */
      margin: 0 auto;
      width: 100%;
      }
      /* ---- Branding (left) ---- */
      .rg-fm-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-shrink: 0;
      }
      .rg-fm-logo-ring {
      width: 54px; height: 54px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c0392b, #e74c3c);
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 0 0 4px rgba(192,57,43,0.22);
      flex-shrink: 0;
      }
      .rg-fm-logo-ring i {
      font-size: 24px;
      color: #fff;
      }
      .rg-fm-brand-text {
      display: flex;
      flex-direction: column;
      }
      .rg-fm-tag {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: #f2c22f;
      text-transform: uppercase;
      margin-bottom: 2px;
      }
      .rg-fm-name {
      font-size: 20px;
      font-weight: 800;
      color: #fff;
      margin: 0 0 2px;
      line-height: 1.1;
      white-space: nowrap;
      }
      .rg-fm-name span { color: #f2c22f; }
      .rg-fm-slogan {
      font-size: 11px;
      color: #9ca3af;
      margin: 0;
      white-space: nowrap;
      }
      /* ---- Center ---- */
      .rg-fm-center {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      min-width: 0;
      }
      /* Wave visualizer */
      .rg-fm-wave {
      display: flex;
      align-items: flex-end;
      gap: 3px;
      height: 36px;
      }
      .rg-fm-wave span {
      display: block;
      width: 4px;
      border-radius: 4px;
      background: #374151;
      height: 6px;
      transform-origin: bottom;
      transition: background 0.3s;
      }
      .rg-fm-wave.playing span {
      animation: rgWave 0.9s ease-in-out infinite alternate;
      background: #f2c22f;
      }
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
      @keyframes rgWave {
      0%   { height: 5px; }
      100% { height: 34px; }
      }
      /* Play button */
      .rg-fm-playbtn {
      width: 52px; height: 52px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c0392b, #e74c3c);
      border: none;
      color: #fff;
      font-size: 18px;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 18px rgba(192,57,43,0.45);
      transition: transform 0.15s, box-shadow 0.15s, background 0.3s;
      flex-shrink: 0;
      }
      .rg-fm-playbtn:hover {
      transform: scale(1.08);
      box-shadow: 0 6px 24px rgba(192,57,43,0.55);
      }
      .rg-fm-playbtn:active { transform: scale(0.97); }
      .rg-fm-btn-playing {
      background: linear-gradient(135deg, #b91c1c, #dc2626) !important;
      box-shadow: 0 0 0 6px rgba(220,38,38,0.25), 0 4px 18px rgba(192,57,43,0.5) !important;
      animation: rgPulse 1.8s ease-in-out infinite;
      }
      @keyframes rgPulse {
      0%, 100% { box-shadow: 0 0 0 6px rgba(220,38,38,0.25), 0 4px 18px rgba(192,57,43,0.5); }
      50%       { box-shadow: 0 0 0 12px rgba(220,38,38,0.08), 0 4px 18px rgba(192,57,43,0.5); }
      }
      /* Now playing text */
      .rg-fm-nowplaying {
      font-size: 11px;
      color: #9ca3af;
      margin: 0;
      text-align: center;
      min-height: 16px;
      }
      .rg-fm-nowplaying.live {
      color: #f2c22f;
      font-weight: 600;
      }
      /* ---- Right: Volume + link ---- */
      .rg-fm-right {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      flex-shrink: 0;
      }
      .rg-fm-vol-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      }
      /* Volume icon */
      .rg-fm-vol-icon {
      color: #9ca3af;
      font-size: 14px;
      transition: color 0.25s, transform 0.2s;
      cursor: pointer;
      }
      .rg-fm-vol-icon:hover { color: #fff; transform: scale(1.15); }
      .rg-fm-muted {
      color: #ef4444 !important;
      animation: rgShake 0.3s ease;
      }
      @keyframes rgShake {
      0%,100% { transform: translateX(0); }
      25%      { transform: translateX(-3px); }
      75%      { transform: translateX(3px); }
      }
      /* Volume track */
      .rg-fm-vol-track {
      position: relative;
      width: 90px;
      height: 6px;
      border-radius: 6px;
      background: #374151;
      }
      .rg-fm-vol-fill {
      position: absolute;
      left: 0; top: 0;
      height: 100%;
      border-radius: 6px;
      width: 80%;
      background: #22c55e;
      pointer-events: none;
      transition: width 0.05s, background 0.3s;
      }
      .rg-fm-vol {
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      width: 100%;
      height: 100%;
      -webkit-appearance: none;
      appearance: none;
      background: transparent;
      outline: none;
      cursor: pointer;
      margin: 0;
      }
      .rg-fm-vol::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 16px; height: 16px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #c0392b;
      cursor: pointer;
      box-shadow: 0 0 0 3px rgba(192,57,43,0.2);
      transition: border-color 0.2s, transform 0.15s;
      }
      .rg-fm-vol::-webkit-slider-thumb:hover {
      border-color: #f2c22f;
      transform: scale(1.2);
      }
      .rg-fm-vol::-moz-range-thumb {
      width: 16px; height: 16px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #c0392b;
      cursor: pointer;
      border: none;
      }
      /* External link */
      .rg-fm-ext-link {
      font-size: 11px;
      color: #9ca3af;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: color 0.2s;
      white-space: nowrap;
      }
      .rg-fm-ext-link:hover { color: #f2c22f; }
      /* ======================================================
      RESPONSIVE
      ====================================================== */
      @media (max-width: 991px) {
      .rg-fm-card {
      gap: 18px;
      padding: 18px 20px;
      }
      .rg-fm-name { font-size: 17px; }
      .rg-fm-slogan { display: none; }
      }
      @media (max-width: 767px) {
      #rg-fm-section { padding: 22px 0; }
      .rg-fm-card {
      flex-direction: column;
      align-items: center;
      padding: 22px 16px;
      gap: 18px;
      text-align: center;
      }
      .rg-fm-brand {
      flex-direction: column;
      align-items: center;
      gap: 8px;
      }
      .rg-fm-brand-text { align-items: center; }
      .rg-fm-slogan { display: block; }
      .rg-fm-center { width: 100%; }
      .rg-fm-right {
      width: 100%;
      flex-direction: row;
      justify-content: center;
      flex-wrap: wrap;
      gap: 14px;
      }
      .rg-fm-vol-track { width: 130px; }
      }
      @media (max-width: 480px) {
      #rg-fm-section { padding: 16px 0; }
      .rg-fm-name { font-size: 20px; }
      .rg-fm-vol-track { width: 110px; }
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
            <!-- <ul class="page-breadcrumb">
               <li><a href="index">Accueil</a></li>
               <li>channels </li>
            </ul> -->
         </section>
         <!-- ======================================================
            RAY GLOBALS FM — Radio Player Section
            ====================================================== -->
         <section id="rg-fm-section">
            <div class="container">
               <div class="rg-fm-card">
                  <!-- Left: Branding -->
                  <div class="rg-fm-brand">
                     <div class="rg-fm-logo-ring">
                        <i class="fa fa-podcast"></i>
                     </div>
                     <div class="rg-fm-brand-text">
                        <span class="rg-fm-tag">RADIO EN LIGNE</span>
                        <h3 class="rg-fm-name">Global+ FM <span>FM</span></h3>
                        <p class="rg-fm-slogan">L'info, la musique &amp; la culture — 24h/24</p>
                     </div>
                  </div>
                  <!-- Center: Visualizer + Controls -->
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
                  <!-- Right: Volume + Link -->
                  <div class="rg-fm-right">
                     <div class="rg-fm-vol-wrap">
                        <!-- Left icon = clickable MUTE toggle -->
                        <i class="fa fa-volume-down rg-fm-vol-icon"
                           id="rg-fm-vol-left"
                           title="Couper le son"></i>
                        <!-- Custom slider track -->
                        <div class="rg-fm-vol-track">
                           <div class="rg-fm-vol-fill" id="rg-fm-vol-fill"></div>
                           <input type="range" class="rg-fm-vol" id="rg-fm-vol"
                              min="0" max="1" step="0.01" value="0.8">
                        </div>
                        <!-- Right icon = volume level indicator -->
                        <i class="fa fa-volume-up rg-fm-vol-icon" id="rg-fm-vol-right"></i>
                     </div>
                     <a href="https://www.youtube.com/@Globalplus243"
                        target="_blank" class="rg-fm-ext-link">
                     <i class="fa fa-external-link"></i> Écouter en ligne
                     </a>
                  </div>
               </div>
            </div>
         </section>
         <!-- End RAY GLOBALS FM -->
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
                                 <a href="https://www.youtube.com/@Globalplus243" target="_blank" class="rg-yt-btn">
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
                        <a href="https://www.youtube.com/@Globalplus243" target="_blank">
                        Tout voir <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                     </div>
                     <!-- Episode cards are injected here by JS -->
                     <div id="rg-episodes-list"></div>
                     <a href="https://www.youtube.com/@Globalplus243" target="_blank" class="rg-yt-subscribe">
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
         
           var CHANNEL_ID = 'UCOwcs6zPiOfhBiG2IHWqSVQ';
         
           var EPISODES = [
             {
               id: 'h8UWASEjsk4',
               title: 'Diesel Mwata',
               meta: 'New Music Kiu '
             },
             {
               id: 'yBWQ3WDtAZ4',
               title: 'ARCHIP REGEP',
               meta: 'Remember'
             },
             {
               id: 'dpy-8M7nIjo',
               title: 'Archip Regep',
               meta: 'Remember ( officiel audio )'
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
      <script>
         (function () {
           /* ---- Replace with your actual stream URL when ready ---- */
           var STREAM_URL = 'https://stream.zeno.fm/0r0xa792kwzuv';
         
           var audio    = new Audio(STREAM_URL);
           audio.volume = 0.8;
           audio.preload = 'none';
           var playing  = false;
           var muted    = false;
           var lastVol  = 0.8;
         
           var btn      = document.getElementById('rg-fm-playbtn');
           var icon     = document.getElementById('rg-fm-icon');
           var status   = document.getElementById('rg-fm-status');
           var wave     = document.getElementById('rg-fm-wave');
           var volSlider = document.getElementById('rg-fm-vol');
           var volIconL  = document.getElementById('rg-fm-vol-left');
           var volIconR  = document.getElementById('rg-fm-vol-right');
           var volFill   = document.getElementById('rg-fm-vol-fill');
         
           /* ---- Update slider fill color based on volume level ---- */
           function updateSliderUI(val) {
             var pct   = val * 100;
             var color = pct === 0  ? '#ef4444'
                       : pct < 40   ? '#f2c22f'
                       :               '#22c55e';
             volFill.style.width      = pct + '%';
             volFill.style.background = color;
             volIconR.style.color     = pct === 0 ? '#ef4444' : color;
           }
         
           /* ---- Toggle mute ---- */
           function toggleMute() {
             if (!muted) {
               lastVol = audio.volume || 0.8;
               audio.volume     = 0;
               volSlider.value  = 0;
               muted            = true;
               volIconL.className = 'fa fa-volume-off rg-fm-vol-icon rg-fm-muted';
               volIconL.title     = 'Réactiver le son';
             } else {
               audio.volume     = lastVol;
               volSlider.value  = lastVol;
               muted            = false;
               volIconL.className = 'fa fa-volume-down rg-fm-vol-icon';
               volIconL.title     = 'Couper le son';
             }
             updateSliderUI(audio.volume);
           }
         
           /* ---- Play ---- */
           function startPlay() {
             audio.src = STREAM_URL;
             audio.play().then(function () {
               playing = true;
               icon.className = 'fa fa-stop';
               btn.classList.add('rg-fm-btn-playing');
               wave.classList.add('playing');
               status.textContent = '● EN DIRECT — RayGlobals FM';
               status.classList.add('live');
             }).catch(function () {
               status.textContent = 'Erreur de connexion. Réessayez.';
             });
           }
         
           /* ---- Stop ---- */
           function stopPlay() {
             audio.pause();
             audio.currentTime = 0;
             playing = false;
             icon.className = 'fa fa-play';
             btn.classList.remove('rg-fm-btn-playing');
             wave.classList.remove('playing');
             status.textContent = 'Cliquez pour écouter';
             status.classList.remove('live');
           }
         
           /* ---- Events ---- */
           btn.addEventListener('click', function () {
             playing ? stopPlay() : startPlay();
           });
         
           volSlider.addEventListener('input', function () {
             var val  = parseFloat(this.value);
             audio.volume = val;
             muted        = (val === 0);
             if (val > 0) lastVol = val;
             volIconL.className = val === 0
               ? 'fa fa-volume-off rg-fm-vol-icon rg-fm-muted'
               : 'fa fa-volume-down rg-fm-vol-icon';
             volIconL.title = val === 0 ? 'Réactiver le son' : 'Couper le son';
             updateSliderUI(val);
           });
         
           volIconL.addEventListener('click', toggleMute);
         
           audio.addEventListener('waiting', function () {
             if (playing) status.textContent = 'Chargement…';
           });
           audio.addEventListener('playing', function () {
             if (playing) status.textContent = '● EN DIRECT — RayGlobals FM';
           });
           audio.addEventListener('error', function () {
             stopPlay();
             status.textContent = 'Stream indisponible pour le moment.';
           });
         
           /* ---- Init ---- */
           updateSliderUI(0.8);
         
         })();
      </script>
   </body>
   <!-- Scroll To Top -->
   <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
   <?php include("includes/script.php")?>
   </body>
</html>