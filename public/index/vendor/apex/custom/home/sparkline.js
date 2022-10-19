var options1 = {
	series: [{
		data: [10, 20, 30, 40, 30, 50, 70]
}],
	chart: {
		type: 'area',
		width: 65,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	colors: ['#1273eb'],
	stroke: {
		curve: 'smooth',
		width: 1,
	},
	tooltip: {
		fixed: {
			enabled: false
		},
		x: {
			show: false
		},	
		marker: {
			show: false
		}
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val
			}
		}
	},
};

var chart1 = new ApexCharts(document.querySelector("#sparklineLine1"), options1);
chart1.render();






var options2 = {
	series: [{
		data: [10, 20, 30, 40, 30, 40, 60]
}],
	chart: {
		type: 'area',
		width: 65,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	colors: ['#1273eb'],
	stroke: {
		curve: 'smooth',
		width: 1,
	},
	tooltip: {
		fixed: {
			enabled: false
		},
		x: {
			show: false
		},	
		marker: {
			show: false
		}
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val
			}
		}
	},
};

var chart2 = new ApexCharts(document.querySelector("#sparklineLine2"), options2);
chart2.render();







var options3 = {
	series: [{
		data: [10, 20, 30, 50, 40, 30, 60]
}],
	chart: {
		type: 'area',
		width: 65,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	colors: ['#f16a5d'],
	stroke: {
		curve: 'smooth',
		width: 1,
	},
	tooltip: {
		fixed: {
			enabled: false
		},
		x: {
			show: false
		},	
		marker: {
			show: false
		}
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val
			}
		}
	},
};

var chart3 = new ApexCharts(document.querySelector("#sparklineLine3"), options3);
chart3.render();