LP.use(['jquery','datepicker'] , function( $ ){
    $(".j-datepicker" ).datepicker({
        showOn: 'click',
        minDate: '2013-06-14',
        onSelect: function( date , context ){
            $(context.input).find('.input-val')
                .html( date );
        }
    });
});