var options = {
  chart: {
    height: 228,
    type: 'line',
    stacked: true,
    toolbar: {
      show: false
    },
    zoom: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      horizontal: false,
    },
  },
  dataLabels: {
    enabled: true
  },
  series: [{
    name: 'Revenue',
    data: [10, 45, 25,  65]
  }],
  xaxis: {
    categories: ['Q1', 'Q2', 'Q3', 'Q4'],
  },
  legend: {
    position: 'bottom',
    offsetY: 0,
  },
  grid: {
    borderColor: '#e0e6ed',
    strokeDashArray: 5,
    xaxis: {
      lines: {
        show: true
      }
    },   
    yaxis: {
      lines: {
        show: false,
      } 
    },
    padding: {
      top: 0,
      right: 5,
      bottom: 10,
      left: 10
    }, 
  },
  yaxis: {
    show: false,
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return "$" + val + " Millions"
      }
    }
  },
  colors: ['#1273eb', '#59a2fb'],
}
var chart = new ApexCharts(
  document.querySelector("#revenue"),
  options
);
chart.render();


