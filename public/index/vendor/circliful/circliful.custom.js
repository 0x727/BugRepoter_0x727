$( document ).ready(function() {

	$("#todaysTarget").circliful({
		animation: 1,
		animationStep: 5,
		foregroundBorderWidth: 15,
		backgroundBorderWidth: 15,
		percent: 70,
		textStyle: 'font-size: 12px;',
		fontColor: '#111111',
		foregroundColor: '#1273eb',
		backgroundColor: '#f2f4f9',
	});


	$("#weeklyEarnings").circliful({
		animation: 1,
		animationStep: 5,
		foregroundBorderWidth: 12,
		backgroundBorderWidth: 10,
		percent: 80,
		textStyle: 'font-size: 12px;',
		fontColor: '#1273eb',
		foregroundColor: '#1273eb',
		backgroundColor: '#efeff7',
		multiPercentage: 1,
		percentages: [10, 20, 30],
	});

	$("#monthlyEarnings").circliful({
		animation: 1,
		animationStep: 5,
		foregroundBorderWidth: 12,
		backgroundBorderWidth: 10,
		percent: 70,
		fontColor: '#ef503f',
		foregroundColor: '#ef503f',
		backgroundColor: '#efeff7',
		multiPercentage: 1,
		percentages: [10, 20, 30]
	});


	// With Icon
	$("#withIcon").circliful({
		animationStep: 5,
		foregroundBorderWidth: 12,
		backgroundBorderWidth: 7,
		percent: 3,
		fontColor: '#000000',
		foregroundColor: '#8796af',
		backgroundColor: 'rgba(0, 0, 0, 0.1)',
		icon: '\ea71',
		iconColor: '#8796af',
		iconPosition: 'middle',
		textBelow: true,
		animation: 1,
		animationStep: 1,
		start: 2,
		showPercent: 1,
	});

});

