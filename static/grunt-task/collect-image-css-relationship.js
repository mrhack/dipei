module.exports = function( grunt ) {
  var tool = require('../grunt-tools/helper');

  var error = function( error ){
    grunt.log.error( msg );
    grunt.verbose.error( error );
  }

  grunt.registerTask('collect-image-css-relationship' , 'collect-image-css-relationship.' , function(){
    var opts = this.options();

    var relationShip = JSON.parse( grunt.file.read( opts.IMG_CSS_RELATIONSHIP_FILE ) );

    // get share data from grunt
    var modifieds = grunt._tmps && grunt._tmps.modifieds || [];
    var newRelation = {};
    modifieds.forEach(function( file ){
        if( /\.css$/.exec( file ) ){ // if css file
            var path = opts.SRC_DIR + '/' + file;
            var filePath = file.replace(/[^/]+$/ , '');
            var content = grunt.file.read( path );
            content.replace(/url\s*\(\s*([\'"]?)([^)\'"]+?)(\?[^)\'"]*)?\1\s*\)/g , function(){
                newRelation[ file ] = newRelation[ file ] || [];
                var img = tool.cleanPath( filePath + grunt.util._.trim( arguments[2] ) );
                newRelation[ file ].push( img );
            });

            if( newRelation[ file ] ){
                relationShip[ file ] = newRelation[ file ];
            }
        }
    });

    // save relationship config file
    grunt.file.write( opts.IMG_CSS_RELATIONSHIP_FILE  , JSON.stringify( relationShip ) );
  });
};


//1. collect modified files
//2. refresh relative files
//3. update all configs