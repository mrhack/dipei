/*
 * page base action
 */
LP.use('jquery' , function( exports ){
    var $ = exports;

    LP.action( 'logout' , function(){
        LP.ajax('logout' , '' , function(){
            LP.reload();
        });
    });

    // for base action
    LP.action( 'reg' , function(){
        LP.panel({
            url: '/login/?reg=1'
            , hideHead: true
            , width: 518
        });
    });
    // for header normal function
    var headerReady = function(){
        // for base action
        var loginLoaded = false;
        $('[data-a="login"]').click( function(){
        //LP.action( 'login' , function(){
            var $wrap = $('#J_login-wrap').show();
            if( loginLoaded ) return;
            $wrap.click(function(){
                return false;
            });
            loginLoaded = true;
            $.get( '/login/' , function( r ){
                $wrap.find('.dropdown-menu-inner').html( r.html );
            } , 'json' );
        } );


        // change header dropdown menu
        $('.top-r-w').on('click' , '.dropdown-menu li' , function(){
            // set cookie
            var cookie = $(this).closest('.dropdown-menu').attr('c');
            if( !cookie ) return;
            var value = $(this).attr('c');
            LP.setCookie( cookie , value , 30 * 24 * 60 * 60 );
            location.href = location.href.replace(/#.*/ , '');
        });

        // click document , hide all the dropdown-menu
        var classname = ".dropdown-menu";
        $(classname).parent()
            .click(function(){
                $(classname).hide();
                $(this).find(classname)
                    .show();
                return false;
            });
        $(document)
            .click(function( ev ){
                $('.dropdown-menu').hide();
            });
    }

    $(headerReady);


    // for footer
    $('.footer .langs').on('click' , 'a' , function(){
        LP.setCookie( 'lang' , $(this).attr('c') , 30 * 24 * 60 * 60 );
        location.href = location.href.replace(/#.*/ , '');
    });
});