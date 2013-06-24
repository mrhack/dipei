/*
 * page base action
 */
LP.use('jquery' , function( exports ){
    var $ = exports;


    // for base action
    LP.action( 'login' , function(){
        LP.panel({
            url: '/login/?'
            , hideHead: true
            , width: 518
        });
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
        $('.top-r-w').click( function(){
            // hide all dropdown-menu , and show current dropdown menu
            $('.dropdown-menu').hide();
            $(this).find('.dropdown-menu')
                .show();

            return false;
        } )
        .on('click' , '.dropdown-menu li' , function(){
            // set cookie
            var cookie = $(this).closest('.dropdown-menu').attr('c');
            var value = $(this).attr('c');
            LP.setCookie( cookie , value , 30 * 24 * 60 * 60 );
            location.href = location.href.replace(/#.*/ , '');
        });

        // click document , hide all the dropdown-menu
        $(document)
            .click(function(){
                $('.dropdown-menu')
                    .hide();
            });
    }

    $(headerReady);


    // for footer
});