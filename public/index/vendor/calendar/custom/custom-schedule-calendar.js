document.addEventListener('DOMContentLoaded', function() {
	var calendarEl = document.getElementById('customScheduleCal');

	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {
			left: 'prev,next, today, dayGridMonth',
			center: '',
			right: 'title'
		},
		initialDate: '2021-02-12',
		navLinks: true, // can click day/week names to navigate views
		businessHours: true, // display business hours
		editable: true,
		selectable: true,
		events: [
			{
				title: '4',
				start: '2021-02-03',
			},
			{
				title: '7',
				start: '2021-02-04',
				color: '#ec4f3d'
			},
			{
				title: '5',
				start: '2021-02-05',
			},
			{
				title: '8',
				start: '2021-02-08',
				color: '#5dab18'
			},
			{
				title: '9',
				start: '2021-02-10',
			},
			{
				title: '4',
				start: '2021-02-12',
			},
			{
				title: '3',
				start: '2021-02-15',
			},
			{
				title: '7',
				start: '2021-02-16',
				color: '#ec4f3d'
			},			
			{
				title: '5',
				start: '2021-02-18',
			},
			{
				title: '8',
				start: '2021-02-21',
			},
			{
				title: '7',
				start: '2021-02-23',
				color: '#5dab18'
			},
			{
				title: '3',
				start: '2021-02-25',
				color: '#ec4f3d'
			},
			// red areas where no events can be dropped
			{
				start: '2021-02-26',
				end: '2021-02-28',
				overlap: false,
				display: 'background',
				color: '#ff9f89'
			},
		]
	});

	calendar.render();
});