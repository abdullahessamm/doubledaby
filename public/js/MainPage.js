function set_size_and_pos() {
    //Cover Plugins

    var window_width        = $(window).innerWidth(),
        window_height       = $(window).innerHeight(),
        cover_upper         = $('.cover-upper'),
        cover_footer        = $('.cover-footer'),
        cover_image         = $('.cover-img');
    
    // setting the elements in cover in center
    cover_upper.css('left', (window_width - cover_upper.innerWidth())/2 + 'px');
    cover_footer.css('left', (window_width - cover_footer.innerWidth())/2 + 'px');

    //setting the height of the cover
    cover_image.height(window_height);

}

$(window).on('load', function(){
    //set positions
    set_size_and_pos();

    //set position of navigation bar
    if ($(this).scrollTop() >= $(this).innerHeight()) {
        $('.navigation-bar').css({
            'position'          : 'fixed',
            'animation'         : 'nav-down 1s ease',
            'background-color'  : 'rgba(255,255,255, 1)',
            'margin-top'        : '10px',
            'box-shadow'        : '0px 1px 10px #000',
            'width'             : '95%',
        });
        $('.navigation-bar').css('left', ($(window).innerWidth() - $('.navigation-bar').innerWidth())/2 + 'px')
    }
    
    //show body
    $('body').css('opacity', '1');


    //scroll button
    var scroll_btn = $('.cover-footer');
    scroll_btn.on('click', function () {
        //animate scroll
        $('html, body').animate({
            scrollTop : $(window).innerHeight()
        }, 2000);
    });

    //on scrolling function
    var navbar_inner = $('.navigation-bar .container-fluid'); //navbar container (bootstrap)
    $(window).on('scroll', function () {
        if($(this).scrollTop() >= $(this).innerHeight()) {
            navbar_inner.css({
                'background-color': 'rgba(255,255,255, 0)',
            });
            $('.navigation-bar').css({
                'position'          : 'fixed',
                'animation'         : 'nav-down 1s ease',
                'background-color'  : 'rgba(255,255,255, 1)',
                'margin-top'        : '10px',
                'box-shadow'        : '0px 1px 10px #000',
                'width'             : '95%',
                'left'              : ($(window).innerWidth() - $('.navigation-bar').innerWidth())/2 + 'px'
            });
            navbar_inner.addClass('container');
            navbar_inner.removeClass('container-fluid');
        } else if ($(window).scrollTop() > $('.navigation-bar').innerHeight() && $(window).scrollTop() < $(window).innerHeight()) {
            $('.navigation-bar').css({
                'width'     : '95%',
                'position'  : 'absolute',
                'animation' : 'none'
            });
        } else {
            navbar_inner.css({
                'background-color': 'transparent',
                'box-shadow'      : 'none',
            });
            $('.navigation-bar').css({
                'position'          : 'absolute',
                'animation'         : 'none',
                'background-color'  : 'transparent',
                'margin-top'        : '0',
                'width'             : '100%',
                'box-shadow'        : 'none',
                'left'              : '0px'
            });
            $('.navigation-bar .container').addClass('container-fluid');
            $('.navigation-bar .container').removeClass('container');
        };
    });
});

$(window).on('resize', set_size_and_pos);