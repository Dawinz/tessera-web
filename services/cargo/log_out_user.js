$(function () {
var username = $("#user_info").data("username");
var fullname = $("#user_info").data("fullname");
    // ============== settings - tweak as needed ==============
    var IDLE_LIMIT_MS     = 30 * 60 * 1000;  // 1 minute of no scroll/hover before the dialog shows
    var COUNTDOWN_SECONDS = 30;             // seconds the user has to click the button before redirect
    var LOGIN_URL         = 'temporary-login.php?username='+username+'&fullname='+fullname;   // change to your real login page

    var $overlay      = $('#idleOverlay');
    var $countdownNum = $('#countdownNum');
    var $stayBtn      = $('#stayBtn');

    var idleTimer      = null;
    var countdownTimer = null;
    var secondsLeft    = COUNTDOWN_SECONDS;
    var dialogOpen      = false;
    var lastReset       = 0;

    // Starts/restarts the 5-minute idle clock.
    function startIdleTimer() {
        if (dialogOpen) return;                  // don't reset while the dialog is open
        var now = Date.now();
        if (now - lastReset < 1000) return;       // throttle - mousemove/scroll fire constantly
        lastReset = now;
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showDialog, IDLE_LIMIT_MS);
    }

    // Called once the user has been idle for IDLE_LIMIT_MS.
    function showDialog() {
        dialogOpen = true;
        secondsLeft = COUNTDOWN_SECONDS;
        $countdownNum.text(secondsLeft);
        $overlay.addClass('show');

        countdownTimer = setInterval(function () {
            secondsLeft--;
            $countdownNum.text(secondsLeft);
            if (secondsLeft <= 0) {
                clearInterval(countdownTimer);
                goToLogin();
            }
        }, 1000);
    }

    // Called when the user clicks "Continue Session" - restarts the timer.
    function closeDialogAndRestart() {
        dialogOpen = false;
        clearInterval(countdownTimer);
        $overlay.removeClass('show');
        startIdleTimer();
    }

    // Called when the countdown reaches zero without a click.
    function goToLogin() {
        window.location.href = LOGIN_URL;
    }

    // Activity = scrolling or hovering/moving the mouse over the page.
    // touchstart/keydown are included too so the timer also resets sensibly
    // on touch devices and keyboard-only use - remove them if you want the
    // timer to react strictly to scroll/hover only.
    $(document).on('mousemove mouseover scroll wheel touchstart keydown', startIdleTimer);

    $stayBtn.on('click', closeDialogAndRestart);

    startIdleTimer(); // start the clock as soon as the page loads
});
