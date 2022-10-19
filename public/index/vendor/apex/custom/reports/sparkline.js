var options1 = {
	series: [{
		data: [5, 10, 20, 15, 25, 35, 25]
	}],
		chart: {
		type: 'bar',
		width: 60,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	plotOptions: {
		bar: {
			columnWidth: '70%'
		}
	},
	xaxis: {
		crosshairs: {
			width: 1
		},
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
	colors: ['#1273eb'],
	xaxis: {
		categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val + ' Sales'
			}
		}
	},
};

var chart1 = new ApexCharts(document.querySelector("#sparklineBar1"), options1);
chart1.render();













var options2 = {
	series: [{
		data: [5, 10, 20, 15, 25, 35, 25]
	}],
		chart: {
		type: 'bar',
		width: 60,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	plotOptions: {
		bar: {
			columnWidth: '70%'
		}
	},
	xaxis: {
		crosshairs: {
			width: 1
		},
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
	colors: ['#1273eb'],
	xaxis: {
		categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val + ' Sales'
			}
		}
	},
};

var chart2 = new ApexCharts(document.querySelector("#sparklineBar2"), options2);
chart2.render();















var options3 = {
	series: [{
		data: [5, 10, 20, 15, 25, 35, 25]
	}],
		chart: {
		type: 'bar',
		width: 60,
		height: 30,
		sparkline: {
			enabled: true
		}
	},
	plotOptions: {
		bar: {
			columnWidth: '70%'
		}
	},
	xaxis: {
		crosshairs: {
			width: 1
		},
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
	colors: ['#ef503f'],
	xaxis: {
		categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val + ' Sales'
			}
		}
	},
};

var chart3 = new ApexCharts(document.querySelector("#sparklineBar3"), options3);
chart3.render();









var options4 = {
	series: [{
	data: [10, 15, 5, 45, 75, 60, 20, 40]
}],
	chart: {
		type: 'line',
		width: 100,
		height: 35,
		sparkline: {
			enabled: true
		}
	},
	stroke: {
		curve: 'smooth',
		width: 4,
	},
	colors: ['#1273eb'],
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

var chart4 = new ApexCharts(document.querySelector("#sparklineLine1"), options4);
chart4.render();






var options5 = {
	series: [{
	data: [10, 15, 5, 45, 75, 60, 20, 40]
}],
	chart: {
		type: 'line',
		width: 100,
		height: 35,
		sparkline: {
			enabled: true
		}
	},
	stroke: {
		curve: 'smooth',
		width: 4,
	},
	colors: ['#ef503f'],
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

var chart5 = new ApexCharts(document.querySelector("#sparklineLine2"), options5);
chart5.render();







var options6 = {
	series: [{
		data: [200, 400, 600, 700, 1000, 800, 500]
	}],
		chart: {
		type: 'line',
		height: 110,
		sparkline: {
			enabled: true
		}
	},
	stroke: {
		curve: 'smooth',
		width: 6,
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
	colors: ['#ffc331'],
	xaxis: {
		categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	},
	tooltip: {
		y: {
			formatter: function(val) {
				return val + ' Sales'
			}
		}
	},
};

var chart6 = new ApexCharts(document.querySelector("#sparklineLine3"), options6);
chart6.render();
