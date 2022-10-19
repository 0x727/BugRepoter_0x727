var options = {
	chart: {
		height: 300,
		type: 'line',
		dropShadow: {
			enabled: true,
			opacity: 0.1,
			blur: 5,
			left: -10,
			top: 10
		},
		zoom: {
			enabled: false
		},
		dataLabels: {
			enabled: false
		},
		shadow: {
			enabled: true,
			color: '#2e323c',
			top: 18,
			left: 7,
			blur: 10,
			opacity: 1
		},
	},
	colors: ['#1273eb', '#2b86f5', '#63a9ff', '#95c5ff', '#c6e0ff'],
	stroke: {
		curve: 'smooth',
		width: 3,
	},
	series: [{
			name: "High - 2018",
			data: [28, 29, 33, 36, 32, 32, 33]
		},
		{
			name: "Low - 2017",
			data: [12, 11, 14, 18, 17, 13, 13]
		}
	],
	title: {
		text: 'Average High & Low Temperature',
		align: 'center'
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
	markers: {
		size: 6
	},
	xaxis: {
		categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
		title: {
			text: 'Month'
		}
	},
	yaxis: {
		title: {
			text: 'Temperature'
		},
		min: 5,
		max: 40
	},
	legend: {
		position: 'top',
		horizontalAlign: 'right',
		floating: true,
		offsetY: -25,
		offsetX: -5
	}
}

var chart = new ApexCharts(
	document.querySelector("#line-with-data-labels"),
	options
);

chart.render();






				