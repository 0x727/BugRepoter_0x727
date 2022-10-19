/*!
 * baguetteBox.js
 * @author  feimosi
 * @version 1.1.0
 * @url https://github.com/feimosi/baguetteBox.js
 */

var baguetteBox = (function() {
	// SVG shapes used within the buttons
	var leftArrow = '<svg width="44" height="60">' +
			'<polyline points="30 10 10 30 30 50" stroke="rgba(255,255,255,0.5)" stroke-width="2"' +
			  'stroke-linecap="butt" fill="none" stroke-linejoin="round"/>' +
			'</svg>',
		rightArrow = '<svg width="44" height="60">' +
			'<polyline points="14 10 34 30 14 50" stroke="rgba(255,255,255,0.5)" stroke-width="2"' +
			  'stroke-linecap="butt" fill="none" stroke-linejoin="round"/>' +
			'</svg>',
		closeX = '<svg width="30" height="30">' +
			'<g stroke="rgb(160, 160, 160)" stroke-width="2">' +
			'<line x1="5" y1="5" x2="16" y2="16"/>' +
			'<line x1="5" y1="16" x2="16" y2="5"/>' +
			'</g></svg>';
	// Global options and their defaults
	var options = {}, defaults = {
		captions: true,
		buttons: 'auto',
		async: false,
		preload: 2,
		animation: 'slideIn'
	};
	// Object containing information about features compatibility
	var supports = {};
	// DOM Elements references
	var overlay, slider, previousButton, nextButton, closeButton;
	// Current image index inside the slider and displayed gallery index
	var currentIndex = 0, currentGallery = -1;
	// Touch event start position (for slide gesture)
	var touchStartX;
	// If set to true ignore touch events because animation was already fired
	var touchFlag = false;
	// Regex pattern to match image files
	var regex = /.+\.(gif|jpe?g|png|webp)/i;
	// Array of all used galleries (DOM elements)
	var galleries = [];
	// 2D array of galleries and images inside them
	var imagesMap = [];
	// Array containing temporary images DOM elements
	var imagesElements = [];

	// forEach polyfill for IE8
	// http://stackoverflow.com/a/14827443/1077846
	if(![].forEach) {
		Array.prototype.forEach = function(callback, thisArg) {
			for(var i = 0; i < this.length; i++)
				callback.call(thisArg, this[i], i, this);
		};
	}

	// filter polyfill for IE8
	// https://gist.github.com/eliperelman/1031656
	if(![].filter) {
		Array.prototype.filter = function(a,b,c,d,e) {
			/*jshint -W030 */
			c=this;d=[];for(e=0;e<c.length;e++)a.call(b,c[e],e,c)&&d.push(c[e]);return d;
		};
	}

	// Script entry point
	function run(selector, userOptions) {
		// Fill supports object
		supports.transforms = testTransformsSupport();
		supports.svg = testSVGSupport();

		buildOverlay();

		// For each gallery bind a click event to every image inside it
		galleries = document.querySelectorAll(selector);
		[].forEach.call(
			galleries,
			function (galleryElement, galleryIndex) {
				// Filter 'a' elements from those not linking to images
				var tags = galleryElement.getElementsByTagName('a');
				tags = [].filter.call(tags, function(element) {
					return regex.test(element.href);
				});

				// Get all gallery images and save them in imagesMap with custom options
				var galleryID = imagesMap.length;
				imagesMap.push(tags);
				imagesMap[galleryID].options = userOptions;

				[].forEach.call(
					imagesMap[galleryID],
					function (imageElement, imageIndex) {
						bind(imageElement, 'click', function(event) {
							/*jshint -W030 */
							event.preventDefault ? event.preventDefault() : event.returnValue = false;
							prepareOverlay(galleryID);
							showOverlay(imageIndex);
						});
					}
				);
			}
		);
	}

	function buildOverlay() {
		overlay = getByID('baguetteBox-overlay');
		// Check if the overlay already exists
		if(overlay) {
			slider = getByID('baguetteBox-slider');
			previousButton = getByID('previous-button');
			nextButton = getByID('next-button');
			closeButton = getByID('close-button');
			return;
		}
		// Create overlay element
		overlay = create('div');
		overlay.id = 'baguetteBox-overlay';
		document.getElementsByTagName('body')[0].appendChild(overlay);
		// Create gallery slider element
		slider = create('div');
		slider.id = 'baguetteBox-slider';
		overlay.appendChild(slider);
		// Create all necessary buttons
		previousButton = create('button');
		previousButton.id = 'previous-button';
		previousButton.innerHTML = supports.svg ? leftArrow : '&lt;';
		overlay.appendChild(previousButton);

		nextButton = create('button');
		nextButton.id = 'next-button';
		nextButton.innerHTML = supports.svg ? rightArrow : '&gt;';
		overlay.appendChild(nextButton);

		closeButton = create('button');
		closeButton.id = 'close-button';
		closeButton.innerHTML = supports.svg ? closeX : 'X';
		overlay.appendChild(closeButton);

		previousButton.className = nextButton.className = closeButton.className = 'baguetteBox-button';

		bindEvents();
	}

	function bindEvents() {
		// When clicked on the overlay (outside displayed image) close it
		bind(overlay, 'click', function(event) {
			if(event.target && event.target.nodeName !== "IMG")
				hideOverlay();
		});
		// Add event listeners for buttons
		bind(previousButton, 'click', function(event) {
			/*jshint -W030 */
			event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
			showPreviousImage();
		});
		bind(nextButton, 'click', function(event) {
			/*jshint -W030 */
			event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
			showNextImage();
		});
		bind(closeButton, 'click', function(event) {
			/*jshint -W030 */
			event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
			hideOverlay();
		});
		// Add touch events
		bind(overlay, 'touchstart', function(event) {
			// Save x axis position
			touchStartX = event.changedTouches[0].pageX;
		});
		bind(overlay, 'touchmove', function(event) {
			// If action was already triggered return
			if(touchFlag)
				return;
			/*jshint -W030 */
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			touch = event.touches[0] || event.changedTouches[0];
			// Move at least 40 pixels to trigger the action
			if(touch.pageX - touchStartX > 40) {
				touchFlag = true;
				showPreviousImage();
			} else if (touch.pageX - touchStartX < -40) {
				touchFlag = true;
				showNextImage();
			}
		});
		bind(overlay, 'touchend', function(event) {
			touchFlag = false;
		});
		// Activate keyboard shortcuts
		bind(document, 'keydown', function(event) {
			switch(event.keyCode) {
				case 37: // Left arrow
					showPreviousImage();
					break;
				case 39: // Right arrow
					showNextImage();
					break;
				case 27: // Esc
					hideOverlay();
					break;
			}
		});
	}

	function prepareOverlay(galleryIndex) {
		// If the same gallery is being opened prevent from loading it once again
		if(currentGallery === galleryIndex)
			return;
		currentGallery = galleryIndex;
		// Update gallery specific options
		setOptions(imagesMap[galleryIndex].options);
		// Empty slider of previous contents (more effective than .innerHTML = "")
		while(slider.firstChild)
			slider.removeChild(slider.firstChild);
		imagesElements.length = 0;
		// Prepare and append images containers
		for(var i = 0, fullImage; i < imagesMap[galleryIndex].length; i++) {
			fullImage = create('div');
			fullImage.className = 'full-image';
			fullImage.id = 'baguette-img-' + i;
			imagesElements.push(fullImage);
			slider.appendChild(imagesElements[i]);
		}
	}

	function setOptions(newOptions) {
		if(!newOptions)
			newOptions = {};
		// Fill options object
		for(var item in defaults) {
			options[item] = defaults[item];
			if(typeof newOptions[item] !== 'undefined')
				options[item] = newOptions[item];
		}
		/* Apply new options */
		// Change transition for proper animation
		slider.style.transition = slider.style.webkitTransition = (options.animation === 'fadeIn' ? 'opacity .4s ease' : 
			options.animation === 'slideIn' ? '' : 'none');
		// Hide buttons if necessary
		if(options.buttons === 'auto' && ('ontouchstart' in window || imagesMap[currentGallery].length === 1))
			options.buttons = false;
		// Set buttons style to hide or display them
		previousButton.style.display = nextButton.style.display = (options.buttons ? '' : 'none');
	}

	function showOverlay(index) {
		// Return if overlay is already visible
		if(overlay.style.display === 'block')
			return;
		// Set current index to a new value and load proper image
		currentIndex = index;
		loadImage(currentIndex, function() {
			preloadNext(currentIndex);
			preloadPrev(currentIndex);
		});

		updateOffset();
		overlay.style.display = 'block';
		// Fade in overlay
		setTimeout(function() {
			overlay.className = 'visible';
		}, 50);
	}

	function hideOverlay() {
		// Return if overlay is already hidden
		if(overlay.style.display === 'none')
			return;
		// Fade out and hide the overlay
		overlay.className = '';
		setTimeout(function() {
			overlay.style.display = 'none';
		}, 500);
	}

	function loadImage(index, callback) {
		var imageContainer = imagesElements[index];
		// If index is invalid return
		if(typeof imageContainer === 'undefined')
			return;
		// If image is already loaded run callback and return
		if(imageContainer.getElementsByTagName('img')[0]) {
			if(callback)
				callback();
			return;
		}
		// Get element reference, optional caption and source path
		imageElement = imagesMap[currentGallery][index];
		imageCaption = imageElement.getAttribute('data-caption') || imageElement.title;
		imageSrc = getImageSrc(imageElement);
		// Prepare image container elements
		var figure = create('figure');
		var image = create('img');
		var figcaption = create('figcaption');
		imageContainer.appendChild(figure);
		// Add loader element
		figure.innerHTML = '<div class="spinner">' +
			'<div class="double-bounce1"></div>' +
			'<div class="double-bounce2"></div>' +
			'</div>';
		// Set callback function when image loads
		image.onload = function() {
			// Remove loader element
			var spinner = document.querySelector('#baguette-img-' + index + ' .spinner');
			figure.removeChild(spinner);
			if(!options.async && callback)
				callback();
		};
		image.setAttribute('src', imageSrc);
		figure.appendChild(image);
		// Insert caption if available
		if(options.captions && imageCaption) {
			figcaption.innerHTML = imageCaption;
			figure.appendChild(figcaption);
		}
		// Run callback
		if(options.async && callback)
			callback();
	}

	function getImageSrc(image) {
		// Set dafult image path from href
		var result = imageElement.href;
		// If dataset is supported find the most suitable image
		if(image.dataset) {
			var srcs = [];
			// Get all possible image versions depending on the resolution
			for(var item in image.dataset) {
				if(item.substring(0, 3) === 'at-' && !isNaN(item.substring(3)))
					srcs[item.replace('at-', '')] = image.dataset[item];
			}
			// Sort resolutions ascending
			keys = Object.keys(srcs).sort(function(a, b) {
				return parseInt(a) < parseInt(b) ? -1 : 1;
			});
			// Get real screen resolution
			var width = window.innerWidth * window.devicePixelRatio;
			// Find the first image bigger than or equal to the current width
			var i = 0;
			while(i < keys.length - 1 && keys[i] < width)
				i++;
			result = srcs[keys[i]] || result;
		}
		return result;
	}

	function showNextImage() {
		// Check if next image exists
		if(currentIndex <= imagesElements.length - 2) {
			currentIndex++;
			updateOffset();
			preloadNext(currentIndex);
		} else if(options.animation) {
			slider.className = 'bounce-from-right';
			setTimeout(function() {
				slider.className = '';
			}, 400);
		}
	}

	function showPreviousImage() {
		// Check if previous image exists
		if(currentIndex >= 1) {
			currentIndex--;
			updateOffset();
			preloadPrev(currentIndex);
		} else if(options.animation) {
			slider.className = 'bounce-from-left';
			setTimeout(function() {
				slider.className = '';
			}, 400);
		}
	}

	function updateOffset() {
		var offset = -currentIndex * 100 + '%';
		if(options.animation === 'fadeIn') {
			slider.style.opacity = 0;
			setTimeout(function() {
				/*jshint -W030 */
				supports.transforms ?
					slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
					: slider.style.left = offset;
				slider.style.opacity = 1;
			}, 400);
		} else {
			/*jshint -W030 */
			supports.transforms ?
				slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
				: slider.style.left = offset;
		}
	}

	// CSS 3D Transforms test
	function testTransformsSupport() {
		var div = create('div');
		return typeof div.style.perspective !== 'undefined' || typeof div.style.webkitPerspective !== 'undefined';
	}

	// Inline SVG test
	function testSVGSupport() {
		var div = create('div');
		div.innerHTML = '<svg/>';
		return (div.firstChild && div.firstChild.namespaceURI) == 'http://www.w3.org/2000/svg';
	}

	function preloadNext(index) {
		if(index - currentIndex >= options.preload)
			return;
		loadImage(index + 1, function() { preloadNext(index + 1); });
	}

	function preloadPrev(index) {
		if(currentIndex - index >= options.preload)
			return;
		loadImage(index - 1, function() { preloadPrev(index - 1); });
	}

	function bind(element, event, callback) {
		if(element.addEventListener)
			element.addEventListener(event, callback, false);
		else // IE8 fallback
			element.attachEvent('on' + event, callback);
	}

	function getByID(id) {
		return document.getElementById(id);
	}

	function create(element) {
		return document.createElement(element);
	}

	return {
		run: run
	};

})();
