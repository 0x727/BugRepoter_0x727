var options = {
  chart: {
    height: 300,
    type: 'bar',
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
      columnWidth: '40%',
    },
  },
  dataLabels: {
    enabled: true
  },
  series: [{
    name: 'Orders',
    data: [10, 15, 25, 35, 45, 55, 65, 75, 85, 95, 105, 115]
  },{
    name: 'Revenue',
    data: [15, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120]
  }],
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
      right: 0,
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
        return "$" + val + " thousands"
      }
    }
  },
  colors: ['#1273eb', '#4c9dff'],
}
var chart = new ApexCharts(
  document.querySelector("#sales2"),
  options
);
chart.render();


