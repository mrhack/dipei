/*
 * page base action
 */
LP.use('jquery' , function( exports ){
    var $ = exports;

    LP.action('logout' , function(){
        LP.ajax('logout' , '' , function(){
            LP.reload();
        });
    });
    // for base action
    LP.action( 'login' , function(){
        var $wrap = $('#J_login-wrap').show();
        $.get( '/login/' , function( r ){
            $wrap.find('.dropdown-menu-inner').html( r.html );
        } , 'json' );
    } );

    // for base action
    LP.action( 'reg' , function(){
        LP.panel({
            url: '/login/?reg=1'
            , hideHead: true
            , width: 518
        });
    } );
    // for header normal function
    var headerReady = function(){
        // change header dropdown menu
        $('.top-r-w').click( function( ev ){
            // hide all dropdown-menu , and show current dropdown menu
            var target = ev.target;
            $('.dropdown-menu').hide();
            var $menu = $(this).find('.dropdown-menu')
                .show();
            return $(target).closest('.dropdown-menu').length || $(this).hasClass('J_no-stop');
        } )
        .on('click' , '.dropdown-menu li' , function(){
            // set cookie
            var cookie = $(this).closest('.dropdown-menu').attr('c');
            if( !cookie ) return;
            var value = $(this).attr('c');
            LP.setCookie( cookie , value , 30 * 24 * 60 * 60 );
            location.href = location.href.replace(/#.*/ , '');
        })
        ;

        // click document , hide all the dropdown-menu
        $(document)
            .click(function(){
                $('.dropdown-menu')
                    .hide();
            });
    }

    $(headerReady);


    // for footer
    $('.footer .langs').on('click' , 'a' , function(){
        LP.setCookie( 'lang' , $(this).attr('c') , 30 * 24 * 60 * 60 );
        location.href = location.href.replace(/#.*/ , '');
    });
});