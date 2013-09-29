/*
 * util for LP
 * include common js function and others
 */
define(function( require , exports , model ){
    'use strict';
    var $ = require('jquery');

    // for success tip and failure tip
    LP.mix( exports , {
        // success tip or failure tip
        //{content: xx , close: bool , $wrap:xx , fadeOutTime: 4000 , className: xxx}
        tip: function( cfg ){
            var $dom = $('<div class="alert"></div>')
                .addClass(cfg.className)
                .html(cfg.content)
                .appendTo( cfg.$wrap )
                .hide()
                .slideDown( 500 );
            if( cfg.fadeOutTime ){
                $dom.fadeOut(cfg.fadeOutTime , function(){
                    $dom.remove();
                });
            }
            if( cfg.close ){
                $dom.append('<a href="javascript:;" class="close" data-dismiss="alert">×</a>');
                $dom.find('.close')
                    .click( function(){
                        $dom.fadeOut( function(){
                            $dom.remove();
                        });
                    } );
            }
        }
        , success: function(cfg){
            LP.mix( cfg , {className:'alert-success'} , true );
            this.tip( cfg );
        }
        , error: function(cfg){
            LP.mix( cfg , {className:'alert-error'} , true );
            this.tip( cfg );
        }
        , warn: function(cfg){
            LP.mix( cfg , {className:'alert-block'} , true );
            this.tip( cfg );
        }
    } , true);


    LP.mix( exports , {
        btnLoading: function(){

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
        , isTelephone: function(){

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

        // plus 1 , if $dom innerText has num
        , plus: function( $dom ){
            var text = $dom.html();
            text = text.replace(/\d+/ , function( num ){
                return parseInt( num ) + 1;
            });

            $dom.html( text );
        }
        // reduce 1 , if $dom innerText has num
        , reduce: function( $dom ){
            var text = $dom.html();
            text = text.replace(/\d+/ , function( num ){
                return parseInt( num ) - 1;
            });

            $dom.html( text );
        }

        /**
         * @desc: an element has click event , send ajax to server
                  lock the send event
         * @date:
         * @author: hdg1988@gmail.com
         * @param { jQuery Element }
         * @return { true | false } if return true , lock success , else lock failure
         */
        , lock: function ( $dom ){
            if( $dom.attr('disabled') ){
                return false;
            }
            $dom.attr( 'disabled' , 1 );
            return true;
        }
        , unlock: function( $dom ){
            $dom.removeAttr( 'disabled' );
        }

        , trigger: function( type ){
            switch( type ){
                case "login":
                    LP.panel({
                        url: '/login/'
                        , hideHead: true
                        , width: 518
                    });
                    // scroll to top
                    /*
                    $(document.body).animate({
                        scrollTop: 0
                    } , 500 , '' , function(){
                        // show login panel
                        $('#J_l-top').trigger('click');
                    });
                    */
                break;
            }
        }
        
    } , true );
    
    /**
     * textarea util for lp
     */
    !(function(){
        LP.mix( exports , {
            // 设置光标位置
            setPos: function(textarea , start , length){
                var value = textarea.value;
                start = LP.isNumber(start) ? start : value.length;
                length = length || 0;
                //textarea.focus();
                if(textarea.createTextRange){
                    var textRange = textarea.createTextRange();
                    //textRange.moveStart("character" , -value.length);
                    //textRange.moveEnd("character" , -value.length);
                    textRange.collapse(true);
                    textRange.moveStart("character", start);
                    textRange.moveEnd("character" , length);
                    textRange.select();
                }else{
                    try{// TODO firefox bug , textarea.setSelectionRange === undefined  && textarea.__proto__.setSelectionRange === function
                        textarea.focus();
                        textarea.setSelectionRange(start , start + length);
                    }catch(e){}
                }
            },
            autoHeight: function(textarea , min , max , cb){
                min = min || 1;
                max = max || 100;
                max = 100;
                var $textarea = $(textarea),// jQuery对象
                    textarea = $textarea[0],// DOM对象
                    rowHeight = parseInt($textarea.css('line-height')) || 22, // 行高
                    minHeight = min * rowHeight,
                    maxHeight = max * rowHeight,
                    resizeTextarea = function(dom) {
                        var _rows = dom.rows, _height, _overflow,
                            scrollTop = $(window).scrollTop();
                        dom.style.height = 'auto';
                        dom.rows = min;
                        var _continue = false;
                        // ie6-8需要这个来获取正确的scrollHeight
                        dom.scrollHeight;
                        var _scrollHeight = dom.scrollHeight;
                        if (!_rows || _rows < min || !dom.value) { _rows = min; }
                        while( true ) {
                            _continue = false;
                            if (( _rows * rowHeight > _scrollHeight + rowHeight / 2 || _rows > max ) && _rows > min){ 
                                _continue = true;
                                _rows -= 1;
                            }
                            if (( _rows * rowHeight < _scrollHeight - rowHeight / 2 || _rows < min ) && _rows < max) {
                                _continue = true;
                                _rows += 1;
                            }
                            //dom.setAttribute('rows' , _rows);
                            if( !_continue ) break;
                        }
                        if (_rows >= min && _rows < max) {
                            _height = _rows * rowHeight + 'px';
                            _overflow = 'hidden';
                        } else {
                            _height = maxHeight + 'px';
                            _overflow = 'auto';
                        }
                        $(dom).css({ 'height' : _height, 'overflow-y' : _overflow }).attr('rows', _rows);
                        $(window).scrollTop(scrollTop);
                        cb && cb();
                    };
                
                $textarea.css({ 
                    'height' : !$textarea.val()? minHeight : textarea.scrollHeight,
                    'line-height': rowHeight + 'px'
                    // fixbug set 0 to rows getting an exception in firefox
                }).attr('rows', Math.max(Math.ceil(textarea.scrollHeight/rowHeight) , 1));
                
                // bind self defined event
                $textarea.bind('autoheight' , function(){
                    resizeTextarea(this);
                }).attr('auto-height' , true).trigger('autoheight'); // resize first

                $textarea.keydown(function(){
                    $textarea.trigger('autoheight');
                });
            }
            , toTail: function( $input ){
                this.setPos($input[0] , $input.val().length);
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
    })();

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
        // searchCountry: function( $dom , callback ){
        //     LP.use('autoComplete' , function( auto ){
        //         auto.autoComplete( $dom , {
        //             availableCssPath: 'li'
        //             , renderData: function(data){
        //                 var aHtml = ['<ul>'];
        //                 var num = 10;
        //                 var key =  this.key;
        //                 $.each( data || [] , function( i , v ){
        //                     if( i == num ) return false;
        //                     aHtml.push('<li lid="' + v.id + '">' +
        //                         v.name.replace(key , '<em>' + key + '</em>') +
        //                         '</li>');
        //                 } );

        //                 aHtml.push('</ul>');
        //                 return aHtml.join('');
        //             }
        //             , onSelect: function( $dom , data ){
        //                 $dom.val( data.name );
        //                 callback && callback( data );
        //             }
        //             // how to get data
        //             , getData: function(cb){
        //                 var key = this.key;
        //                 LP.ajax( 'countrysug' , {k: decodeURIComponent( key )} , function( r ){
        //                     cb( r.data );
        //                 } );
        //             }
        //         });
        //     });
        // },
        // no country
        searchLoc: function( $dom , callback , type){
            var searchTypes = {
                country:{
                    url: 'countrysug'
                },
                city:{
                    url: 'citySearch'
                },
                all:{
                    url: 'locsug'
                }
            }
            type = type || 'all';

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
                                [ v.name.replace(key , '<strong>' + key + '</strong>') ,
                                type != 'country' ? '<span>' + v.parentName + '</span>' : '' ].join(' , ') +
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
                        LP.ajax( searchTypes[type].url , {k: encodeURIComponent( key )} , function( r ){
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

        upload: function( $dom , config ){
            // extend for LP
            var defaultConfig = {
                $btn: $dom,
                action : "/image/upload/",
                name: 'upFile',
                parent: null,
                allowExt: 'jpg,jpeg,bmp,gif,png',
                autoSubmit : true,
                responseType: 'json',
                onChange : function(file , extension){},
                onSubmit : function(file , extension){},
                onComplete : function(file , extension){}
            }
            var cfg = LP.mix({} , defaultConfig , config);
            // fix onchange
            if(cfg.allowExt){
                var fn = cfg.onChange;
                cfg.onChange = function(file , extension){
                    if((","+cfg.allowExt+",").indexOf(","+extension+",") < 0){
                        LP.error(_e("只允许上传 #[ext] 格式的文件!" , {ext:cfg.allowExt}));
                        return false;
                    }
                    return fn ? fn.call(this , file , extension) : true;
                }
            }
            LP.use(['upload'] , function( AjaxUpload ){
                return new AjaxUpload(cfg.$btn , cfg);
            });
        }
    } , true );

    
    // for upload
    LP.mix( exports , {
        photoHoverShow: function( $preview , $imgs ){
            var timer = null;
            $imgs.hover(function(){
                var src = this.getAttribute('src');
                clearInterval(timer);
                timer = setTimeout(function(){
                    $preview.fadeIn();
                    var height = $preview.height();
                    var width = $preview.width();
                    var $img = $preview.find('img');
                    if( !$img.length ){
                        $img = $('<img/>').appendTo($preview);
                    }
                    $img.attr('src' , LP.getUrl( src , 'img' , width , height ));
                } , 200);
            } , function(){
                clearInterval(timer);
                timer = setTimeout(function(){
                    $preview.fadeOut();
                } , 200);
            });
        }
    } , true );
    

    // for password strength detach
    !(function(){
        var _is_complex_password = function(str) {
            var n = str.length;
            if ( n < 6 ) { return false; }
            var cc = 0, c_step = 0;
            for (var i=0; i<n; ++i) {
                if ( str.charCodeAt(i) == str.charCodeAt(0) ) { ++ cc; }
                if ( i > 0 && str.charCodeAt(i) == str.charCodeAt(i-1)+1) { ++c_step; }
            }
            if ( cc == n || c_step == n-1) { return false; }
            return true;
        },
        _num = {
            l: 0, // lower case letter number
            L: 0, // upper case letter number
            N: 0, // number letter number
            S: 0,  // special letter number

            // 权重
            lv: 0.3,
            Lv: 0.3,
            Nv: 0.3,
            Sv: 1.5
        },
        _score = function(value){
            var score = 0;
            _num.l = _num.L = _num.N = _num.S = 0;
            if(value.length < 6 || !_is_complex_password(value))
                return score;
            value.replace(/./g , function( $1 ){
                if( $1 >= 'a' && $1 <= 'z' ){
                    _num.l ++;
                } else if( $1 >= 'A' && $1 <= 'Z' ){
                    _num.L ++;
                } else if( $1 >= '0' && $1 <= '9' ){
                    _num.N ++;
                } else {
                    _num.S ++;
                }
            });

            // get score
            var val = 0;

            if( _num.l > 0 ){
                score += _num.l * _num.lv;
                val++;
            }
            if( _num.L > 0 ){
                score += _num.L * _num.Lv;
                val++;
            }
            if( _num.N > 0 ){
                score += _num.N * _num.Nv;
                val++;
            }
            if( _num.S > 0 ){
                score += _num.S * _num.Sv + 2;
                val++;
            }

            score += val - 1;
            return Math.min( ( ~~score ) / 10 , 1 );
        };

        LP.mix( exports , {
            passwordStrength: function( $input , cb ){
                $input.bind('keyup' , function(){
                    cb && cb( _score( this.value ) );
                });
            }
        } , true );
    })();


    // for date select
    !(function(){
        var isLeap = function( year ){
            return ( !( year % 4 ) && year % 100 ) || !(year % 400);
        }
        var genareteOptions = function( num ){
            var ops = ["<option>" + _e('请选择') + "</option>"];
            for (var i = 1; i <= num; i++) {
                ops.push(['<option value="', i, '">', i , '</option>'].join(''));
            };
            return ops.join('');
        }
        LP.mix( exports , {
            datetime: function( $year , $month , $day ){
                $year.add($month)
                    .change(function(){
                        var day = parseInt($day.val());
                        var month = parseInt($month.val());
                        var year = parseInt($year.val());
                        if( year && month ){
                            var maxDay = 31;
                            switch( month ){
                                case 2:
                                    maxDay = isLeap( year ) ? 29 : 28;
                                    break;
                                case 1:
                                case 3:
                                case 5:
                                case 7:
                                case 8:
                                case 10:
                                case 12:
                                    maxDay = 31;
                                    break;
                                default:
                                    maxDay = 30;
                            }
                            $day.html(genareteOptions( maxDay ))
                                .children()
                                .eq(day > $day.children().length ? 0 : day )
                                .attr('selected' , 'selected');
                        }
                    });
            }
        } , true );
    })();
});