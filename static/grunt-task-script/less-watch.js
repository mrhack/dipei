module.exports = function( grunt ) {
    var cp = require('child_process');
    grunt.registerTask('watch' , 'less watch task running...' , function(){
        var ls = cp.exec('compass watch . -c config.rb',  [''] , {
            cwd: '../src'
        } , function( err , suc ){
            console.log( err );
        });
    });
}