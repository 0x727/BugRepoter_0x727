document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('dayGrid');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
      },
      initialDate: '2020-09-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2020-09-01',
          color: '#ec4f3d'
        },
        {
          title: 'Long Event',
          start: '2020-09-07',
          end: '2020-09-10'
        },
        {
          groupId: 999,
          title: 'Birthday',
          start: '2020-09-09T16:00:00',
          color: '#5dab18'
        },
        {
          groupId: 999,
          title: 'Birthday',
          start: '2020-09-16T16:00:00',
          color: '#ec4f3d'
        },
        {
          title: 'Conference',
          start: '2020-09-11',
          end: '2020-09-13'
        },
        {
          title: 'Meeting',
          start: '2020-09-12T10:30:00',
          end: '2020-09-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2020-09-12T12:00:00',
          color: '#5dab18'
        },
        {
          title: 'Meeting',
          start: '2020-09-12T14:30:00'
        },
        {
          title: 'Interview',
          start: '2020-09-12T17:30:00'
        },
        {
          title: 'Meeting',
          start: '2020-09-12T20:00:00'
        },
        {
          title: 'Birthday',
          start: '2020-09-13T07:00:00',
          color: '#5dab18'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2020-09-28',
          color: '#5dab18'
        }
      ]
    });

    calendar.render();
  });