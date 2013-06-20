var cp = require('child_process');


var ls = cp.exec('compass watch . -c config.rb',  [''] , {
    cwd: '../src'
} , function( err , suc ){
    console.log( err );
});

ls.on('message', function(m) {
  console.log('CHILD got message:', m);
});
ls.stdout.on('data' , function( data ){
    console.log( "data: " , data );
});
ls.stderr.on('data' , function( data ){
    console.log( "data: " , data );
})