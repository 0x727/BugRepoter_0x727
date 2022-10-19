var options = {
	chart: {
		height: 300,
		type: 'bar',
		dropShadow: {
			enabled: true,
			opacity: 0.1,
			blur: 5,
			left: -10,
			top: 10
		},
	},
	plotOptions: {
		bar: {
			dataLabels: {
				position: 'top', // top, center, bottom
			},
		}
	},
	dataLabels: {
		enabled: true,
		formatter: function (val) {
			return val + "%";
		},
		offsetY: -20,
		style: {
			fontSize: '12px',
			colors: ["#2e323c"]
		}
	},
	series: [{
		name: 'Inflation',
		data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
	}],
	xaxis: {
		categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		position: 'top',
		labels: {
			offsetY: -18,
		},
		axisBorder: {
			show: false
		},
		axisTicks: {
			show: false
		},
		crosshairs: {
			fill: {
				type: 'gradient',
				gradient: {
					colorFrom: '#1273eb',
					colorTo: '#95c5ff',
					stops: [0, 100],
					opacityFrom: 0.4,
					opacityTo: 0.5,
				}
			}
		},
		tooltip: {
			enabled: true,
			offsetY: -35,
		}
	},
	fill: {
		gradient: {
			shade: 'light',
			type: "horizontal",
			shadeIntensity: 0.25,
			gradientToColors: undefined,
			inverseColors: true,
			opacityFrom: 1,
			opacityTo: 1,
			stops: [50, 0, 100, 100]
		},
	},
	yaxis: {
		axisBorder: {
			show: false
		},
		axisTicks: {
			show: false,
		},
		labels: {
			show: false,
			formatter: function (val) {
				return val + "%";
			}
		}
	},
	title: {
		text: 'Monthly Inflation in Argentina, 2018',
		floating: true,
		offsetY: 320,
		align: 'center',
		style: {
			color: '#2e323c'
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
      bottom: 0,
      left: 0
    }, 
  },
	colors: ['#1273eb', '#2b86f5', '#63a9ff', '#95c5ff', '#c6e0ff'],
}
var chart = new ApexCharts(
	document.querySelector("#basic-column-graph-datalables"),
	options
);
chart.render();
