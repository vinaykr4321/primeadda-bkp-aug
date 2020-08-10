var config = {
    map: {
        '*': {
            'jquery.slick': 'Absolute_AdvancedSlider/js/vendor/slick/slick.min',
            'picturefill': 'Absolute_AdvancedSlider/js/vendor/picturefill.min'
        }
    },
    shim: {
        'jquery.slick': {
            deps: ['jquery'],
            exports: 'jQuery.fn.slick'
        }
    }
};