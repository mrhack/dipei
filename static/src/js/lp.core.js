/***********************************************************
 * LP js for application
 * every page in this application will load this javascipt file
 * So , once you want to do something in all pages , you would
 * add code here.
 *
 * author  : hdg1988@gmail.com
 * version : 1.0
 ***********************************************************/

// before this , a loader must be in

!!(function( host ){
    'use strict';
    // save host global var LP
    if( host.LP )
        host._LP = host.LP;
    var __Cache = {};


    // use third part js and css loader
    var _loader = window.seajs || {};

    var _guid = 0;
    var LP = host.LP = {
        config: {
            debug: true,
            imageServer: "www.lepei.cc/public/img",
            staServer: "www.lepei.cc"
        }
        /*
         * @desc : static model loader
         */
        , loader: _loader
        /**
         * @desc : static file relationship loader
         * @param ... : the same as _loader adapter
         */
        , use: function(){
            var arg = Array.prototype.splice.call( arguments , 0 );
            // adapter AMD
            if( _loader.use )
                _loader.use.apply( _loader , arg );
        }
        , isLogin: function(){
            return true;
        }
        , guid: function(){
            return _guid++;
        }
        , reload: function(){
            window.location.href = window.location.href.replace(/#.*/ , '');
        }
        , getUrl: function( str , type , width , height ){
            type = type || 'sta';
            if( str.match(/^http:\/\// ) )
                return str;
            switch( type ){
                case "sta":
                    return 'http://' + LP.config.staServer
                    + ( LP.config.debug ? "/src/" : "/public/" ) + str;
                case "img":
                    str = str.replace(/(_\d+-\d+)(_\d+-\d+)?(\.\w+)/ , "$1" + "_" + width + "-"+ height + "$3");
                    if( str.indexOf( 'http://' >= 0 ) )
                        return str;
                    return 'http://' + LP.config.imageServer + '/'
                    + str;
            }
        }
        /**
         * @desc : mix several object attribute
         * @param { object } : object need to mix or be mixed
         * @return { object } : if last parameter is boolean value true , this would
                add other object's attribute to first parameter. Otherwise it would
                return a new Object
         */
        , mix: function( ){
            var o = {};
            var len = arguments.length;
            var i = 0;
            if( arguments[ len - 1 ] === true ){
                o = arguments[0];
                i = 1;
                len = len -1;
            }
            for ( ; i < len ; i++ ) {
                for( var k in arguments[ i ] ){
                    if( LP.isObject( arguments[ i ][ k ] ) )
                        o[ k ] = LP.mix( {} , arguments[ i ][ k ] );
                    else if( LP.isArray( arguments[ i ][ k ] ) )
                        o[ k ] = [].concat( arguments[ i ][ k ] );
                    else
                        o[ k ] = arguments[ i ][ k ];
                }
            };
            return o;
        }
        /*
         * @desc : run the fn width every. Ugly forEach function
         *  It loses some feature such as: return false to end the loop.
         *
         * @param arr { array like | object } : If arr has length attribute , deal an array or object
         * @param fn { function } first argument is index , second argument is array value , just like jQuery
         * @param isObj { boolen } if true, deal it as object , otherwise deal it as it be.
         * @return { undefined }
         */
        , each: function( arr , fn , isObj ){
            // just like an array
            if( !isObj && arr.length ){
                for (var i = 0 , len = arr.length ; i < len; i++) {
                    if( fn( i , arr[i] ) === false )
                        return;
                }
            } else { // just like an object
                for ( var key in arr ){
                    if( fn( key , arr[key] ) === false )
                        return;
                }
            }
        }
        /**
         * @desc : format a string and fill it with obj's attribute
         * @param str { string } : a string need to format .
         * @return { string } string after be formated
         * @example:
            LP.format('hello #[name] , ')
         */
        , format: function( str , obj ){
            return str.replace(/#\[(.*?)\]/g , function( $0 , $1 ){
                return obj[ $1 ] === undefined || obj[ $1 ] === false ? "" : obj[ $1 ];
            });
        }
        , parseUrl: function( url ){
            url = url || location.href;
            var uReg = /(.*):\/\/([^/]+)([^?]*)(\?[^#]*)?(#.*)?/;
            var match = url.match( uReg ) || [];
            return {
                protocol: match[1]
                , host: match[2]
                , path: match[3]
                , params: LP.query2json( match[4] )
                , hash: match[5]
            }
        }
        /*
         * @desc : parse url parameters to json object
         * @param str { string } : a url string or serialize string
         * @return { object }
         */
        , query2json: function( str ){
            var strm = str.match(/.*\?(.*)(#.*)?/);
            str = strm ? strm[1] : str;
            var querySplit = str.split('&');
            var nameValue ;
            var arrReg = /(.*)\[\]$/;
            var result = {};
            for (var i = 0 , len = querySplit.length , tmpMatch; i < len ; i++) {
                nameValue = querySplit[i].split('=');
                tmpMatch = nameValue[0].match( arrReg );
                if( tmpMatch ){
                    result[ tmpMatch[1] ] = result[ tmpMatch[1] ] || [];
                    result[ tmpMatch[1] ].push( decodeURIComponent( nameValue[1] ) )
                } else {
                    result[ nameValue[0] ] = decodeURIComponent( nameValue[1] );
                }
            };
            return result;
        }
        , json2query: function( json ){
            var str = [];
            for (var key in json ) {
                if( !json[ key ] ) continue;
                if( LP.isArray( json[key] ) ){
                    LP.each( json[key] , function( i , val ){
                        str.push( key + '[]=' + val );
                    } )
                } else {
                    str.push( key + '=' + json[key] );
                }
            };
            return str.join('&');
        }
    };

    var _classObj = {};
    var _classReg = /([^\s]+)\]$/;
    var _typeof = function( s ){
        var t = _classObj.toString.call( s );
        t = t.match( _classReg );
        return t ? t[1].toLowerCase() : 'unknow';
    }

    LP.each( ['number','function','object','array','string','boolean'] , function( i , type ){
        LP['is' + type.replace(/^\w/, function( $1 ){
            return $1.toUpperCase();
        })] = function( s ){
            return _typeof( s ) === type;
        }
    } );


    // page var operation , include set and get
    __Cache['pageVar'] = {};
    LP.mix( LP , {
        /**
         * @desc : pass page parameter to js
         * @param varObj { object } : php array to json object.
         * @return null
         */
        setPageVar: function( varObj ){
            __Cache.pageVar = LP.mix( __Cache.pageVar , varObj );
        }
        /**
         * @desc : get page parameter from js
         * @param key { string } : page var key
         * @return { all }
         */
        ,getPageVar: function( key ){
            return __Cache.pageVar[ key ];
        }
    } , true );


    // page base action
    !!(function(){
        __Cache['actions'] = {};
        var actionAttr = 'data-a';
        var actionDataAttr = 'data-d';

        // fix action
        LP.mix( LP , {
            /**
             * @desc : action to global env
             * @param type { string } : action name
             * @param fn { function } : the action function
             */
            action : function( type , fn ){
                __Cache['actions'] [ type ] = fn;
            }
            , bind : document.addEventListener ? function( dom , type , fn ){
                dom.addEventListener( type , function( ev ){
                    var r = fn.call( dom , ev );
                    if( r === false ){
                        ev.preventDefault();
                        ev.stopPropagation();
                    }
                } , false );
            } : function( dom , type , fn ){
                dom.attachEvent( 'on' + type , function( ev ){
                    ev = ev || window.event;
                    var r = fn.call( dom , ev );
                    if( r === false ){
                        ev.returnValue = false
                        ev.cancelBubble = true;
                    }
                } );
            }
        } , true );

        var _fireAction = function( type , dom , data ){

            var fn = __Cache['actions'][type];
            if( !fn ) return;

            return fn.call( dom , data );
        };

        LP.bind( document , 'click' , function( ev ){
            var target = ev.srcElement || ev.target;
            while( target &&
                target !== document &&
                !target.getAttribute( actionAttr ) ){
                target = target.parentNode;
            }
            if( !target || target == document ) return;
            var action = target.getAttribute( actionAttr );

            if( !action ) return;
            // fire action
            var aData = target.getAttribute( actionDataAttr ) || '';
            var r = LP.query2json( aData );
            _fireAction( action , target , r );
        });
    })();

    // oo
    !!(function(){
        LP.ajax = function( ){
            var args = [].splice.call( arguments , 0 );
            LP.use('api' , function( api ){
                api.ajax.apply( api , args );
            });
        }
    })();


    // panel
    !!(function(){
        LP.mix( LP , {
            error: function(){
                var args = [].splice.call( arguments , 0 );
                LP.use('panel' , function( exports ){
                    exports.error.apply( exports , args );
                });
            }
            , right: function(){
                var args = [].splice.call( arguments , 0 );
                LP.use('panel' , function( exports ){
                    exports.right.apply( exports , args );
                });
            }
            , alert: function(){
                return this.warn.apply( this , [].splice.call( arguments , 0 ));
            }
            , warn: function(){
                var args = [].splice.call( arguments , 0 );
                LP.use('panel' , function( exports ){
                    exports.warn.apply( exports , args );
                });
            }
            , confirm: function(){
                var args = [].splice.call( arguments , 0 );
                LP.use('panel' , function( exports ){
                    exports.confirm.apply( exports , args );
                });
            }
            , panel: function(){
                var args = [].splice.call( arguments , 0 );
                LP.use('panel' , function( exports ){
                    exports.panel.apply( exports , args );
                });
            }
        } , true );
    })();

    // cookie
    !!(function(){
        /**
         * 根据cookie名称取得cookie值
         * @static
         * @param {string} name cookie名称
         * @return {string}
         */
        LP.getCookie = function( name ){
            var doc=document, val = null, regVal;

            if (doc.cookie){
                regVal = doc.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
                if(regVal != null){
                    val = decodeURIComponent(regVal[2]);
                }
            }
            return val;
        };

        /**
         * 设置cookie
         * @static
         * @param {string} name cookie名称
         * @param {string} value cookie值
         * @param {int} expire 过期时间(秒)，默认为零
         * @param {string} path 路径，默认为空
         * @param {string} domain 域
         * @return {boolean} 设置成功返回true
         */
        LP.setCookie = function(name, value, expire, path, domain, s)
        {
            if ( document.cookie === undefined ){
                return false;
            }

            expire = ! LP.isNumber( expire ) ? 0 : parseInt(expire);
            if (expire < 0){
                value = '';
            }

            var dt = new Date();
            dt.setTime(dt.getTime() + 1000 * expire);

            document.cookie = name + "=" + encodeURIComponent(value) +
                ((expire) ? "; expires=" + dt.toGMTString() : "") +
                "; path=" + (path || '/') +
                "; domain=" + (domain || '') +
                ((s) ? "; secure" : "");

            return true;
        };

        /**
         * 移除cookie
         * @static
         * @param {string} name cookie名称
         * @param {string} path 路径，默认为空
         * @param {string} domain 域
         * @return {boolean} 移除成功返回true
         */
        LP.removeCookie = function(name, path, domain)
        {
            return LP.setCookie(name, '', -1, path, domain);
        };
    })();

    // page language
    !!(function(){
        // get from cookie
        var cookieName = 'lang';

        var i18n = LP.getCookie( cookieName );
        LP.lang = {};
        // use loader to set current language
        LP.loader.config({
            vars: {
                'locale': i18n
            }
            ,preload: ['i18n']
        });

        /*
         * @desc : set current lang of website
         * @param str { string } : the string , which needed to be show
         * @param object { object } : the string replace data
         * @return { string }
         */
        host._e = function( str , object ){
            str = LP.lang[ str ] || str;
            return LP.format( str , object );
        }
    })();
})( window );