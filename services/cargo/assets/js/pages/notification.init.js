(function () {
  function bindClick(id, fn) {
    var el = document.getElementById(id);
    if (el) el.addEventListener('click', fn);
  }

  if (typeof alertify === 'undefined') return;

  bindClick('alert', function () {
    alertify.alert('Alert Title', 'Alert Message!', function () {
      alertify.success('Ok');
    });
  });
  bindClick('alert-confirm', function () {
    alertify.confirm(
      'This is a confirm dialog.',
      function () {
        alertify.success('Ok');
      },
      function () {
        alertify.error('Cancel');
      }
    );
  });
  bindClick('alert-prompt', function () {
    alertify.prompt(
      'This is a prompt dialog.',
      'Default value',
      function (e, t) {
        alertify.success('Ok: ' + t);
      },
      function () {
        alertify.error('Cancel');
      }
    );
  });
  bindClick('alert-success', function () {
    alertify.success('Success message');
  });
  bindClick('alert-error', function () {
    alertify.error('Error message');
  });
  bindClick('alert-warning', function () {
    alertify.warning('Warning message');
  });
  bindClick('alert-message', function () {
    alertify.message('Normal message');
  });
})();
