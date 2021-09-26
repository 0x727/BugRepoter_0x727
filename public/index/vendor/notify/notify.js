(function($){

	/**
	 * Notify
	 *
	 * Create the class and pass it the settings and the parent scope
	 *
	 * @param settings object Contains the global options for the notifications container
	 */
	$.fn.notify = function( settings ) {
		return new notify( this, settings );
	}

	/**
	 * Show
	 *
	 * Create a new notification
	 *
	 * @param message string Contains the message for the notification
	 * @param options object Contains the settings for the notification
	 */
	notify.prototype.show = function( message, options ) {

		// Settings specific for the notification
		// speed, delay, easing, effect and removeIcon are inherited by the global settings
		var opts = $.extend({
			id: 'note-' + this.notes++,
			type: false,
			title: false,
			message: message,
			sticky: false,
			speed: this.sets.speed,
			delay: this.sets.delay,
			easing: this.sets.easing,
			effect: this.sets.effect,
			icon: false,
			removeIcon: this.sets.removeIcon,
		}, options);

		// New notification is created
		note = $('<div>')
			.addClass('note')
			.addClass( opts.type ? 'note-' + opts.type : opts.type )
			.addClass( opts.id );

		// Icon is attached to the new notification if one is specified
		if ( opts.icon )
			note.append( $('<span>')
				.addClass('image')
				.html( $( opts.icon ) ) );

		// Remove button for the notification is added
		note.append( $('<button>').attr('type', 'button').addClass('remove').html( $( opts.removeIcon ) ) );

		// New content container is created for the notification
		content = $('<div>').addClass('content');

		// Add a notification title if one is specified
		if ( opts.title )
			content.append( $('<strong>')
				.addClass('title')
				.html( opts.title ) );

		// Notification message is added to the content container
		content.append( opts.message  );
		// Content container is added to the notification
		note.append( content );

		// Notification is added to the notification parent container
		this.element.prepend( note );

		// Ignore the close timer if the notification is a sticky
		if ( !opts.sticky ) {

			// Create a new close timer for the notification
			var noteTimer = new closeTimer( this.element.find('.' + opts.id), opts );

			// Pause the close timer if the mouse is over the notification
			this.element.on('mouseover', '.' + opts.id, function() {
				noteTimer.pause();
			});

			// Resume the close timer from the paused position when the mouse is moved away from the notification
			this.element.on('mouseout', '.' + opts.id, function() {
				noteTimer.resume();
			});

		}

		// Remove the notification if the remove button has been clicked
		this.element.on('click', '.remove', function() {

			$(this).closest('.note').animate({
				'opacity': 0
			}, opts.speed, opts.easing).slideUp( opts.speed, function() {
				$(this).remove();
			});

		});

	}

	/**
	 * Close
	 *
	 * Closes the notification using the option effect
	 *
	 * @param element object Contains the element that needs to be closed & removed
	 * @param opts    object Contains all the options associated with the particular notification
	 */
	notify.prototype.close = function ( element, opts ) {

		// Determine which effect was specified and use run the appropriate close code
		switch ( opts.effect ) {
			case 'fade':

				element.animate({
					'opacity': 0
				}, opts.speed, opts.easing).slideUp( opts.speed, function() {
					$(this).remove();
				});

				break;

			case 'slide':

				element.slideToggle( opts.speed, function() {
					$(this).remove();
				});

				break;

			default:

				element.toggle( opts.speed, function() {
					$(this).remove();
				});

				break;
		}

	}

	/**
	 * Notify
	 *
	 * Takes in the main scope and settings to generate a new notification class
	 *
	 * @param element  object Contains the main scope or the parent notifications container
	 * @param settings object Contains the global settings for the notifications
	 */
	function notify ( element, settings ) {

		this.sets = $.extend({
			type: 'notes',
			speed: 500,
			delay: 43000,
			easing: 'easeInBounce',
			effect: 'fade',
			removeIcon: '<span>x</span>',
		}, settings);

		// Sets the element to be available globally
		this.element = element;
		// Adds an identifying 'notify' class to the main container
		this.element.addClass('notify');
		// Adds the type of the notification i.e. notes, messages, etc.
		this.element.addClass('notify-' + this.sets.type);
		// Creates a notification notes counter so each notification can be identified
		this.notes = 0;

	}

	/**
	 * Close Timer
	 *
	 * Takes in an element and its options to create a timeout function to close the notification
	 *
	 * @param element object Contains the element that needs to be closed
	 * @param opts    object Contains all the options associated with the particular notification
	 */
	function closeTimer( element, opts ) {

		// Creates a unique id, start and remaining time using the option's delay var
		var timerId, start, remaining = opts.delay;

		// Pause function that clears the timeout and determines what the remaining time before the close is
		this.pause = function() {
			window.clearTimeout( timerId );
			remaining -= new Date() - start;
		};

		// Resume function starts the timeout function which will fire the close prototype function
		this.resume = function() {
			start = new Date();
			timerId = window.setTimeout( function() {
				notify.prototype.close( element, opts );
			}, remaining );
		};

		// 3, 2, 1 ... START!
		this.resume();

	}

})(jQuery);