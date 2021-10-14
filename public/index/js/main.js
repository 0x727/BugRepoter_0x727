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

function fIsUrL(sUrl) {
    var sRegex = '^((https|http)?://)' + '?(([0-9a-z_!~*\'().&=+$%-]+: )?[0-9a-z_!~*\'().&=+$%-]+@)?' //ftp的user@ 
        + '(([0-9]{1,3}.){3}[0-9]{1,3}' // IP形式的URL- 199.194.52.184 
        + '|' // 允许IP和DOMAIN（域名） 
        + '([0-9a-z_!~*\'()-]+.)*' // 域名- www. 
        + '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z].' // 二级域名 
        + '[a-z]{2,6})' // first level domain- .com or .museum 
        + '(:[0-9]{1,4})?' // 端口- :80 
        + '((/?)|' // a slash isn't required if there is no file name 
        + '(/[0-9a-z_!~*\'().;?:@&=+$,%#-]+)+/?)$';
    var re = new RegExp(sRegex);
    //re.test() 
    if (re.test(sUrl)) {
        return true;
    }
    return false;
}

(function (root,factory) {
  if (typeof define === 'function' && define.amd) {
    define([], factory());
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root['watermark'] = factory();
  }
}(this, function () {
  var watermark = {};
  var defaultSettings={
    watermark_id: 'wm_div_id',
    watermark_prefix: 'mask_div_id',
    watermark_txt:"",
    watermark_x:20,
    watermark_y:20,
    watermark_rows:0,                  
    watermark_cols:0,                  
    watermark_x_space:50,              
    watermark_y_space:50,              
    watermark_font:'微软雅黑',         
    watermark_color:'black',           
    watermark_fontsize:'18px',         
    watermark_alpha:0.15,              
    watermark_width:200,               
    watermark_height:100,              
    watermark_angle:15,                
    watermark_parent_width:0,   
    watermark_parent_height:0,  
    watermark_parent_node:null, 
    monitor:true,               
  };

  var settingsToDefaultSetting = function (settings) {
    defaultSettings.watermark_id = settings.watermark_id || defaultSettings.watermark_id;
    defaultSettings.watermark_prefix = settings.watermark_prefix || defaultSettings.watermark_prefix;
    defaultSettings.watermark_txt = settings.watermark_txt || defaultSettings.watermark_txt;
    defaultSettings.watermark_x = settings.watermark_x || defaultSettings.watermark_x;
    defaultSettings.watermark_y = settings.watermark_y || defaultSettings.watermark_y;
    defaultSettings.watermark_rows = settings.watermark_rows || defaultSettings.watermark_rows;
    defaultSettings.watermark_cols = settings.watermark_cols || defaultSettings.watermark_cols;
    defaultSettings.watermark_x_space = settings.watermark_x_space || defaultSettings.watermark_x_space;
    defaultSettings.watermark_y_space = settings.watermark_y_space || defaultSettings.watermark_y_space;
    defaultSettings.watermark_font = settings.watermark_font || defaultSettings.watermark_font;
    defaultSettings.watermark_color = settings.watermark_color || defaultSettings.watermark_color;
    defaultSettings.watermark_fontsize = settings.watermark_fontsize || defaultSettings.watermark_fontsize;
    defaultSettings.watermark_alpha = settings.watermark_alpha || defaultSettings.watermark_alpha;
    defaultSettings.watermark_width = settings.watermark_width || defaultSettings.watermark_width;
    defaultSettings.watermark_height = settings.watermark_height || defaultSettings.watermark_height;
    defaultSettings.watermark_angle = settings.watermark_angle || defaultSettings.watermark_angle;
    defaultSettings.watermark_parent_width = settings.watermark_parent_width || defaultSettings.watermark_parent_width;
    defaultSettings.watermark_parent_height = settings.watermark_parent_height || defaultSettings.watermark_parent_height;
    defaultSettings.watermark_parent_node = settings.watermark_parent_node || defaultSettings.watermark_parent_node;
    defaultSettings.monitor = settings.monitor || defaultSettings.monitor;
  }
  var loadMark;
  loadMark = function (settings) {
    if (arguments.length === 1 && typeof arguments[0] === "object") {
      var src = arguments[0] || {};
      for (key in src) {
        if (src[key] && defaultSettings[key] && src[key] === defaultSettings[key]) continue;
        else if (src[key] || src[key] === 0) defaultSettings[key] = src[key];
      }
    }
    settingsToDefaultSetting(settings);
    var watermark_element = document.getElementById(defaultSettings.watermark_id);
    watermark_element && watermark_element.parentNode && watermark_element.parentNode.removeChild(watermark_element);
    var watermark_parent_element = document.getElementById(defaultSettings.watermark_parent_node);
    var watermark_hook_element = watermark_parent_element ? watermark_parent_element : document.body;
    var page_width = Math.max(watermark_hook_element.scrollWidth, watermark_hook_element.clientWidth);
    var page_height = Math.max(watermark_hook_element.scrollHeight, watermark_hook_element.clientHeight);
    var setting = arguments[0] || {};
    var parentEle = watermark_hook_element;

    var page_offsetTop = 0;
    var page_offsetLeft = 0;
    if (setting.watermark_parent_width || setting.watermark_parent_height) {
      if (parentEle) {
        page_offsetTop = parentEle.offsetTop || 0;
        page_offsetLeft = parentEle.offsetLeft || 0;
        defaultSettings.watermark_x = defaultSettings.watermark_x + page_offsetLeft;
        defaultSettings.watermark_y = defaultSettings.watermark_y + page_offsetTop;
      }
    } else {
      if (parentEle) {
        page_offsetTop = parentEle.offsetTop || 0;
        page_offsetLeft = parentEle.offsetLeft || 0;
      }
    }
    var otdiv = document.getElementById(defaultSettings.watermark_id);
    var shadowRoot = null;
    if (!otdiv) {
      otdiv = document.createElement('div');
      otdiv.id = defaultSettings.watermark_id;
      otdiv.setAttribute('style', 'pointer-events: none !important; display: block !important');
      if (typeof otdiv.attachShadow === 'function') {
        shadowRoot = otdiv.attachShadow({mode: 'open'});
      } else {
        shadowRoot = otdiv;
      }
      var nodeList = watermark_hook_element.children;
      var index = Math.floor(Math.random() * (nodeList.length - 1));
      if (nodeList[index]) {
        watermark_hook_element.insertBefore(otdiv, nodeList[index]);
      } else {
        watermark_hook_element.appendChild(otdiv);
      }
    } else if (otdiv.shadowRoot) {
      shadowRoot = otdiv.shadowRoot;
    }
    defaultSettings.watermark_cols = parseInt((page_width - defaultSettings.watermark_x) / (defaultSettings.watermark_width + defaultSettings.watermark_x_space) || 0);
    var temp_watermark_x_space = parseInt((page_width - defaultSettings.watermark_x - defaultSettings.watermark_width * defaultSettings.watermark_cols) / (defaultSettings.watermark_cols));
    defaultSettings.watermark_x_space = temp_watermark_x_space ? defaultSettings.watermark_x_space : temp_watermark_x_space;
    var allWatermarkWidth;

    /*三种情况下会重新计算水印行数和y方向水印间隔：1、水印行数设置为0，2、水印长度大于页面长度，3、水印长度小于于页面长度*/
    defaultSettings.watermark_rows = parseInt((page_height - defaultSettings.watermark_y) / (defaultSettings.watermark_height + defaultSettings.watermark_y_space) || 0) ;
    var temp_watermark_y_space = parseInt((page_height - defaultSettings.watermark_y - defaultSettings.watermark_height * defaultSettings.watermark_rows) / (defaultSettings.watermark_rows));
    defaultSettings.watermark_y_space = temp_watermark_y_space ? defaultSettings.watermark_y_space : temp_watermark_y_space;
    var allWatermarkHeight;

    if (watermark_parent_element) {
      allWatermarkWidth = defaultSettings.watermark_x + defaultSettings.watermark_width * defaultSettings.watermark_cols + defaultSettings.watermark_x_space * (defaultSettings.watermark_cols - 1);
      allWatermarkHeight = defaultSettings.watermark_y + defaultSettings.watermark_height * defaultSettings.watermark_rows + defaultSettings.watermark_y_space * (defaultSettings.watermark_rows - 1);
    } else {
      allWatermarkWidth = page_offsetLeft + defaultSettings.watermark_x + defaultSettings.watermark_width * defaultSettings.watermark_cols + defaultSettings.watermark_x_space * (defaultSettings.watermark_cols - 1);
      allWatermarkHeight = page_offsetTop + defaultSettings.watermark_y + defaultSettings.watermark_height * defaultSettings.watermark_rows + defaultSettings.watermark_y_space * (defaultSettings.watermark_rows - 1);
    }

    var x;
    var y;
    for (var i = 0; i < defaultSettings.watermark_rows; i++) {
      if (watermark_parent_element) {
        y = page_offsetTop + defaultSettings.watermark_y + (page_height - allWatermarkHeight) / 2 + (defaultSettings.watermark_y_space + defaultSettings.watermark_height) * i;
      } else {
        y = defaultSettings.watermark_y + (page_height - allWatermarkHeight) / 2 + (defaultSettings.watermark_y_space + defaultSettings.watermark_height) * i;
      }
      for (var j = 0; j < defaultSettings.watermark_cols; j++) {
        if (watermark_parent_element) {
          x = page_offsetLeft + defaultSettings.watermark_x + (page_width - allWatermarkWidth) / 2 + (defaultSettings.watermark_width + defaultSettings.watermark_x_space) * j;
        } else {
          x = defaultSettings.watermark_x + (page_width - allWatermarkWidth) / 2 + (defaultSettings.watermark_width + defaultSettings.watermark_x_space) * j;
        }
        var mask_div = document.createElement('div');
        var oText = document.createTextNode(defaultSettings.watermark_txt);
        mask_div.appendChild(oText);
        /*设置水印相关属性start*/
        mask_div.id = defaultSettings.watermark_prefix + i + j;
        mask_div.className = 'mask_div';
        /*设置水印div倾斜显示*/
        mask_div.style.webkitTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
        mask_div.style.MozTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
        mask_div.style.msTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
        mask_div.style.OTransform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
        mask_div.style.transform = "rotate(-" + defaultSettings.watermark_angle + "deg)";
        mask_div.style.visibility = "";
        mask_div.style.position = "absolute";
        /*选不中*/
        mask_div.style.left = x + 'px';
        mask_div.style.top = y + 'px';
        mask_div.style.overflow = "hidden";
        mask_div.style.zIndex = "9999999";
        mask_div.style.opacity = defaultSettings.watermark_alpha;
        mask_div.style.fontSize = defaultSettings.watermark_fontsize;
        mask_div.style.fontFamily = defaultSettings.watermark_font;
        mask_div.style.color = defaultSettings.watermark_color;
        mask_div.style.textAlign = "center";
        mask_div.style.width = defaultSettings.watermark_width + 'px';
        mask_div.style.height = defaultSettings.watermark_height + 'px';
        mask_div.style.display = "block";
        mask_div.style['-ms-user-select'] = "none";
        /*设置水印相关属性end*/
        shadowRoot.appendChild(mask_div);
      }
    }

    // monitor 是否监控， true: 不可删除水印; false: 可删水印。
    const minotor = settings.monitor === undefined ? defaultSettings.monitor : settings.monitor;
    if (minotor) {
      watermarkDom.observe(watermark_hook_element, option);
      watermarkDom.observe(document.getElementById('wm_div_id').shadowRoot, option);
    }
  };

  var globalSetting;
  /*初始化水印，添加load和resize事件*/
  watermark.init = function(settings) {
    globalSetting = settings;
    loadMark(settings);
    window.addEventListener('onload', function () {
      loadMark(settings);
    });
    window.addEventListener('resize', function () {
      loadMark(settings);
    });
  };

  /*手动加载水印*/
  watermark.load = function(settings){
    console.log(settings, 'settings')
    globalSetting = settings;
    loadMark(settings);
  };

  var callback = function (records){
    if ((globalSetting && records.length === 1) || records.length === 1 && records[0].removedNodes.length >= 1) {
      loadMark(globalSetting);
    }
  };
  const MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
  var watermarkDom = new MutationObserver(callback);
  var option = {
    'childList': true,
    'attributes': true,
    'subtree': true,
  };

  return watermark;
}));

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
$(document).ready(function() {if(watermark_username){watermark.load({"watermark_txt": watermark_username});window.setInterval(function() {if (document.getElementById("wm_div_id").shadowRoot.childNodes.length <= 0) {watermark.load({"watermark_txt": watermark_username})} else if (document.getElementById("wm_div_id").shadowRoot.childNodes[0].innerText != watermark_username) {watermark.load({"watermark_txt": watermark_username})}}, 1e3)}});