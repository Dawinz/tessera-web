$(document).ready(function () {
  function srRoleFromResponse(response) {
    var text = $.trim(String(response || '').replace(/<[^>]+>/g, ' '));
    if (!text || text === 'nothing') return 'nothing';
    if (/\bSuper Administrator\b/i.test(text)) return 'Super Administrator';
    if (/\bAdministrator\b/i.test(text)) return 'Administrator';
    if (/\bAgent\b/i.test(text)) return 'Agent';
    return text;
  }

  function srHandleLoginResponse(response) {
    var role = srRoleFromResponse(response);
    if (role === 'suspended') {
      $('#login').prop('disabled', false).text('Log In');
      swal('Operator suspended', 'This operator is suspended on Tessera. Contact the platform owner.', 'error');
      return;
    }
    if (role === 'nothing') {
      $('#login').text('Log In');
      swal('Authentication Error', 'Check the username and password or Consult your administator!', 'warning');
      return;
    }
    $('#login').text('Redirecting...');
    if (role === 'Agent') {
      window.location = 'routes.php';
    } else {
      window.location = 'home.php';
    }
  }

  function srDoLogin() {
    var username = $('#username').val();
    var password = $('#password').val();

    if (username === '') {
      swal('', 'Please Enter username!', 'warning');
      return;
    }
    if (password === '') {
      swal('', 'Please Enter password!', 'warning');
      return;
    }

    $('#login').prop('disabled', true).text('.. authenticating ..');
    $.ajax({
      url: 'login.php',
      method: 'POST',
      data: { username: username, password: password },
      timeout: 15000,
      success: function (response) {
        srHandleLoginResponse(response);
      },
      error: function () {
        $('#login').prop('disabled', false).text('Log In');
        swal('Connection Error', 'Login timed out or the server did not respond. Please try again.', 'error');
      },
      complete: function () {
        // Re-enable if we did not redirect
        setTimeout(function () {
          if ($('#login').text().indexOf('Redirecting') === -1) {
            $('#login').prop('disabled', false);
          }
        }, 50);
      }
    });
  }

  $('#login').click(function () {
    srDoLogin();
  });

  $(document).on('keypress', function (e) {
    if (e.which === 13) {
      srDoLogin();
      return false;
    }
  });
});
