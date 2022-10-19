var options = {
	chart: {
		height: 500,
		type: 'bar',
		stacked: true,
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
	colors: ['#1273eb', '#63a9ff'],
	plotOptions: {
		bar: {
			horizontal: true,
			barHeight: '80%',

		},
	},
	dataLabels: {
		enabled: false
	},
	stroke: {
		width: 1,
		colors: ["#fff"]
	},
	series: [{
			name: 'Male',
			data: [0.4, 0.65, 0.76, 0.88, 1.5, 2.1, 2.9, 3.8, 3.9, 4.2, 4, 4.3, 4.1, 4.2, 4.5, 3.9, 3.5, 3]
		},
		{
			name: 'Female',
			data: [-0.8, -1.05, -1.06, -1.18, -1.4, -2.2, -2.85, -3.7, -3.96, -4.22, -4.3, -4.4, -4.1, -4, -4.1, -3.4, -3.1, -2.8]
		}
	],
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
	yaxis: {
		min: -5,
		max: 5,
		title: {
			// text: 'Age',
		},
	},
	tooltip: {
		shared: false,
		x: {
			formatter: function(val) {
				return val
			}
		},
		y: {
			formatter: function(val) {
				return Math.abs(val) + "%"
			}
		}
	},
	title: {
		text: 'World population by Age & Gender',
		align: 'center',
	},
	xaxis: {
		categories: ['85+', '80-84', '75-79', '70-74', '65-69', '60-64', '55-59', '50-54', '45-49', '40-44', '35-39', '30-34', '25-29', '20-24', '15-19', '10-14', '5-9', '0-4'],
		title: {
			text: 'Percent'
		},
		labels: {
			formatter: function(val) {
				return Math.abs(Math.round(val)) + "%"
			}
		}
	},
}

var chart = new ApexCharts(
	document.querySelector("#basic-bar-negative-values"),
	options
);

chart.render();