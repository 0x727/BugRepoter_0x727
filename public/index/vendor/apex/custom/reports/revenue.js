var options = {
  chart: {
    height: 170,
    type: 'bar',
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '50%',
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
    name: 'Net Profit',
    data: [10, 15, 25, 35, 45, 55, 65]
  }, {
    name: 'Revenue',
    data: [15, 20, 30, 40, 50, 60, 70]
  }],
  xaxis: {
    categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  },
  yaxis: {
    title: {
      text: '$ (thousands)'
    }
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
    borderColor: '#ffffff',
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
      bottom: 10,
      left: 0
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
  colors: ['#ffffff', '#77c137'],
}
var chart = new ApexCharts(
  document.querySelector("#revenue"),
  options
);
chart.render();