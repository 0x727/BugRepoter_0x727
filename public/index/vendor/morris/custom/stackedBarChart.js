// Stacked Bar Chart
Morris.Bar({
	element: 'stackedBarChart',
	data: [
		{x: '2019 Q1', y: 3, z: 2, a: 3},
		{x: '2019 Q2', y: 2, z: null, a: 1},
		{x: '2019 Q3', y: 0, z: 2, a: 1},
		{x: '2019 Q4', y: 2, z: 3, a: 3},
		{x: '2018 Q1', y: 3, z: 2, a: 3},
		{x: '2018 Q2', y: 2, z: null, a: 1},
		{x: '2018 Q3', y: 0, z: 2, a: 4},
		{x: '2018 Q4', y: 2, z: 3, a: 3}
	],
	xkey: 'x',
	ykeys: ['y', 'z', 'a'],
	labels: ['Y', 'Z', 'A'],
	stacked: true,
	hideHover: "auto",
	resize: true,
	gridLineColor: "#e1e5f1",
	barColors:['#1273eb', '#2b86f5', '#63a9ff'],
});