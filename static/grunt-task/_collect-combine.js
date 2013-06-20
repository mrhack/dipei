module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var error = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }

  grunt.registerTask('collect-combine-config' , 'Collect combine config from template file.' , function(){
    var baseTpl = 'base/frame.twig';
    var opts = this.options();

    var combineConfig = {};

    var _tplCache = {};
    var readTpl = function( tpl ){
        if( _tplCache[ tpl ] )
            return _tplCache[ tpl ];
        var content = grunt.file.read( opts.TPL_DIR + '/' + tpl );
        _tplCache[ tpl ] = content;

        return content;
    }
    var collectTemplateHeadResource = function( tpl ){
        var result = [];
        var content = readTpl( tpl );

        content.replace( /\{\{\s*sta\s*\(\s*([\'"])([^\'"]+)\1/g , function( ){
            result.push( arguments[2] ) ;
        });

        if( tpl != baseTpl ){
            return arguments.callee( baseTpl );
        }
        return result.join(',');
    }

    var collectTemplatePageResource = function( key , tpl ){

        var content = readTpl( tpl );
        var stas = [];
        var callee = arguments.callee;
        content.replace(/\{\{\s*require\s*\(\s*([\'"])([^\'"]+)\1/g , function( ){
            stas.push( arguments[2] );
        });

        content.replace(/\{%\s*include\s+([\'"])([^"\']+)\1\s*%\}/g , function( ){
            stas = stas.concat( callee( key , arguments[2] ) );
        });
        return stas.join(',');
    }
    tool.loopdir( opts.TPL_DIR , function( f , stat ){
        // get file content
        var content = grunt.file.read( f );
        // if is a page template
        if( content.indexOf( 'base/frame.twig' ) >= 0 ){
            var tpl = f.replace( opts.TPL_DIR + '/' , '' );
            combineConfig [ tpl ] = {};
            // collect head css and js
            var headRes = tool.seperateJsAndCss( collectTemplateHeadResource( tpl ) );
            if( headRes['css'].length > 1 )
                combineConfig [ tpl ]['headcss'] = headRes['css'];
            if( headRes['js'].length > 1 )
                combineConfig [ tpl ]['headjs'] = headRes['js'];
            // collect page css and js
            // collect the css file , css file is like follows
            var pageRes = tool.seperateJsAndCss( collectTemplatePageResource( tpl , tpl ) );
            if( pageRes['css'].length > 1 )
                combineConfig [ tpl ]['pagecss'] = pageRes['css'];
            if( pageRes['js'].length > 1 )
                combineConfig [ tpl ]['pagejs'] = pageRes['js'];
        }

    } );
    grunt._tmps = grunt._tmps || {};
    grunt._tmps.combines = combineConfig;
    // write combine files
    // fs.writeFileSync( opts.COMBINE_FILE  , JSON.stringify( combineConfig ) , {encoding: 'utf-8'} );
  });
};


//1. collect modified files
//2. refresh relative files
//3. update all configs