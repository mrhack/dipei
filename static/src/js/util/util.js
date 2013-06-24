/*
 * util for LP
 * include common js function and others
 */
define(function( require , exports , model ){
    var $ = require('jquery');

    var ToolTip = (function(){
        var template = ['<div class="tips">',
                    '<p node-type="tooltip-content-wrap"></p>',
                    '<a href="javascript:void(0);" class="closeWrap" node-type="close-wrap">关闭</a>',
                    '<span class="arrow" node-type="arrow"></span>',
                '</div>'].join('');
        var Tip = function( cfg ){
            this.config = $.extend({
                handleElement: null,
                position: 'top',
                closeAble: true,
                width: 158,
                topOff: 0,
                leftOff: 0,
                inner: false,
                closeTimer: 0,
                content: '',
                zIndex: 1000000
            } , cfg );
            this.create().show();
        }
        Tip.prototype = {
            create: function(){
                var t = this, o = t.config;
                t.$wrap = $(template).appendTo(o.inner? o.handleElement : document.body).css('width' , o.width);
                t.$contentWrap = t.$wrap.findNode('tooltip-content-wrap').html(o.content);

                t.$closeBtn = t.$wrap.findNode('close-wrap');
                if(o.closeAble){
                    t.$closeBtn.click(function(){
                        t.close();
                    });
                }else{
                    t.$closeBtn.remove();
                }
                return this;
            },
            show: function(){
                var t = this
                ,   o = t.config
                ,   $wrap = t.$wrap
                ,   wrapH = $wrap.height()
                ,   wrapW = $wrap.outerWidth()
                ,   $dom = $(o.handleElement)
                ,   domPos = $dom.offset()
                ,   domH = $dom.outerHeight()
                ,   domW = $dom.outerWidth()
                ,   top = 0
                ,   left = 0
                ,   $arrow = $wrap.findNode('arrow')
                ,   width = o.width == 'auto' ? wrapW : o.width;
                // 如果是在handler里面，这里的handler必须为absolute或者relative元素
                switch(o.position){
                    case 'top':
                        $arrow.addClass('arrow-t');
                        top = domH + 7 + o.topOff;
                        left = Math.max(domW - width - 22  , - width + 10 + domW/2) + o.leftOff;
                        break;
                    case 'right':
                        $arrow.addClass('arrow-r');
                        top = Math.min(domH/2 - 22 , 0) + o.topOff;
                        left = - wrapW - 29 + o.leftOff;
                        break;
                    case 'left':
                        $arrow.addClass('arrow-l');
                        top = Math.min(0 , domH/2 - 25) + o.topOff;
                        left = domW + 7 + o.leftOff;
                        break;
                    case 'bottom':
                        $arrow.addClass('arrow-b');
                        top = -wrapH - 18 + o.topOff;
                        left = Math.min(0 , domW/2 - 25) + o.leftOff;
                        break;
                }
                if(!o.inner){
                    top += domPos.top;
                    left += domPos.left;
                }
                $wrap.css({
                    top: Math.ceil(top),
                    left: Math.ceil(left),
                    zIndex: o.zIndex
                });
                return this;
            },
            close: function(){
                if( this.$wrap ){
                    this.$wrap.remove();
                }
                return this;
            }
        };
        return Tip;
    })();

    model.exports = {
        createTip: function( cfg ){
            return new Tip( cfg );
        }
        , btnLoading: function(){

        }
        , createLoading: function( $dom , text ){
            $dom.html('<span class="i-icon i-loading"></span>' + ( text ? '<span>' + text + '</span>' : '' ) );
            return {
                remove: function(){
                    $dom.html('');
                }
            }
        }
        , length: function( str , isByte ){
            isByte = isByte === undefined? true : isByte;
            if(isByte){
                var oLength = str.length , tmp = str.replace(/[\u0000-\u0080]/g , '**'),
                    singleLetter = tmp.length - oLength;
                return singleLetter + (oLength - singleLetter)*2;
            }else{
                return str.length;
            }
        }
        , isEmail: function( str ){
            return !!/^[0-9a-zA-Z_][0-9a-zA-Z_.-]*@[0-9a-zA-Z_][0-9a-zA-Z.]+[a-zA-Z]$/.test( str );
        }
        , tab: function( $list , fn , sClass , event ){
            event = event || 'click';
            sClass = sClass || 'selected';
            $list.bind( event , function(){
                $list.removeClass( sClass );

                $(this).addClass( sClass );

                fn && fn.call( this , $list.index( this ) );

                return false;
            });
        }
        , scrollIntoView: function( $dom , $scroll ){
            // scroll into view
            var position    = $dom.position()
            ,   domHeight   = $dom.height()
            ,   scrollTop   = $scroll.scrollTop()
            ,   height      = $scroll.parent().height();
            if ( position.top <= 0 ){
                $scroll.scrollTop( scrollTop + position.top );
            } else if ( position.top + domHeight >= height ) {
                $scroll.scrollTop( position.top - height + scrollTop + domHeight );
            }
        }



        , loop: function( array , time , process , callback ){

        }
        // for form element
        , error: function( $el ){
            var attr    = '_e_t_';
            if( $el.data( attr ) ) return;
            var colors = ['#FFF' ,'#FEE','#FDD','#FCC','#FBB','#FAA','#FBB','#FCC','#FDD','#FEE','#FFF',
                '#FEE','#FDD','#FCC','#FBB','#FAA','#FBB','#FCC','#FDD','#FEE']
            ,   runTimer = function( el , index ){
                    var cssText = el.style.cssText;
                    var _errorTimer = null;
                    var _index = 0;
                    // fix for textarea is hidden ,and focus error
                    _errorTimer = setInterval(function(){
                        if(_index >= colors.length){
                            clearInterval(_errorTimer);
                            _errorTimer = null;
                            if( index == 0 ){
                                $(el).focus();
                            }
                            el.style.cssText = cssText;
                            $(el).removeData( attr );
                            return;
                        }
                        el.style.background = colors[_index];
                        _index ++;
                    } , 40 );
                };
            $el.data( attr , 1 );
            // scroll first element intoview
            var off = $el.eq(0).offset();
            var w_st = $(window).scrollTop();
            var w_height = $(window).height();
            if( off.top < w_st + 50 || off.top > w_st + w_height ){
                $('html,body').animate({
                    scrollTop: off.top - 50
                } , 500 , '' , function(){
                    $el.each(function( index ){
                        runTimer( this , index );
                    });
                } );
            } else {
                $el.each(function( index ){
                    runTimer( this , index );
                });
            }
        }
    }
});