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
    name: 'Orders',
    data: [200, 400, 600, 700, 1000, 800, 200]
  }],
  theme: {
    monochrome: {
      enabled: true,
      color: '#ef503f',
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
}

var chart = new ApexCharts(
  document.querySelector("#ordersGraph"),
  options
);

chart.render();