// USA map 1
$(function(){
	var cityAreaData = [
		230.20,
		750.90,
		440.28,
		180.15,
		69.35,
		280.90,
		510.50,
		99.60,
		135.50
	]
	$('#us-map2').vectorMap({
		map: 'us_aea_en',
		scaleColors: ['#1273eb'],
		normalizeFunction: 'polynomial',
		focusOn:{
			x: 2,
			y: 0,
			scale: 1
		},
		zoomOnScroll: false,
		zoomMin: 1,
		hoverColor: true,
		regionStyle:{
			initial: {
				fill: '#1273eb',
			},
			hover: {
				"fill-opacity": 0.8
			},
		},
		markerStyle: {
			initial: {
				fill: '#333333',
				stroke: '#FFFFFF',
				r: 5
			}
		},
		backgroundColor: 'transparent',
		 markers :[
			{latLng: [32.90, -97.03], name: 'Dallas/FW,TX'},
			{latLng: [34.11, -79.24], name: 'Marion S.C'},
			{latLng: [40.09, -74.51], name: 'Levittown, Pa'},
			{latLng: [32.33, -92.55], name: 'Arcadia, La'},
			{latLng: [35.53, -11.25], name: 'Cameron, Ariz'},
			{latLng: [39.46, -86.09], name: 'Indianapolis'},
			{latLng: [38.32, -82.41], name: 'Ironton, Ohio'},
			{latLng: [38.50, -104.49], name: 'Colorado Springs'},
			{latLng: [45.14, -120.11], name: 'Condon'},
			{latLng: [19.12, -155.29], name: 'Pahala'},
			{latLng: [64.44, -120.17], name: 'Los Alamos, Calif'},
			{latLng: [70.10, -105.06], name: 'Longmont'},
			{latLng: [57.05, -134.50], name: 'Baranof'},
			{latLng: [37.30, -119.30], name: 'California, CA'},
			{latLng: [36.10,-115.09], name: 'Las Vegas, Nev'},
			{latLng: [56.48, -132.58], name: 'Petersburg, Alaska'},
			{latLng: [29.35, -95.46 ], name: 'Richmond Tex'},
			{latLng: [31.02, -85.52], name: 'Geneva, Ala'},
			{latLng: [42.11, -73.30], name: 'Hillsdale, N.Y'},
			{latLng: [48.30, -122.14], name: 'Sedro Wooley'},
			{latLng: [32.46, -108.17], name: 'Silver City'},
			{latLng: [43.25, -74.22], name: 'Hamilton Mt.'},
			{latLng: [32.42, -108.08], name: 'Hurley, N. Mex'},
			{latLng: [35.22, -117.38], name: 'Johannesburg'},
			{latLng: [40.50, -79.38], name: 'Worthington Pa'},
			{latLng: [37.45, -119.40], name: 'Yosemite Nat. Park'},
			{latLng: [41.09, -81.22], name: 'Kent, Ohio'},
			{latLng: [40.0, -74.30], name: 'New Jersey'},
		],
		series: {
			markers: [{
				attribute: 'r',
				scale: [3, 7],
				values: cityAreaData
			}]
		},
	});
});