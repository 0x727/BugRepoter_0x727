var options = {
	chart: {
		height: 300,
		type: 'area',
		toolbar: {
      show: false,
    },
    dropShadow: {
			enabled: true,
			opacity: 0.1,
			blur: 5,
			left: -10,
			top: 10
		},
	},
	dataLabels: {
		enabled: false
	},
	stroke: {
		curve: 'smooth',
		width: 3
	},
	series: [{
		name: 'Uni Pro Admin',
		data: [3100, 4000, 2800, 5100, 4200, 1090, 1000]
	}, {
		name: 'UniPro Dashboard',
		data: [1100, 3200, 4500, 3200, 3400, 5200, 4100]
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
      left: 0
    }, 
  },
	xaxis: {
		type: 'datetime',
		categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"],                
	},
	theme: {
		monochrome: {
			enabled: true,
			color: '#1273eb',
			shadeIntensity: 0.1
		},
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
	tooltip: {
		x: {
			format: 'dd/MM/yy'
		},
	}
}

var chart = new ApexCharts(
	document.querySelector("#basic-area-spline-graph"),
	options
);

chart.render();
