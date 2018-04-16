
// Sticky Plugin
// =============
// Author: Anthony Garand
// Improvements by German M. Bravo (Kronuz) and Ruud Kamphuis (ruudk)
// Improvements by Leonardo C. Daronco (daronco)
// Created: 2/14/2011
// Date: 2/12/2012
// Website: http://labs.anthonygarand.com/sticky
// Description: Makes an element on the page stick on the screen as you scroll
//              It will only set the 'top' and 'position' of your element, you
//              might need to adjust the width in some cases.

//Customized by Tarik Assagai for thinkbasic
if (jQuery.fn.onePageNav) {
jQuery('#nav').onePageNav({
currentClass: 'active',
scrollThreshold: 0.2,
scrollOffset: 95,	
});
}
(function($) {
    var defaults = {
            topSpacing: 0,
            bottomSpacing: 0,
            className: 'is-sticky',
            wrapperClassName: 'sticky-wrapper'
        },
        $window = $(window),
        $document = $(document),
        sticked = [],
        windowHeight = $window.height(),
        scroller = function() {
            var scrollTop = $window.scrollTop(),
                documentHeight = $document.height(),
                dwh = documentHeight - windowHeight,
                extra = (scrollTop > dwh) ? dwh - scrollTop : 0;
            for (var i = 0; i < sticked.length; i++) {
                var s = sticked[i],
                    elementTop = s.stickyWrapper.offset().top,
                    etse = elementTop - s.topSpacing - extra;
                if (scrollTop <= etse +121) {
                    if (s.currentTop !== null) {
                        s.stickyElement
                            .css('position', '')
                            .css('top', '')
                            //.removeClass(s.className).animate({ opacity: '1'}, 500);
                            .removeClass(s.className).animate({ opacity: '1', top: -200}, 500);
                        s.stickyElement.parent().removeClass(s.className);
                        s.currentTop = null;
                    }
                }
                else {
                    var newTop = documentHeight - s.stickyElement.outerHeight()
                        - s.topSpacing - s.bottomSpacing - scrollTop - extra;
                    if (newTop < 0) {
                        newTop = newTop + s.topSpacing;
                    } else {
                        newTop = s.topSpacing;
                    }
                    if (s.currentTop != newTop) {
                        s.stickyElement
                            .css('position', 'fixed')
                            .css('top', -200)
                            .addClass(s.className).animate({ opacity: '1', top: 0}, 500);
                        s.stickyElement.parent().addClass(s.className);
                        s.currentTop = newTop;
                    }
                }
            }
        },
        resizer = function() {
            windowHeight = $window.height();
        },
        methods = {
            init: function(options) {
                var o = $.extend(defaults, options);
                return this.each(function() {
                    var stickyElement = $(this);

                    stickyId = stickyElement.attr('id');
                    wrapper = $('<div></div>')
                        .attr('id', stickyId + '-sticky-wrapper')
                        .addClass(o.wrapperClassName);
                    stickyElement.wrapAll(wrapper);
                    var stickyWrapper = stickyElement.parent();
                    stickyWrapper.css('height', stickyElement.outerHeight());
                    sticked.push({
                        topSpacing: o.topSpacing,
                        bottomSpacing: o.bottomSpacing,
                        stickyElement: stickyElement,
                        currentTop: null,
                        stickyWrapper: stickyWrapper,
                        className: o.className
                    });
                });
            },
            update: scroller
        };

    // should be more efficient than using $window.scroll(scroller) and $window.resize(resizer):
    if (window.addEventListener) {
        window.addEventListener('scroll', scroller, false);
        window.addEventListener('resize', resizer, false);
    } else if (window.attachEvent) {
        window.attachEvent('onscroll', scroller);
        window.attachEvent('onresize', resizer);
    }

    $.fn.sticky = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.sticky');
        }
    };
    $(function() {
        setTimeout(scroller, 0);
    });
})(jQuery);
  
 $(window).load(function(){
     
      $("header").sticky({ topSpacing: 0,  wrapperClassName: 'my-wrapper' });
      
});;
