/*
 * ajax login model
 */
LP.use(['util' , 'validator'] , function( util , val ){

    var $loginWrap = $('#login-register');

    // focus first input element
    $loginWrap.find('form:visible')
        .find('input')
        .eq(0)
        .focus();

    // init click reg author link
    $loginWrap.find('.j-reg')
        .click(function(){
            $loginWrap.find('.tab')
                .eq(1)
                .trigger('click');
            return false;
        });
    // toggle tab
    util.tab( $loginWrap.find('.tab') , function( index ){
        $loginWrap.find('.tab-con')
            .children()
            .hide()
            .eq( index )
            .fadeIn()
            .find('input') // find first input element and focus it
            .eq(0)
            .focus();
    } );

    // login action
    // validator
    val.setValidatorConfig({
        successCallBack: function( $dom , $tip , msg ){
            $tip.hide().html("");
        },
        focusCallBack: function( $dom , $tip , msg ){
            $tip.hide().html("");
        },
        failureCallBack: function( $dom , $tip , msg ){
            var html = $tip.html();
            if( !html || $tip.is(':hidden') ){
                $tip.show().html( msg );
            }
        }
    });
    var $lTip = $('#J_l-tip');
    var $loginForm = $loginWrap.find('.login form');
    var loginValidator = val.formValidator()
        // for email
        .add(
            val.validator( $loginForm.find('input[name="email"]') )
                .setTipDom( $lTip )
                .setRequired( _e("请输入邮箱地址") )
                .setRegexp( 'email' , _e("请输入正确的邮箱地址") )
            )
        // for password
        .add(
            val.validator( $loginForm.find('input[name="password"]') )
                .setTipDom( $lTip )
                .setRequired( _e("请输入密码") )
            );
    $loginWrap.find('.login form .J-login-btn')
        .click(function(){
            $lTip.hide().html('');
            var $form = $(this).closest('form');
            loginValidator.valid(function(){
                var data = LP.url2json( $form.serialize() );

                LP.ajax('login' , data , function(){
                    location.href = location.href.replace(/#.*$/ , '');
                } , function( msg ){
                    $lTip.html( msg ).css('color' , 'red');
                });
            });
            return false;
        });

    // sign up action
    var $rTip = $('#J_r-tip');
    var $regForm = $loginWrap.find('.register form');
    var regValidator = val.formValidator()
        // for name
        .add(
            val.validator( $regForm.find('input[name="name"]') )
                .setTipDom( $rTip )
                .setRequired( _e("请输入昵称") )
                .addSync(function( val , cb ){
                    // TODO check user nick name
                    LP.ajax('validate' , { field:'name',value: val} , function( r ){
                        cb( '' );
                    } , function( r ){
                        cb( r.msg );
                    });
                })
            )
        // for email
        .add(
            val.validator( $regForm.find('input[name="email"]') )
                .setTipDom( $rTip )
                .setRequired( _e("请输入常用的邮箱") )
                .setFocusMsg( _e('用于接收到激活邮件') )
                .setRegexp( 'email' , _e("请输入正确的邮箱") )
                .addSync(function( val , cb ){
                    // TODO check email
                    LP.ajax('validate' , { field:'email',value: val } , function( r ){
                        cb( '' );
                    } , function( r ){
                        cb( r.msg );
                    });
                })
            )
        // for password
        .add(
            val.validator( $regForm.find('input[name="password"]') )
                .setTipDom( $rTip )
                .setFocusMsg( _e('请输入6位以上的密码，区分大小写') )
                .setMinLength( 6 , _e("密码太短了，最少6字符") )
                .setRequired( _e("请输入登录密码") )
            );
    var $regForm = $loginWrap.find('.register form .J-reg-btn')
        .click(function(){
            $rTip.html('');
            var $form = $(this).closest('form');
            regValidator.valid(function(){
                var data = $(this).serialize();
                LP.ajax('reg' , data , function(){
                    location.href = location.href.replace(/#.*$/ , '');
                } , function( msg ){
                    $rTip.html( msg );
                });
            });
            return false;
        });

    util.passwordStrength( $regForm.find('[name="password"]') , function( score ){
        score *= 10;
        $regForm.find('.pw-strength span')
            .removeClass('s')
            .filter(function(index){
                return index < score;
            })
            .addClass('s');
    });
});