$(function() {
	$("#loading-wrapper").fadeOut(1000);
});


$("#toggle-sidebar").on('click', function () {
	$(".page-wrapper").toggleClass("toggled");
});


$(function() {
	$(".graph-day-selection .btn").on('click', function () {
		$(".graph-day-selection .btn").removeClass("active");
		$(this).addClass("active");   
	});
});

$(function() {
	var interval = setInterval(function() {
		var momentNow = moment();
		$('#todays-date').html(momentNow.format('DD MMMM YYYY'));
	}, 100);
});


$('.todo-body').on('click', 'li.todo-list', function() {
	$(this).toggleClass('done');
});

$(".users-container .users-list li").on('click', function () {
	$(".empty-chat-screen").addClass("d-none");
	$(".chat-content-wrapper").removeClass("d-none");
	$(".users-container .users-list li").removeClass("active-chat");
	$(this).addClass("active-chat");
});

(function($) {
	var checkList = $('.task-checkbox'),
	toDoCheck = checkList.children('input[type="checkbox"]');
	toDoCheck.each(function(index, element) {
		var $this = $(element),
		taskItem = $this.closest('.task-block');
		$this.on('click', function(e) {
			taskItem.toggleClass('task-checked');
		});
	});
})(jQuery);


$(function() {
	$(".task-actions a.important").on('click', function () {
		$(this).toggleClass("active");
	});
});
$(function() {
	$(".task-actions a.star").on('click', function () {
		$(this).toggleClass("active");
	});
});
$(function() {
	$(".task-action-items a.mark-done-item").on('click', function () {
		$( event.target ).closest( ".task-block" ).toggleClass( "task-checked" );
	});
});
$(function() {
	$(".task-action-items a.delete-task-item").on('click', function () {
		$( event.target ).closest( ".task-block" ).remove();
	});
});





jQuery(function ($) {

	$(".default-sidebar-dropdown > a").on('click', function () {
		$(".default-sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".default-sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".default-sidebar-dropdown").removeClass("active");
			$(this).next(".default-sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});


	$(".compact-sidebar-dropdown > a").on('click', function () {
		$(".compact-sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".compact-sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".compact-sidebar-dropdown").removeClass("active");
			$(this).next(".compact-sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});


	$(function() {
		$(".slim-sidebar");
		$(".default-sidebar-wrapper").on('hover', function () {
				console.log("mouseenter");
				$(".slim-sidebar").addClass("sidebar-hovered");
			},
			function () {
				console.log("mouseout");
				$(".slim-sidebar").removeClass("sidebar-hovered");
			}
		)
	});
	$(function(){
		$(window).resize(function(){
			if ($(window).width() <= 768){
				$(".page-wrapper").removeClass("pinned");
			}
		});
		$(window).resize(function(){
			if ($(window).width() >= 768){
				$(".page-wrapper").removeClass("toggled");
			}
		});
	});
});
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
})

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	return new bootstrap.Popover(popoverTriggerEl)
})
function watermark(settings) {
	$(".mask_div").remove();
    var defaultSettings = {
        watermark_txt: "text",
        watermark_x: 20,
        watermark_y: 50,
        watermark_rows: 7,
        watermark_cols: 20,
        watermark_x_space: 100,
        watermark_y_space: 50,
        watermark_color: '#aaa',
        watermark_alpha: 0.4,
        watermark_fontsize: '15px',
        watermark_font: '微软雅黑',
        watermark_width: 210,
        watermark_height: 80,
        watermark_angle: 30
    };
    if (arguments.length === 1 && typeof arguments[0] === "object") {
        var src = arguments[0] || {};
        for (key in src) {
            if (src[key] && defaultSettings[key] && src[key] === defaultSettings[key]) continue;
            else if (src[key]) defaultSettings[key] = src[key];
        }
    }
    var oTemp = document.createDocumentFragment();
    var page_width = Math.max(document.body.scrollWidth, document.body.clientWidth);
    var cutWidth = page_width * 0.0150;
    var page_width = page_width - cutWidth;
    var page_height = Math.max(document.body.scrollHeight, document.body.clientHeight) + 450;
    page_height = Math.max(page_height, window.innerHeight - 30);
    if (defaultSettings.watermark_cols == 0 || (parseInt(defaultSettings.watermark_x + defaultSettings.watermark_width * defaultSettings.watermark_cols + defaultSettings.watermark_x_space * (defaultSettings.watermark_cols - 1)) > page_width)) {
        defaultSettings.watermark_cols = parseInt((page_width - defaultSettings.watermark_x + defaultSettings.watermark_x_space) / (defaultSettings.watermark_width + defaultSettings.watermark_x_space));
        defaultSettings.watermark_x_space = parseInt((page_width - defaultSettings.watermark_x - defaultSettings.watermark_width * defaultSettings.watermark_cols) / (defaultSettings.watermark_cols - 1));
    }
    if (defaultSettings.watermark_rows == 0 || (parseInt(defaultSettings.watermark_y + defaultSettings.watermark_height * defaultSettings.watermark_rows + defaultSettings.watermark_y_space * (defaultSettings.watermark_rows - 1)) > page_height)) {
        defaultSettings.watermark_rows = parseInt((defaultSettings.watermark_y_space + page_height - defaultSettings.watermark_y) / (defaultSettings.watermark_height + defaultSettings.watermark_y_space));
        defaultSettings.watermark_y_space = parseInt(((page_height - defaultSettings.watermark_y) - defaultSettings.watermark_height * defaultSettings.watermark_rows) / (defaultSettings.watermark_rows - 1));
    }
    var x;
    var y;
    for (var i = 0; i < defaultSettings.watermark_rows; i++) {
        y = defaultSettings.watermark_y + (defaultSettings.watermark_y_space + defaultSettings.watermark_height) * i;
        for (var j = 0; j < defaultSettings.watermark_cols; j++) {
            x = defaultSettings.watermark_x + (defaultSettings.watermark_width + defaultSettings.watermark_x_space) * j;
            var mask_div = document.createElement('div');
            mask_div.id = 'mask_div' + i + j;
            mask_div.className = 'mask_div';
            mask_div.appendChild(document.createTextNode(defaultSettings.watermark_txt));
            mask_div.style.webkitTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.MozTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.msTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.OTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.transform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
            mask_div.style.visibility = "";
            mask_div.style.position = "absolute";
            mask_div.style.left = x + 'px';
            mask_div.style.top = y + 'px';
            mask_div.style.overflow = "hidden";
            mask_div.style.zIndex = "9999";
            mask_div.style.pointerEvents = 'none';
            mask_div.style.opacity = defaultSettings.watermark_alpha;
            mask_div.style.fontSize = defaultSettings.watermark_fontsize;
            mask_div.style.fontFamily = defaultSettings.watermark_font;
            mask_div.style.color = defaultSettings.watermark_color;
            mask_div.style.textAlign = "center";
            mask_div.style.width = defaultSettings.watermark_width + 'px';
            mask_div.style.height = defaultSettings.watermark_height + 'px';
            mask_div.style.display = "block";
            oTemp.appendChild(mask_div);
        };
    };
    document.body.appendChild(oTemp);
}
document.onkeydown = document.onkeyup = document.onkeypress = function(event) {
    var e = event || window.event || arguments.callee.caller.arguments[0];
    if (e && e.keyCode == 123) {
            e.returnValue = false;
            return (false);
    }
}

window.oncontextmenu = function() {
	event.preventDefault();
	return false;
}
$(document).ready(function() {if(watermark_username){watermark({"watermark_txt": watermark_username});window.setInterval(function() {if ($(".mask_div").length <= 0) {watermark({"watermark_txt": watermark_username})} else if ($(".mask_div").eq(0).text() != watermark_username) {watermark({"watermark_txt": watermark_username})}}, 1e3)}});