/*
 * api model
 */
define(function( require , exports , model ){
    var $ = require('jquery');
    // 公开的API
    // 签名：ajax = function ( api, data, successHandler, errorHandler, completeHandler ) {}
    // 备注：POST操作默认要登录，GET操作默认不需要登录
    //       默认是POST操作

    // 配置
    var _api = {
        // u:   api url
        // m:   api desc

        // 每一个Config可选参数：
        // data      : {} // 需要提交的参数，会与转递进来的值进行mix操作，所以这里可以填一些默认值
        // dataType  : 'json' // 返回的数据类型
        // alertOnError: true // 如果出错了，是否允许弹出错误信息
        // method    : 'POST' // 提交类型
        // needLogin : false  // 是否需要登录，如果需要登录，则会弹出登录框
        // timeout   : 10000  // 超时时间
        // cache     : true   // 是否需要使用jquery的ajax cache功能
        // global    : false  // 是否需要出错时  抛出给外部直到document上
        login        : {u:'/reg/login', m:_e('login') }
        , reg        : {u:'/reg/index', m:_e('sign up') }
    };

    // 内部API
    var _unloginErrorNum = -2;
    var _checkCodeErrorNum = -161;
    var _needRefresh     = {};
    var _ensure          = {};

    function _load ( api , data , success , error , complete ) {
        if ( typeof data == "function" ) {
            return arguments.callee(api, {} , data, success, error);
        }
        if( typeof data == "string" ){
            data = LP.url2json( data );
        }
        var ajaxConfig = _api[api];
        if ( !ajaxConfig ) { return console && console.error( api + ' api is not exists!' ); }

        var method = ajaxConfig.method || "";
        if ( method == "")  {
            var res = /get/i.exec(api);
            method = (res && res.index == 0) ? "GET" : "POST";
        } else {
            method = method.toUpperCase();
        }

        error = error || ajaxConfig.error;
        var doAjax = function () {
            $.ajax({
                  url      : ajaxConfig.u
                , data     : LP.mix( ajaxConfig.data || {} , data )
                , type     : method
                , dataType : ajaxConfig.dataType || 'json'
                , cache    : ajaxConfig.cache || false
                , global   : ajaxConfig.global === undefined ? true : ajaxConfig.global
                , error    : error
                , complete : complete
                , timeout  : ajaxConfig.timeout
                , success: function(e) {
                    if ( e && typeof e == "string" ) {
                        success(e);
                    } else {
                        _callback( e , api , success , error,  ajaxConfig , doAjax );
                    }
                }
            });
        }

        if ( ajaxConfig.needLogin === true || (ajaxConfig.needLogin !== false && method !== "GET") ) {
            // 如果不为GET的话，则默认是要登录的
            _execAfterLogin( doAjax , api );
        } else {
            doAjax();
        }
    }

    // err_no
    // err_info
    function _callback ( result, api, success, error, config , ajaxFn ) {
        if ( !result ) return;
        var isAlertError = config.alertOnError;

        var err_no = result['err_no'];
        if ( err_no != 0 ) {
            if( isAlertError !== false ){
                // 如果是未登录错误，弹出登录框
                if( err_no == _unloginErrorNum ){
                    // TODO ..  show login tempalte
                    require.async('login' , function( exports ){
                        exports.login( ajaxFn );
                    });
                    return;
                }

                LP.error( result['err_info'] || _api[api].m + _e('出错啦，请稍候重试...') );
            }
            error && error( result );
        } else if ( success ) {
            success( result );
        }

        // 用于判断页面是否需要刷新
        if( _needRefresh[api] ) {
            _needRefresh[api] = false;
            // remove url hash
            setTimeout(function() { location.href = location.href.replace(/#.*/,''); } , 1000);
        }
    }

    function _execAfterLogin ( cb , api ) {
        if( !LP.isLogin() ) {
            if ( _api[api].forceNoRefresh != true )
                _needRefresh[api] = true;
            // request login
            request.async('login' , function( exports ){
                exports.login( cb );
            });
        } else if (cb) {
            cb();
        }
    }

    $(document).ajaxError(function(evt, xhr, ajaxOptions, thrownError) {
        try{
            if ( xhr.status == 200 || thrownError.match(/^Invalid JSON/)) {
                LP.alert(_e(' (*´Д｀*) 系统出错了。请反馈给我们。'), 3000);
            } else if ( thrownError !== "" ) {
                // 请求被Canceled的时候，thrownError为空【未验证】。这时候直接忽略。
                LP.alert(_e('发生了未知的网络错误，请稍后重试。'), 3000);
            }
        } catch(e) {};
    });

    // for model
    exports.ajax = _load;
});
