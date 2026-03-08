'use strict';

(function () {
  const chartEl = document.querySelector('#dashboardTrendChart');
  if (!chartEl || typeof ApexCharts === 'undefined') return;

  const labels = JSON.parse(chartEl.dataset.labels || '[]');
  const credit = JSON.parse(chartEl.dataset.credit || '[]');
  const debit = JSON.parse(chartEl.dataset.debit || '[]');

  const axisColor = isDarkStyle ? config.colors_dark.borderColor : config.colors.borderColor;
  const labelColor = isDarkStyle ? config.colors_dark.textMuted : config.colors.textMuted;
  const headingColor = isDarkStyle ? config.colors_dark.headingColor : config.colors.headingColor;

  const options = {
    series: [
      { name: '«·≈Ìœ«⁄', data: credit },
      { name: '«·”Õ»', data: debit }
    ],
    chart: {
      type: 'line',
      height: 340,
      toolbar: { show: false },
      parentHeightOffset: 0
    },
    colors: [config.colors.success, config.colors.danger],
    stroke: {
      width: 3,
      curve: 'smooth'
    },
    grid: {
      borderColor: axisColor,
      strokeDashArray: 6,
      padding: {
        left: 10,
        right: 10
      }
    },
    xaxis: {
      categories: labels,
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
        style: {
          colors: labelColor,
          fontSize: '12px'
        }
      }
    },
    yaxis: {
      labels: {
        style: {
          colors: labelColor,
          fontSize: '12px'
        }
      }
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      labels: {
        colors: headingColor
      }
    },
    dataLabels: { enabled: false },
    tooltip: {
      y: {
        formatter: function (value) {
          return Number(value || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          });
        }
      }
    }
  };

  const chart = new ApexCharts(chartEl, options);
  chart.render();
})();
