module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var error = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }

  grunt.registerTask('update-modified-with-relationship' , 'update-modified-with-relationship.' , function(){
    var opts = this.options();

    var relationShip = JSON.parse( grunt.file.read( opts.IMG_CSS_RELATIONSHIP_FILE ) );

    // get share data
    var modifieds = grunt._tmps && grunt._tmps.modifieds || [];
    // change fix modifieds
    var tmp = [];

    modifieds.forEach( function( file ){
        if( !/(\.css)|(\.js)$/.exec( file ) ){
            for( var css in relationShip ){
                if( relationShip[css].indexOf( file ) >= 0
                && tmp.indexOf( css ) < 0 ){
                    tmp.push( css );
                }
            }
        }
    });

    tmp.forEach(function( css ){
        if( modifieds.indexOf( css ) < 0 ){
            modifieds.push( css );
        }
    });
    if( tmp.length > 0 ){
        grunt.log.writeln('== mixed modifieds files as follow : `');
        grunt.log.writeln( '  ' + tmp.join('\n  ') );
    }
    // update modifieds
    grunt._tmps.modifieds = modifieds;
  });
};


//1. collect modified files
//2. refresh relative files
//3. update all configs