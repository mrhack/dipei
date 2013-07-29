/*
 * ajax login model
 */
LP.use(['util'] , function( util ){

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

    var $lTip = $('#J_l-form-tip');
    var $loginForm = $loginWrap.find('.login form');
    $loginWrap.find('.login form .J-login-btn')
        .click(function(){
            //$lTip.hide().html('');
            var $form = $(this).closest('form');
            var data = LP.query2json( $form.serialize() );
            var err = "";
            var inputName = "";
            if( !data.email ){
                err = _e("请输入邮箱地址或者用户昵称");
                inputName = "email";
            } else if ( !data.password ){
                err = _e("请输入密码");
                inputName = "password";
            }
            if( err ){
                $lTip.show()
                    .html( err );
                util.error( $loginForm.find('input[name="' + inputName + '"]') );
                return false;
            }
            if( !util.isEmail( data.email ) ){
                data.name = data.email;
                delete data.email;
            }
            
            LP.ajax('login' , data , function(){
                location.href = location.href.replace(/#.*$/ , '');
            } , function( msg ){
                $lTip.show()
                    .html( msg );
            });
            return false;
        });

    // sign up action
    var $rTip = $('#J_r-form-tip');
    var $regForm = $loginWrap.find('.register form');
    $loginWrap.find('.register form .J-reg-btn')
        .click(function(){
            var $form = $(this).closest('form');
            var data = LP.query2json( $form.serialize() );
            // valid 
            var err = "";
            var inputName = "";
            if( !data.name ){
                err = _e('请输入昵称');
                inputName = "name";
            } else if( !util.isEmail( data.email ) ) {
                err = _e('请输入邮箱');
                inputName = "email";
            } else if( data.password.length < 6 ){
                err = _e('密码太短了，最少6字符');
                inputName = "password";
            }
            if( err ){
                $rTip.show()
                    .html( err );
                util.error( $regForm.find('input[name="' + inputName + '"]') );
                return false;
            }
            LP.ajax('reg' , data , function( e ){
                location.href = location.href.replace(/#.*$/ , '');
            } , function( msg ){
                $rTip.show()
                    .html( msg );
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