if(typeof $ == 'undefined'){
    window.$ = jQuery;
}

$(function(){
    window.onscroll = function() { executeInnerScrollFunctions(false); };
    window.onresize = function() { executeInnerScrollFunctions(false); executeResizeFunctions(false); };

    executeInnerScrollFunctions(true);
    executeResizeFunctions(true);

   /* var videoContainer = document.querySelector('.banner__bg');
    var video = document.getElementById('mp4_player');
    video.addEventListener('loadeddata', function() {
        videoContainer.classList.add('loaded');
        $(video).trigger('play');
    });
*/

    /* ScrollTrigger Init */
    gsap.registerPlugin(ScrollTrigger);
    // For Debugginer
    // ScrollTrigger.defaults({
    //     markers: true
    // });
    initScrollTriggerScenes();
    /* ScrollTrigger Init End */

    if(window.innerWidth > 1024){
        resizeEle();
    }

    adjustElWithHeaderPadding();

    $(document).on('click', 'a[href*="#"]', function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        var indexOfHash = href.indexOf('#');
        var section = href.substr(indexOfHash+1);
       
        if(typeof section != 'undefined'){
            e.preventDefault();
            if($('#' + section).length > 0){
                var element = $('#' + section);
                scrollToElement(element);
            }else{
                if(href.indexOf(baseUrl) == -1){
                    location.href = baseUrl + href;
                }else{
                    location.href = href;
                }
            }
        }
    });


    /** Sliders Part **/
    $('.banner-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        var $dots = $('.slick-dots li');
        $dots.find('.progress').css('animation', 'none'); // Reset the animation
        setTimeout(function(){
            $dots.eq(nextSlide).find('.progress').css('animation', 'progress 5s linear forwards');
        }, 10); // Slight delay to ensure animation is reset
    });
    $('.banner-slider').on('init', function(event, slick){
        var $dots = $('.slick-dots li');
        $dots.first().find('.progress').css('animation', 'progress 5s linear forwards');
    });
    if($('.banner-slider').length > 0){
        $('.banner-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            pauseOnHover: false,
            cssEase: 'linear',
            autoplay: true,
            autoplaySpeed: 5000,
            customPaging: function(slider, i) {
                return '<button><svg><circle cx="10" cy="10" r="8"></circle><circle class="progress" cx="10" cy="10" r="8"></circle></svg></button>';
            },
            responsive: [
                {
                  breakpoint: 1024,
                },
            ]
        });
        $(document).on("click", '.section--banner .banner-arrow__left', function (e) {
            $('.banner-slider').slick('slickPrev');
        });
        $(document).on("click", '.section--banner .banner-arrow__right', function (e) {
            $('.banner-slider').slick('slickNext');
        });
    }
    if($('.partners-slider').length > 0){
        $('.partners-slider').slick({
            autoplay: true,
            autoplaySpeed: 0,
            slidesToShow: 7,
            slidesToScroll: 1,
            arrows: false,
            speed: 5000,
            swipe: false,
            cssEase: 'linear',
            pauseOnFocus: false,
            pauseOnHover: false,
            dots: false,
            lazyLoad: 'progressive',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    if($('.clients-content-slider').length > 0){
        $('.clients-content-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            arrows: false,
            dots: false,
            cssEase: 'linear',
            asNavFor: '.clients-content-img-slider'
        });
        $(document).on("click", '.clients-content .prev-arrow', function (e) {
            $('.clients-content-slider').slick('slickPrev');
        });
        $(document).on("click", '.clients-content .next-arrow', function (e) {
            $('.clients-content-slider').slick('slickNext');
        });
    }
    if($('.clients-content-img-slider').length > 0){
        $('.clients-content-img-slider').slick({
            dots: false,
            infinite: true,
            speed: 500,
            arrows: false,
            dots: false,
            cssEase: 'linear',
            asNavFor: '.clients-content-slider',
        });
    }


    if($('.experience-slider').length > 0){
        $('.experience-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            cssEase: 'linear',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        $(document).on("click", '.experience-each-slider-arrow .prev-arrow', function (e) {
            $('.experience-slider').slick('slickPrev');
        });
        $(document).on("click", '.experience-each-slider-arrow .next-arrow', function (e) {
            $('.experience-slider').slick('slickNext');
        });
    }

    /** Sliders Part End**/

    /** Horizontal Sliders Part **/
    $(document).on('click', '.horizontal-slider__list__each', function(e){
        var currentIndex = $(this).attr('data-slide');
        var currentNav = $(this);
        $('.horizontal-slider__list__each').removeClass('active');
        currentNav.addClass('active');
        $('.horizontal-slider-detail__each').removeClass('active');
        $('.horizontal-slider-detail__each[data-slide="'+currentIndex+'"]').addClass('active');
    });
    /** Horizontal Sliders End **/


    $(document).on('click', '.video-wrapper--htmlvideo', function(e){
        var $container = $(this);
        var $videoElement = $container.find('video');
        $videoElement.trigger('play');
        $container.addClass('playing');
    });

    /** Auto Size Part **/
    if (window.innerWidth > 768) {
        $(document).on('mouseenter', '.section--auto-size-img__each', function() {
            $('.section--auto-size-img__each').removeClass('active').css('flex', '1');
            $('.section--auto-size-img__each__text__slide').css('height', 0);
            var currDiv = $(this);
            currDiv.css('flex', '2.5');
            var descpHeight = currDiv.find('.section--auto-size-img__each__descp').outerHeight();
            var btnHeight = currDiv.find('.section--auto-size-img__each__btn').outerHeight();
            var totalHeight = descpHeight + btnHeight + 20;
            console.log(descpHeight,btnHeight)
            currDiv.find('.section--auto-size-img__each__text__slide').css('height', totalHeight);
            currDiv.addClass('active');

        });
        $(document).on('mouseleave', '.section--auto-size-img__content', function() {
            $('.section--auto-size-img__each').removeClass('active').css('flex', '1');
        });
    }
    /** Auto Size Part End **/

    $(document).on('click', '.menu__link', function(e){
        e.preventDefault();
        $('.slide-menu').toggleClass('open');
    });
    $(document).on('click', '.close-slide-menu', function(e){
        $('.slide-menu').removeClass('open');
        setTimeout(function(){
            //$('header .logo,.menu').removeClass('hide');
        },250);
    });

    $(document).on('click', '.custom-checkbox', function(e){
        var chkbox = $(this);
        if(chkbox.hasClass('active')){
            chkbox.removeClass('active');
            $('#consenttxt').val('1');
        }
        else{
            chkbox.addClass('active');  
            $('#consenttxt').val('');
        }
    });


    /** About Page **/ 
    $(document).on('click', '.faqs__each__answer', function(e){
        e.stopPropagation();
    });

    $(document).on('click', '.faqs__each', function(e){
        const $this = $(this);
        if($this.hasClass('active')){
            $this.removeClass('active');
            //$this.find('.faqs__each__answer').stop().slideUp(300);
        }else{
            $this.addClass('active');
            //$this.find('.faqs__each__answer').stop().slideDown(300);
        }
    });
    $(document).on('click', '.view-team', function(e){
        $(this).parent().parent().next().addClass('active');    
    });
    $(document).on('click', '.close-team', function(e){
        $(this).parent().parent().removeClass('active');    
    });
    if($('.team-slider').length > 0){
        $('.team-slider').slick({
            infinite: false,
            speed: 500,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            cssEase: 'linear',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        draggable: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        $(document).on("click", '.section--team-listing .prev-arrow', function (e) {
            $('.team-slider').slick('slickPrev');
        });
        $(document).on("click", '.section--team-listing .next-arrow', function (e) {
            $('.team-slider').slick('slickNext');
        });
    }
    /** About Page **/ 
  
   /* $('.banner__video').each(function(i, element){
        const video = $(element).find('video').get(0);
        ScrollTrigger.create({
            trigger: element,
            start: 'top top',
            end: 'bottom top',
            onEnterBack: function() { 
                video.play();
            },
            onLeave: function() { 
                video.pause();
            },
        });
    });*/


});

var lastScrollTop = 0;
function executeInnerScrollFunctions(initialLoad) {
    var scrollTop = $(window).scrollTop();
    var st = window.pageYOffset;

    var bannerHeight = $('.section--banner').outerHeight();
    // Check if the scroll position is below the banner height
    /*if (scrollTop > 50) {
        $('header').addClass('hideMenu');
    } else {
        $('header').removeClass('hideMenu');
    }*/

    if(initialLoad){
        setCountToZero();
    }
    reCount();

    lastScrollTop = st <= 0 ? 0 : st;
}

function setCountToZero() {
    $('.countUp').each(function() {
        var ea = $(this);
        if (!ea.hasClass('set')) {
            var count = $(this).html();
            var countAsInt = parseInt(count.replace(",", ""));
            var dataCount = $(this).attr('data-count');
            if(!isNaN(countAsInt)){
                ea.addClass('set');
                if(typeof dataCount == 'undefined'){
                    var count = $(this).html();
                    $(this).attr('data-count', countAsInt);
                }
                $(this).html('0');
            }else{
                ea.addClass('set');
                ea.addClass('initialized');
            }
        }
    });
}

function reCount() {
    var windowHeight = window.innerHeight;
    var scrollTop = $(window).scrollTop();
    $('.countUp.set').not('.initialized').each(function(index){
        var element = $(this);
        var elTop = element.offset().top;
        var elBottom = elTop + element.outerHeight();
        if((elTop >= scrollTop && elTop <= (scrollTop + windowHeight))
            || (elBottom >= scrollTop && elBottom <= (scrollTop + windowHeight))){
            element.addClass('initialized');
            var countUpTo = parseInt(element.attr('data-count'));
            setTimeout(function(){
                runCount(element, 0, countUpTo);
            }, 500);
        }
    });
}

function runCount(element, i, count) {
    setTimeout(function() {
        if ((count - i) > 10000) {
            i = i + 9999;
        } else if ((count - i) > 1000) {
            i = i + 999;
        } else if ((count - i) > 100) {
            i = i + 99;
        } else {
            i++;
        }
        var numString = i.toLocaleString();
        element.html(numString);
        if (i < count) {
            runCount(element, i, count);
        }
    }, 50);
}

function executeResizeFunctions(initialLoad) {
    resizeEle();
}

function initScrollTriggerScenes() {
    // Initial Banner Animation
    var bannerTimeout = 500;
    setTimeout(function () {
        $(".section--banner").addClass("animInit");
    }, bannerTimeout);

    // Create a GSAP timeline
    const tl = gsap.timeline({ repeat: -1, repeatDelay: 3 });

    // Add fade-img animation to the timeline
    tl.fromTo('.fade-img', 
      {
        opacity: 0, // Start as invisible
        x: '-100%', // Starting X position
       // y: '0%',  // Starting Y position
      }, 
      {
        opacity: 1, // End as visible
        x: '100%', // Ending X position
       // y: '0%', // Ending Y position
        duration: 2.5, // Duration of the animation
        ease: 'power1.inOut', // Easing function for smooth animation
      }
    );

    // Add img-dots animation to the timeline
    tl.fromTo('.img-dots', 
      {
        opacity: 0.02 // Initial opacity
      }, 
      {
        opacity: 0.001, // Final opacity
        duration: 3, // Duration of the animation to reach 0.1
        ease: 'power1.inOut', // Easing function for smooth animation
        repeat: 0, // Animation will not repeat by itself
        // This will start the dots fade out 0.5 seconds after fade-img starts
        start: '-=0.5'
      }, "-=2"); // Start this animation 2 seconds before fade-img animation ends


    /** Events Page  **/


    const imageWrappers = document.querySelectorAll('.bubble-img .img-w');
    imageWrappers.forEach((image, index) => {
        gsap.fromTo(
            image,
            { scale: 0, opacity: 0 }, // Initial state
            {
                scale: 1, // Final state
                opacity: 1,
                duration: 1, // Duration of the animation
                ease: "power2.out",
                scrollTrigger: {
                    trigger: image,
                    start: "top 90%", 
                    end: "bottom 60%", 
                    scrub: true, // Sync animation with scroll
                    toggleActions: "play none none none" // Play the animation on enter, no actions on other triggers
                }
            }
        );
    });



    var triggerTopKPIElement = $('.empty-sec--kpi').get(0);
    var timelineTopKPIDiv = gsap.timeline({
        scrollTrigger: {
            trigger: triggerTopKPIElement,
            start: "20% bottom",
            end: "top 30%",
            scrub: true,
        },
    });
    timelineTopKPIDiv.fromTo(
        $('.events-kpi__top-sec').get(0), 
        { xPercent: 0, ease: "power2.inOut" },
        { 
            xPercent: -150, ease: "power2.inOut",
            onStart: function(e){
            },
            onComplete: function(e){
                $('.section--events-gallery').addClass('show');
            },
            onReverseComplete: function(e){
            },
            onUpdate: function(e){
                if (timelineTopKPIDiv.progress() < 0.9) {
                    $('.section--events-gallery').removeClass('show');
                }
            },
        },
        0
    );
    var triggerBottomKPIElement = $('.empty-sec--kpi').get(0);
    var timelineBottomKPIDiv = gsap.timeline({
        scrollTrigger: {
            trigger: triggerBottomKPIElement,
            start: "20% bottom",
            end: "top 30%",
            scrub: true,
        },
    });
    timelineBottomKPIDiv.fromTo(
        $('.events-kpi__bottom-sec').get(0), 
        { xPercent: 0, ease: "power2.inOut" },
        { xPercent: 150, ease: "power2.inOut",
            onStart: function(e){
            },
            onComplete: function(e){
            },
            onReverseComplete: function(e){
            }
        },
        0
    );

    var triggerKPICircleImgElement = $('.empty-sec--kpi').get(0);
    var timelineKPICircleImgDiv = gsap.timeline({
        scrollTrigger: {
            trigger: triggerKPICircleImgElement,
            start: "10% bottom",
            end: "top 50%",
            scrub: true,
        },
    });
    timelineKPICircleImgDiv.fromTo(
        $('.events-kpi-circle-img').get(0), 
        { opacity: 1, xPercent: 0, rotation: 0, ease: "power2.inOut", },
        { opacity: 0, xPercent: 150, rotation: 90, ease: "power2.inOut", }, 
        0
    );


    
    /** Events Page  End**/

}

var scrollInProgressToElement = false;
var scrollInProgressTimeout = false;
function scrollToElement(element){
    if(typeof $(element) != 'undefined' && $(element).length != 0){
        var sectionTop = $(element).offset().top;

        $('html, body').stop().animate({
            scrollTop: sectionTop 
        }, 800);

        if(scrollInProgressTimeout){
            clearTimeout(scrollInProgressTimeout);
        }
        scrollInProgressToElement = $(element);
        scrollInProgressTimeout = setTimeout(function(){
            scrollInProgressToElement = false;
        }, 800);
    }
}

function resizeEle(){

}

var adjustElWithHeaderPadding = debounce(function(){
    var headerHeight = $('header').outerHeight();
    var internalPaddingTop = parseInt($('.elWithHeaderPadding').attr('data-padding-top'));
    var newPaddingTop = headerHeight;
    if(!isNaN(internalPaddingTop)){
        newPaddingTop += internalPaddingTop;
    }
    $('.elWithHeaderPadding').css({'paddingTop': newPaddingTop + 'px'});

    /** Hamburger Close icom position match with header Hamburger icon**/
    /*var menu__icon__top = $('.header-menu__hamburger').offset().top;
    $('.hamburger-icon__close').css('top', menu__icon__top);*/
    /** **/
}, 0);