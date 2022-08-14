(function($){
  "use strict";
  var POTENZA = {};

  /*************************
  Predefined Variables
*************************/ 
var $window = $(window),
    $document = $(document),
    $body = $('body'),
    $countdownTimer = $('.countdown'),
    $bar = $('.bar'),
    $pieChart = $('.round-chart'),
    $counter = $('.counter'),
    $datetp = $('.datetimepicker');
    //Check if function exists
    $.fn.exists = function () {
        return this.length > 0;
    };

/*************************
      Tooltip
*************************/
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover()
  

 /*************************
       counter
*************************/  
 POTENZA.counters = function () {
  var counter = jQuery(".counter");
  if(counter.length > 0) {  
      loadScript(plugin_path + 'counter/jquery.countTo.js', function() {
        $counter.each(function () {
         var $elem = $(this);                 
           $elem.appear(function () {
             $elem.find('.timer').countTo();
          });                  
        });
      });
    }
  };   

/*************************
     Back to top
*************************/
 POTENZA.goToTop = function () {
  var $goToTop = $('#back-to-top');
      $goToTop.hide();
        $window.scroll(function(){
          if ($window.scrollTop()>100) $goToTop.fadeIn();
          else $goToTop.fadeOut();
      });
    $goToTop.on("click", function () {
        $('body,html').animate({scrollTop:0},1000);
        return false;
    });
  }
  
/*************************
        NiceScroll
*************************/ 
    POTENZA.pniceScroll = function () { 
        $(".scrollbar").niceScroll({
          scrollspeed: 150,
          mousescrollstep: 38,
          cursorwidth: 5,
          cursorborder: 0,
          cursorcolor: 'rgba(0,0,0,0.1)',
          autohidemode: true,
          zindex: 9,
          horizrailenabled: false,
          cursorborderradius: 0,
        });
        // menu scrollbar
        $('.side-menu .collapse').on('shown.bs.collapse', function () {
           $(".side-menu-fixed .scrollbar").getNiceScroll().resize();
         });
         $(".scrollbar-x").niceScroll({
          scrollspeed: 150,
          mousescrollstep: 38,
          cursorwidth: 5,
          cursorborder: 0,
          cursorcolor: 'rgba(0,0,0,0.1)',
          autohidemode: true,
          zindex: 9,
          verticalenabled: false,
          cursorborderradius: 0,
        });
    }



/****************************************************
              pieChart
****************************************************/
 POTENZA.pieChart = function () {
        if ($pieChart.exists()) {
            loadScript(plugin_path + 'easy-pie-chart/easy-pie-chart.js', function() {
            $pieChart.each(function () {
                var $elem = $(this),
                    pieChartSize = $elem.attr('data-size') || "160",
                    pieChartAnimate = $elem.attr('data-animate') || "2000",
                    pieChartWidth = $elem.attr('data-width') || "6",
                    pieChartColor = $elem.attr('data-color') || "#84ba3f",
                    pieChartTrackColor = $elem.attr('data-trackcolor') || "rgba(0,0,0,0.10)";
                $elem.find('span, i').css({
                    'width': pieChartSize + 'px',
                    'height': pieChartSize + 'px',
                    'line-height': pieChartSize + 'px'
                });
                $elem.appear(function () {
                    $elem.easyPieChart({
                        size: Number(pieChartSize),
                        animate: Number(pieChartAnimate),
                        trackColor: pieChartTrackColor,
                        lineWidth: Number(pieChartWidth),
                        barColor: pieChartColor,
                        scaleColor: false,
                        lineCap: 'square',
                        onStep: function (from, to, percent) {
                            $elem.find('span.percent').text(Math.round(percent));
                        }
                    });
               });
            });
         });
      }
    }
 


/*********************************
    Wow animation on scroll
*********************************/
POTENZA.wowanimation = function () {
    if ($('.wow').exists()) {
        var wow = new WOW({
           animateClass: 'animated',
           offset: 100,
           mobile: false
        });
       wow.init();
     }
  } 
  


 
  /*************************
      Accordion
*************************/
  POTENZA.accordion = function () {

    $('.accordion').each(function (i, elem) {
       var $elem = $(this),
           $acpanel = $elem.find(".acd-group > .acd-des"),
           $acsnav =  $elem.find(".acd-group > .acd-heading");
          $acpanel.hide().first().slideDown("easeOutExpo");
          $acsnav.first().parent().addClass("acd-active");
          $acsnav.on('click', function () {
            if(!$(this).parent().hasClass("acd-active")){
              var $this = $(this).next(".acd-des");
              $acsnav.parent().removeClass("acd-active");
              $(this).parent().addClass("acd-active");
              $acpanel.not($this).slideUp("easeInExpo");
              $(this).next().slideDown("easeOutExpo");
            }else{
               $(this).parent().removeClass("acd-active");
               $(this).next().slideUp("easeInExpo");
            }
            return false;
        });
      });
  } 

/*************************
       Search
*************************/ 
POTENZA.searchbox = function () {
   if (jQuery('.search').exists()) {
      jQuery('.search-btn').on("click", function () {
         jQuery('.search').toggleClass("search-open");
           return false;
          });
       jQuery("html, body").on('click', function (e) {
        if (!jQuery(e.target).hasClass("not-click")) {

             jQuery('.search').removeClass("search-open");
         }
     });
    }
}     

/*************************
    Sidebarnav
*************************/ 
POTENZA.Sidebarnav = function () { 
  /*Sidebar Navigation*/
    $(document).on('click', '#button-toggle', function (e) {
      $(".dropdown.open > .dropdown-toggle").dropdown("toggle");
      return false;
    });
    $(document).on('click', '#button-toggle', function (e) {
      $('.wrapper').toggleClass('slide-menu');
      return false;
    });

    $(document).on("mouseenter mouseleave",".wrapper > .side-menu-fixed", function(e) {
      if (e.type == "mouseenter") {
        $wrapper.addClass("sidebar-hover"); 
      }
      else { 
        $wrapper.removeClass("sidebar-hover");  
      }
      return false;
    });
    $(document).on("mouseenter mouseleave",".wrapper > .side-menu-fixed", function(e) {
      if (e.type == "mouseenter") {
        $wrapper.addClass("sidebar-hover"); 
      }
      else { 
        $wrapper.removeClass("sidebar-hover");  
      }
      return false;
    });
    
    $(document).on("mouseenter mouseleave",".wrapper > .setting-panel", function(e) {
      if (e.type == "mouseenter") {
        $wrapper.addClass("no-transition"); 
      }
      else { 
        $wrapper.removeClass("no-transition");  
      }
      return false;
    });
}

/*************************
    Fullscreenwindow
*************************/ 

POTENZA.Fullscreenwindow = function () { 
    if ($('#btnFullscreen').exists()) {
   function toggleFullscreen(elem) {
    elem = elem || document.documentElement;
    if (!document.fullscreenElement && !document.mozFullScreenElement &&
      !document.webkitFullscreenElement && !document.msFullscreenElement) {
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
      } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      }
    }
  }
  document.getElementById('btnFullscreen').addEventListener('click', function() {
    toggleFullscreen();
  });
 }
}

/*************************
    Today date and time
*************************/ 

POTENZA.todatdayandtime = function () { 
      var d = new Date();
      var weekday = new Array(7);
      weekday[0] = "Sunday";
      weekday[1] = "Monday";
      weekday[2] = "Tuesday";
      weekday[3] = "Wednesday";
      weekday[4] = "Thursday";
      weekday[5] = "Friday";
      weekday[6] = "Saturday";
      var n = weekday[d.getDay()];
      $('.today-day').html(n);  
      var n =  new Date();
      var y = n.getFullYear();
      var m = n.getMonth() + 1;
      var d = n.getDate();
      $('.today-date').html(m + " / " + d + " / " + y); 
    }

/*************************
    Summernote
*************************/ 

POTENZA.summernoteeditor = function () { 
 if ($('#summernote').exists()) {
        $('#summernote').summernote({
        height: 300,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: true                  // set focus to editable area after initializing summernote
      });
     }
   }

/*************************
    Colorpicker
*************************/ 

POTENZA.colorpicker = function () { 
  if ($('#cp1, #cp2, #cp3, #cp4, #cp5, #cp6, #cp17, #cp8, #cp9, #cp10, #cp11, #cp12, #cp13').exists()) {
    $('#cp1').colorpicker();
    $('#cp2, #cp3a, #cp3b').colorpicker();
    $('#cp4').colorpicker({"color": "#16813D"});
    $('#cp5').colorpicker({ format: null });
    $('#cp5b').colorpicker({ format: "rgba"  });
    $('#cp6').colorpicker({ horizontal: true });
    $('#cp7').colorpicker({
      color: '#DD0F20',
      inline: true,
      container: true
    });
     $('#cp8').colorpicker({
      color: '#F18A31',
      inline: true,
      container: '#cp8_container'
    });
     $('#cp9').colorpicker({
      useAlpha: false
    });
     $('#cp10').colorpicker({
      useHashPrefix: false
    });
     $('#cp11').colorpicker({
      fallbackColor: 'rgb(48, 90, 162)'
    });
     $('#cp12').colorpicker();
     $('#cp13').colorpicker({
      autoInputFallback: false
    });
  }
 }

/*************************
    Touchspin
*************************/ 

 POTENZA.ptTouchSpin = function () { 
   if ($('input.touchspin-input').exists()) {
      $("input[name='demo1'].touchspin-input").TouchSpin({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
        });
        $("input[name='demo2'].touchspin-input").TouchSpin({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
        });
        $("input[name='demo_vertical'].touchspin-input").TouchSpin({
          verticalbuttons: true
        });

        $("input[name='demo_vertical2'].touchspin-input").TouchSpin({
          verticalbuttons: true,
          verticalupclass: 'fa fa-plus',
          verticaldownclass: 'fa fa-minus'
        });
        $("input.touchspin-input").TouchSpin();
        $("input[name='demo3_21'].touchspin-input,input[name='demo3_22'].touchspin-input").TouchSpin({
              initval: 40
        });
        $("input[name='demo4'].touchspin-input").TouchSpin({
            postfix: "a button",
            postfix_extraclass: "btn btn-default"
        });
       $("input[name='demo4_2'].touchspin-input").TouchSpin({
          postfix: "a button",
          postfix_extraclass: "btn btn-default"
      });
     }
  }

/*************************
    Editormarkdown
*************************/ 

POTENZA.editormarkdown = function () { 
  if ($('#editor-markdown-01, #editor-markdown-02, #editor-markdown-03').exists()) {
    new SimpleMDE({
      element: document.getElementById("editor-markdown-01"),
      spellChecker: false,
    });

    new SimpleMDE({
      element: document.getElementById("editor-markdown-02"),
      spellChecker: false,
      autosave: {
        enabled: true,
        unique_id: "editor-markdown-02",
      },
    });

    new SimpleMDE({
      element: document.getElementById("editor-markdown-03"),
      status: false,
      toolbar: false,
    });
  }
}





/*************************
   Dynamic active menu
*************************/ 

POTENZA.navactivation = function () { 
    var path = window.location.pathname.split("/").pop();
    var target = $('.side-menu-fixed .navbar-nav a[href="'+path+'"]');
    target.parent().addClass('active');        
    $('.side-menu-fixed .navbar-nav li.active').parents('li').addClass('active');
}
POTENZA.ShowMenu = function () { 
   if($('.side-menu li.active:has(>ul)')){$('.side-menu li.active ul.collapse').addClass("show");$('.side-menu li.active a').addClass("collapsed");$('.side-menu li.active a').attr("aria-expanded","true");}
}
/****************************************************
          javascript
****************************************************/
var _arr  = {};
  function loadScript(scriptName, callback) {
    if (!_arr[scriptName]) {
      _arr[scriptName] = true;
      var body    = document.getElementsByTagName('body')[0];
      var script    = document.createElement('script');
      script.type   = 'text/javascript';
      script.src    = scriptName;
      // then bind the event to the callback function
      // there are several events for cross browser compatibility
      // script.onreadystatechange = callback;
      script.onload = callback;
      // fire the loading
      body.appendChild(script);
    } else if (callback) {
      callback();
    }
  };


/****************************************************
     POTENZA Window load and functions
****************************************************/
  //Window load functions
    $window.on("load",function(){
          POTENZA.pieChart();
    });
 //Document ready functions
    $document.ready(function () {
        POTENZA.counters(),
        POTENZA.goToTop(),
        POTENZA.pniceScroll(),
        POTENZA.accordion(),
        POTENZA.wowanimation(),
        POTENZA.searchbox(),
        POTENZA.Sidebarnav(),
        POTENZA.todatdayandtime(),
        POTENZA.editormarkdown(),
        POTENZA.ptTouchSpin(),
        POTENZA.navactivation(),
        POTENZA.ShowMenu(),
        POTENZA.Fullscreenwindow();
    });
})(jQuery);


startTime();

function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('showtime').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};
  return i;
}

function showToast(message,type){$.notify(message,{globalPosition:"bottom center",className:type});}
function convertToSlug(Text){return Text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');}
