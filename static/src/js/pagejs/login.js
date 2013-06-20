/*
 * ajax login model
 */
LP.use(['util','../com/password'] , function( util , password ){

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
    $loginWrap.find('.login form')
        .submit(function(){
            var data = $(this).serialize();
            LP.ajax('login' , data , function(){
                alert(1);
            });
            return false;
        });

    // sign up action
    var $regForm = $loginWrap.find('.register form')
        .submit(function(){
            var data = $(this).serialize();
            LP.ajax('reg' , data , function(){
                alert(1);
            });
            return false;
        });

    password.strength( $regForm.find('[name="password"]') , function( score ){
        score *= 10;
        $regForm.find('.pw-strength span')
            .removeClass('s')
            .filter(function(index){
                return index < score;
            })
            .addClass('s');
    });
});