var options = {
	chart: {
		width: 400,
		type: 'donut',
	},
	labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
	series: [20, 20, 20, 20, 20],
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 200
			},
			legend: {
				position: 'bottom'
			}
		}
	}],
	stroke: {
		width: 0,
	},
	colors: ['#1273eb', '#2b86f5', '#63a9ff', '#95c5ff', '#c6e0ff'],
}
var chart = new ApexCharts(
	document.querySelector("#basic-donut-graph"),
	options
);
chart.render();