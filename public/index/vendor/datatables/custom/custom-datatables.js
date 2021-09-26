// Basic DataTable
$(function(){
	$('#basicExample').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
			"info": "Showing Page _PAGE_ of _PAGES_",
		}
	});
});



// Print/Copy/CSV
$(function(){
	$('#copy-print-csv').DataTable( {
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5',
			'print'
		],
	});
});



// Print/Copy/CSV
$(function(){
	$('#copy-print-scroll').DataTable( {
		"scrollY": "calc(100vh - 310px)",
		"scrollCollapse": true,
		"paging": false,
		"bInfo" : false,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5',
			'print'
		],
	});
});


// Fixed Header
$(document).ready(function(){
	var table = $('#fixedHeader').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		fixedHeader: true,
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
			"info": "Showing Page _PAGE_ of _PAGES_",
		}
	});
});


// Vertical Scroll
$(function(){
	$('#scrollVertical').DataTable({
		"scrollY": "207px",
		"scrollCollapse": true,
		"paging": false,
		"bInfo" : false,
	});
});



// Row Selection
$(function(){
	$('#rowSelection').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
			"info": "Showing Page _PAGE_ of _PAGES_",
		}
	});
	var table = $('#rowSelection').DataTable();

	$('#rowSelection tbody').on( 'click', 'tr', function () {
		$(this).toggleClass('selected');
	});

	$('#button').on('click', function () {
		alert( table.rows('.selected').data().length +' row(s) selected' );
	});
});



// Highlighting rows and columns
$(function(){
	$('#highlightRowColumn').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
		}
	});
	var table = $('#highlightRowColumn').DataTable();  
	$('#highlightRowColumn tbody').on('mouseenter', 'td', function (){
		var colIdx = table.cell(this).index().column;
		$(table.cells().nodes()).removeClass('highlight');
		$(table.column(colIdx).nodes()).addClass('highlight');
	});
});



// Using API in callbacks
$(function(){
	$('#apiCallbacks').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
		},
		"initComplete": function(){
			var api = this.api();
			api.$('td').on('click', function(){
			api.search(this.innerHTML).draw();
		});
		}
	});
});


// Hiding Search and Show entries
$(function(){
	$('#hideSearchExample').DataTable({
		"lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]],
		"searching": false,
		"language": {
			"lengthMenu": "Display _MENU_ Records Per Page",
			"info": "Showing Page _PAGE_ of _PAGES_",
		}
	});
});




// Orders
$(function(){
	$('#orders').DataTable( {
		"lengthMenu": [[3, 6, 10, 20], [3, 6, 10, 20, "All"]],
		"searching": false,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
	});
});
