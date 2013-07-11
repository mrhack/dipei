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
    var $lTip = $('#J_l-tip');
    $loginWrap.find('.login form .J-login-btn')
        .click(function(){
            $lTip.html('');
            var data = $(this).serialize();
            LP.ajax('login' , data , function(){
                location.href = location.href.replace(/#.*$/ , '');
            } , function( msg ){
                $lTip.html( msg ).css('color' , 'red');
            });
            return false;
        });

    // sign up action
    var $rTip = $('#J_r-tip');
    var $regForm = $loginWrap.find('.register form .J-reg-btn')
        .click(function(){
            $rTip.html('');
            var data = $(this).serialize();
            LP.ajax('reg' , data , function(){
                location.href = location.href.replace(/#.*$/ , '');
            } , function( msg ){
                $rTip.html( msg );
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