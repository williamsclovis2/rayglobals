// =============================================================
//  channels.js — Ray Globals
//  Three independent IIFEs, each guards its own DOM elements.
//  Safe to include on every page.
// =============================================================


// ─────────────────────────────────────────────────────────────
//  1. VIDEO PLAYER & LIVE YOUTUBE CHANNEL
//     Requires: #rg-player-iframe, #rg-episodes-list
// ─────────────────────────────────────────────────────────────
(function () {
   var CHANNEL_ID = 'UCOwcs6zPiOfhBiG2IHWqSVQ';

   var EPISODES = [
      { id: 'h8UWASEjsk4', title: 'Diesel Mwata',  meta: 'New Music Kiu' },
      { id: 'yBWQ3WDtAZ4', title: 'ARCHIP REGEP',  meta: 'Remember' },
      { id: 'dpy-8M7nIjo', title: 'Archip Regep',  meta: 'Remember ( officiel audio )' }
   ];

   var iframe    = document.getElementById('rg-player-iframe');
   var nolive    = document.getElementById('rg-nolive-screen');
   var liveBadge = document.getElementById('rg-live-badge');
   var offBadge  = document.getElementById('rg-offline-badge');
   var epList    = document.getElementById('rg-episodes-list');

   // Guard — only runs on the channel page
   if (!iframe || !epList) return;

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
         .then(function (res) { return res.json(); })
         .then(function (data) { data.live ? loadLive() : showOffline(); })
         .catch(function () { showOffline(); });
   }

   checkLiveStatus();
   setInterval(checkLiveStatus, 30000);
}());


// ─────────────────────────────────────────────────────────────
//  2. FM RADIO PLAYER — full section
//     Requires: #rg-fm-playbtn, #rg-fm-vol, #rg-fm-share-btn …
//     Only runs on pages that include #rg-fm-section.
// ─────────────────────────────────────────────────────────────
(function () {
   var STREAM_URL = 'https://stream.zeno.fm/0r0xa792kwzuv';
   var SHARE_URL  = 'https://www.rayglobals.org/channel#rg-fm-section';
   var SHARE_META = {
      title : 'Global+ FM — Radio en ligne | RayGlobals',
      text  : '🎙️ Écoutez Global+ FM en direct — actualités, musique & culture 24h/24 sur RayGlobals.',
      url   : SHARE_URL
   };

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

   // Guard — only runs on pages that have the full FM section
   if (!btn || !volSlider || !volIconL || !shareBtn) return;

   var audio   = new Audio();
   audio.volume  = 0.8;
   audio.preload = 'none';

   // Expose audio globally so the mini player can share it
   window._rgFmAudio = audio;

   var playing = false;
   var muted   = false;
   var lastVol = 0.8;

   /* Focus mode (triggered via URL hash) */
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
      try { document.execCommand('copy'); showCopied(); } catch (e) {}
      document.body.removeChild(tmp);
   }

   function doShare() {
      if (navigator.share) { navigator.share(SHARE_META).catch(function () {}); return; }
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
}());


// ─────────────────────────────────────────────────────────────
//  3. GLOBAL+ FM — FIXED MINI PLAYER (bottom-left / bottom mobile)
//     Runs on EVERY page (lives in the footer include).
//     Shares audio with IIFE 2 if on the channel page,
//     otherwise creates its own Audio instance.
//     Requires: #rg-mini-player, #rg-mini-playbtn (footer HTML)
// ─────────────────────────────────────────────────────────────
(function () {
   var STREAM_URL  = 'https://stream.zeno.fm/0r0xa792kwzuv';
   var SLIDE_DELAY = 1200; // ms after page load before card slides in

   var player   = document.getElementById('rg-mini-player');
   var reopen   = document.getElementById('rg-mini-reopen');
   var playbtn  = document.getElementById('rg-mini-playbtn');
   var miniIcon = document.getElementById('rg-mini-icon');
   var status   = document.getElementById('rg-mini-status');
   var wave     = document.getElementById('rg-mini-wave');
   var closeBtn = document.getElementById('rg-mini-close-btn');

   // Guard — exits if the footer widget HTML is not present
   if (!player || !playbtn || !miniIcon || !status || !wave || !closeBtn || !reopen) return;

   // Share audio with the full FM section if it is on this page,
   // otherwise create a standalone Audio instance.
   var audio = window._rgFmAudio || new Audio();
   if (!window._rgFmAudio) {
      audio.preload = 'none';
      audio.volume  = 0.8;
      window._rgFmAudio = audio;
   }

   var playing = false;

   /* UI state */
   function setPlaying(state) {
      playing = state;
      if (state) {
         miniIcon.className = 'fa fa-stop';
         playbtn.classList.add('playing');
         wave.classList.add('playing');
         status.textContent = 'EN DIRECT — Global+ FM';
         status.classList.add('live');
      } else {
         miniIcon.className = 'fa fa-play';
         playbtn.classList.remove('playing');
         wave.classList.remove('playing');
         status.textContent = 'Cliquez pour écouter';
         status.classList.remove('live');
      }
   }

   function startPlay() {
      audio.src = STREAM_URL;
      status.textContent = 'Connexion…';
      audio.play()
         .then(function () { setPlaying(true); })
         .catch(function () { status.textContent = 'Erreur de connexion.'; });
   }

   function stopPlay() {
      audio.pause();
      audio.currentTime = 0;
      setPlaying(false);
   }

   /* Play button */
   playbtn.addEventListener('click', function () {
      playing ? stopPlay() : startPlay();
   });

   /* Sync status text with native audio events */
   audio.addEventListener('waiting', function () {
      if (playing) status.textContent = 'Chargement…';
   });
   audio.addEventListener('playing', function () {
      if (playing) {
         status.textContent = 'EN DIRECT — Global+ FM';
         status.classList.add('live');
      }
   });
   audio.addEventListener('error', function () {
      setPlaying(false);
      status.textContent = 'Stream indisponible.';
   });

   /* Close & reopen */
   function hidePlayer() {
      player.classList.remove('rg-mini-visible');
      reopen.classList.add('rg-mini-visible');
   }

   function showPlayer() {
      reopen.classList.remove('rg-mini-visible');
      player.classList.add('rg-mini-visible');
   }

   closeBtn.addEventListener('click', hidePlayer);
   reopen.addEventListener('click', showPlayer);
   reopen.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') showPlayer();
   });

   /* Slide in after delay — only animates on first visit per session */
   var SESSION_KEY = 'rgMiniSeen';
   var delay = sessionStorage.getItem(SESSION_KEY) ? 0 : SLIDE_DELAY;

   setTimeout(function () {
      player.classList.add('rg-mini-visible');
      sessionStorage.setItem(SESSION_KEY, '1');
   }, delay);

}());