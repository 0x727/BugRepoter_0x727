// Morris Days
var day_data = [
	{"period": "2016-10-01", "licensed": 3213, "Le Rouge": 887},
	{"period": "2016-09-30", "licensed": 3321, "Le Rouge": 776},
	{"period": "2016-09-29", "licensed": 3671, "Le Rouge": 884},
	{"period": "2016-09-20", "licensed": 3176, "Le Rouge": 448},
	{"period": "2016-09-19", "licensed": 3376, "Le Rouge": 565},
	{"period": "2016-09-18", "licensed": 3976, "Le Rouge": 627},
	{"period": "2016-09-17", "licensed": 2239, "Le Rouge": 660},
	{"period": "2016-09-16", "licensed": 3871, "Le Rouge": 676},
	{"period": "2016-09-15", "licensed": 3659, "Le Rouge": 656},
	{"period": "2016-09-10", "licensed": 3380, "Le Rouge": 663}
];
Morris.Line({
	element: 'dayData',
	data: day_data,
	xkey: 'period',
	ykeys: ['licensed', 'Le Rouge'],
	labels: ['Licensed', 'Le Rouge'],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#e1e5f1",
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['#ee0000'],
	lineColors:['#1273eb', '#2b86f5', '#63a9ff'],
});