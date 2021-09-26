// Morris Bar No Axis
Morris.Bar({
	element: 'barNoAxis',
	axes: false,
	data: [
		{x: '2017 Q1', y: 3, z: 2},
		{x: '2017 Q2', y: 2, z: 1},
		{x: '2017 Q3', y: 5, z: 2},
		{x: '2017 Q4', y: 2, z: 4},
		{x: '2016 Q1', y: 3, z: 2},
		{x: '2016 Q2', y: 2, z: 1},
		{x: '2016 Q3', y: 5, z: 2},
		{x: '2016 Q4', y: 2, z: 4},
	],
	xkey: 'x',
	ykeys: ['y', 'z'],
	labels: ['Y', 'Z'],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#e1e5f1",
	barColors:['#1273eb', '#2b86f5', '#63a9ff'],
});