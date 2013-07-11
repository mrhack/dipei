/**
 * @desc: desc
 * @date:
 * @author: hdg1988@gmail.com
 */
define(function( require , exports , model ){
    var $ = require('jquery');
    var util = require('util');
    var _isFunction = LP.isFunction,
        _isString = LP.isString,
        _isBoolean = LP.isBoolean,
        _isCheckDom = function( $dom ){
            return $dom.length && ($dom[0].type == 'checkbox' || $dom[0].type == 'radio');
        },
        _mix = LP.mix,
        _config = {
            event: 'blur',  // blur , input ,
            tipDom: '',     // required
            focusMsg: '',   // msg if the element is focused
            delay: '',      // delay time to validator

            // validate rule
            requireMsg: '', // required message tip if current value is ''
            maxLength:  '', // maxlength for string input
            maxLengthMsg: '', // maxlength error message for string input
            minLength: '',  // minlength for string input
            minLengthMsg: '', // minlength error message for string input
            lengthMsg: '',   // common length error message
            lengthType: 'word', // word字节或者是字符 byte 字节（一个中文算两个字节）

            ignore: function(){
                //return true || false
            }, // 是否取消验证的条件，用于最后提交的校验
            regexps: [], // [[/regexp/ , 'message']]

            requireCheckNum: 1, // 如果是checkbox的话，该选项表示至少需要勾选多少项

            syncQueue: [], // 异步校验队列
            // example syncQueue = [function(cb){setTimeout(function(){ cb( "error" ) } , 10 )}]
            // 里面的函数接收一个参数：一个回调函数，该函数接收参数如下：
            // 1. { true } --> 校验通过
            // 2. { String } --> 校验失败，同时错误信息为String 或者为默认信息
            callBacks: []  // 校验完成后的回调队列  function(){}
        },
        /*
         * 正则配制
         */
        REGEXP = {
            'telephone': function(value){
                return GZ.isTelephone(value);
            },
            'telephoneOrLandline': function( value ){
                return /^[0-9\-\(\)]+$/.test( value );
            },
            'email': function(value){
                return GZ.isEmail(value);
            },
            'number': function(value){
                return /(^\d+$)|(^\d+\.\d+$)/.test(value);
            }
        },
        Validator = function( name , config ){
            /*
             * 0 未开始
             * 1 正在进行
             * 2 已经完成
             */
            this.status = 0 ;
            this.error = ''; // 出错信息 , 有出错
            if( typeof name == "string" ){
                this.$dom = $('[name="' + name + '"]');
                this.name = name;
            } else {
                this.$dom = $(name);
                this.name = this.$dom.attr('name');
            }
            this.config = _mix( _config , config );

            this.id = LP.guid();

            var t = this , o = this.config;
            // fix config
            t.$tipDom = $(o.tipDom);
            // checkbox or radio will trigger click event
            if( _isCheckDom( t.$dom ) ){
                o.event = 'click';
            }
            switch(o.event){
                case 'input':
                    t.$dom.bind( 'keyup' , function(){
                        t.valid( true );
                    } );
                    break;
                default:
                    t.$dom.bind( o.event , function(){
                        t.valid( true );
                    } );
            }

            t.$dom.focus(function(){
                Validator.focusCallBack( t.$dom , t.$tipDom , o.focusMsg );
            });
        };
    Validator.prototype = {
        /*
         * @focus 是否需要强制刷新为之前的状态
         */
        valid: function( focus ){
            var t = this , o = t.config;
            var syncVal = function(){
                    // create loading
                    var loading = util.createLoading( t.$tipDom , '正在校验...');
                    var syncArr = o.syncQueue || [] , index = 0;
                    (function(){
                        // 如果后面还有异步函数 且 之前的校验还没出错
                        var callee = arguments.callee;
                        if( index < syncArr.length && ( t.error === true || !t.error )){
                            syncArr[index].call(t , function( result ){
                                index ++;
                                t.error = result;
                                callee();
                            });
                        } else {
                            loading.remove();
                            complete( t.error );
                        }
                    })();
                },
                complete = function( error ){
                    if( error === false ){
                        error = '校验失败';
                    } else if( error === true ){
                        error = '';
                    }3
                    t.error = error;
                    t.status = 2;
                    t[ error ? '_validError' : '_validSuccess' ]();
                    t.onComplete && t.onComplete();
                },
                validRunning = function(){
                    var val = ( function(){
                        var dom = t.$dom.get(0) , r = '';
                        if( dom && (dom.type == 'checkbox' || dom.type == 'radio')){
                            t.isCheckValue = true;
                            var tmp = [];
                            t.$dom.each(function(i , item){
                                item.checked ? tmp.push(item.value) : '';
                            });
                            r = tmp.join(',');
                        }else{
                            r = t.$dom.val();
                        }
                        return r;
                    })();
                    // 如果需要取消验证 直接设置成验证完成即可
                    if(_isFunction(o.ignore) ? o.ignore(val) : o.ignore)
                        t.status = 2;

                    // 如果不是必填的，且没有值，则直接成功
                    if( !val && !o.requireMsg && !o.requireCheckNum ){
                        return complete( true );
                    }
                     // validate required
                    if( o.requireMsg && !val ){
                        return complete( o.requireMsg );
                    }
                    // val requireCheckNum
                    if( t.isCheckValue && val.split(',').length < o.requireCheckNum ){
                        return complete( o.requireMsg || ( '必选' + o.requireCheckNum + '个' ) );
                    }
                    // validate string
                    if( o.minLength || o.maxLength){
                        var len = 0;
                        switch(o.lengthType){
                            case 'byte':
                                len = util.length(val);// TODO get length
                                break;
                            case 'word':
                                len = util.length(val , false);
                                break;
                        }

                        if(o.minLength && o.minLength > len){
                            return complete( o.minLengthMsg || o.lengthMsg );
                        }else if(o.maxLength && o.maxLength < len){
                            return complete ( o.maxLengthMsg || o.lengthMsg );
                        }
                    }
                    // validate regexp
                    $.each(o.regexps || [] , function(i , arr){
                        // if regexp is string , it means it is a function of REGEXP , arr[2] default is false
                        if((_isString(arr[0]) ? REGEXP[arr[0]](val) : arr[0].test(val)) === !!arr[2]){
                            complete( arr[1] );
                            return false;
                        }
                    });

                    // validator validatorCallBack
                    if( !t.isComplete() ){
                        $.each(o.callBacks , function(i , fn){
                            var result = fn.call(t , val);
                            if(_isString(result) && result){
                                complete( result );
                                return false;
                            };
                        });
                    }

                    // validate ajax
                    if( !t.isComplete() ){
                        syncVal();
                    } else {
                        complete( true );
                    }
                };
            // if already finished , return
            if( t.isComplete() && !focus ){
                return this;
            }
            // set validator status
            t.status = 1;
            t.error = '';

            if( o.delay ){
                clearTimeout(t.__timer);
                t.__timer = setTimeout( function(){
                    validRunning();
                } , o.delay );
            }else{
                validRunning();
            }
            return this;
        },
        reset : function(){
            this.status   = 0 ;
            this.error    = '' ;
            this.$tipDom.hide();
            return this;
        },
        _validError: function(){
            Validator.failureCallBack(this.$dom , this.$tipDom , this.error);
            return this;
        },
        _validSuccess: function(){
            Validator.successCallBack(this.$dom , this.$tipDom , '');
            return this;
        },
        // 是否正在进行
        isRunning: function(){
            return this.status == 1;
        },
        // 是否已经开始过
        isStarted: function(){
            return this.status != 0;
        },
        // 是否已经完成
        isComplete: function(){
            return this.status == 2;
        },
        isSuccess: function(){
            return !this.error;
        },
        stop: function(){
            clearTimeout(this.__timer);
        },
        // 多少延迟时间进行校验
        setDelay: function( time ){
            this.config.delay = time;
            return this;
        },
        setForm: function( form ){
            this.config.$form = $(form);
            return this;
        },
        // input focus event, show the focus msg
        setFocusMsg: function(msg){
            this.config.focusMsg = msg;
            return this;
        },
        /*
         * @dom 信息提示dom
         */
        setTipDom: function(dom){
            this.config.tipDom = dom;
            this.$tipDom = $(dom);
            return this;
        },
        /*
         * @min 最小长度值
         * @max 最大长度值
         * @msg 当不满足要求时的出错信息
         */
        setLength: function(min , max , msg){
            this.config.maxLength = max;
            this.config.minLength = min;
            this.config.lengthMsg = msg;
            return this;
        },
        /*
         * @type 长度类型
         */
        setLengthType: function(type){
            this.config.lengthType = type;
            return this;
        },
        /*
         * @num 最大长度值
         * @msg 超过最大长度时的出错信息
         */
        setMaxLength: function(num , msg){
            this.config.maxLength = num;
            this.config.maxLengthMsg = msg;
            return this;
        },
        /*
         * @num 最小长度值
         * @msg 超过最小长度时的出错信息
         */
        setMinLength: function(num , msg){
            this.config.minLength = num;
            this.config.minLengthMsg = msg;
            return this;
        },
        /*
         * @msg value当为空时的错误信息
         */
        setRequired: function(msg , checkNum){
            this.config.requireMsg = msg;
            this.config.requireCheckNum = checkNum;
            return this;
        },
        /*
         * @reg 需要进行验证的正则表达式
         * @msg 正则验证为false时返回的错误信息
         * @eqvalue 当正则为true或者false时候的触发，默认是为false
         * 可以同时存在多个正则
         */
        setRegexp: function(reg , msg , eqvalue){
            this.config.regexps.push([reg , msg , eqvalue]);
            return this;
        },
        /*
         * @url: 需要ajax的网络路径
         * @data: 额外参数
         * @cb: ajax回调，其参数为ajax后回来的参数，返回值作为判断是否需要显示的错误信息，如果为true，则表示该条件满足要求，否则返回字符串，表示出错信息
         */
        setAjax: function(url , data , cb){
            var t = this , o = t.config;
            o.syncQueue.push(function( asyncCB ){
                $.ajax({
                    url: url,
                    data: $.extend(_isFunction(data) ? data() :  data, (function(){var d = {}; d[t.name] = t.$dom.val();return d})()),
                    dataType: 'json',
                    timeout: 5000,
                    success: function( r ){
                        asyncCB( cb( r ) );
                    },
                    error: function(){
                        asyncCB(false);
                    }
                });
            });
            return this;
        },
        /*
         * @fn: 设置异步回调的函数，函数的第一个参数是一个回调，返回的为true,则校验成功，返回其它则校验失败
         fn(cb("出错了"));
         */
        addSync: function( fn ){
            this.config.syncQueue.push( fn );
            return this;
        },
        /*
         * @fn: 回调的function，可以设置多个回调函数，根据当前输入框值去校验是否合法，返回的信息为出错信息，例如
         * function(val){
              return val == '1' ? true : '你输入的不是1';
         * }
         */
        addCallBack: function( fn ){ // 设置验证回调
            this.config.callBacks.push( fn );
            return this;
        },
        /*
         * @fn: 设置是否忽略该项的验证
         * function(val){
              return val == '1'; // 如果val是1 则忽略该项的验证
         * }
         */
        setIgnore: function( fn ){
            this.config.ignore = fn;
            return this;
        },

        setComplete: function( fn ){
            this.onComplete = fn;
            return this;
        }
    };
    var FormValidator = function( form ){
        this.validators = [];
        this.form = form;
        this.statues = {completeQueue:[] , errorQueue: [] , isCompleted: true};
    }
    FormValidator.prototype = {
        reset: function(){
            var t = this , st = t.statues;
            st.completeQueue = [];
            st.errorQueue = [];
            st.isCompleted = false;

            $.each(t.validators , function( i , validator){
                if( validator.isComplete() ){
                    st.completeQueue.push(validator);
                    if(!validator.isSuccess()){
                        st.errorQueue.push(validator);
                    }
                }
            });
        },
        valid: function( success , failure ){
            var t = this , st = t.statues;
            if( st.isCompleted === false ) return this;
            t.success = success;
            t.failure = failure;
            t.reset();

            if( st.completeQueue.length === t.validators.length ){
                t.complete();
            }

            t.runValidatorCallBack = true;

            $.each( t.validators , function( i , validator ){
                validator.valid();
            });

            return this;
        },
        // add form element validator instance
        add: function( validator ){
            if( this.form ){
                validator.setForm( this.form );
            }
            this.validators.push( validator );
            var t = this , st = t.statues;
            validator.setComplete(function(){
                if( this.error && st.errorQueue.indexOf( validator ) < 0 ){
                    st.errorQueue.push( validator );
                }
                st.completeQueue.push( validator );
                if( t.runValidatorCallBack
                     && st.completeQueue.length == t.validators.length )
                        t.complete();
            });
            return this;
        },
        complete: function(){
            var t = this , st = t.statues;
            t.runValidatorCallBack = false;
            st.isCompleted = true;
            st.errorQueue.length > 0 ? t.failure && t.failure() : t.success && t.success();

            // show errors
            if( st.errorQueue.length ){
                var $errors = $();
                $.each( st.errorQueue , function( i ,  val ){
                    $errors = $errors.add( val.$dom[0] );
                });
                util.error( $errors );
            }
        }
    };

    // 验证完成后的回调
    Validator.successCallBack = function($dom , $tip , msg){
        $tip.html('<span class="v-right"><i class="i-icon i-v-right"></i>&nbsp;</span>');
    }
    Validator.focusCallBack = function($dom , $tip , msg){
        $tip.html('<span class="v-msg">' + msg + '</span>');
    }
    Validator.failureCallBack = function($dom , $tip , msg){
        $tip.html('<span class="v-error"><i class="i-icon i-v-error"></i>' + msg + '</span>');
    }


    // export
    exports.formValidator = function(){
        return new FormValidator();
    }
    exports.validator = function(name , cfg){
        return new Validator(name , cfg);
    }
    exports.setValidatorConfig = function( o ){
        _mix( Validator , o , true);
    }
});