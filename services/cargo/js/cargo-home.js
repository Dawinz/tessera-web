/**
 * Cargo Home - batched KPI fetch + chart fragments with abort/retry.
 */
(function ($) {
  'use strict';

  var requestId = 0;
  var kpiXhr = null;
  var chartXhrs = [];
  var KPI_TTL_MS = 90000;
  if (!window.__tesseraCargoKpiCache) {
    window.__tesseraCargoKpiCache = Object.create(null);
  }
  var kpiCache = window.__tesseraCargoKpiCache;

  function routeParam() {
    var hidden = ($('#route_name').text() || '').trim();
    if (hidden) {
      return hidden;
    }
    var label = ($('#route option:selected').text() || '').trim();
    if (!label) {
      return '';
    }
    return window.btoa(unescape(encodeURIComponent(label)));
  }

  function kpiSkeleton() {
    return '<div class="placeholder-glow"><span class="placeholder col-8"></span></div>';
  }

  function chartSkeleton(h) {
    return '<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:' + (h || 280) + 'px;"></span></div>';
  }

  function errorBanner(msg) {
    return (
      '<div class="alert alert-danger border-0 mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">' +
      '<span><i class="uil uil-exclamation-triangle me-1"></i>' + (msg || 'Could not load dashboard data.') + '</span>' +
      '<button type="button" class="btn btn-sm btn-light sr-cargo-home-retry">Retry</button>' +
      '</div>'
    );
  }

  function abortCharts() {
    chartXhrs.forEach(function (x) {
      try { if (x && x.abort) x.abort(); } catch (e) { /* ignore */ }
    });
    chartXhrs = [];
  }

  function loadFragment($el, url, height) {
    $el.html(chartSkeleton(height));
    var x = $.ajax({
      url: url,
      dataType: 'html',
      cache: false,
      timeout: 45000
    })
      .done(function (html) {
        $el.html(html);
      })
      .fail(function (_j, status) {
        if (status === 'abort') return;
        $el.html('<div class="text-muted small py-4 text-center">Could not load this chart.</div>');
      });
    chartXhrs.push(x);
  }

  function showError(msg) {
    var $banner = $('#sr-cargo-home-error');
    if (!$banner.length) {
      $('.sr-cargo-dashboard').prepend('<div id="sr-cargo-home-error" class="mb-3"></div>');
      $banner = $('#sr-cargo-home-error');
    }
    $banner.removeClass('d-none').html(errorBanner(msg));
  }

  function paintKpis(k) {
    k = k || {};
    $('#totol_cash').html(k.total_label || '0');
    $('#load_paid_cash').html(k.paid_label || '0');
    $('#load_to_paid').html(k.to_be_label || '0');
    $('#total_number_l').html(k.luggage_label || '0');
  }

  function loadDashboard(force) {
    var route = routeParam();
    if (!route) {
      return;
    }
    var myId = ++requestId;
    var qs = '?route=' + encodeURIComponent(route);
    var cached = kpiCache[route];

    if (!$('#sr-cargo-home-error').length) {
      $('.sr-cargo-dashboard').prepend('<div id="sr-cargo-home-error" class="mb-3 d-none"></div>');
    }
    $('#sr-cargo-home-error').addClass('d-none').empty();

    if (!force && cached && cached.kpis && Date.now() - cached.at < KPI_TTL_MS) {
      paintKpis(cached.kpis);
    } else if (cached && cached.kpis) {
      paintKpis(cached.kpis);
    } else {
      $('#totol_cash, #load_paid_cash, #load_to_paid, #total_number_l').html(kpiSkeleton());
    }

    if (kpiXhr && kpiXhr.readyState !== 4) {
      try { kpiXhr.abort(); } catch (e) { /* ignore */ }
    }
    abortCharts();

    var skipKpiNet = !force && cached && cached.kpis && Date.now() - cached.at < KPI_TTL_MS;
    if (!skipKpiNet) {
      kpiXhr = $.ajax({
        url: 'fetch_home_dashboard.php' + qs,
        dataType: 'json',
        cache: false,
        timeout: 30000
      })
        .done(function (data) {
          if (myId !== requestId) return;
          if (!data || !data.ok) {
            if (!(cached && cached.kpis)) {
              showError((data && data.error) || 'Dashboard returned an error.');
            }
            return;
          }
          kpiCache[route] = { at: Date.now(), kpis: data.kpis || {} };
          paintKpis(data.kpis);
        })
        .fail(function (_j, status) {
          if (myId !== requestId || status === 'abort') return;
          if (!(cached && cached.kpis)) {
            showError('Could not load cargo totals. Check your connection and try again.');
          }
        });
    }

    loadFragment($('#all_route_monthly'), 'load_assgine_monthly_graph.php' + qs, 300);
    loadFragment($('#mchart'), 'load_monthly_chart.php' + qs, 300);
    loadFragment($('#load_daily_inc'), 'daily_chart.php' + qs, 300);
    loadFragment($('#load_daily_recipt'), 'load_daily_recipt.php' + qs, 280);
  }

  $(document).ready(function () {
    $(document).on('click', '.sr-cargo-home-retry', function (e) {
      e.preventDefault();
      loadDashboard(true);
    });

    $('#route').on('change', function () {
      var label = ($('#route option:selected').text() || '').trim();
      if (!label) {
        return;
      }
      var route2 = encodeURIComponent(window.btoa(unescape(encodeURIComponent(label))));
      window.location = 'home.php?route=' + route2;
    });

    loadDashboard(false);
  });
})(jQuery);
