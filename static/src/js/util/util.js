/*
 * util for LP
 * include common js function and others
 */
define(function( require , exports , model ){
    'use strict';
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

    LP.mix( exports , {
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
    } , true );



    /**
     * JSON stringify and parse
     * Converts the given argument into a JSON representation.
     *
     * @param o {Mixed} The json-serializable *thing* to be converted
     *
     * If an object has a toJSON prototype, that will be used to get the representation.
     * Non-integer/string keys are skipped in the object, as are keys that point to a
     * function.
     *
     */
    !(function(){
        var escape = /["\\\x00-\x1f\x7f-\x9f]/g,
            meta = {
                '\b': '\\b',
                '\t': '\\t',
                '\n': '\\n',
                '\f': '\\f',
                '\r': '\\r',
                '"' : '\\"',
                '\\': '\\\\'
            },
            hasOwn = Object.prototype.hasOwnProperty;

        var stringify = typeof JSON === 'object' && JSON.stringify ? JSON.stringify : function (o) {
            if (o === null) {
                return 'null';
            }

            var pairs, k, name, val,
                type = $.type(o);

            if (type === 'undefined') {
                return undefined;
            }

            // Also covers instantiated Number and Boolean objects,
            // which are typeof 'object' but thanks to $.type, we
            // catch them here. I don't know whether it is right
            // or wrong that instantiated primitives are not
            // exported to JSON as an {"object":..}.
            // We choose this path because that's what the browsers did.
            if (type === 'number' || type === 'boolean') {
                return String(o);
            }
            if (type === 'string') {
                return $.quoteString(o);
            }
            if (typeof o.toJSON === 'function') {
                return $.toJSON(o.toJSON());
            }
            if (type === 'date') {
                var month = o.getUTCMonth() + 1,
                    day = o.getUTCDate(),
                    year = o.getUTCFullYear(),
                    hours = o.getUTCHours(),
                    minutes = o.getUTCMinutes(),
                    seconds = o.getUTCSeconds(),
                    milli = o.getUTCMilliseconds();

                if (month < 10) {
                    month = '0' + month;
                }
                if (day < 10) {
                    day = '0' + day;
                }
                if (hours < 10) {
                    hours = '0' + hours;
                }
                if (minutes < 10) {
                    minutes = '0' + minutes;
                }
                if (seconds < 10) {
                    seconds = '0' + seconds;
                }
                if (milli < 100) {
                    milli = '0' + milli;
                }
                if (milli < 10) {
                    milli = '0' + milli;
                }
                return '"' + year + '-' + month + '-' + day + 'T' +
                    hours + ':' + minutes + ':' + seconds +
                    '.' + milli + 'Z"';
            }

            pairs = [];

            if ($.isArray(o)) {
                for (k = 0; k < o.length; k++) {
                    pairs.push($.toJSON(o[k]) || 'null');
                }
                return '[' + pairs.join(',') + ']';
            }

            // Any other object (plain object, RegExp, ..)
            // Need to do typeof instead of $.type, because we also
            // want to catch non-plain objects.
            if (typeof o === 'object') {
                for (k in o) {
                    // Only include own properties,
                    // Filter out inherited prototypes
                    if (hasOwn.call(o, k)) {
                        // Keys must be numerical or string. Skip others
                        type = typeof k;
                        if (type === 'number') {
                            name = '"' + k + '"';
                        } else if (type === 'string') {
                            name = $.quoteString(k);
                        } else {
                            continue;
                        }
                        type = typeof o[k];

                        // Invalid values like these return undefined
                        // from toJSON, however those object members
                        // shouldn't be included in the JSON string at all.
                        if (type !== 'function' && type !== 'undefined') {
                            val = $.toJSON(o[k]);
                            pairs.push(name + ':' + val);
                        }
                    }
                }
                return '{' + pairs.join(',') + '}';
            }
        };

        /**
         * jQuery.secureEvalJSON
         * Evals JSON in a way that is *more* secure.
         *
         * @param str {String}
         */
        var parse = typeof JSON === 'object' && JSON.parse ? JSON.parse : function (str) {
            var filtered =
                str
                .replace(/\\["\\\/bfnrtu]/g, '@')
                .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                .replace(/(?:^|:|,)(?:\s*\[)+/g, '');

            if (/^[\],:{}\s]*$/.test(filtered)) {
                /*jshint evil: true */
                return eval('(' + str + ')');
            }
            throw new SyntaxError('Error parsing JSON, source is not valid.');
        }

        LP.mix( exports , {
            stringify   : stringify
            , parse     : parse
        } , true );
    })();


    // loc search init
    LP.mix( exports , {
        // search country
        searchCountry: function( $dom , callback ){
            LP.use('autoComplete' , function( auto ){
                auto.autoComplete( $dom , {
                    availableCssPath: 'li'
                    , renderData: function(data){
                        var aHtml = ['<ul>'];
                        var num = 10;
                        var key =  this.key;
                        $.each( data || [] , function( i , v ){
                            if( i == num ) return false;
                            aHtml.push('<li lid="' + v.id + '">' +
                                [ v.name.replace(key , '<em>' + key + '</em>') ,
                                '<span class="c999">' + v.parentName + '</span>' ].join(' , ') +
                                '</li>');
                        } );

                        aHtml.push('</ul>');
                        return aHtml.join('');
                    }
                    , onSelect: function( $dom , data ){
                        $dom.val( data.name );
                        callback && callback( data );
                    }
                    // how to get data
                    , getData: function(cb){
                        var key = this.key;
                        LP.ajax( 'countrysug' , {k: decodeURIComponent( key )} , function( r ){
                            cb( r.data );
                        } );
                    }
                });
            });
        },
        // no country
        searchLoc: function( $dom , callback ){
            LP.use('autoComplete' , function( auto ){
                auto.autoComplete( $dom , {
                    availableCssPath: 'li'
                    , renderData: function(data){
                        var aHtml = ['<ul>'];
                        var num = 10;
                        var key =  this.key;
                        $.each( data || [] , function( i , v ){
                            if( i == num ) return false;
                            aHtml.push('<li lid="' + v.id + '">' +
                                [ v.name.replace(key , '<em>' + key + '</em>') ,
                                '<span class="c999">' + v.parentName + '</span>' ].join(' , ') +
                                '</li>');
                        } );

                        aHtml.push('</ul>');
                        return aHtml.join('');
                    }
                    , onSelect: function( $dom , data ){
                        $dom.val( data.name );
                        callback && callback( data );
                    }
                    // how to get data
                    , getData: function(cb){
                        var key = this.key;
                        LP.ajax( 'locsug' , {k: decodeURIComponent( key )} , function( r ){
                            cb( r.data );
                        } );
                    }
                });
            });
        }
    } , true );

    // for upload
    LP.mix( exports , {
        /**
         * @desc: desc
         * @date:
         * @author: hdg1988@gmail.com
         * cfg => {
            dom: $dom,
            onSuccess: function(){}
         }
         */
        upload: function( $dom , cfg ){
            var config = LP.mix({
                'auto'              : true,
                'multi'             : false,
                'uploadLimit'       : 1,
                'buttonText'        : _e('请选择图片'),
                'height'            : 20,
                'width'             : 120,
                'removeCompleted'   : false,
                'swf'               : LP.getUrl( 'js/uploadify/uploadify.swf' ),
                'uploader'          : 'upload.php',
                'fileTypeExts'      : '*.gif; *.jpg; *.jpeg; *.png; *.bmp;',
                'fileSizeLimit'     : '1024KB',
                'onUploadSuccess' : function(file, data, response) {
                    var msg = $.parseJSON( data );
                    if( !msg.err ){
                        if( cfg.onSuccess )
                            cfg.onSuccess( msg.data );
                    } else {
                        LP.error( msg.msg );
                    }
                },
                'onClearQueue' : function(queueItemCount) {
                },
                'onCancel' : function(file) {
                }
            } , cfg , true );
            LP.use(['uploadify'] , function(){
                $( $dom ).uploadify( config );
            });
        }
    } , true );
});