var options = {
  chart: {
    height: 232,
    type: 'donut',
  },
  labels: ['New', 'Resolved', 'Pending'],
  series: [60000, 45000, 15000],
  legend: {
    position: 'bottom',
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    width: 2,
    colors: ['#ffffff'],
  },
  colors: ['#1273eb', '#59a2fb', '#8ec0fd'],
  tooltip: {
    y: {
      formatter: function(val) {
        return  "$" + val
      }
    }
  },
}
var chart = new ApexCharts(
  document.querySelector("#tickets"),
  options
);
chart.render();