/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use(['jquery' , 'validator' , 'autoComplete'] , function( $ , valid , auto ){
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
        auto.autoComplete($sug , {
            availableCssPath: 'li'
            , renderData: function(data){
                var aHtml = ['<ul>'];
                var num = 10;
                var key =  this.key;
                $.each( data || [] , function( i , v ){
                    if( i == num ) return false;
                    aHtml.push('<li lid="' + v.id + '">' +
                        [ v.name.replce(key , '<span style="color:#058f31;">' + key + '</span>') ,
                        '<span class="c999">' + v.parentName + '</span>' ].join(' , ') +
                        '</li>');
                } );

                aHtml.push('</ul>');
                return aHtml.join('');
            }
            , onSelect: function( $dom , data ){
                $sug.val( data.name );
                $('input[name="lid"]').val( data.id );
            }
            // how to get data
            , getData: function(cb){
                var key = this.key;
                LP.ajax( 'locsug' , {k: decodeURIComponent( key )} , function( r ){
                    cb( r.data );
                } );
            }
        });

        var val1 = valid.formValidator()
            .add(
                valid.validator( 'lepei_type' )
                    .setRequired( _e('乐陪类型必填') )
                )
            .add(
                valid.validator('desc')
                    .setRequired( _e('乐陪描述必填') )
                    .setLength( 10 , 100 , _e('乐陪描述必须小于100个字') )
                )
            .add(
                valid.validator( 'agreement' )
                    .setTipDom('#J_agreement-tip')
                    .setRequired( _e('请同意乐陪服务条款') )
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
                // get contact
                var contact = {};
                $('.contact').find('input')
                    .each(function(){
                        contact[ this.name ] = this.value;
                    });
                // get desc
                // get lepei_type
                var data = {};
                data.step = 1;
                data.langs = lang;
                data.contacts = contact;
                $.each( ['lid','lepei_type' , 'desc'] , function( i , v ){
                    data[v] = $('[name="' + v + '"]').val();
                });
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
