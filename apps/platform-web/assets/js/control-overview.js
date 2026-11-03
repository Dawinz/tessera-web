(function () {
  'use strict';

  function readPayload() {
    var el = document.getElementById('control-overview-data');
    if (!el || !el.textContent) return null;
    try {
      return JSON.parse(el.textContent);
    } catch (e) {
      return null;
    }
  }

  function mountVolumeChart(data) {
    var host = document.getElementById('control-chart-volume');
    if (!host || !window.ApexCharts || !data || !data.series) return;

    var isDark = document.body.getAttribute('data-layout-mode') === 'dark';
    var labelColor = isDark ? '#adb5bd' : '#74788d';
    var gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';

    new ApexCharts(host, {
      chart: {
        type: 'line',
        height: 260,
        stacked: false,
        toolbar: { show: false },
        fontFamily: 'Roboto, system-ui, sans-serif',
        animations: { enabled: true, speed: 500 }
      },
      series: data.series,
      colors: ['#556ee6', '#50a5f1', '#34c38f'],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: [0, 0, 2.5] },
      fill: {
        type: ['solid', 'solid', 'gradient'],
        opacity: [0.85, 0.85, 1],
        gradient: { shadeIntensity: 0.9, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] }
      },
      plotOptions: {
        bar: { borderRadius: 3, columnWidth: '42%' }
      },
      xaxis: {
        categories: data.labels,
        labels: { style: { colors: labelColor, fontSize: '11px' } },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: [
        {
          seriesName: 'Bookings',
          title: { text: 'Volume', style: { color: labelColor, fontSize: '11px' } },
          labels: { style: { colors: labelColor } }
        },
        {
          seriesName: 'Parcels',
          show: false
        },
        {
          seriesName: 'Revenue',
          opposite: true,
          title: { text: 'Revenue', style: { color: labelColor, fontSize: '11px' } },
          labels: {
            style: { colors: labelColor },
            formatter: function (v) {
              if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
              if (v >= 1000) return (v / 1000).toFixed(0) + 'k';
              return Math.round(v);
            }
          }
        }
      ],
      grid: { borderColor: gridColor, strokeDashArray: 4 },
      legend: { position: 'top', horizontalAlign: 'right', labels: { colors: labelColor } },
      tooltip: { shared: true, theme: isDark ? 'dark' : 'light' }
    }).render();
  }

  function mountOperatorChart(data) {
    var host = document.getElementById('control-chart-operators');
    if (!host || !window.ApexCharts || !data || !data.labels || !data.labels.length) {
      if (host) {
        host.innerHTML = '<div class="control-empty">No operator status data</div>';
      }
      return;
    }

    var isDark = document.body.getAttribute('data-layout-mode') === 'dark';
    new ApexCharts(host, {
      chart: { type: 'donut', height: 220, fontFamily: 'Roboto, system-ui, sans-serif' },
      series: data.values,
      labels: data.labels,
      colors: ['#34c38f', '#f1b44c', '#f46a6a'],
      legend: { position: 'bottom', labels: { colors: isDark ? '#ced4da' : '#495057' } },
      plotOptions: {
        pie: {
          donut: {
            size: '72%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Operators',
                color: isDark ? '#e9ecef' : '#03314d',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                }
              }
            }
          }
        }
      },
      dataLabels: { enabled: false }
    }).render();
  }

  function mountOnboardingChart(data) {
    var host = document.getElementById('control-chart-onboarding');
    if (!host || !window.ApexCharts || !data || !data.labels || !data.labels.length) {
      if (host) {
        host.innerHTML = '<div class="control-empty">No open onboarding cases</div>';
      }
      return;
    }

    var isDark = document.body.getAttribute('data-layout-mode') === 'dark';
    new ApexCharts(host, {
      chart: { type: 'bar', height: 200, toolbar: { show: false }, fontFamily: 'Roboto, system-ui, sans-serif' },
      series: [{ name: 'Cases', data: data.values }],
      colors: ['#50a5f1'],
      plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
      dataLabels: { enabled: false },
      xaxis: {
        categories: data.labels,
        labels: { style: { colors: isDark ? '#adb5bd' : '#74788d', fontSize: '10px' }, rotate: -35 }
      },
      yaxis: {
        labels: { style: { colors: isDark ? '#adb5bd' : '#74788d' } },
        tickAmount: 4
      },
      grid: { borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)' }
    }).render();
  }

  function boot() {
    var data = readPayload();
    if (!data) return;
    mountVolumeChart(data.volume);
    mountOperatorChart(data.operators);
    mountOnboardingChart(data.onboarding);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
