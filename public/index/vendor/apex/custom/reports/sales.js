var options = {
  chart: {
    height: 150,
    type: 'area',
    toolbar: {
      show: false,
    }
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    show: true,
    width: 3,
    lineCap: 'square'
  },
  series: [{
    name: 'Earnings',
    data: [200, 400, 600, 700, 1000, 800, 200]
  }],
  theme: {
    monochrome: {
      enabled: true,
      color: '#1cbeac',
      shadeIntensity: 0.2
    },
  },
  xaxis: {
    axisBorder: {
      show: false
    },
    categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    axisTicks: {
      show: false
    },
    labels: {
      show: false
    }
  },
  yaxis: {
    show: false
  },
  grid: {
    xaxis: {
      lines: {
        show: false
      }
    },   
    yaxis: {
      lines: {
        show: false,
      } 
    },
    padding: {
      top: -30,
      right: -50,
      bottom: -50,
      left: -50
    }, 
  },
  fill: {
    type:"gradient",
    gradient: {
      type: "vertical",
      shadeIntensity: 1,
      inverseColors: !1,
      opacityFrom: .2,
      opacityTo: .01,
      stops: [35, 100]
    }
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return '$' + val
      }
    }
  },
}

var chart = new ApexCharts(
  document.querySelector("#salesGraph"),
  options
);

chart.render();