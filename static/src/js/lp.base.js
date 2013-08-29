/*
 * page base action
 */
LP.use(['jquery' , 'util'] , function( exports , util ){
    'use strict';
    var $ = exports;
    // extent jquery , rewrite serialize method , for it 
    // would replace blank space to '+'
    var _tmpSerialize = $.fn.serialize;

    $.fn.serialize = function(){
        var data = _tmpSerialize.call(this);
        return data.replace(/\+/g , ' ');
    }

    // dom ready
    $(function(){
        /* 
         * 自动插入HTML 
         */
        $('[data-autoload]').each(function(){
            if( $(this).attr('run-auto-load') ) return;
            $(this).attr( 'run-auto-load' , 1 );
            var $self = $(this);
            var data  = LP.query2json( $self.data('autoload') );
            var api   = data.api;
            if ( api ) {
                delete data.api;
                LP.ajax(api, data, function(e){ 
                    e = e || ''; 
                    var html = e.html !== undefined ? e.html : e;
                    $self.html(html); 
                });
            }
        });
    });

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

    // for msg
    var msgTemplate = "<div class=\"send-msg-panel\">\
        <textarea placeholder=" + _e("请输入私信内容") + "></textarea>\
        <div class=\"msg-tip\"></div>\
    </div>";
    LP.action('send-msg' , function( data ){
        LP.panel({
            content: msgTemplate
            ,title: _e("发私信给") + " [ " + data.name + "]"
            ,submitButton: true
            ,onSubmit: function(){
                var panel = this;
                var $area = panel.$content.find('textarea');
                var $tip = panel.$content.find('.msg-tip');
                var val = $area.val();
                $tip.hide();
                if( !val ){
                    util.error( $area );
                } else if( val.length > 300 ){
                    util.error( $area );
                    $tip.show().html(_e('私信最大长度为300'));
                } else {
                    LP.ajax('addMsg' , {
                        tid: data.tid,
                        content: val
                    } , function(){
                        panel.close();
                    });
                }
                return false;
            }
        })
    });

    // for link to 
    LP.action('link' , function(){
        var $dom = $(this);
        window.location.href = $dom.attr('href');
        return false;
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

        // get user messages
        /*
        (function(){
            if( !LP.isLogin() ){
                return;
            }
            var renderMessage = function(){
                LP.ajax('msg' , '' , function(){

                });
            }

            renderMessage();
        })();
        */
    }

    $(headerReady);


    // for footer
    $('.footer .langs').on('click' , 'a' , function(){
        LP.setCookie( 'lang' , $(this).attr('c') , 30 * 24 * 60 * 60 );
        location.href = location.href.replace(/#.*/ , '');
    });
});