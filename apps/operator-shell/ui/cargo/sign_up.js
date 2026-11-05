(function () {
  var root = document.getElementById('tds-cargo-signup-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  window.location.replace('/settings/staff.php');
})();
