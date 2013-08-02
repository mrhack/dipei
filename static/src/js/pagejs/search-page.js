/**
 * @desc: page js for search index page
 * @date:
 * @author: hdg1988@gmail.com
 */
LP.use('jquery' , function(){
    $('#J_search-metas')
        .on('click' , 'div[data-name]' , function(){
            $(this).find('.i-icon')
                .toggleClass('i-checked')

            // refresh the page
            var param = LP.query2json( location.href );
            var r = {lid: param.lid};
            // collect page parameters
            $('#J_search-metas').find('[data-name]')
                .each(function(){
                    var name = $(this).data('name');
                    var value = $(this).data('value');
                    if( $(this).find('.i-checked').length ){
                        r[ name ] = r[ name ] || [];
                        r[ name ].push( value );
                    }
                });

            var obj = LP.parseUrl();
            window.location.href = obj.protocol 
                + '://'
                + obj.host 
                + obj.path 
                + '?'
                + LP.json2query( r );
        });
});