jQuery(document).ready(function($) {

    /**
     * Gives the nav bar a fixed class to stick to top
     */
    var stick_header_nav = function(event) {
        var scroll_top = $(window).scrollTop();
        var offset_top = $('.header-nav').offset().top;
        if (offset_top - scroll_top <= 0)
        {
            $('.header-nav nav').addClass('nav-fixed');
        }
        else
        {
            $('.header-nav nav').removeClass('nav-fixed');
        }
    }

    $(window).scroll(stick_header_nav);
    $(window).resize(stick_header_nav);
    $(window).trigger('scroll');

    /**
     * Sets the current nav section to active
     */
    var nav_set_active = function(nav_section) {
        $('.header-nav nav ul li').each(function(index) {
            $(this).removeClass('active');
            if ($(this).attr('data-section') === nav_section)
            {
                $(this).addClass('active');
            }
        });
    }

    $(window).scroll(function(event) {
        var scroll_top = $(window).scrollTop();
        $('.content-block').each(function(index) {
            if ($(this).offset().top <= scroll_top + 72)
            {
                nav_set_active($(this).attr('id'));
            }
            else if (index === $('.content-block').length - 1 && scroll_top + $(window).height() === $(document).height())
            {
                nav_set_active($(this).attr('id'));
            }
        });
    });


});