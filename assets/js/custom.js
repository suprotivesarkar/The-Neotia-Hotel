(function($){'use strict';jQuery('.mean-menu').meanmenu({meanScreenWidth:"991"});jQuery(window).on('load',function(){$('.preloader').fadeOut();});$(window).on('scroll',function(){if($(this).scrollTop()>150){$('.navbar-area').addClass("is-sticky");}
else{$('.navbar-area').removeClass("is-sticky");}
var scrolled=$(window).scrollTop();if(scrolled>300)$('.go-top').addClass('active');if(scrolled<300)$('.go-top').removeClass('active');});$('.eorik-slider').owlCarousel({loop:true,margin:0,nav:false,mouseDrag:false,items:1,dots:true,autoHeight:true,autoplay:true,smartSpeed:1500,autoplayHoverPause:true,animateOut:"fadeOut",navText:["<i class='bx bx-chevron-left bx-fade-left'></i>","<i class='bx bx-chevron-right bx-fade-right'></i>",],});$('.eorik-slider-three').owlCarousel({loop:true,margin:0,nav:true,mouseDrag:false,items:1,dots:false,autoHeight:true,autoplay:true,smartSpeed:1500,autoplayHoverPause:true,navText:["<i class='bx bx-chevron-left bx-fade-left'></i>","<i class='bx bx-chevron-right bx-fade-right'></i>",],});$('.eorik-slider-four').owlCarousel({loop:true,nav:false,thumbs:false,dots:true,thumbsPrerendered:true,autoplayHoverPause:true,autoplay:true,items:1,animateOut:"fadeOut",});$('.eorik-slider-five').owlCarousel({loop:true,nav:true,thumbs:true,dots:false,thumbsPrerendered:true,autoplayHoverPause:true,autoplay:true,items:1,navText:["<i class='bx bx-chevron-left'></i>","<i class='bx bx-chevron-right'></i>",],});$('.single-city-item').owlCarousel({loop:true,margin:0,nav:true,mouseDrag:true,items:1,dots:false,autoHeight:true,autoplay:true,smartSpeed:1500,autoplayHoverPause:true,navText:["<i class='flaticon-back'></i>","<i class='flaticon-right'></i>",],});$('.restaurants-wrap').owlCarousel({items:1,loop:true,nav:false,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:0,center:false,dots:false,slideTransition:'linear',autoplayTimeout:4500,autoplayHoverPause:true,autoplaySpeed:4500,responsive:{0:{items:1,},576:{items:1,},768:{items:2,},992:{items:2,},1200:{items:3,},},});$('.slider-gallery-wrap').owlCarousel({items:1,loop:true,nav:false,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:0,center:false,dots:false,slideTransition:'linear',autoplayTimeout:4500,autoplayHoverPause:true,autoplaySpeed:4500,responsive:{0:{items:2,},576:{items:2,},768:{items:3,},992:{items:5,},1200:{items:5,},},});$('.testimonials-wrap').owlCarousel({items:1,loop:true,nav:false,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:30,center:false,dots:false,smartSpeed:1500,responsive:{0:{items:1,},576:{items:1,},768:{items:2,},992:{items:3,},1200:{items:3,},},});$('.exclusive-top-wrap').owlCarousel({items:1,loop:true,nav:false,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:30,center:false,dots:false,smartSpeed:1500,responsive:{0:{items:1,},576:{items:1,},768:{items:2,},992:{items:2,},1200:{items:2,},1920:{items:3,},},});$('.customer-wrap').owlCarousel({loop:true,nav:false,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:30,center:true,dots:true,smartSpeed:1500,responsive:{0:{items:1,},576:{items:1,},768:{items:1,},992:{items:3,},1200:{items:3,},},});$('.customer-wrap-four').owlCarousel({items:1,loop:true,nav:true,autoplay:false,autoplayHoverPause:true,mouseDrag:true,margin:30,center:true,dots:false,smartSpeed:1500,navText:["<i class='flaticon-back'></i>","<i class='flaticon-right'></i>",],});$('.news-wrap-slider').owlCarousel({items:1,loop:true,nav:true,autoplay:true,autoplayHoverPause:true,mouseDrag:true,margin:0,center:false,dots:false,smartSpeed:1500,responsive:{0:{items:1,},576:{items:1,},768:{items:2,margin:20,},992:{items:2,},1200:{items:2,},},navText:["<i class='flaticon-back'></i>","<i class='flaticon-right'></i>",],});$('.service-img-wrap').owlCarousel({loop:true,margin:0,nav:true,mouseDrag:true,items:1,dots:false,autoHeight:true,autoplay:true,smartSpeed:1500,autoplayHoverPause:true,animateOut:"flipOutY",navText:["<i class='flaticon-back'></i>","<i class='flaticon-right'></i>",],});$('#datetimepicker-1').datepicker({weekStart:0,todayBtn:"linked",language:"es",orientation:"bottom auto",keyboardNavigation:false,autoclose:true});$('#datetimepicker-2').datepicker({weekStart:0,todayBtn:"linked",language:"es",orientation:"bottom auto",keyboardNavigation:false,autoclose:true});$('#datetimepicker-3').datepicker({weekStart:0,todayBtn:"linked",language:"es",orientation:"bottom auto",keyboardNavigation:false,autoclose:true});$('.go-top').on('click',function(){$("html, body").animate({scrollTop:"0"},500);});$('.accordion').find('.accordion-title').on('click',function(){$(this).toggleClass('active');$(this).next().slideToggle('fast');$('.accordion-content').not($(this).next()).slideUp('fast');$('.accordion-title').not($(this)).removeClass('active');});function makeTimer(){var endTime=new Date("november  30, 2021 17:00:00 PDT");var endTime=(Date.parse(endTime))/1000;var now=new Date();var now=(Date.parse(now)/1000);var timeLeft=endTime-now;var days=Math.floor(timeLeft/86400);var hours=Math.floor((timeLeft-(days*86400))/3600);var minutes=Math.floor((timeLeft-(days*86400)-(hours*3600))/60);var seconds=Math.floor((timeLeft-(days*86400)-(hours*3600)-(minutes*60)));if(hours<"10"){hours="0"+hours;}
if(minutes<"10"){minutes="0"+minutes;}
if(seconds<"10"){seconds="0"+seconds;}
$("#days").html(days+"<span>Days</span>");$("#hours").html(hours+"<span>Hours</span>");$("#minutes").html(minutes+"<span>Minutes</span>");$("#seconds").html(seconds+"<span>Seconds</span>");}
setInterval(function(){makeTimer();},300);if($('.wow').length){var wow=new WOW({mobile:false});wow.init();}
$('.tab ul.tabs').addClass('active').find('> li:eq(0)').addClass('current');$('.tab ul.tabs li').on('click',function(g){var tab=$(this).closest('.tab'),index=$(this).closest('li').index();tab.find('ul.tabs > li').removeClass('current');$(this).closest('li').addClass('current');tab.find('.tab_content').find('div.tabs_item').not('div.tabs_item:eq('+index+')').slideUp();tab.find('.tab_content').find('div.tabs_item:eq('+index+')').slideDown();g.preventDefault();});$('.tab .tabs').addClass('active').find('> .single-tab:eq(0)').addClass('current');$('.tab .tabs .single-tab').on('click',function(g){var tab=$(this).closest('.tab'),index=$(this).closest('.single-tab').index();tab.find('.tabs > .single-tab').removeClass('current');$(this).closest('.single-tab').addClass('current');tab.find('.tab_content').find('div.tabs_item').not('div.tabs_item:eq('+index+')').slideUp();tab.find('.tab_content').find('div.tabs_item:eq('+index+')').slideDown();g.preventDefault();});$('.odometer').appear(function(e){var odo=$(".odometer");odo.each(function(){var countNumber=$(this).attr("data-count");$(this).html(countNumber);});});$('.popup-youtube, .popup-vimeo').magnificPopup({disableOn:300,type:'iframe',mainClass:'mfp-fade',removalDelay:160,preloader:false,fixedContentPos:false,});$(".newsletter-form").validator().on("submit",function(event){if(event.isDefaultPrevented()){formErrorSub();submitMSGSub(false,"Please enter your email correctly.");}else{event.preventDefault();}});function callbackFunction(resp){if(resp.result==="success"){formSuccessSub();}
else{formErrorSub();}}
function formSuccessSub(){$(".newsletter-form")[0].reset();submitMSGSub(true,"Thank you for subscribing!");setTimeout(function(){$("#validator-newsletter").addClass('hide');},4000)}
function formErrorSub(){$(".newsletter-form").addClass("animated shake");setTimeout(function(){$(".newsletter-form").removeClass("animated shake");},1000)}
function submitMSGSub(valid,msg){if(valid){var msgClasses="validation-success";}else{var msgClasses="validation-danger";}
$("#validator-newsletter").removeClass().addClass(msgClasses).text(msg);}
$(".newsletter-form").ajaxChimp({url:"https://envytheme.us20.list-manage.com/subscribe/post?u=60e1ffe2e8a68ce1204cd39a5&amp;id=42d6d188d9",callback:callbackFunction});var sync1=$("#sync1");var sync2=$("#sync2");var slidesPerPage=4;var syncedSecondary=true;sync1.owlCarousel({items:1,slideSpeed:2000,nav:false,autoplay:true,dots:false,loop:true,responsiveRefreshRate:200,mouseDrag:false,}).on('changed.owl.carousel',syncPosition);sync2.on('initialized.owl.carousel',function(){sync2.find(".owl-item").eq(0).addClass("current");}).owlCarousel({dots:false,nav:false,smartSpeed:200,slideSpeed:500,autoplay:true,loop:true,center:true,mouseDrag:false,slideBy:slidesPerPage,responsiveRefreshRate:100,responsive:{0:{items:1},576:{items:2},768:{items:3},992:{items:3},1200:{items:3}}})
function syncPosition(el){var count=el.item.count-1;var current=Math.round(el.item.index-(el.item.count/2)-.5);if(current<0){current=count;}
if(current>count){current=0;}
sync2.find(".owl-item").removeClass("current").eq(current).addClass("current");var onscreen=sync2.find('.owl-item.active').length-1;var start=sync2.find('.owl-item.active').first().index();var end=sync2.find('.owl-item.active').last().index();if(current>end){sync2.data('owl.carousel').to(current,100,true);}
if(current<start){sync2.data('owl.carousel').to(current-onscreen,100,true);}}
$('.shorting').mixItUp();objectFitImages();jarallax(document.querySelectorAll('.jarallax'));jarallax(document.querySelectorAll('.jarallax-keep-img'),{keepImg:true,});$('.input-counter').each(function(){var spinner=jQuery(this),input=spinner.find('input[type="text"]'),btnUp=spinner.find('.plus-btn'),btnDown=spinner.find('.minus-btn'),min=input.attr('min'),max=input.attr('max');btnUp.on('click',function(){var oldValue=parseFloat(input.val());if(oldValue>=max){var newVal=oldValue;}else{var newVal=oldValue+1;}
spinner.find("input").val(newVal);spinner.find("input").trigger("change");});btnDown.on('click',function(){var oldValue=parseFloat(input.val());if(oldValue<=min){var newVal=oldValue;}else{var newVal=oldValue-1;}
spinner.find("input").val(newVal);spinner.find("input").trigger("change");});});})(jQuery);

$("#register").click(function(){
    $("#lg1").hide();
    $("#lg2").show();
    });
    $("#login").click(function(){
        $("#lg2").hide();
        $("#lg1").show();
        });

            $("#logfrm").on('submit',(function(e){
            e.preventDefault();
            var url="_auth";
            var data = new FormData(this);
            data.append("operation","login");
            $.ajax({
                type:"POST",
                url:url,
                data:data,
                contentType:false,
                cache:false,
                timeout:3000,
                processData:false, 
                dataType:"json",
                beforeSend:function(){$('.logbtn').addClass('eventbtn');},
                error:function(jqXHR,textStatus,errorThrown){
                    $('.logbtn').removeClass('eventbtn');
                    if(textStatus==="timeout"){$(".logmsg").html("Request Could Not Reach");}
                    else{$(".logmsg").html("Error occured try Later");}
                },
                success:function(res){
                    $('.logbtn').removeClass('eventbtn');
                    $(".logmsg").html(res.msg);
                    if(res.status){
                        $("#login").trigger('reset');
                        location.reload();
                    }
                }
            }); 
            })); 

        $("#regform").on('submit',(function(e){
            e.preventDefault();
            var url="_auth";
            var data = new FormData(this);
            data.append("operation","reg");
            $.ajax({
                type:"POST",
                url:url,
                data:data,
                contentType:false,
                cache:false,
                timeout:10000,
                processData:false, 
                dataType:"json",
                beforeSend:function(){$('.regbtn').addClass('eventbtn');},
                error:function(jqXHR,textStatus,errorThrown){
                    $('.regbtn').removeClass('eventbtn');
                    if(textStatus==="timeout"){$(".regmsg").html("Request Could Not Reach");}
                    else{$(".regmsg").html("Error occured try Later");}
                },
                success:function(res){
                    $('.regbtn').removeClass('eventbtn');
                    $(".regmsg").html(res.msg);
                    if(res.status){
                        $("#regform").trigger('reset');
                        location.reload();
                    }
               }
            }); 
            })); 