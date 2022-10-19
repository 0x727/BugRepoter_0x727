$(document).ready(function() {

	var citynames = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: {
			url: 'https://raw.githubusercontent.com/bootstrap-tagsinput/bootstrap-tagsinput/master/examples/assets/citynames.json',
			filter: function(list) {
				return $.map(list, function(cityname) {
					return { name: cityname }; });
				}
			}
	});

	citynames.initialize();
	
	$('#typeahead').tagsinput({
		typeaheadjs: {
			name: 'citynames',
			displayKey: 'name',
			valueKey: 'name',
			source: citynames.ttAdapter()
		}
	});

});
