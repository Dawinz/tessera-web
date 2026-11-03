$(document).ready(function () {
  function doLogin() {
    var username = $('#username').val();
    var password = $('#password').val();
    if (!username) {
      swal('', 'Please enter your email.', 'warning');
      return;
    }
    if (!password) {
      swal('', 'Please enter your password.', 'warning');
      return;
    }
    $('#login').prop('disabled', true).text('Authenticating…');
    $.ajax({
      url: 'login.php',
      method: 'POST',
      data: { username: username, password: password },
      timeout: 15000,
      success: function (response) {
        var text = $.trim(String(response || ''));
        if (text === 'ok') {
          $('#login').text('Redirecting…');
          window.location = 'overview.php';
          return;
        }
        $('#login').prop('disabled', false).text('Sign in');
        swal('Authentication error', 'Check your email and password.', 'warning');
      },
      error: function () {
        $('#login').prop('disabled', false).text('Sign in');
        swal('Connection error', 'Login timed out or the server did not respond.', 'error');
      }
    });
  }

  $('#login').on('click', doLogin);
  $(document).on('keypress', function (e) {
    if (e.which === 13) {
      doLogin();
      return false;
    }
  });
});
