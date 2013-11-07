/*
 * Select panel. Like most need keyboard event panel , just as auto complete panel or suggestion panel.
 * It support keyboard event ( up , down ) , and
 *
 */
define(function( require , exports , model ){

    var $ = require("jquery");
    var util = require("util");
    var __query_timer = null;
    var mix = LP.mix;
    var panels = [];
    $(document).bind('scroll click resize' , function(){
        $.each( panels , function( i , panel ){
            panel.hide();
        } );
    });

    var template = ['<div>',
                    //'<div class="__auto_head"></div>',
                    //'<div class="__auto_body"></div>',
                    //'<div class="__auto_foot"></div>',
                    '</div>'].join('');
    var defaultConfig = {
        cached      : true,
        enableEmpty : false,
        zIndex      : 2000000,
        hoverClass  : 'hover', // hover class
        availableCssPath: '', // 用于hover的css path
        wrapClass   : '',
        autoSelect  : true,
        // loop        : true,
        //maxHeight: 180,
        supportKeyEvent: true, // 是否支持键盘事件
        width       : '',
        leftOff     : 0, // 向左的偏移值
        topOff      : 0, // 向上的偏移值
        onSelect    : null,
        onHover     : null,
        hideWhenBlank: true, /*  当结果为空时 不显示wrap */
        loadingContent: '',
        getCacheKey: function(key){
            return key;
        },
        renderHead: function(data){
        },
        // how to render data , and return a html string 
        // if no result need to show , return false
        renderData: function(data){
        },
        renderFoot: function(data){
        },
        // how to get data
        getData: function(cb){
            cb([]);
        }
    };
    var BaseSelectPanel = function( handler , o ){
        this.status = 0; // status 1 面板的显示状态
        this.cache = {}; // 不能用在prototype中，所有的实例不能共享
        this.config = mix( defaultConfig , o || {} );
        var t = this , o = this.config;
        t.$handler = $(handler);
        if(!o.width){
            o.width = t.$handler.outerWidth();
        }
        t.$wrap = $(template)
            .addClass('__auto_wrap ' + o.wrapClass)
            .css({'position': 'absolute' , 'z-index': o.zIndex , 'width': o.width-2})
            .appendTo(document.body)
            .hide();

        // init event
        t.$wrap.delegate(o.availableCssPath , "mouseover" , function(){
            t.hover($(this));
        }).delegate(o.availableCssPath , "click" , function(){
            t.select( $(this) );
            //o.onclick && o.onclick($(this));
            //var data = t.data[t.$wrap.find(o.availableCssPath).index($(this))];
            //t.select( $(this) , data );
            return false;
        }).click(function(ev){ // click element clear the time out
            //var tar = ev.target; // if there is a element , do not return false , if has action-type attribute do not return false;
            //if(tar.tagName != 'A' || (tar.getAttribute('href').indexOf('javascript') >= 0 && !tar.getAttribute('action-type')))
            //  return false;
            ev.stopPropagation();
        });

        // key down event
        if(o.supportKeyEvent){
            t.$handler.keydown(function(ev){// textarea or inputt.$textarea
                if(t.$wrap.is(':hidden')) return;
                switch(ev.keyCode){
                    case 40: // down
                        t.movehover( 1 );
                        break;
                    case 38: // up
                        t.movehover( -1 );
                        break;
                    case 13: //enter
                        t.select( t.$hoverDom );
                        break;
                    case 9: // tab
                    case 27: //esc
                        t.hide(); // need return
                    default:
                        return;
                }
                return false;
            });
        }

        panels.push( t );
    };
    BaseSelectPanel.prototype = {
        // events: [
        //     /*选中事件*/
        //     'select',
        //     /*显示面板事件*/
        //     'show',
        //     'hover',
        //     'beforeShow',
        //     /*隐藏面板事件*/
        //     'hide'
        // ],
        __getBody : function(){
            return this.$wrap.find('.__auto_body');
        },
        __getIndex: function(){
            return this.$hoverDom ?
                this
                    .$wrap
                    .find( this.config.availableCssPath)
                    .index( this.$hoverDom)
                : -1 ;
        },
        show : function( left , top , key ){
             // setPosition
            var t   = this
            , o     = t.config
            , callback = function( data ){
                if(o.cached)t.cache[cacheKey] = data;
                // 如果当前的key 不是输入框的最后的key，则需要隐藏
                if( t.key != key ) return;
                // 如果在请求时， 用户已经取消请求，则请求成功回来后不render数据
                if( t.status == 0 ) return;
                // 如果没有结果，则需要隐藏
                if(o.hideWhenBlank && (!data || !data.length)) {
                    t.hide();
                    return;
                }
                var html = o.renderData.call(t , data) || '' ,
                    hHtml = o.renderHead ? o.renderHead.call(t , data) : '' ,
                    fHtml = o.renderFoot ? o.renderFoot.call(t , data) : '';

                t.data = data;
                // if(!html || t.fireEvent("beforeShow" , t , html) === false){
                //     t.$wrap.hide();
                //     return;
                // }
                hHtml = hHtml ? '<div class="__auto_head">' + hHtml + '</div>' : '';
                fHtml = fHtml ? '<div class="__auto_foot">' + fHtml + '</div>' : '';
                html = '<div class="__auto_body">' + html + '</div>';
                t.$wrap
                    .css('height' , 'auto')
                    .html(hHtml + html + fHtml)
                    .show();
                // select first element
                var $dom = t.$wrap.find(o.availableCssPath).eq(0);
                if( $dom.length && o.autoSelect ){
                    t.hover( $dom );
                }
                // set height of the $wrap
                var $body = t.__getBody();
                if(o.maxHeight && $body.height() > o.maxHeight){
                    $body.height(o.maxHeight).css({
                        'overflow-y': 'auto',
                        'overflow-x': 'hidden'
                    });
                }
                // fire event
                // t.fireEvent("show" , t);
            };

            t.$hoverDom = null;

            if( !o.enableEmpty && !key ){
                t.hide()
                return;
            }
            // change status
            t.status = 1;

            // set default top and left
            t.key = key === undefined ? t.$handler.val() : key;
            var off = t.$handler.offset();
            top = (top || (off && off.top + t.$handler.outerHeight())) + o.topOff;
            left = (left || (off && off.left)) + o.leftOff;
            t.$wrap
                .html(o.loadingContent || '')
                .css({
                    position: 'absolute',
                    top     : ~~top ,
                    left    : ~~left
                })
                [o.loadingContent ? 'show' : 'hide']();

            var cacheKey = o.getCacheKey(key);
            if(o.cached && t.cache[cacheKey] !== undefined){
                callback(t.cache[cacheKey]);
            }else{
                clearTimeout(__query_timer);
                __query_timer = setTimeout(function(){
                    o.getData.call(t , function(data){
                        callback(data);
                    } , function(){
                        t.$wrap.html('<span style="padding-left:10px;color:#FFD991;">出错啦，请稍候重试...</span>').show();
                    });
                } , 150);
            }
        },
        // hide panel
        hide: function(){
            this.$wrap.hide();
            this.status = 0;
            // fire event
            // this.fireEvent("hide" , this);
        },
        select: function( $dom ){
            var t = this , o = t.config;
            t.$hoverDom = $dom || t.$hoverDom;
            t.hide();
            if(t.$hoverDom && t.$hoverDom.length){
                // t.fireEvent("select" , t.$hoverDom);
                var index = t.__getIndex();
                if(o.onSelect)
                    o.onSelect.call(t , t.$hoverDom , t.data[index] );
            }
        },
        hover: function( $dom ){
            if( !$dom || !$dom.length ) return;
            var t   = this
            , o     = t.config
            , hoverClass    = o.hoverClass
            , $lastHoverDom = t.$hoverDom;
            if( t.$hoverDom )
                t.$hoverDom.removeClass( hoverClass );
            $dom.addClass( hoverClass );
            t.$hoverDom = $dom;
            // if curr hover dom is last hover dom , then return
            if( $lastHoverDom && t.$hoverDom.get(0) === $lastHoverDom.get(0) ) return;

            // t.fireEvent( 'hover' , t.$hoverDom , $lastHoverDom );
            if( o.onHover ) o.onHover.call( t , t.$hoverDom , $lastHoverDom );
            // scroll into view
            var $body = t.__getBody();
            util.scrollIntoView( t.$hoverDom , $body );
        },
        movehover: function( step ){
            var $list = this.$wrap.find( this.config.availableCssPath );
            var len = $list.length;
            if( len ){
                var index = this.$hoverDom ? $list.index( this.$hoverDom ) :
                step > 0 ? -1 : 0;
                index = ( index + step + len ) % len;
                this.hover( $list.eq( index ) );
            }
        }
    };
    /*
     * cfg = {
        regx : /xxx/,
        tag : '{',
        selectConfig : {},
        // $el is textarea element
        afterSelect : function(area , value , lastIndex , len){}
        beforeShow : function(){}
     }
     */
    var inputSuggestion = function( $textarea , cfg ){
        var regx = cfg.regx,
            tag = cfg.tag,
            lastIndex = 0,
            currIndex = 0,
            lastText = '',
            suggestion = null,
            _timeout = null,
            showSuggestion = function( ev ){
                if(suggestion && suggestion.$wrap.is(':visible')){
                    switch(ev.keyCode){
                        case 40: // down
                        case 38: // up
                        case 13: //enter
                            return;
                    }
                }

                var textarea = this,
                    value = textarea.value,
                    range = tUtil.getPos(textarea),
                    text = value.substring(0 , range.start);

                currIndex = range.start;
                lastIndex = text.lastIndexOf(tag);
                lastText = text.substring(lastIndex);
                if(!regx.test(lastText)){
                    suggestion && suggestion.hide();
                    return;
                }
                if(!suggestion){
                    suggestion = new BaseSelectPanel(textarea , cfg.selectConfig);
                    suggestion.addListener("select" , function($dom){
                        var name = $dom.attr('data-insert');
                        if(!name){
                            tUtil.setText(textarea , '\n' , currIndex);
                        }else{
                            cfg.afterSelect && cfg.afterSelect(textarea , name , lastIndex , lastText.length);
                        }
                    });
                    suggestion.addListener("beforeShow" , function(t , data){
                        if(cfg.beforeShow){
                            return !!cfg.beforeShow( t , data );
                        }
                        return true;
                        //return !!$(data).find('li').length;
                    });
                }

                // show suggestion
                var pos = tUtil.getPagePos(textarea ,lastIndex);
                suggestion.show( pos.left , pos.bottom + 3 , lastText.substring(1));
            },
            eventFn = function(ev){
               if(ev.keyCode == 27){
                   return false;;
               }
               // 延迟处理
               clearTimeout(_timeout);
               var textarea = this;
               _timeout = setTimeout(function(){
                   showSuggestion.call(textarea , ev);
               },100);
            };
        // key up event
        $textarea.keyup(eventFn);
        // mouse down event
        $textarea.mouseup (eventFn);
        return suggestion;
    };
    exports.autoComplete = function(input , cfg){
        var o = mix({
                //loadingContent: '<div style="text-align:center;padding:20px 0;"><span class="loading tgrey3">正在载入，请稍后...</span></div>',
                width: $(input).outerWidth()
                ,onSelect: function( $dom , data ){
                    $input.val( $dom.text() );
                }
            } , cfg )
        , __timer     = null
        , __suggest   = new BaseSelectPanel(input , o)
        , __show      = function(){
            var off = $input.offset();
            __suggest.show(off.left , off.top + $input.outerHeight() , o.getKey ? o.getKey() : $input.val());
        }
        ,   $input      = $(input)
        ,   eventFn     = function(){
           clearTimeout(__timer);
           __timer = setTimeout(function(){
               __show();
           } , 300);
        };
        // search suggestion
        $input.keyup( function( ev ){
            switch(ev.which){
                case 37:
                case 38:
                case 39:
                case 40:
                 return;
            }
            eventFn();
        } );
        $input.focus( eventFn );
        return __suggest;
    };
});
