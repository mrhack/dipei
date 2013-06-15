/*
 * 瓜子注册页面的密码组件，包括功能是密码强度校验，确认密码检测
 * 特点：<br />
 * @module password
 * @file com/password.js
 * @requires jquery
 * @author huangdegang@ganji.com
 * @version 2011/10/8
 *
 */
define(function( require , exports , model ){
    var $ = require('jquery');
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

    exports.strength = function( $input , cb ){
        $input.bind('keyup' , function(){
            cb && cb( _score( this.value ) );
        });
    }
});