var options = {
	chart: {
		height: 300,
		type: 'candlestick',
		toolbar: {
			show: false,
		},
		dropShadow: {
			enabled: true,
			opacity: 0.1,
			blur: 5,
			left: -10,
			top: 10
		},
	},
	series: [{
		data: [{
				x: new Date(1538778600000),
				y: [6629.81, 6650.5, 6623.04, 6633.33]
			},
			{
				x: new Date(1538780400000),
				y: [6632.01, 6643.59, 6620, 6630.11]
			},
			{
				x: new Date(1538782200000),
				y: [6630.71, 6648.95, 6623.34, 6635.65]
			},
			{
				x: new Date(1538784000000),
				y: [6635.65, 6651, 6629.67, 6638.24]
			},
			{
				x: new Date(1538785800000),
				y: [6638.24, 6640, 6620, 6624.47]
			},
			{
				x: new Date(1538787600000),
				y: [6624.53, 6636.03, 6621.68, 6624.31]
			},
			{
				x: new Date(1538789400000),
				y: [6624.61, 6632.2, 6617, 6626.02]
			},
			{
				x: new Date(1538791200000),
				y: [6627, 6627.62, 6584.22, 6603.02]
			},
			{
				x: new Date(1538793000000),
				y: [6605, 6608.03, 6598.95, 6604.01]
			},
			{
				x: new Date(1538794800000),
				y: [6604.5, 6614.4, 6602.26, 6608.02]
			},
			{
				x: new Date(1538796600000),
				y: [6608.02, 6610.68, 6601.99, 6608.91]
			},
		]
	}],
	plotOptions: {
		candlestick: {
			colors: {
				upward: '#1273eb',
				downward: '#95c5ff'
			}
		}
	},
	xaxis: {
		type: 'datetime'
	},
	yaxis: {
		tooltip: {
			enabled: true
		}
	}
}

var chart = new ApexCharts(
	document.querySelector("#basic-candlestick-graph"),
	options
);

chart.render();