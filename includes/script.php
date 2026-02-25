<script src="<?= DN ?>/assets/js/jquery.js"></script>
<script src="<?= DN ?>/assets/js/popper.min.js"></script>
<script src="<?= DN ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= DN ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?= DN ?>/assets/js/jquery.fancybox.js"></script>
<script src="<?= DN ?>/assets/js/appear.js"></script>
<script src="<?= DN ?>/assets/js/parallax.min.js"></script>
<script src="<?= DN ?>/assets/js/tilt.jquery.min.js"></script>
<script src="<?= DN ?>/assets/js/jquery.paroller.min.js"></script>
<script src="<?= DN ?>/assets/js/owl.js"></script>
<script src="<?= DN ?>/assets/js/wow.js"></script>
<script src="<?= DN ?>/assets/js/mixitup.js"></script>
<script src="<?= DN ?>/assets/js/validate.js"></script>
<script src="<?= DN ?>/assets/js/nav-tool.js"></script>
<script src="<?= DN ?>/assets/js/jquery-ui.js"></script>
<script src="<?= DN ?>/assets/js/script.js"></script>
<script src="<?= DN ?>/assets/js/color-settings.js"></script>

 <script>
	$(document).ready(function () {
    const videos = $(".custom-video");

    // Play button toggle
    $(".play-btn").on("click", function () {
        const video = $(this).closest(".video-container").find(".custom-video")[0];

        // Pause other videos
        videos.each(function () {
        if (this !== video) {
            this.pause();
            $(this).closest(".video-container").find(".play-btn i").removeClass("fa-pause").addClass("fa-play");
        }
        });

        if (video.paused) {
        video.play();
        $(this).find("i").removeClass("fa-play").addClass("fa-pause");
        } else {
        video.pause();
        $(this).find("i").removeClass("fa-pause").addClass("fa-play");
        }
    });

    // Update time & progress
    videos.on("timeupdate", function () {
        const container = $(this).closest(".video-container");
        const start = container.find(".time.start");
        const end = container.find(".time.end");
        const progress = container.find(".progress-bar");

        start.text(formatTime(this.currentTime));
        end.text(formatTime(this.duration));
        progress.val((this.currentTime / this.duration) * 100);
    });

    // Seek progress
    $(".progress-bar").on("input", function () {
        const video = $(this).closest(".video-container").find(".custom-video")[0];
        video.currentTime = (this.value / 100) * video.duration;
    });

    // Fullscreen
    $(".fullscreen-btn").on("click", function () {
        const video = $(this).closest(".video-container").find(".custom-video")[0];
        if (video.requestFullscreen) {
        video.requestFullscreen();
        } else if (video.webkitRequestFullscreen) {
        video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) {
        video.msRequestFullscreen();
        }
    });

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    }
    });
</script>