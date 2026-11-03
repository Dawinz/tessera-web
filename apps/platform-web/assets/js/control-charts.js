(function (global) {
  'use strict';

  function isDark() {
    return document.body.getAttribute('data-layout-mode') === 'dark';
  }

  function labelColor() {
    return isDark() ? '#adb5bd' : '#74788d';
  }

  function gridColor() {
    return isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
  }

  function mountDonut(hostId, data, opts) {
    opts = opts || {};
    var host = document.getElementById(hostId);
    if (!host || !global.ApexCharts || !data || !data.labels || !data.labels.length) {
      if (host) {
        host.innerHTML = '<div class="control-empty">' + (opts.empty || 'No chart data') + '</div>';
      }
      return null;
    }
    var chart = new global.ApexCharts(host, {
      chart: { type: 'donut', height: opts.height || 200, fontFamily: 'Roboto, system-ui, sans-serif' },
      series: data.values,
      labels: data.labels,
      colors: opts.colors || ['#34c38f', '#f1b44c', '#f46a6a', '#50a5f1', '#556ee6'],
      legend: { position: 'bottom', labels: { colors: isDark() ? '#ced4da' : '#495057' } },
      plotOptions: {
        pie: {
          donut: {
            size: opts.donutSize || '72%',
            labels: {
              show: !!opts.centerLabel,
              total: {
                show: !!opts.centerLabel,
                label: opts.centerLabel || '',
                color: isDark() ? '#e9ecef' : '#03314d',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                }
              }
            }
          }
        }
      },
      dataLabels: { enabled: false }
    });
    chart.render();
    return chart;
  }

  function mountBar(hostId, data, opts) {
    opts = opts || {};
    var host = document.getElementById(hostId);
    if (!host || !global.ApexCharts || !data || !data.labels || !data.labels.length) {
      if (host) {
        host.innerHTML = '<div class="control-empty">' + (opts.empty || 'No chart data') + '</div>';
      }
      return null;
    }
    var chart = new global.ApexCharts(host, {
      chart: { type: 'bar', height: opts.height || 200, toolbar: { show: false }, fontFamily: 'Roboto, system-ui, sans-serif' },
      series: [{ name: opts.seriesName || 'Count', data: data.values }],
      colors: [opts.color || '#50a5f1'],
      plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
      dataLabels: { enabled: false },
      xaxis: {
        categories: data.labels,
        labels: { style: { colors: labelColor(), fontSize: '10px' }, rotate: opts.rotateLabels || -35 }
      },
      yaxis: { labels: { style: { colors: labelColor() } }, tickAmount: 4 },
      grid: { borderColor: gridColor() }
    });
    chart.render();
    return chart;
  }

  function readJson(id) {
    var el = document.getElementById(id);
    if (!el || !el.textContent) return null;
    try {
      return JSON.parse(el.textContent);
    } catch (e) {
      return null;
    }
  }

  function bootFromPayload(payloadId, map) {
    var data = readJson(payloadId);
    if (!data) return;
    Object.keys(map).forEach(function (hostId) {
      var spec = map[hostId];
      var slice = data[spec.key];
      if (spec.type === 'donut') {
        mountDonut(hostId, slice, spec.opts || {});
      } else if (spec.type === 'bar') {
        mountBar(hostId, slice, spec.opts || {});
      }
    });
  }

  global.ControlCharts = {
    mountDonut: mountDonut,
    mountBar: mountBar,
    readJson: readJson,
    bootFromPayload: bootFromPayload
  };
})(window);
