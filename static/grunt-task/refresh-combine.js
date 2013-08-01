module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var PATH = require('path');

  var UglifyJS = require("uglify-js");
  //var compiler = require("closurecompiler");
  var cssmin = require('sqwish');

  var failed = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }

  var baseTpl = 'base/frame.twig';


  grunt.registerTask('refresh-combine' , 'refresh-public.' , function(){
    var baseTpl = 'base/frame.twig';
    var opts = this.options();

    var combines = {};

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
        content.replace(/\{%\s*include\s+([\'"])([^"\']+)\1/g , function( ){
            stas = stas.concat( callee( key , arguments[2] ) );
        });
        return stas.join(',');
    }

    var staTypes = ['headcss' , 'headjs' , 'pagecss' , 'pagejs'];
    var getCombineFiles = function( combines ){
      var fname = '';
      var files = {};
      for (var tpl in combines ) {
        staTypes.forEach( function( v ){
          if( !combines[ tpl ][ v ] ) return;
          fname = combines[ tpl ][ v ].join(',').replace(/\/+/g , opts.REPLACE_CAHR);
          files[ fname ] = combines[ tpl ][ v ];
        });
      }
      return files;
    }


    // combine file from public dir
    var refreshedCombines = {};
    var combine = function( files , name ){
      if( refreshedCombines[ name ] ) return;
      refreshedCombines[ name ] = 1;
      var contents = [];
      var isCss = name.match(/\.css$/);
      files.forEach(function( file ){

        var filepath = opts.PUB_DIR + '/' + ( isCss ? 'css/' : 'js/' )  + file;

        // get combine map
        var fileKey = tool.getFileKey( filepath );
        filepath = opts.PUB_DIR + '/' + ( opts.combineMap && opts.combineMap[fileKey] || fileKey );

        if( !grunt.file.exists( filepath ) ){
          grunt.log.error('== file `' + filepath + '` not exists!');
          return;
        }
        var con = grunt.file.read( filepath )
        if( isCss ){
          // fix image path
          var fp = PATH.dirname(filepath);
          con = con.replace(/url\s*\(\s*([\'"]?)([^)\'"]+?)(\?[^)\'"]*)?\1\s*\)/g , function(){
            var img = tool.cleanPath( fp + '/' + grunt.util._.trim( arguments[2] ) );
            img = tool.getRelPath( img , opts.COMBINE_DIR );
            return ['url(' , img , arguments[3] || '' , ')'].join('');
          });
        }
        contents.push( con );
      });

      // write combine file
      grunt.log.writeln('== combine file `' + name + '`' );
      grunt.file.write( opts.COMBINE_DIR + '/' + name , contents.join('\n') );
    }

    tool.loopdir( opts.TPL_DIR , function( f , stat ){
        // get file content
        var content = grunt.file.read( f );
        // if is a page template
        if( content.indexOf( 'base/frame.twig' ) >= 0 ){
            var tpl = f.replace( opts.TPL_DIR + '/' , '' );
            combines [ tpl ] = {};
            // collect head css and js
            var headRes = tool.seperateJsAndCss( collectTemplateHeadResource( tpl ) );
            if( headRes['css'].length > 1 )
                combines [ tpl ]['headcss'] = headRes['css'];
            if( headRes['js'].length > 1 )
                combines [ tpl ]['headjs'] = headRes['js'];
            // collect page css and js
            // collect the css file , css file is like follows
            var pageRes = tool.seperateJsAndCss( collectTemplatePageResource( tpl , tpl ) );
            if( pageRes['css'].length > 1 )
                combines [ tpl ]['pagecss'] = pageRes['css'];
            if( pageRes['js'].length > 1 )
                combines [ tpl ]['pagejs'] = pageRes['js'];
        }

    } );

    var oldCombines = grunt.file.readJSON( opts.COMBINE_FILE );

    var nCombines = getCombineFiles( combines );
    var oCombines = getCombineFiles( oldCombines );
    for ( var fname in oCombines ) {
      if( !nCombines[ fname ] ){
        grunt.log.writeln("== remove combine file `" + fname + '`')
        grunt.file.delete( opts.COMBINE_DIR + '/' + fname );
      }
    };


    var modifieds = grunt._tmps.modifieds;

    for ( var fname in nCombines ) {
      if( !oCombines[ fname ] ){
        // build combine file
        combine( nCombines[ fname ] , fname );
      } else {
        var isCss = fname.match(/\.css/);
        var path = isCss ? 'css/' : 'js/';

        for ( var i = 0 ; i < nCombines[ fname ].length ; i ++  ) {
          if( modifieds.indexOf( path + nCombines[ fname ][i] ) >= 0 ){
            combine( nCombines[ fname ] , fname );
            break;
          }
        };
      }
    }

    // write combine config file
    grunt.file.write( opts.COMBINE_FILE  , JSON.stringify( combines ) );

  });
};
