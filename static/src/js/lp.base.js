/*
 * page base action
 */
LP.use('jquery' , function( exports ){
    var $ = exports;


    // for base action
    LP.action( 'login' , function(){
        LP.panel({
            url: '/github/dipei/login/login?ajax=1'
            , width: 518
        });
    } );

    // for base action
    LP.action( 'reg' , function(){
        LP.panel({
            url: '/github/dipei/login/login?ajax=1&reg=1'
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
        } );

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