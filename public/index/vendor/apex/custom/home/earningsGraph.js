var options = {
  chart: {
    height: 200,
    type: 'bar',
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '45px',
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  series: [{
    name: 'Revenue',
    data: [2000, 3000, 4000, 5000]
  }, {
    name: 'Profit',
    data: [2500, 3500, 4500, 5500]
  }],
  legend: {
    show: false,
  },
  xaxis: {
    categories: ['Q1', 'Q2', 'Q3', 'Q4'],
  },
  yaxis: {
    show: false,
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return "$ " + val + " thousands"
      }
    }
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
      right: 0,
      bottom: 0,
      left: 0
    }, 
  },
  colors: ['#f16a5d', '#1273eb'],
}
var chart = new ApexCharts(
  document.querySelector("#earningsGraph"),
  options
);
chart.render();