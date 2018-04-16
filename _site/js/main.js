jQuery(document).ready(function () {
    if (jQuery.fn.imgLiquid) {
        jQuery(".imgLiquidFill").imgLiquid();
    }


    //slider

    if (jQuery.fn.bxSlider) {
      jQuery('.bxslider').bxSlider({
            mode: 'vertical',
            slideMargin: 5,
            pager: false,
            auto: true
        });
       
    }
    

       
    $.fn.visible = function (partial) {

        var $t = $(this),
            $w = $(window),
            viewTop = $w.scrollTop(),
            viewBottom = viewTop + $w.height(),
            _top = $t.offset().top,
            _bottom = _top + $t.height(),
            compareTop = partial === true ? _bottom : _top,
            compareBottom = partial === true ? _top : _bottom;

        return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

    };


    if (jQuery('#back-to-top').length) {
      
        jQuery('#back-to-top').on('click', function (e) {
            e.preventDefault();
            jQuery('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }



});

var win = $(window);

var allMods = $(".module");

allMods.each(function (i, el) {
    var el = $(el);
    if (el.visible(true)) {
        el.addClass("already-visible");
    }
});

win.scroll(function (event) {

    allMods.each(function (i, el) {
        var el = $(el);
        if (el.visible(true)) {
            el.addClass("come-in");
        }
    });

});


(function ($) {

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *     the user visible viewport of a web browser.
     *     only accounts for vertical position, not horizontal.
     */

    $.fn.visible = function (partial) {

        var $t = $(this),
            $w = $(window),
            viewTop = $w.scrollTop(),
            viewBottom = viewTop + $w.height(),
            _top = $t.offset().top,
            _bottom = _top + $t.height(),
            compareTop = partial === true ? _bottom : _top,
            compareBottom = partial === true ? _top : _bottom;

        return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

    };

})(jQuery);

var win = $(window);

var allMods = $(".contact-blk");

allMods.each(function (i, el) {
    var el = $(el);
    if (el.visible(true)) {
        el.addClass("already-visible");
    }
});

win.scroll(function (event) {

    allMods.each(function (i, el) {
        var el = $(el);
        if (el.visible(true)) {
            el.addClass("come-in");
        }
    });

});

function verticalCenterElement(father, son) {
	// We add 87px for the header height
	var marginTop = ((father.height() - son.height()) + 87) / 2;
	son.css('margin-top', marginTop);	
}

jQuery(document).ready(function($) {
	if($('#proyectos_ficha').length) {
		$('#undefined-sticky-wrapper').css('height','0');
		if($(window).width() > 1023) {
			var element = $('#full-height-block');
			element.children().css('height', $(window).height());
			var textBlock = $('.description-block > div');
			verticalCenterElement(element, textBlock);
			var img = $('.image-block > img');
			verticalCenterElement(element, img);
            $('#undefined-sticky-wrapper').css('height','0');
            
            $(document).scroll(function() {
                if($(document).scrollTop() > $(window).height()) {
                    $('.other-projects-block').css('position','fixed');
                } else {
                    $('#undefined-sticky-wrapper').css('height','0');
                    $('.other-projects-block').css('position','static');				
                }
            });
		}
		
	}
});
