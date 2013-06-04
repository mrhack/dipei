/***********************************************************
 * base js for application
 * every page in this application will load this javascipt file
 * So , once you want to do something in all pages , you would
 * add code here.
 *
 * author  : hdg1988@gmail.com
 * version : 1.0
 ***********************************************************/

!!(function( host ){
    // save host global var LP
    if( host.LP )
        host._LP = host.LP;

    var __Cache = {};

    var LP = host.LP = {
        mix: function( ){
            var o = {};
            for ( var i = 0 , len = arguments.length ; i < len ; i++ ) {
                for( var k in arguments[ i ] ){
                    o[ k ] = arguments[ i ][ k ];
                }
            };
            return o;
        }
    };


    // page var operation , include set and get
    __Cache['pageVar'] = {};
    LP.mix( LP , {
        // page var
        // @varObj {object}
        setPageVar: function( varObj ){
            __Cache.pageVar = LP.mix( __Cache.pageVar , varObj );
        }
        // get page var
        // @key {string}
        ,getPageVar: function( key ){
            return __Cache.pageVar[ key ];
        }
    });


    //



    // page base action
    __Cache['actions'] = {};

        _addAction  = function(type , fn){
            _actions[type] = fn;
        },
        _needActiveAction = [
        /*'add-follow-user' ,'f-user','un-f-user', 'un-follow-user' , 'add-f-u' , 'un-f-u' , 'del-fans' ,*/
        'add-follow-loc' , 'un-follow-loc' , 'forward' ,
            /*'del-feed' , 'del-feed-detail' ,'del-fav' , 'del-fav-del' , 'del-reply' , 'send-msg' , 'get-badge' , 'send-msg-to-none' , */
            'edit-loc-desc' , 'add-block' , 'del-block' , 'buss-correction'],
        _needLoginAction = _needActiveAction,
        // action fire
        _fireAction = function(type , dom , argsObj){
            var fn = _actions[type],
                // data call back
                callback = $(dom).data('actionCallBack');
            if(!fn) return;
            return fn.apply(dom , [argsObj , callback]);
       };

    // fix ie for before document.body is loaded
    GJ.use('gzCmbBase' , function(){
        GJ.waiter(function(){
            return !!document.body;
        } , function(){
            $(document.body).delegate('[action-type]' , 'click' , function(ev){
                var data = $(this).attr('action-data'),
                    type = $(this).attr('action-type'),
                    actionData = $(this).data('actionData'),
                    needLogin = $.inArray(type , _needLoginAction) >=0 && !GZ.trigger('login');
                // test if user is logined
                if(needLogin){
                    return;
                }
                // test state of active
                if(!($.inArray(type , _needActiveAction) >=0 && !GZ.trigger('active'))){
                    if(_fireAction(type ,this, GJ.mix(GZ.queryToJson(data) , actionData , true)) === false){return false}
                }
                // prevent default action
                ev.preventDefault();
            });
        }, 50);
    });
})( window );