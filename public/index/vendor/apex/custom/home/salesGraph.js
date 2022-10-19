var options = {
	chart: {
		height: 280,
		type: 'area',
		toolbar: {
      show: false,
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
		name: 'Sales',
		data: [10, 40, 15, 40, 20, 35, 20, 10, 31, 43, 56, 29]
	}, {
		name: 'Revenue',
		data: [2, 8, 25, 7, 20, 20, 51, 35, 42, 20, 33, 67]
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
      bottom: 10,
      left: 0
    }, 
  },
	xaxis: {
		categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	},
	yaxis: {
		labels: {
			show: false,
		}
	},
	colors: ['#3e8ef1'],
	markers: {
		size: 0,
		opacity: 0.3,
		colors: ["#3e8ef1"],
		strokeColor: "#ffffff",
		strokeWidth: 2,
		hover: {
			size: 7,
		}
	},
}

var chart = new ApexCharts(
	document.querySelector("#salesGraph"),
	options
);

chart.render();
