(function($) {
    "use strict";

    //AOS Animation
    AOS.init({
        offset: 50,
        duration: 1300,
        easing: 'ease',
        disable: 'mobile',
    });

    //Preloader 
    $('#loader').addClass('hide_loader');
    $('#loader').delay(2000).css('z-index', '-9999');


    //Toggle navbar on href click (mobile)
    $(document).on('click', '.navbar-collapse', function(e) {
        if ($(e.target).is('a') && $(e.target).attr('class') !== 'dropdown-toggle') {
            $(this).collapse('hide');
        }
    });

    //Main Homepage Carousel
    function heroCarousel() {
        $("#hero-carousel").on("swiperight", function() {
            $("#hero-carousel").carousel('prev');
        });
        $("#hero-carousel").on("swipeleft", function() {
            $("#hero-carousel").carousel('next');
        });

        function doAnimations(slider_captions) {
            var animEndEv = 'webkitAnimationEnd animationend';

            slider_captions.each(function() {
                var $this = $(this),
                    $create_animation = $this.data('animation');
                $this.addClass($create_animation).one(animEndEv, function() {
                    $this.removeClass($create_animation);
                });
            });
        }
        var $myCarousel = $('#hero-carousel'),
            $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        $myCarousel.carousel();
        doAnimations($firstAnimatingElems);

        $myCarousel.on('slide.bs.carousel', function(e) {
            var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
            doAnimations($animatingElems);
        });

    }

    //Header style on scroll
    function navbarScrolled() {
        $(window).on("scroll", function() {
            if ($(window).scrollTop() > 50) {
                $(".navbar-head").addClass("navbar-scrolled");
            } else {
                $(".navbar-head").removeClass("navbar-scrolled");
            }
        });
    }

    //Arrow down on scroll
    function hideArrowOnScroll() {
        $(window).on("scroll", function() {
            if ($(window).scrollTop() > 350) {
                $('.scroll-down').fadeOut();
            } else {
                $('.scroll-down').fadeIn();
            }
        });
    }

    //Filter portfolios/images
    function portfolio() {

        if ($('#filterizr').length > 0) {

            $(".filter-pack li").on("click", function() {
                $(".filter-pack li").removeClass("active");
                $(this).addClass("active");
            });

            if ($('').filterizr) {
                $(".filtr-container").filterizr();
            }
        }
    }
    //Magnify pop-up images
    function magnificPopup() {
        if ($('').magnificPopup) {
            $(".portfolio-gallery").magnificPopup({
                delegate: "a",
                type: "image",
                mainClass: "mfp-fade",
                gallery: {
                    enabled: true
                }
            });
        }
    }

    //Progress Bars
    function progressbar1() {
        $('.progressbar1').progressBar({
            height: "2",
            percentage: true,
            shadow: true,
            border: false,
            animation: true,
            animateTarget: true,
        });
    }
    //Masonry blog section
    function bricks() {

        if ($('#masonry-style').length > 0) {


            $('#masonry-style').layout({});
        }

    }

    //Lightbox Video pop-up
    function magnificPopupV() {
        if ($('').magnificPopup) {
            $('.popup-youtube').magnificPopup({
                type: 'iframe',
            });
        }
    }

    //Counter up
    function counterUp() {
        if ($('').counterUp) {
            $('.counter').counterUp({
                delay: 10,
                time: 4000
            });
        }
    }

    /*Tooltips*/
    function tooltip() {
        if ($('').tooltip) {
            $('[data-toggle="tooltip"]').tooltip({
                html: true
            });
        }
    }

    //Parallax
    function parallax() {
        if ($('').parallax) {
            $('.parallax0').parallax("50%", 0.3);
            $('.parallax1').parallax("50%", 0.3);
            $('.parallax2').parallax("50%", 0.2);
            $('.parallax3').parallax("50%", 0.1);
            $('.parallax4').parallax("50%", 0.2);
        }
    }

    //Smooth Local scroll
    function localScroll() {

        $(".local-scroll[href^='#']").on("click", function(anchor) {
            var go = $(this.hash);
            anchor.preventDefault();
            $("html, body").stop().animate({ scrollTop: go.offset().top }, 1000, "easeInOutCubic", function() {})
        })
    }

    //Smooth scroll-up
    function goUp() {
        $(".go-up").on('click', function() {
            $('html, body').animate({
                scrollTop: $("body").offset().top
            }, 2500, 'easeInOutCubic');
        });
    }

    //Ajax - load more blog posts
    function ajaxLoadingBlog() {
        $(".single-ajax-item").slice(0, 3).show();
        $("#loadMore").on('click', function(e) {
            e.preventDefault();
            $(".single-ajax-item:hidden").slice(0, 3).slideDown();
            if ($(".single-ajax-item:hidden").length === 0) {
                $("#loadMore").text("NO MORE POSTS");
                //$("#loadMore").fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top - 80
            }, 1000, 'easeInOutCubic');
        });
    }

    //Dark Overlay Set Opacity
    function darkOverlay() {
        if ($('.dark-overlay').length > 0) {
            $(".dark-overlay").each(function() {
                var attr = $(this).attr('data-overlay-opacity');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $(this).css('background', 'rgba(0, 0, 0, ' + attr + ')');
                }
            });
        }
    }

    //White Overlay Set Opacity
    function whiteOverlay() {
        if ($('.white-overlay').length > 0) {
            $(".white-overlay").each(function() {
                var attr = $(this).attr('data-overlay-opacity');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $(this).css('background', 'rgba(255, 255, 255, ' + attr + ')');
                }
            });
        }
    }

    //Testimonials Carousel
    function testimonials() {

        $("#testimonials").on("swiperight", function() {
            $("#testimonials").carousel('prev');
        });
        $("#testimonials").on("swipeleft", function() {
            $("#testimonials").carousel('next');
        });

        function doAnimations(slider_captions) {
            var animEndEv = 'webkitAnimationEnd animationend';

            slider_captions.each(function() {
                var $this = $(this),
                    $create_animation = $this.data('animation');
                $this.addClass($create_animation).one(animEndEv, function() {
                    $this.removeClass($create_animation);
                });
            });
        }
        var $testimonials = $('#testimonials'),
            $firstAnimatingElems = $testimonials.find('.item:first').find("[data-animation ^= 'animated']");
        $testimonials.carousel();
        doAnimations($firstAnimatingElems);

        $testimonials.on('slide.bs.carousel', function(e) {
            var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
            doAnimations($animatingElems);
        });

    }


    //Team Carousel
    function teamCarousel() {
        var team_owl = $('.team-carousel');
        team_owl.owlCarousel({
            loop: false,
            nav: false,
            margin: 10,
            smartSpeed: 500,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                960: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });
    }

    //Logos Carousel
    function logosCarousel() {
        var logos_owl = $('.logos-carousel');
        logos_owl.owlCarousel({
            loop: true,
            nav: false,
            dots: false,
            margin: 40,
            smartSpeed: 1000,
            autoplay: true,
            autoplayTimeout: 2500,
            autoplayHoverPause: false,
            responsive: {
                0: {
                    items: 3
                },
                600: {
                    items: 4
                },
                960: {
                    items: 5
                },
                1200: {
                    items: 5
                }
            }
        });
    }

    //Portfolio Carousel
    function portfolioCarousel() {
        var portfolio_owl = $('.portfolio-carousel');
        portfolio_owl.owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            margin: 0,
            smartSpeed: 1000,
            navText: ["<span class='ion-ios-arrow-left'></span>", "<span class='ion-ios-arrow-right'></span>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                960: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        });
    }

    //Blog Carousel
    function blogCarousel() {
        var blog_owl = $('.blog-carousel');
        blog_owl.owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            margin: 0,
            smartSpeed: 1000,
            navText: ["<span class='ion-ios-arrow-left'></span>", "<span class='ion-ios-arrow-right'></span>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                960: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        });
    }

    //Parallax Owl Animation
    function owlAnimatedParallax() {

        var owl = $('.owl-animated-parallax');

        owl.owlCarousel({
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayHoverPause: false,
            autoplayTimeout: 2800,
            navSpeed: 2000,
            smartSpeed: 400,
            nav: false,
            dots: false,
            items: 1
        });

        function setAnimation(_elem, _InOut) {
            var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            _elem.each(function() {
                var $elem = $(this);
                var $animationType = 'animated ' + $elem.data('animation-' + _InOut);
                $elem.addClass($animationType).one(animationEndEvent, function() {
                    $elem.removeClass($animationType);
                });
            });
        }
        owl.on('change.owl.carousel', function(event) {
            var $currentItem = $('.owl-item', owl).eq(event.item.index);
            var $elemsToanim = $currentItem.find("[data-animation-out]");
            setAnimation($elemsToanim, 'out');
        });

        owl.on('changed.owl.carousel', function(event) {

            var $currentItem = $('.owl-item', owl).eq(event.item.index);
            var $elemsToanim = $currentItem.find("[data-animation-in]");
            setAnimation($elemsToanim, 'in');
        })
    }



    function coloredSocialIcons() {
        //Add color on clicked icons
        $(".blog-icons .ion-heart").on("click", function() {
            $(this).toggleClass("heart-colored");
        });

        $(".blog-icons .ion-social-facebook").on("click", function() {
            $(this).toggleClass("facebook-colored");
        });


        $(".blog-icons .ion-social-twitter").on("click", function() {
            $(this).toggleClass("twitter-colored");
        });
    }

    //Ajax Contact Form
    function contactFomr() {
        $('#contact-form').validator();
        $('#contact-form').on('submit', function(e) {
            //e.preventDefault();
            if (!e.isDefaultPrevented()) {
                $('.form-submitted').fadeIn();

                $.ajax({
                    type: "POST",
                    url: $('#contact-form').attr('action'),
                    data: $(this).serialize(),
                    success: function(data) {
                        console.log(data);
                        $('#contact-form')[0].reset();
                        $('.form-submitted').fadeOut();
                        $('#status-notification').text(data);
                        $('#contact-field form').slideUp({ duration: 1200, easing: "easeOutExpo" });
                    }
                });
                return false;
            }
        })
    }

    //Ajax Newsletter
    function newsletter() {
        $('#subscribe').validator();
        $('#subscribe').on('submit', function(e) {
            //e.preventDefault();
            if (!e.isDefaultPrevented()) {
                $('.newsletter-submitted img').fadeIn();

                $.ajax({
                    type: "POST",
                    url: $('#subscribe').attr('action'),
                    data: $(this).serialize(),
                    success: function(mdata) {
                        console.log(mdata);
                        $('#subscribe')[0].reset();
                        $('#chimpmsg').text(mdata).fadeIn('slow');
                        $('.newsletter-submitted img').fadeOut();
                    }
                });
                return false;
            }
        })
    }

    //Countdowns
    function countDown() {
        if ($('.countdown').length > 0) {
            $(".countdown").countdown();
        }
    }


    function circularStats() {

        if ($('#circular-counters').length > 0) {

            var wheel = document.getElementById('circular-counters'),
                wheelsCreated = false;

            function onScroll() {
                if (!wheelsCreated && elementInViewport(wheel)) {
                    wheelsCreated = true;
                    createCircles();
                }
            }

            function elementInViewport(el) {
                var rect = el.getBoundingClientRect();

                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight)
                );
            }

            var maxValue1 = $('#circles-1').data('max-value1');
            var maxValue2 = $('#circles-2').data('max-value2');
            var maxValue3 = $('#circles-3').data('max-value3');

            var circleBorderWidth1 = $('#circles-1').data('circle-border1');
            var circleBorderWidth2 = $('#circles-2').data('circle-border2');
            var circleBorderWidth3 = $('#circles-3').data('circle-border3');

            var circleDuration1 = $('#circles-1').data('circle-duration1');
            var circleDuration2 = $('#circles-2').data('circle-duration2');
            var circleDuration3 = $('#circles-3').data('circle-duration3');

            var circleTitle1 = $('#circles-1').data('circle-title1');
            var circleTitle2 = $('#circles-2').data('circle-title2');
            var circleTitle3 = $('#circles-3').data('circle-title3');

            function createCircles() {

                var myCircle = Circles.create({
                    id: 'circles-1',
                    radius: 90,
                    value: maxValue1 || 90, //default value is 90
                    maxValue: 100,
                    width: circleBorderWidth1 || 5, //default value is 5
                    text: function(value) { return value.toFixed(0) + '%<br>' + '<p class="circles-subtitle">' + circleTitle1 + '</p>'; },
                    duration: circleDuration1 || 1000, //default value is 1000
                    styleWrapper: true,
                    styleText: true
                });


                var myCircle = Circles.create({
                    id: 'circles-2',
                    radius: 90,
                    value: maxValue2 || 90, //default value is 90
                    maxValue: 100,
                    width: circleBorderWidth2 || 5, //default value is 5
                    text: function(value) { return value.toFixed(0) + '%<br>' + '<p class="circles-subtitle">' + circleTitle2 + '</p>'; },
                    duration: circleDuration2 || 1000, //default value is 1000
                    styleWrapper: true,
                    styleText: true
                });


                var myCircle = Circles.create({
                    id: 'circles-3',
                    radius: 90,
                    value: maxValue3 || 90, //default value is 90
                    maxValue: 100,
                    width: circleBorderWidth3 || 5, //default value is 5
                    text: function(value) { return value.toFixed(0) + '%<br>' + '<p class="circles-subtitle">' + circleTitle3 + '</p>'; },
                    duration: circleDuration3 || 1000, //default value is 1000
                    styleWrapper: true,
                    styleText: true
                });

            }

            window.onscroll = onScroll;
        }
    };



    $(document).ready(function() {

        heroCarousel();
        navbarScrolled();
        hideArrowOnScroll();
        portfolio();
        magnificPopup();
        progressbar1();
        bricks();
        magnificPopupV();
        counterUp();
        tooltip();
        parallax();
        localScroll();
        goUp();
        ajaxLoadingBlog();
        testimonials();
        blogCarousel();
        teamCarousel();
        portfolioCarousel();
        logosCarousel();
        coloredSocialIcons();
        contactFomr();
        newsletter();
        countDown();
        circularStats();
        darkOverlay();
        whiteOverlay();
        owlAnimatedParallax();
        // patchVideoPlayer();

    });

    /*    
        $(window).load(function () {
            twitterFeedSliderInit();
        });*/

    $(window).on('scroll', function() {

    });





}(jQuery));