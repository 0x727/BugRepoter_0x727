var options = {
	chart: {
		height: 248,
		type: 'line',
		zoom: {
			enabled: false
		},
		toolbar: {
			show: false,
		},
	},
	series: [{
		name: 'Net Profit',
		type: 'column',
		data: [230, 420, 350, 270, 500, 300, 400, 350, 690, 320, 260, 160]
	}, {
		name: 'Revenue',
		type: 'line',
		data: [100, 320, 270, 200, 400, 280, 320, 270, 600, 220, 160, 60]
	}],
	stroke: {
		width: [0, 5],
		curve: "smooth"
	},
	plotOptions: {
		bar: {
			horizontal: !1, 
			columnWidth: "35%"
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
      bottom: 10,
      left: 10
    }, 
  },
  yaxis: {
    show: false,
  },
	labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
  xaxis: {
    type: 'month'
  },
	tooltip: {
    y: {
      formatter: function (val) {
        return "$" + val + " thousands"
      }
    }
  },
  colors: ['#1273eb', '#e5f0ff'],
}
var chart = new ApexCharts(
  document.querySelector("#revenue"),
  options
);
chart.render();
