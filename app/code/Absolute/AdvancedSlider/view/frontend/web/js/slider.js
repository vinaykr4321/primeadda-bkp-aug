define([
    'jquery',
    'jquery.slick',
    'picturefill'
], function ($) {
    'use strict';

    return function(config, node) {
        $(function() {
            // Slider elements / supplied options
            let $slider = $(node).children('.c-aslider__slides'),
                $initalSlider = $(node).children('.c-aslider__loading'),
                $sliderNav = $(node).children('.c-aslider__nav'),
                specifiedOptions = config.config;

            // Options - compiled to pass to Slick instantiation
            let options = {
                arrows: specifiedOptions['arrows']?true:false,
                autoplay: specifiedOptions['autoplay']?true:false,
                autoplaySpeed: specifiedOptions['delay'] || 3000,
                adaptiveHeight: true,
                mobileFirst: true
            };

            // Add fade
            if (specifiedOptions['transition'] === 'fade') {
                options['fade'] = true;
                options['cssEase'] = 'linear';
            }

            // Navigation
            if ($sliderNav.length && specifiedOptions['pagination']) {
                // If pagination content has been set then show dots up to 768px and then pagination content

                // Makes sure both sliders are linked so current status works correctly
                options['asNavFor'] = $sliderNav;

                // Pagination content
                let $slideCount = $sliderNav.children().length;
                
                $sliderNav.slick({
                    asNavFor: $slider,
                    focusOnSelect: true,
                    slidesToShow: $slideCount,
                    slidesToScroll: 1
                });

                // Dots 
                options['dots'] = true;
                options['responsive'] = [
                    {
                        breakpoint: 992,
                        settings: {
                            dots: false
                        }
                    }
                ];
            } else if (specifiedOptions['pagination']) {
                // Dots
                options['dots'] = true;
            }

            // Events - Setup to stop FOUC
            $slider.on('init', function(){    
                // Hide inital image 
                $initalSlider.hide();
                
                // Show slider
                $slider.css({
                    'visibility': 'visible',
                    'height': 'auto'
                });

                // Show slider nav
                $sliderNav.css({
                    'visibility': 'visible',
                    'opacity': 1
                });
            })
            .slick(options); // Init Slider
        });
    }
});