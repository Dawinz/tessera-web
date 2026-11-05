/**
 * T-P0-06 Home dashboard — modern console UI (TDS + ApexCharts + console API).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-home-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var charts = {};
  var errEl = document.getElementById('tds-home-error');
  var routeEl = document.getElementById('tds-home-route');
  var leadEl = document.getElementById('tds-home-lead');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }

  function fetchJson(url) {
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var message =
              body && body.error && body.error.message ? body.error.message : 'Request failed';
            throw new Error(message);
          }
          return body;
        });
      }
    );
  }

  function destroy(key) {
    if (charts[key] && typeof charts[key].destroy === 'function') {
      try {
        charts[key].destroy();
      } catch (e) { /* ignore */ }
    }
    charts[key] = null;
  }

  function ink() {
    return getComputedStyle(document.documentElement).getPropertyValue('--tds-ink').trim() || '#1f2937';
  }
  function muted() {
    return getComputedStyle(document.documentElement).getPropertyValue('--tds-ink-muted').trim() || '#6b7280';
  }
  function accent() {
    return getComputedStyle(document.documentElement).getPropertyValue('--tds-accent').trim() || '#038edc';
  }

  function spark(elId, data, color) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById(elId);
    if (!host) return;
    destroy(elId);
    host.innerHTML = '';
    charts[elId] = new ApexCharts(host, {
      series: [{ data: data || [] }],
      chart: { type: 'area', height: 56, sparkline: { enabled: true }, animations: { speed: 300 } },
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
      colors: [color],
    });
    charts[elId].render();
  }

  function paint(data) {
    var k = data.kpis || {};
    var period = data.period || {};
    document.getElementById('tds-kpi-sales').textContent = k.sales_label || '0 Tsh';
    document.getElementById('tds-kpi-seats').textContent = k.seats_label || '0 seats';
    document.getElementById('tds-kpi-trips').textContent = k.trips_label || '0 trips';
    document.getElementById('tds-kpi-foc').textContent = k.foc_label || '0 seats';
    if (leadEl) {
      leadEl.textContent =
        'Ticket sales, trips, and revenue for ' + (period.month_label || 'this month') + '.';
    }
    var yearlyTitle = document.getElementById('tds-yearly-title');
    if (yearlyTitle && data.yearly && data.yearly.title) {
      yearlyTitle.textContent = data.yearly.title;
    }

    var sp = data.sparklines || {};
    spark('tds-spark-sales', sp.sales, accent());
    spark('tds-spark-seats', sp.seats, '#51d28c');
    spark('tds-spark-trips', sp.trips, '#5b73e8');
    spark('tds-spark-foc', sp.foc, '#f7cc53');

    renderYearly(data.yearly || {});
    renderRoutes(data.route_sales || {});
    renderBus(data.bus_income || {});
    renderDaily(data.daily || {});
  }

  function renderYearly(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('tds-chart-yearly');
    if (!host) return;
    destroy('yearly');
    host.innerHTML = '';
    charts.yearly = new ApexCharts(host, {
      chart: { height: 320, type: 'bar', toolbar: { show: false }, foreColor: muted() },
      plotOptions: { bar: { borderRadius: 4, columnWidth: '55%', dataLabels: { position: 'top' } } },
      dataLabels: {
        enabled: true,
        formatter: function (v) {
          return Number(v).toLocaleString();
        },
        offsetY: -16,
        style: { fontSize: '11px', colors: [ink()] },
      },
      series: [{ name: 'Sales', data: payload.series || [] }],
      colors: [accent()],
      grid: { borderColor: 'rgba(0,0,0,0.06)' },
      xaxis: {
        categories: payload.categories || [],
        axisBorder: { show: false },
        axisTicks: { show: false },
      },
      yaxis: {
        labels: {
          formatter: function (v) {
            return Number(v).toLocaleString();
          },
        },
      },
    });
    charts.yearly.render();
  }

  function renderDaily(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('tds-chart-daily');
    if (!host) return;
    destroy('daily');
    host.innerHTML = '';
    charts.daily = new ApexCharts(host, {
      series: [{ name: 'Daily sales', data: payload.series || [] }],
      chart: { height: 320, type: 'area', zoom: { enabled: false }, toolbar: { show: false }, foreColor: muted() },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
      colors: ['#5b73e8'],
      grid: { borderColor: 'rgba(0,0,0,0.06)' },
      markers: { size: 3, strokeWidth: 2 },
      xaxis: { categories: payload.categories || [], title: { text: 'Day of month' } },
      yaxis: {
        labels: {
          formatter: function (v) {
            return Number(v).toLocaleString();
          },
        },
      },
    });
    charts.daily.render();
  }

  function renderBus(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('tds-chart-bus');
    if (!host) return;
    destroy('bus');
    host.innerHTML = '';
    charts.bus = new ApexCharts(host, {
      chart: { height: 300, type: 'bar', toolbar: { show: false }, foreColor: muted() },
      plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '65%' } },
      dataLabels: {
        enabled: true,
        formatter: function (v) {
          return Number(v).toLocaleString();
        },
        style: { fontSize: '11px' },
      },
      series: [{ name: 'Income', data: payload.series || [] }],
      colors: ['#51d28c'],
      grid: { borderColor: 'rgba(0,0,0,0.06)' },
      xaxis: { categories: payload.categories || [] },
    });
    charts.bus.render();
  }

  function renderRoutes(payload) {
    if (typeof ApexCharts === 'undefined') return;
    var host = document.getElementById('tds-chart-routes');
    if (!host) return;
    destroy('routes');
    host.innerHTML = '';
    charts.routes = new ApexCharts(host, {
      series: payload.series || [],
      chart: { height: 300, type: 'pie', foreColor: muted() },
      labels: payload.labels || [],
      legend: { position: 'bottom' },
      dataLabels: { dropShadow: { enabled: false } },
      colors: [accent(), '#51d28c', '#f7cc53', '#f34e4e', '#564ab1', '#5b73e8'],
    });
    charts.routes.render();
  }

  function loadDashboard() {
    showError('');
    var route = routeEl && routeEl.value ? routeEl.value : '__all__';
    var params = new URLSearchParams({ route: route });
    return fetchJson(API + '/home.php?' + params.toString())
      .then(function (body) {
        paint(body.data || {});
      })
      .catch(function (err) {
        showError(err.message || 'Could not load dashboard');
      });
  }

  function loadFilters() {
    return fetchJson(API + '/home_filters.php').then(function (body) {
      var d = body.data || {};
      if (!routeEl) return;
      routeEl.innerHTML = '';
      if (d.can_all) {
        var all = document.createElement('option');
        all.value = '__all__';
        all.textContent = 'All routes (company-wide)';
        routeEl.appendChild(all);
      }
      (d.routes || []).forEach(function (r) {
        var o = document.createElement('option');
        o.value = r.id;
        o.textContent = r.label;
        routeEl.appendChild(o);
      });
      if (!d.can_all && (!d.routes || !d.routes.length)) {
        var none = document.createElement('option');
        none.value = '';
        none.textContent = 'No routes assigned';
        routeEl.appendChild(none);
      }
      if (d.default_route) routeEl.value = d.default_route;
    });
  }

  if (routeEl) {
    routeEl.addEventListener('change', function () {
      loadDashboard();
    });
  }

  loadFilters()
    .catch(function () {
      /* filters optional */
    })
    .then(function () {
      return loadDashboard();
    });
})();
