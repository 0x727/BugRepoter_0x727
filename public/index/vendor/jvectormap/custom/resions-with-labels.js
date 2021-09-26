// Resions with Labels
$(function(){
	new jvm.Map({
		map: 'us_aea',
		container: $('#resionsWithLabels'),
		zoomOnScroll: false,
		zoomMin: 1,
		hoverColor: true,
		regionLabelStyle: {
			initial: {
				fill: '#ffffff'
			},
			hover: {
				fill: '#333333'
			}
		},
		regionStyle:{
			initial: {
				fill: '#1273eb',
			},
			hover: {
				"fill-opacity": 0.8
			},
		},
		backgroundColor: 'transparent',
		labels: {
			regions: {
				render: function(code){
					var doNotShow = ['US-RI', 'US-DC', 'US-DE', 'US-MD'];

					if (doNotShow.indexOf(code) === -1) {
						return code.split('-')[1];
					}
				},
				offsets: function(code){
					return {
						'CA': [-10, 10],
						'ID': [0, 40],
						'OK': [25, 0],
						'LA': [-20, 0],
						'FL': [45, 0],
						'KY': [10, 5],
						'VA': [15, 5],
						'MI': [30, 30],
						'AK': [50, -25],
						'HI': [25, 50]
					}[code.split('-')[1]];
				}
			}
		}
	});
});