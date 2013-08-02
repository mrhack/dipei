module.exports = function( grunt ) {
  var path = require('path');
  var tool = require('../grunt-tools/helper');
  var script = require('../grunt-tools/script').init(grunt);

  var error = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }

  grunt.registerTask('cmd-transport' , 'cmd-transport.' , function(){
    var opts = this.options();
    var jsDir = opts.SRC_DIR + '/js';
    var trsDir = opts.TRANSPORT_DIR + '/js';
    var options = this.options({
      idleading: '',
      alias: {},
      // create a debug file or not
      debug: false,
      // process a template or not
      process: false,
      // for handlebars
      handlebars: {
        knownHelpers: [],
        knownHelpersOnly: false
      },
      paths: [],

      // output beautifier
      uglify: {
        beautify: false,
        comments: false
      }
    });

    var modifieds = grunt._tmps.modifieds;
    modifieds.forEach( function( f ){
      if( path.extname( f ) == '.js' )
        script.jsParser( opts.SRC_DIR + '/' + f , opts.TRANSPORT_DIR + '/' + f ,
          f , options );
    })

  });
};
