var options = {
  chart: {
    height: 120,
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
    width: 2,
    lineCap: 'square'
  },
  series: [{
    name: 'Customers',
    data: [200, 400, 500, 700, 1000, 800, 900]
  }],
  theme: {
    monochrome: {
      enabled: true,
      color: '#1273eb',
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
    borderColor: '#ced1e0',
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
  fill: {
    type:"gradient",
    gradient: {
      type: "vertical",
      shadeIntensity: 1,
      inverseColors: !1,
      opacityFrom: .3,
      opacityTo: .05,
      stops: [35, 100]
    }
  },
}

var chart = new ApexCharts(
  document.querySelector("#customersGraph"),
  options
);

chart.render();