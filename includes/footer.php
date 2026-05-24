<div class="auto-container">
    <!-- Widgets Section -->
    <div class="widgets-section">
        <div class="row clearfix">
            <!-- Column -->
            <div class="big-column col-lg-6 col-md-12 col-sm-12">
                <div class="row clearfix">
                    <!-- Footer Column -->
                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                        <div class="footer-widget logo-widget">
                            <div class="widget-content">
                                <div class="logo">
                                    <a href="#"><img src="<?= DN ?>/assets/images/logo-3.png" alt="Ray Globals Logo" /></a>
                                </div>
                                <div class="text">Ray Globals, une entreprise multisectorielle innovante, dédiée à fournir des solutions fiables et adaptées à vos besoins.</div>
                                <!-- Social Box -->
                                <ul class="social-box">
                                    <li><a href="https://www.facebook.com/" class="fa fa-facebook-f"></a></li>
                                    <li><a href="https://www.twitter.com/" class="fa fa-twitter"></a></li>
                                    <li><a href="https://www.linkedin.com/" class="fa fa-linkedin"></a></li>
                                    <li><a href="https://youtube.com/" class="fa fa-youtube-play"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Column -->
                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                        <div class="footer-widget links-widget">
                            <h5>Liens Rapides</h5>
                            <ul class="list-link">
                                <li><a href="#">Accueil</a></li>
                                <li><a href="#">À Propos</a></li>
                                <li><a href="#">Projets</a></li>
                                <li><a href="#">Services</a></li>
                                <li><a href="#">Contactez-nous</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="big-column col-lg-6 col-md-12 col-sm-12">
                <div class="row clearfix">
                    <!-- Footer Column -->
                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                        <div class="footer-widget links-widget">
                            <h5>Nos Services</h5>
                            <ul class="list-link">
                                <li><a href="#">Transport</a></li>
                                <li><a href="#">Matériaux & Blocs</a></li>
                                <li><a href="#">Électronique</a></li>
                                <li><a href="#">Import-Export</a></li>
                                <li><a href="#">Communication</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Footer Column -->
                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                        <div class="footer-widget links-widget">
                            <h5>Inscrivez-vous à Notre Newsletter :</h5>
                            <!-- Subscribe Form -->
                            <div class="subscribe-form-two">
                                <form method="post" action="#">
                                    <div class="form-group">
                                        <span class="icon fa fa-envelope"></span>
                                        <input type="email" name="email" value="" placeholder="Entrez votre adresse e-mail" required="">
                                        <button type="submit" class="theme-btn btn-style-one"><span class="txt">S'abonner</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer Bottom -->
<div class="footer-bottom">
    <div class="auto-container">
        <div class="copyright">© <a href="index">Ray Globals</a> <?php echo date("Y"); ?>. Tous droits réservés.</div>
    </div>
</div>

<!-- =====================================================
     Global+ FM — Fixed Mini Player
     ===================================================== -->

<div id="rg-mini-player" role="complementary" aria-label="Global+ FM mini lecteur">
   <!-- Close × -->
   <button class="rg-mini-close" id="rg-mini-close-btn" aria-label="Masquer le lecteur">&#10005;</button>

   <!-- Logo -->
   <div class="rg-mini-logo">
      <img src="<?= DN ?>/assets/images/globalplus.png" alt="Global+ FM" />
   </div>

   <!-- Info + wave -->
   <div class="rg-mini-info">
      <p class="rg-mini-label">Radio en ligne</p>
      <p class="rg-mini-name">Global+ FM</p>
      <!-- Wave bars -->
      <div class="rg-mini-wave" id="rg-mini-wave">
         <span></span><span></span><span></span><span></span><span></span>
      </div>
      <p class="rg-mini-status" id="rg-mini-status">Cliquez pour écouter</p>
   </div>

   <!-- Play/Stop -->
   <button class="rg-mini-playbtn" id="rg-mini-playbtn" aria-label="Écouter / Arrêter Global+ FM">
      <i class="fa fa-play" id="rg-mini-icon"></i>
   </button>
</div>

<!-- Reopen tab (shown after player is closed) -->
<div id="rg-mini-reopen" role="button" tabindex="0" aria-label="Réouvrir Global+ FM">
   <img src="<?= DN ?>/assets/images/globalplus.png" alt="Global+ FM" />
   <span>Global+ FM</span>
</div>