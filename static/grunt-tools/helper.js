
var fs = require('fs');


exports.loopdir = function( dir , process ){
    var callee = arguments.callee;
    var files = fs.readdirSync( dir );
    files.forEach(function( f , i ){
        var path = dir + '/' + f;
        var stat = fs.statSync( path );
        if( stat.isFile() ){
            process && process( path , stat );
        } else {
            callee( path , process );
        }
    });
}
exports.cleanPath = function( path ){
    var ps = path.split(/[\/\\]+/);
    var paths = [];
    ps.forEach( function( p ){
        switch( p ){
            case '..':
                // if no path prev and prev is '..' , push it in array
                !paths.length || paths[ paths.length - 1 ] == '..' ?
                paths.push( p ) : paths.pop();
                break;
            case '.':
            case '':
                break;
            default:
                paths.push( p );
        }
    });

    return paths.join('/');
}
exports.getRelPath = function( path1 , path2 ){
    path1 = this.cleanPath( path1 );
    path2 = this.cleanPath( path2 );

    var r = [];
    var isSame = true;
    path1 = path1.split('/');
    path2 = path2.split('/');

    path2.forEach(function( p , index ){
        if( isSame && p == path1[index] ){
            return;
        }
        isSame = false;
        r.push('..');
    });

    r = r.concat( path1.slice( path2.length - r.length ) );

    return r.join( '/' );
}

exports.seperateJsAndCss = function( strs ){

    var result = {css:[],js:[]};
    strs.split(',').forEach( function( v ){
        v = v.replace(/(^\s+)|(\s+$)/ , '');
        if( !v ) return;
        var arr = result[v.match(/\.css$/) ? 'css' : 'js' ];
        if( arr.indexOf( v ) < 0 )
            arr.push(v);
    });

    return result;
}


exports.getFileKey = function( file ){
    var file = this.cleanPath( file );
    return file.replace(/^(public\/)|(src\/)/ , '');
}