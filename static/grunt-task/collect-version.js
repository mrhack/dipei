module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var error = function( error ){
    grunt.log.error( error );
    grunt.verbose.error( error );
  }

  grunt.registerTask('version' , 'Collect all static file version.' , function(){
    var opts = this.options();

    // 1. get version form version config file
    try{
        var versions = grunt.file.readJSON( opts.VERSION_FILE );
    } catch ( e ){
        error(' parse version file failed! ');
    }

    var modifieds = [];
    opts.dirs.forEach(function( dir ){
        tool.loopdir( dir , function( f , stat ){
            var key = f.replace(/^src\// , '');
            var v = + stat.mtime / 1000;

            if( !versions[ key ] || versions[ key ] != v ){
                versions[ key ] = v;
                modifieds.push( key );
            }
        } );
    });

    // share data with other tasks
    grunt._tmps = grunt._tmps || {};
    grunt._tmps.modifieds = modifieds;
    grunt._tmps.versions = versions;
    if( modifieds.length ){
        grunt.log.writeln('== modifieds files as follow : `');
        grunt.log.writeln( '  ' + modifieds.join('\n  '));
    }
    // write version files
    grunt.file.write( opts.VERSION_FILE , JSON.stringify( versions ) );
  });
};


//1. collect modified files
//2. refresh relative files
//3. update all configs