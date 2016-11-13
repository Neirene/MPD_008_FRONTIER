$(function () {

    console.log("INIT!");
   // $('body').panelSnap();

    function animatedHeader() {
        var initializeVisual = true;
        var video = document.getElementById('videoBackground');
        var source = document.createElement('source');
        var videoPreload = "auto";
        var videoAutoplay = "autoplay";
        var videoLoop = "loop";

        if (video.canPlayType('video/mp4;codecs="avc1.42E01E, mp4a.40.2"')) {
            source.setAttribute('src', '../video/video_header.mp4');
        } else {
            source.setAttribute('src', '../video/video_headerw.webm');
        }

        video.setAttribute('preload', videoPreload);
        video.setAttribute('autoplay', videoAutoplay);
        video.setAttribute('loop', videoLoop);

        video.appendChild(source);

        video.oncanplaythrough = function () {

            if (initializeVisual == true) {
                setTimeout(function () {
                    $(".video-top").addClass('show-video');
                    stopVisual();
                }, 2000);
            } else {
                return null;
            }

            function stopVisual() {
                initializeVisual = false;
                clearTimeout(initializeVisual);
            }

        };



    }
    animatedHeader();
    

    
    $('.highlights-banners').slick({
        dots: true,
        infinite: true,
        speed: 1200,
        autoplay: true,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1
    });



    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });


    $(".interface-bt-container").on('click', function () {

        $(".download-menu-container").fadeIn(200, function () {

            $(".menu-splash").removeClass('hide-menu');

        });

    });

    $(".close-splash").on('click', function () {

        $(".download-menu-container").fadeOut(200);
        $(".menu-splash").addClass('hide-menu');
    });



    $('.nextSlideAbout').on('click', function () {
        $('.about-slide-01').addClass('disable-slide');
        $('.about-slide-02').removeClass('disable-slide');
    });

    $('.prevSlideAbout').on('click', function () {
        $('.about-slide-02').addClass('disable-slide');
        $('.about-slide-01').removeClass('disable-slide');
    });


    var pvpZoneIsScrolling = false;
    $(document).on('click', '#pvp', function (e) {

        if (pvpZoneIsScrolling == true){
            //console.log("please wait the event is still working");
        }else{

            $('.map-point').addClass('hidden-point');
            $('.pvp-bg').removeClass('scroll-pvp-bg');
            showMapMarks();

        }

    });

    var zoneCounter = 0;
    var zoneAmount = $('.map-point').length;
    
    function showMapMarks() {
        pvpZoneIsScrolling = true;
            var scrollerSystem = setTimeout(function () {

                $('.map-point:eq("' + zoneCounter + '")').removeClass('hidden-point');
                zoneCounter++;

                if (zoneCounter > 13) {
                    $('.pvp-bg').addClass('scroll-pvp-bg');
                } else {
                    $('.pvp-bg').removeClass('scroll-pvp-bg');
                }

                if (zoneCounter <= zoneAmount) {
                    showMapMarks();
                } else {
                    zoneCounter = 0;
                    stopPvpZoneScroll();

                }

            }, 200);


            function stopPvpZoneScroll() {
                pvpZoneIsScrolling = false;
                clearTimeout(scrollerSystem);
            }


        } 











    //if ($("#urawaza").hasClass('active')) {
    //    console.log('showtime');
    //} else {
    //    console.log('hide everything!');
    //}


    // Event demo
    //$('.event_demo .panels').on('panelsnap:start', event_log);
    $('#urawaza').on('panelsnap:start', checkSnap);
    //$('.event_demo .panels').on('panelsnap:activate', event_log);

    function checkSnap() { console.log(e + "this was a panelsnap event?") }





    $(document).on("scroll", function () {

        if ($(this).scrollTop() >= $("#pvp").outerHeight() ) {

            if (pvpZoneIsScrolling == true){
                console.log("please wait the event is still working");
            }else{

                $('.map-point').addClass('hidden-point');
                $('.pvp-bg').removeClass('scroll-pvp-bg');
                showMapMarks();

            }

        } 

    

        
        if ($(this).scrollTop() >= $("#header").outerHeight() ) {

            $(".overlay-menu").addClass("show-menu");

        } else {
            $(".overlay-menu").removeClass("show-menu");
        }

    });


});