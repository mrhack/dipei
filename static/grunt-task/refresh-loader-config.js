module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');
  var path = require('path');

  grunt.registerTask('refresh-loader-config' , 'refresh-loader-config.' , function(){
    var opts = this.options();
    var configFile = opts.LOADER_CONFIG_FILR;
    var loaderDir = opts.loaderDir;
    var realLoaderDir = opts.realLoaderDir;

    var content = grunt.file.read( configFile );
    content = content.replace(/\r\n/g , '');
    content = content.replace(/^.*seajs.config\((.*)\);\s*$/ , '$1');

    content = content.replace(/([^,{"\']+):/g , '"$1":');
    var config = JSON.parse( content );
    // get version
    var v = grunt._tmps && grunt._tmps.versions || {};
    var modifieds = grunt._tmps.modifieds;
    var needRefresh = false;
    ["shim" , "alias"].forEach(function( plugin ){
      if( !config[ plugin ] ) return;
      for ( var id in config[ plugin ] ) {
        var isString = typeof config[ plugin ][ id ] == 'string';
        var f = isString ? config[ plugin ][ id ]
            : config[ plugin ][ id ].src;
        if( f.indexOf('http://') >= 0  )
          continue;
        var m1 = f.match(/([^?#]+)/);
        if( m1 )
          f = m1[0];
        var match = f.match(/(\.js)|(\.css)/);
        if( !match ){
          f = f + '.js';
        }
        var fkey = tool.getFileKey( loaderDir + '/' + f );
        var fpath = tool.getRelPath( loaderDir + '/' + f , realLoaderDir );

        if( !needRefresh && modifieds.indexOf( fkey ) >= 0 ){
          needRefresh = true;
        }

        if( isString ){
          config[ plugin ][ id ] = fpath + '?_=' +  ( v[ fkey ] || '' );
        } else {
          config[ plugin ][ id ].src = fpath + '?_=' + ( v[ fkey ] || '' );
        }
      };
    });

    // add config file to modifieds
    var configFileKey = tool.getFileKey( configFile );

    if( needRefresh && modifieds.indexOf( configFileKey ) < 0 ){
      modifieds.push( configFileKey );
    }
    if( needRefresh || modifieds.indexOf( configFileKey ) >= 0 ){
      grunt.log.writeln( '== refresh model loader config ' );
      grunt.file.write( configFile + '.combine' , 'seajs.config( ' + JSON.stringify( config ) + ');' );
    }
  });
};
