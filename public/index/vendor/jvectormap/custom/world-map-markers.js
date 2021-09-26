// Markers on the world map
$(function(){
	$('#world-map-markers').vectorMap({
		map: 'world_mill_en',
		normalizeFunction: 'polynomial',
		hoverOpacity: 0.7,
		hoverColor: false,
		zoomOnScroll: false,
		markerStyle: {
			initial: {
				fill: '#333333',
				stroke: '#FFFFFF',
				r: 6
			}
		},
		zoomMin: 1,
		hoverColor: true,
		series: {
			regions: [{
				values: gdpData,
				scale: ['#1273eb'],
				normalizeFunction: 'polynomial'
			}]
		},
		backgroundColor: 'transparent',
		markers: [
			{latLng: [41.90, 12.45], name: 'Vatican City'},
			{latLng: [43.73, 7.41], name: 'Monaco'},
			{latLng: [-0.52, 166.93], name: 'Nauru'},
			{latLng: [-8.51, 179.21], name: 'Tuvalu'},
			{latLng: [43.93, 12.46], name: 'San Marino'},
			{latLng: [47.14, 9.52], name: 'Liechtenstein'},
			{latLng: [7.11, 171.06], name: 'Marshall Islands'},
			{latLng: [37.30, -119.30], name: 'California, CA'},
			{latLng: [56.48, -132.58], name: 'Petersburg, Alaska'},
			{latLng: [28.61, 77.20], name: 'New Delhi'},
			{latLng: [57.9, 2.9], name: 'Aberdeen'},
			{latLng: [36.52, 174.45], name: 'Auckland'},
			{latLng: [39.55, 116.25], name: 'Beijing'},
			{latLng: [12.56, 38.27], name: 'Salvador'},
			{latLng: [33.55, 18.22], name: 'Cape Town'},
			{latLng: [61.52, 105.31], name: 'Moscow'},
			{latLng: [31.57, 115.52], name: 'Perth'},
			{latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis'},
			{latLng: [3.2, 73.22], name: 'Maldives'},
			{latLng: [35.88, 14.5], name: 'Malta'},
			{latLng: [-4.61, 55.45], name: 'Seychelles'},
			{latLng: [42.5, 1.51], name: 'Andorra'},
			{latLng: [-21.13, -175.2], name: 'Tonga'},
			{latLng: [26.02, 50.55], name: 'Bahrain'},
		]
	});
});