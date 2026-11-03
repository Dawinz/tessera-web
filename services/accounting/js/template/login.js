$(document).ready(function () {
  $('#login').click(function () {
    var username = $('#username').val();
    var password = $('#password').val();
    if (username === '') {
      Swal.fire('Please Enter username');
      return;
    }
    if (password === '') {
      Swal.fire('Please Enter password');
      return;
    }
    $('#login').prop('disabled', true).text('.. authenticating ..');
    $.ajax({
      url: 'select/login.php',
      method: 'POST',
      data: { username: username, password: password },
      timeout: 15000,
      success: function (response) {
        var text = $.trim(String(response || '').replace(/<[^>]+>/g, ' '));
        if (text === 'suspended') {
          $('#login').prop('disabled', false).text('Sign In');
          Swal.fire({
            title: 'Operator suspended',
            text: 'This operator is suspended on Tessera. Contact the platform owner.',
            icon: 'error'
          });
          return;
        }
        if (!text || text === 'nothing') {
          $('#login').prop('disabled', false).text('Sign In');
          Swal.fire({
            title: 'Authentication Problem',
            text: 'Wrong username,password or your temporary locked out of the system',
            icon: 'error'
          });
          return;
        }
        window.location = 'pointOsales.php';
      },
      error: function () {
        $('#login').prop('disabled', false).text('Sign In');
        Swal.fire({
          title: 'Connection Error',
          text: 'Login timed out or the server did not respond. Please try again.',
          icon: 'error'
        });
      }
    });
  });
});
