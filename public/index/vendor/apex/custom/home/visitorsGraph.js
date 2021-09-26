var options = {
  chart: {
    height: 200,
    type: 'line',
    zoom: {
      enabled: false
    },
    toolbar: {
      show: false
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'smooth',
    width: 5,
  },
  series: [{
    name: "Visitors",
    data: [10, 41, 35, 51, 49, 21, 37]
  }],
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
      left: 10
    }, 
  },
  xaxis: {
    categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fir', 'Sat'],
  },
  yaxis: {
    show: false,
  },
  theme: {
    monochrome: {
      enabled: true,
      color: '#1273eb',
      shadeIntensity: 0.1
    },
  },
  fill: {
    type: 'solid',
  },
  markers: {
    size: 0,
    opacity: 0.2,
    colors: ["#1273eb"],
    strokeColor: "#fff",
    strokeWidth: 2,
    hover: {
      size: 7,
    }
  },
}

var chart = new ApexCharts(
  document.querySelector("#visitorsGraph"),
  options
);

chart.render();