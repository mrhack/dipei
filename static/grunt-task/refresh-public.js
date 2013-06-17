module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var path = require('path');

  var UglifyJS = require("uglify-js");
  //var compiler = require("closurecompiler");
  var cssmin = require('sqwish');

  var failed = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }


  grunt.registerTask('refresh-public' , 'refresh-public.' , function(){
    var modifieds = grunt._tmps && grunt._tmps.modifieds || [];
    // get share data
    var opts = this.options();
    var versions = grunt._tmps.versions;
    // file = 'src/css/a.css'pub + '/' + file
    // path = src
    var minifyCss = function( file , tarfile ){
      var content = grunt.file.read( file ) || '';
      var filePath = file.replace(new RegExp('(^' + opts.SRC_DIR + '/)|([^/]+$)' , 'g') , '');
      if( content ){
        content = cssmin.minify( content , true );
        // add image version
        content = content.replace(/url\s*\(\s*([\'"]?)([^)\'"]+?)(\?[^)\'"]*)?\1\s*\)/g , function(){
            var img = tool.cleanPath( filePath + grunt.util._.trim( arguments[2] ) );
            // get image version
            var v = versions[ img ];
            return ['url(' , arguments[2] , '?_=' , v , ')'].join('');
        });
      }
      // write file
      grunt.file.write( tarfile , opts.banner + content );
    }

    var minifyJs = function( srcFile , tarfile ){
      var content = grunt.file.read( srcFile );
      var r = UglifyJS.minify( content , {fromString: true , mangle: false});
      // write file
      grunt.file.write( tarfile , opts.banner + r.code );
    }

    modifieds.forEach(function( f ){
        var srcFile = opts.SRC_DIR + '/' + f;
        var tarFile = opts.PUB_DIR + '/' + f;
        var disFile = opts.TRANSPORT_DIR + '/' + f;
        // if css file
        if( /\.css$/.exec( f ) ){
            minifyCss( srcFile , tarFile );
        } else if( /\.js$/.exec( f ) ){
            // if disFile exist , use this file instend
            minifyJs( grunt.file.exists( disFile ) ? disFile : srcFile , tarFile );
        } else { // only copy
            grunt.file.copy( srcFile , tarFile );
        }
        grunt.log.writeln('== publish File `' + tarFile + '`')
    });

    // remove dist dir
    if( grunt.file.exists( opts.TRANSPORT_DIR ) )
      grunt.file.delete( opts.TRANSPORT_DIR );
  });
};
