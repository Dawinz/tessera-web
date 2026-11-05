<?php
/**
 * Critical platform chrome - inlined so stale CSS caches cannot hide updates.
 */
$dir = str_replace('\\', '/', __DIR__);
// Production deploy path is /var/www/html/accounts/... (not /services/accounting/).
$accountsCss = __DIR__ . '/../../css/sr-platform-shell.css';
$moduleAssetsCss = __DIR__ . '/../assets/css/sr-platform-shell.css';
if (
	is_file($accountsCss)
	|| strpos($dir, '/services/accounting/') !== false
	|| strpos($dir, '/accounts/template/') !== false
) {
	$sr_shell_file = $accountsCss;
	$sr_shell_href = '/accounts/css/sr-platform-shell.css';
} elseif (strpos($dir, '/services/cargo/') !== false || strpos($dir, '/cargo/') !== false) {
	$sr_shell_file = $moduleAssetsCss;
	$sr_shell_href = '/cargo/assets/css/sr-platform-shell.css';
} else {
	/* tickets: services/tickets/includes or /tickets/includes */
	$sr_shell_file = $moduleAssetsCss;
	$sr_shell_href = '/tickets/assets/css/sr-platform-shell.css';
}
$sr_shell_v = is_file($sr_shell_file) ? filemtime($sr_shell_file) : time();
?>
<!-- sr-platform-chrome v16 — system fonts only (no fonts.googleapis.com) -->
<link rel="stylesheet" href="<?php echo htmlspecialchars($sr_shell_href, ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo $sr_shell_v; ?>&sr=12">
<style id="sr-platform-chrome-critical">
:root {
  --sr-chrome-height: 120px;
  --sr-ui-font: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
}
#page-topbar { font-family: var(--sr-ui-font) !important; }
#page-topbar *:not(i):not(svg):not(.fa):not(.fas):not(.far):not(.bx):not(.mdi):not([class^="bx"]):not([class*=" bx"]):not([class^="mdi"]):not([class*=" mdi"]) {
  font-family: var(--sr-ui-font) !important;
}
/* Keep icon fonts working (Roboto override was blanking the hamburger) */
#page-topbar i.fa,
#page-topbar i.fas,
#page-topbar i.far,
#page-topbar .fa {
  font-family: "Font Awesome 5 Free" !important;
  font-weight: 900 !important;
}
#page-topbar i.bx,
#page-topbar .bx {
  font-family: boxicons !important;
  font-weight: normal !important;
}
#page-topbar i.mdi,
#page-topbar .mdi {
  font-family: "Material Design Icons" !important;
  font-weight: normal !important;
}
#page-topbar {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  width: 100% !important;
  z-index: 1005 !important;
  background: #03314d !important;
}
body[data-layout-scrollable="true"] #page-topbar,
body[data-layout-scrollable="true"][data-layout="horizontal"] #page-topbar {
  position: fixed !important;
}
#page-topbar .navbar-header {
  position: relative !important;
  display: flex !important;
  align-items: center !important;
  width: 100% !important;
  height: 70px !important;
  margin: 0 !important;
  padding: 0 1.25rem !important;
  box-sizing: border-box !important;
  background: #03314d !important;
  border-bottom: 1px solid rgba(255,255,255,.12) !important;
}
#page-topbar .navbar-header > .d-flex:first-child {
  flex: 0 0 240px !important;
  width: 240px !important;
  min-width: 240px !important;
  max-width: 240px !important;
  align-items: center !important;
  z-index: 2 !important;
}
#page-topbar .sr-header-actions {
  flex: 0 0 240px !important;
  width: 240px !important;
  min-width: 240px !important;
  max-width: 240px !important;
  margin-left: auto !important;
  display: flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
  gap: .25rem !important;
  z-index: 2 !important;
}
#page-topbar .logo-dark { display: none !important; }
#page-topbar .logo-light,
body[data-topbar=dark] #page-topbar .logo-light,
body[data-topbar=light] #page-topbar .logo-light,
.navbar-brand-box .logo-light {
  display: inline-flex !important;
  align-items: center !important;
  text-decoration: none !important;
}
#page-topbar .logo img {
  height: 40px !important;
  width: auto !important;
  display: block !important;
}
#page-topbar .tessera-brand {
  display: inline-flex !important;
  align-items: center !important;
  font-family: Roboto, sans-serif !important;
  font-size: 1.25rem !important;
  font-weight: 700 !important;
  letter-spacing: 0.14em !important;
  text-transform: uppercase !important;
  color: #fff !important;
  line-height: 1 !important;
  white-space: nowrap !important;
}
#page-topbar .sr-avatar-wrap {
  position: relative !important;
  display: inline-flex !important;
  width: 36px !important;
  height: 36px !important;
  flex-shrink: 0 !important;
}
#page-topbar .sr-avatar-wrap .header-profile-user {
  width: 36px !important;
  height: 36px !important;
  object-fit: cover !important;
  background: #0b4a6b !important;
}
#page-topbar .sr-avatar-fallback {
  display: none;
  width: 36px !important;
  height: 36px !important;
  border-radius: 50% !important;
  align-items: center !important;
  justify-content: center !important;
  background: #5b73e8 !important;
  color: #fff !important;
  font-size: 14px !important;
  font-weight: 600 !important;
}
.sr-modules-center {
  position: absolute !important;
  left: 50% !important;
  top: 50% !important;
  transform: translate(-50%, -50%) !important;
  z-index: 5 !important;
}
.sr-modules-inline {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: .5rem !important;
  margin: 0 !important;
  padding: 0 !important;
  list-style: none !important;
}
.sr-module-link {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: .45rem !important;
  min-width: 7.5rem !important;
  padding: .55rem 1.15rem !important;
  margin: 0 !important;
  font-size: 14.4px !important;
  font-weight: 400 !important;
  line-height: 1.4 !important;
  color: rgba(233,236,239,.78) !important;
  text-decoration: none !important;
  white-space: nowrap !important;
  border-radius: .25rem !important;
}
.sr-module-link svg {
  width: 16px !important;
  height: 16px !important;
  margin: 0 !important;
  color: inherit !important;
}
.sr-module-link.is-active,
.sr-module-link.active {
  color: #fff !important;
  font-weight: 500 !important;
}
#page-topbar .topnav {
  background: #022a42 !important;
  margin: 0 !important;
  border-bottom: 1px solid rgba(255,255,255,.12) !important;
  padding: 0 .5rem !important;
}
@media (min-width: 992px) {
  #page-topbar .topnav .navbar,
  #page-topbar .topnav .navbar-collapse,
  #page-topbar.sr-accounts-chrome .topnav .navbar,
  #page-topbar.sr-accounts-chrome .topnav .navbar-collapse,
  body.sr-platform-body #page-topbar .topnav .navbar,
  body.sr-platform-body #page-topbar .topnav .navbar-collapse {
    justify-content: center !important;
  }
  #page-topbar .topnav .navbar-nav,
  #page-topbar.sr-accounts-chrome .topnav .navbar-nav,
  body.sr-platform-body #page-topbar .topnav .navbar-nav {
    justify-content: center !important;
    margin: 0 auto !important;
    width: auto !important;
  }
}
#page-topbar .topnav .navbar-nav {
  display: flex !important;
  flex-wrap: nowrap !important;
  align-items: center !important;
  gap: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
}
#page-topbar .topnav .navbar-nav .nav-link {
  display: inline-flex !important;
  align-items: center !important;
  gap: .45rem !important;
  padding: .85rem 1.15rem !important;
  margin: 0 !important;
  font-size: 14.4px !important;
  font-weight: 400 !important;
  line-height: 1.4 !important;
  color: rgba(233,236,239,.72) !important;
  text-decoration: none !important;
  white-space: nowrap !important;
  background: transparent !important;
}
#page-topbar .topnav .navbar-nav .nav-link svg,
#page-topbar .topnav .navbar-nav .nav-link .nav-icon {
  display: inline-block !important;
  width: 16px !important;
  height: 16px !important;
  margin: 0 !important;
  color: inherit !important;
  flex-shrink: 0 !important;
}
#page-topbar .topnav .navbar-nav .nav-link span {
  display: inline-block !important;
  line-height: 1.2 !important;
  white-space: nowrap !important;
}
#page-topbar .topnav .navbar-nav > .nav-item.sr-nav-current > .nav-link {
  color: #fff !important;
  font-weight: 500 !important;
}
#page-topbar .topnav .navbar-nav .nav-item.active > .nav-link::before,
#page-topbar .topnav .navbar-nav .nav-link.active::before,
#page-topbar .topnav .navbar-nav .dropdown.active > a::before {
  content: none !important;
  display: none !important;
}
#page-topbar .topnav .navbar-nav > .nav-item.sr-nav-current > .nav-link::after {
  content: "" !important;
  position: absolute !important;
  left: 1.15rem !important;
  right: 1.15rem !important;
  bottom: 0 !important;
  height: 2px !important;
  border-radius: 2px 2px 0 0 !important;
  background: #5b73e8 !important;
}
#page-topbar .header-profile-user {
  width: 36px !important;
  height: 36px !important;
}
#page-topbar .user-name {
  display: block !important;
  color: #fff !important;
  font-size: 13px !important;
  font-weight: 500 !important;
}
#page-topbar .user-sub-title {
  display: block !important;
  color: rgba(233,236,239,.55) !important;
  font-size: 11px !important;
}
#page-topbar.sr-accounts-chrome .topnav .navbar,
#page-topbar.sr-accounts-chrome .topnav .navbar-collapse,
#page-topbar.sr-accounts-chrome .topnav .navbar-nav {
  justify-content: center !important;
}
#page-topbar.sr-accounts-chrome .topnav .navbar-nav {
  width: auto !important;
  margin: 0 auto !important;
}
/* Content clears fixed topbar (+ ops topnav when present) */
body[data-layout="horizontal"] .page-content,
body[data-layout=horizontal] .page-content,
.main-content .page-content {
  margin-top: 0 !important;
  padding-top: calc(var(--sr-chrome-height, 120px) + 1.25rem) !important;
}
@media (max-width: 991.98px) {
  #page-topbar .navbar-header {
    height: auto !important;
    min-height: 70px !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    row-gap: 0 !important;
    padding: 0 0.75rem 0 0 !important;
  }
  #page-topbar .navbar-header > .d-flex:first-child {
    flex: 1 1 auto !important;
    width: auto !important;
    min-width: 0 !important;
    max-width: none !important;
    min-height: 70px !important;
    align-items: center !important;
    padding-left: 0.75rem !important;
  }
  #page-topbar .sr-header-actions {
    flex: 0 0 auto !important;
    width: auto !important;
    min-width: 0 !important;
    max-width: none !important;
    min-height: 70px !important;
    align-items: center !important;
  }
  #page-topbar .navbar-brand-box {
    padding-right: 0 !important;
  }
  /* Dashonic-style horizontal toggle: tall header hit-target beside brand */
  #page-topbar .sr-topnav-toggle.header-item {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 70px !important;
    width: auto !important;
    min-width: 3rem !important;
    padding: 0 0.85rem !important;
    margin: 0 0 0 0.15rem !important;
    color: #e9ecef !important;
    background: transparent !important;
    border: 0 !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    line-height: 1 !important;
  }
  #page-topbar .sr-topnav-toggle.header-item:hover,
  #page-topbar .sr-topnav-toggle.header-item:focus {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.05) !important;
  }
  #page-topbar .sr-topnav-toggle.header-item i {
    font-size: 1.25rem !important;
    line-height: 1 !important;
    width: auto !important;
    height: auto !important;
  }
  #page-topbar .sr-header-actions .user-item-desc {
    display: none !important;
  }
  #page-topbar .tessera-brand {
    font-size: 1.05rem !important;
    letter-spacing: 0.1em !important;
  }
  .sr-modules-center {
    position: static !important;
    transform: none !important;
    width: 100% !important;
    order: 3 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding: 0.4rem 0.75rem !important;
  }
  .sr-modules-inline {
    flex-wrap: wrap !important;
    justify-content: flex-start !important;
    gap: 0.25rem !important;
  }
  .sr-module-link {
    min-width: 0 !important;
    padding: 0.4rem 0.65rem !important;
    font-size: 13px !important;
  }
  #page-topbar .topnav {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
    padding: 0 !important;
  }
  #page-topbar .topnav .navbar-collapse:not(.show) {
    display: none !important;
  }
  #page-topbar .topnav .navbar-collapse.show {
    display: block !important;
    width: 100% !important;
  }
  #page-topbar .topnav .navbar-nav {
    flex-direction: column !important;
    flex-wrap: wrap !important;
    align-items: stretch !important;
    width: 100% !important;
    margin: 0 !important;
  }
  #page-topbar .topnav .navbar-nav .nav-link {
    width: 100% !important;
    justify-content: flex-start !important;
    padding: 0.75rem 1rem !important;
  }
  #page-topbar.sr-accounts-chrome .topnav .navbar,
  #page-topbar.sr-accounts-chrome .topnav .navbar-collapse,
  #page-topbar.sr-accounts-chrome .topnav .navbar-nav,
  body.sr-platform-body #page-topbar .topnav .navbar,
  body.sr-platform-body #page-topbar .topnav .navbar-collapse,
  body.sr-platform-body #page-topbar .topnav .navbar-nav {
    justify-content: flex-start !important;
    margin: 0 !important;
    width: 100% !important;
  }
  body[data-layout="horizontal"] .page-content,
  body[data-layout=horizontal] .page-content,
  .main-content .page-content {
    margin-top: 0 !important;
    padding-top: calc(var(--sr-chrome-height, 140px) + 0.75rem) !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
  }
}
@media (min-width: 992px) {
  #page-topbar .topnav .navbar-collapse {
    display: flex !important;
  }
}
</style>
<script>
(function () {
  function srSyncChromeHeight() {
    var bar = document.getElementById('page-topbar');
    if (!bar) return;
    var h = Math.ceil(bar.getBoundingClientRect().height);
    if (h > 0) {
      document.documentElement.style.setProperty('--sr-chrome-height', h + 'px');
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', srSyncChromeHeight);
  } else {
    srSyncChromeHeight();
  }
  window.addEventListener('resize', srSyncChromeHeight);
  window.addEventListener('load', srSyncChromeHeight);
  setTimeout(srSyncChromeHeight, 50);
  setTimeout(srSyncChromeHeight, 300);
})();
</script>
