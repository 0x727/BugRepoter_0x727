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
      horizontal: true,
    }
  },
  dataLabels: {
    enabled: false
  },
  grid: {
    borderColor: '#e0e6ed',
    strokeDashArray: 5,
    xaxis: {
      lines: {
        show: false,
      }
    },   
    yaxis: {
      lines: {
        show: true,
      } 
    },
    padding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0
    }, 
  },
  series: [{
    data: [2000, 3000, 4000, 5000, 6000]
  }],
  colors: ['#1273eb', '#999999'],
  xaxis: {
    categories: ["Organic", "Search", "TV Ads", "Social", "Video"],
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return val + ' Visits'
      }
    }
  },
}

var chart = new ApexCharts(
  document.querySelector("#byChannel"),
  options
);

chart.render();