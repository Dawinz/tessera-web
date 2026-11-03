/**
 * Tickets Home - one dashboard fetch, skeletons, error + retry.
 */
(function ($) {
  'use strict';

  var requestId = 0;
  var xhr = null;
  var chartInstances = {};
  var DASH_TTL_MS = 90000;
  if (!window.__tesseraHomeDashCache) {
    window.__tesseraHomeDashCache = Object.create(null);
  }
  var dashCache = window.__tesseraHomeDashCache;

  function routeParam() {
    var val = $('#route option:selected').val() || '';
    if (val === '__all__') {
      return '__all__';
    }
    if (val !== '') {
      return val.replace(/ /g, '_');
    }
    return ($('#route option:selected').text() || '').replace(/ /g, '_');
  }

  function kpiSkeleton() {
    return '<div class="placeholder-glow"><span class="placeholder col-8"></span></div>';
  }

  function chartSkeleton(h) {
    return '<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:' + (h || 300) + 'px;"></span></div>';
  }

  function errorBanner(msg) {
    return (
      '<div class="alert alert-danger border-0 mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">' +
      '<span><i class="uil uil-exclamation-triangle me-1"></i>' + (msg || 'Could not load dashboard data.') + '</span>' +
      '<button type="button" class="btn btn-sm btn-light sr-home-retry">Retry</button>' +
      '</div>'
    );
  }

  function destroyChart(key) {
    if (chartInstances[key] && typeof chartInstances[key].destroy === 'function') {
      try {
        chartInstances[key].destroy();
      } catch (e) { /* ignore */ }
    }
    chartInstances[key] = null;
  }

  function renderSparkline(elId, data, color) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById(elId);
    if (!host) return;
    destroyChart(elId);
    host.innerHTML = '';
    chartInstances[elId] = new ApexCharts(host, {
      series: [{ data: data || [] }],
      chart: { type: 'area', height: 72, sparkline: { enabled: true }, animations: { speed: 350 } },
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
      colors: [color]
    });
    chartInstances[elId].render();
  }

  function renderYearly(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var el = document.querySelector('#column_chart_datalabel') || document.getElementById('yearly_charts');
    var host = document.getElementById('yearly_charts');
    if (!host) return;
    destroyChart('yearly');
    host.innerHTML = '<div id="column_chart_datalabel" class="apex-charts" dir="ltr"></div>';
    el = document.querySelector('#column_chart_datalabel');
    chartInstances.yearly = new ApexCharts(el, {
      chart: { height: 350, type: 'bar', toolbar: { show: false }, foreColor: '#d5e4ee' },
      plotOptions: { bar: { borderRadius: 4, columnWidth: '55%', dataLabels: { position: 'top' } } },
      dataLabels: {
        enabled: true,
        formatter: function (v) { return Number(v).toLocaleString() + ' Tsh'; },
        offsetY: -18,
        style: { fontSize: '11px', colors: ['#d5e4ee'] }
      },
      series: [{ name: 'Sales', data: payload.series || [] }],
      colors: ['#038edc'],
      grid: { borderColor: 'rgba(255,255,255,0.06)' },
      xaxis: {
        categories: payload.categories || [],
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: { labels: { formatter: function (v) { return Number(v).toLocaleString(); } } },
      title: {
        text: payload.title || 'Yearly sales',
        align: 'left',
        style: { fontSize: '13px', fontWeight: 600, color: '#d5e4ee' }
      }
    });
    chartInstances.yearly.render();
  }

  function renderDaily(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('daily_sales');
    if (!host) return;
    destroyChart('daily');
    host.innerHTML = '<div id="line_chart_basic" class="apex-charts" dir="ltr"></div>';
    chartInstances.daily = new ApexCharts(document.querySelector('#line_chart_basic'), {
      series: [{ name: 'Daily sales', data: payload.series || [] }],
      chart: { height: 350, type: 'area', zoom: { enabled: false }, toolbar: { show: false }, foreColor: '#d5e4ee' },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
      colors: ['#5b73e8'],
      grid: { borderColor: 'rgba(255,255,255,0.06)' },
      markers: { size: 4, strokeWidth: 2 },
      xaxis: { categories: payload.categories || [], title: { text: 'Day of month' } },
      yaxis: { labels: { formatter: function (v) { return Number(v).toLocaleString(); } } }
    });
    chartInstances.daily.render();
  }

  function renderBus(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('load_bus_income');
    if (!host) return;
    destroyChart('bus');
    host.innerHTML = '<div id="bar_chart" class="apex-charts" dir="ltr"></div>';
    chartInstances.bus = new ApexCharts(document.querySelector('#bar_chart'), {
      chart: { height: 350, type: 'bar', toolbar: { show: false }, foreColor: '#d5e4ee' },
      plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '65%' } },
      dataLabels: {
        enabled: true,
        formatter: function (v) { return Number(v).toLocaleString() + ' Tsh'; },
        style: { fontSize: '11px' }
      },
      series: [{ name: 'Income', data: payload.series || [] }],
      colors: ['#51d28c'],
      grid: { borderColor: 'rgba(255,255,255,0.06)' },
      xaxis: { categories: payload.categories || [] }
    });
    chartInstances.bus.render();
  }

  function renderRoutes(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('location_areas');
    if (!host) return;
    destroyChart('routes');
    host.innerHTML = '<div id="simple_pie_chart" class="apex-charts" dir="ltr"></div>';
    chartInstances.routes = new ApexCharts(document.querySelector('#simple_pie_chart'), {
      series: payload.series || [],
      chart: { height: 300, type: 'pie', foreColor: '#d5e4ee' },
      labels: payload.labels || [],
      legend: { position: 'bottom' },
      dataLabels: { dropShadow: { enabled: false } },
      colors: ['#038edc', '#51d28c', '#f7cc53', '#f34e4e', '#564ab1', '#5b73e8']
    });
    chartInstances.routes.render();
  }

  function showSkeletons() {
    $('#load_total_sales, #load_total_sales_number, #load_total_trips, #load_foc').html(kpiSkeleton());
    $('#yearly_charts').html(chartSkeleton(340));
    $('#location_areas, #load_bus_income').html(chartSkeleton(300));
    $('#daily_sales').html(chartSkeleton(340));
    $('#sr-home-error').addClass('d-none').empty();
  }

  function paint(data) {
    var k = data.kpis || {};
    $('#load_total_sales').html('<span class="sr-kpi-value">' + (k.sales_label || '0 Tsh') + '</span>');
    $('#load_total_sales_number').html('<span class="sr-kpi-value">' + (k.seats_label || '0 seats') + '</span>');
    $('#load_total_trips').html('<span class="sr-kpi-value">' + (k.trips_label || '0 trips') + '</span>');
    $('#load_foc').html('<span class="sr-kpi-value">' + (k.foc_label || '0 seats') + '</span>');

    var sp = data.sparklines || {};
    renderSparkline('sparkline-chart-1', sp.sales, '#038edc');
    renderSparkline('sparkline-chart-2', sp.seats, '#51d28c');
    renderSparkline('sparkline-chart-3', sp.trips, '#5b73e8');
    renderSparkline('sparkline-chart-4', sp.foc, '#f7cc53');

    renderYearly(data.yearly || {});
    renderRoutes(data.route_sales || {});
    renderBus(data.bus_income || {});
    renderDaily(data.daily || {});
  }

  function showError(msg) {
    var $banner = $('#sr-home-error');
    if (!$banner.length) {
      $('.sr-home-dashboard').prepend('<div id="sr-home-error" class="mb-3"></div>');
      $banner = $('#sr-home-error');
    }
    $banner.removeClass('d-none').html(errorBanner(msg));
    $('#yearly_charts, #location_areas, #load_bus_income, #daily_sales').html(
      '<div class="text-muted small py-5 text-center">Data unavailable. Use Retry above.</div>'
    );
  }

  function loadDashboard(force) {
    var route = routeParam();
    if (!route) {
      return;
    }
    var myId = ++requestId;
    var cached = dashCache[route];
    if (!force && cached && cached.data && Date.now() - cached.at < DASH_TTL_MS) {
      paint(cached.data);
      return;
    }
    if (cached && cached.data) {
      paint(cached.data);
    } else {
      showSkeletons();
    }
    if (xhr && xhr.readyState !== 4) {
      try { xhr.abort(); } catch (e) { /* ignore */ }
    }
    xhr = $.ajax({
      url: 'fetch_home_dashboard.php',
      data: { route: route },
      dataType: 'json',
      cache: false,
      timeout: 45000
    })
      .done(function (data) {
        if (myId !== requestId) return;
        if (!data || data.ok === false) {
          if (!(cached && cached.data)) {
            showError((data && data.error) || 'Dashboard returned an error.');
          }
          return;
        }
        dashCache[route] = { at: Date.now(), data: data };
        paint(data);
      })
      .fail(function (jq, textStatus) {
        if (myId !== requestId) return;
        if (textStatus === 'abort') return;
        if (!(cached && cached.data)) {
          showError('Could not load dashboard data. Check your connection and try again.');
        }
      });
  }

  $(document).ready(function () {
    if (!$('#sr-home-error').length) {
      $('.sr-home-dashboard').prepend('<div id="sr-home-error" class="mb-3 d-none"></div>');
    }
    $(document).on('click', '.sr-home-retry', function (e) {
      e.preventDefault();
      loadDashboard(true);
    });
    $('#route').on('change', function () {
      loadDashboard(true);
    });
    loadDashboard(false);
  });
})(jQuery);
