/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use(['jquery' , 'validator' , 'util' ] , function( $ , valid , util ){
    // add language
    $('#J_add-lang')
        .click( function(){
            $( this ).prev()
                .clone()
                .insertBefore( this );
        } );

    // for step1
    // validator for auth step1
    if( $('#J_lp-form').length ){
        var $sug = $('#J_loc-sug');
        $sug.blur(function(){
            var dataName = $(this).data('name');
            if( dataName && dataName != this.value ){
                $('input[name="lid"]').val('');
            }
        });
        util.searchLoc($sug , function( data ){
            $sug.val( data.name ).data( 'name' , data.name );
            $('input[name="lid"]').val( data.id );
        });

        var $concats = $('.contact input');
        var val1 = valid.formValidator()
            .add(
                valid.validator( 'locname' )
                    .setRequired( _e('所在位置必填') )
                    .setTipDom('#J_locname-tip')
                    .addAsync(function( val , cb ){
                        setTimeout(function(){
                            if( !$('[name="lid"]').val() ){
                                // set valid ajax , if location name is ok
                                LP.ajax('hasloc' , {n: val} , function(r){
                                    if( !r.data.exist ){
                                        cb( _e('找不到你填写的城市') );
                                    } else {
                                        cb();
                                    }
                                });
                            } else {
                                cb();
                            }
                        } , 500 );
                    })
                )
            .add(
                valid.validator( 'lepei_type' )
                    .setTipDom('#J_lepei_type-tip')
                    .setRequired( _e('请选择小鲜类型') )
                )
            .add(
                valid.validator('desc')
                    .setTipDom('#J_desc-tip')
                    .setRequired( _e('请填写个人介绍') )
                    .setLength( 10 , 100 , _e('个人介绍必须大于10个字，小于100个字') )
                )
            // concat
            .add(
                valid.validator( $concats.eq(0) )
                    .setTipDom('#J_tel-tip')
                    .setRequired( _e('电话必填') )
                    .setRegexp(/[+0-9()]+/ , _e('请输入正确的电话号码'))
                )
            .add(
                valid.validator($concats.eq(3))
                    .setTipDom('#J_email-tip')
                    .setRequired( _e('电子邮箱必填') )
                    .setRegexp('email' , _e('请输入正确的电子邮箱'))
                )
            .add(
                valid.validator( 'agreement' )
                    .setTipDom('#J_agreement-tip')
                    .setRequired( _e('请同意小鲜服务条款') )
                );

        // btn click
        var $lpForm = $('#J_lp-form').submit(function(){
            val1.valid(function(){

                // get lang data
                var lang = {};
                $('.J_lang').each(function(){
                    var $sels = $(this).find('select');
                    lang[ $sels.eq(0).val() ] = $sels.eq(1).val();
                });
                // get desc
                var data = LP.query2json( $lpForm.serialize() );
                data.langs = lang;
                LP.ajax('auth' , data , function(){
                    window.location.href = window.location.href.replace(/#.*/ , '');
                });
            });
            return false;
        });
    } else if( $('#J_p-form').length ){
        $('#J_p-form').data( 'submit' , function( data ){
            LP.ajax('auth' , data , function(){
                window.location.href = window.location.href.replace(/#.*/ , '');
            });
        });
    }
 });
