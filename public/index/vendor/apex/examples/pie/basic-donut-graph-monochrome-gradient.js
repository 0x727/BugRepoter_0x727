var options = {
	chart: {
		width: 400,
		type: 'donut',
	},
	series: [25, 15, 44, 55, 41, 17],
	labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	fill: {
		type: 'gradient',
	},
	theme: {
		monochrome: {
			enabled: true,
			color: '#1273eb',
		}
	},
	title: {
		text: "Weekly Sales",
	},
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
}
var chart = new ApexCharts(
	document.querySelector("#basic-donut-graph-monochrome-gradient"),
	options
);
chart.render();


