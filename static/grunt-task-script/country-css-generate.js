module.exports = function( grunt ) {
  var fs = require('fs');
  var tool = require('../grunt-tools/helper');
  var path = require('path');
  grunt.registerTask('country-css-generate' , '_country-css-generate.' , function(){
    var opts = this.options();

    var files = fs.readdirSync( opts.dir + '/32' );
    // [14 X 11]
    var line = 15;
    var cWidth = opts.width * line;
    var cHeight = opts.height * Math.ceil( files.length / line );
    var Canvas = require('canvas')
      , canvas = new Canvas( cWidth , cHeight )
      , ctx = canvas.getContext('2d');

    var index = 0;
    var css = [];
    tool.loopdir(opts.dir , function( file ){
      // get file name
      var name = path.basename( file , path.extname( file ) );
      name = name.replace('-' , ' ');
      // query id
      var id = 000;
      // draw canvas and save background position
      ctx.drawImage(  )
      var cIndex = index++;
      fs.readFileSync( file , function(err, squid){
        if (err) throw err;
        var img = new Image;
        img.src = squid;

        var x = cIndex % line * opts.width;
        var y = Math.floor( cIndex / line ) * opts.height;
        ctx.drawImage(img, x ,y, opts.width, opts.height , opts.left , opts.top , opts.width , opts.height );

        css.push(".i-" + id + "{background-position:" + (-x) + "px" + " -" + y + "px;");
        // output the image
        if( cIndex == files.length - 1 ){
            console.log('<img src="' + canvas.toDataURL() + '" />');
            console.log( css.join('\n') );
        }
      });
    });
  });

  /**
   * @desc: generate country name with mongodb id
   * @date:
   * @author: hdg1988@gmail.com
   */

  grunt.registerTask('country-idname-generate' , 'country-idname-generate' , function(){
    var done = this.async();
    var opts = this.options();
    var files = fs.readdirSync( opts.dir );


    var http = require('http');

    var index = 0;
    tool.loopdir( opts.dir , function( file ){
      // get file name
      var name = path.basename( file , path.extname( file ) );
      name = name.replace('-' , ' ');
      // query id
      index++;
      console.log( "search loc :: " + name );
      var req = http.get( "http://www.lepei.cc/ajax/countrySearch/k/" + encodeURI( name ) , function(res) {
        res.setEncoding('utf8');
        res.on('data', function (chunk) {

          console.log( chunk );
          var result = JSON.parse( chunk ).data[0];

          if( result ){
            console.log( result.id + " : : " + name );
            // move file
            grunt.file.copy(file, opts.tarDir + "/" + result.id + path.extname( file ) );
          }
          if( index == files.length - 1 )
            done();
        });
      });
    });
  });
};