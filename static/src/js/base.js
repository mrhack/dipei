/***********************************************************
 * base js for application
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
    var LP = host.LP = {
        /*
         * @desc : static model loader
         */
        loader: _loader,
        /**
         * @desc : static file relationship loader
         * @param ... : the same as _loader adapter
         */
        use: function(){
            var arg = Array.prototype.splice.call( arguments , 0 );
            // adapter AMD
            if( _loader.use )
                _loader.use.apply( _loader , arg );
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
                arr = [].splice.call( arr , 0 );
                for (var i = 0 , r , len = arr.length ; i < len; i++) {
                    r = fn( i , arr[i] );
                    if( r === false )
                        return;
                }
            } else { // just like an object
                for ( var key in arr ){
                    r = fn( key , arr[key] );
                    if( r === false )
                        return;
                }
            }
        }
        /**
         * @desc : format a string and fill it with obj's attribute
         * @param str { string } : a string need to format .
         * @return { string } string after be formated
         * @example:
            LP.format('hello #{name} , ')
         */
        , format: function( str , obj ){
            return str.replace(/#\{(.*?)\}/g , function( $0 , $1 ){
                return obj[ $1 ] === undefined || obj[ $1 ] === false ? "" : obj[ $1 ];
            });
        }
    };


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
        var actionAttr = 'data-t';
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
            if( target == document ) return;
            var action = target.getAttribute( actionAttr );

            if( !action ) return;
            // fire action
            var aData = target.getAttribute( actionDataAttr ) || '';
            var r = (function(){
                var tmp = aData.split('&');
                var tmp2 ;
                var result = {};
                for (var i = tmp.length - 1; i >= 0; i--) {
                    tmp2 = tmp[i].split('=');
                    result [ tmp2[0] ] = tmp2[1];
                };

                return result;
            })();
            _fireAction( action , target , r );
        });
    })();

    // page language
    !!(function(){
        var i18n = '';
        LP.lang = {};
        // use loader to set current language
        LP.loader.config({
            vars: {
                'locale': i18n
            }
            ,preload: ['i18n']
        });
        LP.mix( LP , {
            /*
             * @desc : set current lang of website
             * @param str { string } : the string , which needed to be show
             * @param object { object } : the string replace data
             * @return { string }
             */
            lang: function( str , object ){
                str = LP.lang[ str ];
                return LP.format( str , object );
            }
        } , true );
    })();


    // oo
    !!(function(){
        LP.createClass = function(){
            var a = arguments, l = a.length;

            function F(){
                this.__inited__ = false;

                if (F.superclass){
                    F.superclass.constructor.apply(this, arguments);
                }

                if (!this.__inited__ && this.init && GJ.isFunction(this.init)){
                    var ret = this.init.apply(this, arguments);
                    this.__inited__ = true;
                    if (GJ.isObject(ret)) return ret;
                }
            }

            if (GJ.isFunction(a[0])){
                GJ.extend(F, a[0], a[1] || null, a[2] || null);
            } else {
                if (a[0]){
                    F.prototype = a[0];
                }
                if (a[1]){
                    GJ.mix(F, a[1], true);
                }
            }
            return F;
        }
    })();
})( window );