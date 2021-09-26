var options = {
  chart: {
    height: 260,
    type: 'bar',
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      columnWidth: '50%',
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    }
  },
  series: [{
    name: 'Overall sales',
    data: [52, 73, 34, 66, 82, 49]
  }],
  xaxis: {
    categories: ["USA", "India", "Canada", "Brazil", "Turkey", "UK"],
    axisBorder: {
      show: false
    },
    tooltip: {
      enabled: true,
    },
    labels: {
      show: true,
      rotate: -45,
      rotateAlways: true,
    },
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
  },
  grid: {
    borderColor: '#e0e6ed',
    strokeDashArray: 5,
    xaxis: {
      lines: {
        show: true,
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
    }, 
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return val + " thousands"
      }
    }
  },
  colors: ['#1273eb', '#999999'],
}
var chart = new ApexCharts(
  document.querySelector("#byCountry"),
  options
);
chart.render();