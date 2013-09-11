LP.use(['jquery' , 'util'/*,'datepicker'*/] , function( $ , util ){
    /*
    $(".j-datepicker" ).datepicker({
        showOn: 'click',
        minDate: '2013-06-14',
        onSelect: function( date , context ){
            $(context.input).find('.input-val')
                .html( date );
        }
    });

    */

    // show lepei type
    $('.J_dropdown .dropdown-menu').on('click' , 'li a' , function(){
        $(this)
            .closest('.J_dropdown')
            .find('.input-val')
            .html( $(this).text() )
            .end()
            .find('input[type="hidden"]')
            .val( $(this).data('value') );
    });

    $('.search-body')
        .submit(function(){
            var data = LP.query2json( $(this).serialize() );
            if( !data.lid ){
                util.error($('input[name="lid"]').closest('.input-widget'));
                return false;
            }
            window.location.href = '/loc/' + data.lid + '/?type=' + (data.type || '');
            return false;
        });


});