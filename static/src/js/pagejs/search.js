LP.use(['jquery'/*,'datepicker'*/] , function( $ ){
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
});