$(function () {
  var $form = $('#loginForm');
  var $btn = $('#loginBtn');
  var $alert = $('#alert');

  function showError(msg) {
    $alert.text(msg || 'Could not sign in.').addClass('show');
  }

  function clearError() {
    $alert.removeClass('show').text('');
  }

  function doLogin() {
    var username = $.trim($('#username').val() || '');
    var password = $('#password').val() || '';
    var next = $('#next').val() || '';

    clearError();
    if (!username) {
      showError('Enter your username, email, or phone.');
      $('#username').focus();
      return;
    }
    if (!password) {
      showError('Enter your password.');
      $('#password').focus();
      return;
    }

    $btn.prop('disabled', true).text('Signing in…');
    $.ajax({
      url: 'auth.php',
      method: 'POST',
      dataType: 'json',
      timeout: 15000,
      data: { username: username, password: password, next: next },
      success: function (res) {
        if (res && res.redirect && (res.ok || res.error === 'wrong_host')) {
          $btn.text('Opening…');
          window.location = res.redirect;
          return;
        }
        showError((res && res.message) || 'Wrong username or password.');
        $btn.prop('disabled', false).text('Sign in');
      },
      error: function () {
        showError('Connection error. Please try again.');
        $btn.prop('disabled', false).text('Sign in');
      }
    });
  }

  $form.on('submit', function (e) {
    e.preventDefault();
    doLogin();
  });
});
