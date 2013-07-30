/*
 * page base action
 */
LP.use(['jquery' , 'util'] , function( exports , util ){
    var $ = exports;
    // extent jquery , rewrite serialize method , for it 
    // would replace blank space to '+'
    var _tmpSerialize = $.fn.serialize;

    $.fn.serialize = function(){
        var data = _tmpSerialize.call(this);
        return data.replace(/\+/g , ' ');
    }


    LP.action( 'logout' , function(){
        LP.ajax('logout' , '' , function(){
            LP.reload();
        });
    });

    // 1.scroll to body top
    // 2.show the login panel
    LP.action('login' , function(){
        // scroll to top
        util.trigger('login');
    });
    // for base action
    // for register
    LP.action( 'reg' , function(){
        LP.panel({
            url: '/login/?reg=1'
            , hideHead: true
            , width: 518
        });
    });


    // for fav
    LP.action('fav' , function( data ){
        var $dom = $( this );
        if( !util.lock( $dom ) )
            return;
        LP.ajax('fav' , data , function(){
            // plus element
            util.plus( $dom );
        }, null, function( r ){
            util.unlock( $dom );
        });
    });

    $(function(){
        // click document , hide all the dropdown-menu
        var classname = ".dropdown-menu";
        $(classname).parent()
            .click(function( ev ){
                $(classname).hide();
                var $menu = $(this).find(classname)
                    .show();
                return $.contains( $menu[0] , ev.target );
            });
        $(document)
            .click(function( ev ){
                $('.dropdown-menu').hide();
            });
    });
    // for header normal function
    var headerReady = function(){
        // for base action
        var loginLoaded = false;
        $('#J_l-top').click( function(){
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
    }

    $(headerReady);


    // for footer
    $('.footer .langs').on('click' , 'a' , function(){
        LP.setCookie( 'lang' , $(this).attr('c') , 30 * 24 * 60 * 60 );
        location.href = location.href.replace(/#.*/ , '');
    });
});