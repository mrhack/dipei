<?php
/**
 * @desc: desc
 * @date:
 * @author: hdg1988@gmail.com
 * useage : php xxx.php dir tarfile
 */
require_once "../script/common.php";

$dir = $argv[1];
$tar = $argv[2];

if( empty( $dir ) ){
    $dir = '../tmp/16';
}

if( empty( $tar ) ){
    $tar = '../src/image/country.png';
}

$url = "http://xianlvke.com/ajax/countrySearch/k/";


// $config
$lineNum = 15;

$config = array(
    "width"     => 14,
    "height"    => 11,
    "top"       => 2,
    "left"      => 1
    );
// count the file num
$num = count( glob( $dir . "/*.png") );

$tarImg = imagecreatetruecolor( $lineNum * $config["width"] , $config["height"] * ceil( $num / $lineNum ) );
$black = imagecolorallocate($tarImg, 0, 0, 0);
imagecolortransparent( $tarImg , $black );


function curl( $url ){
    // 初始化一个 cURL 对象
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_COOKIE , "lang=en;" );

    // 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_URL, $url );

    // 设置header
    curl_setopt($curl, CURLOPT_HEADER, 0);

    // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // 运行cURL，请求网页
    $data = curl_exec($curl);

    // 关闭URL请求
    curl_close($curl);

    // 显示获得的数据
    return $data;
}

$result = array(array(),array());

loopdir( $dir , function( $file ) use( $url , $config , $lineNum , &$tarImg , &$result){

    $filename = basename( $file , ".png" );
    $filename = str_replace("-", " ", $filename );
    
    $url .= rawurlencode($filename);

    $r = json_decode(curl( $url ) , true );
    if( isset( $r['data'][0] ) ){
        //
        $srcImg = imagecreatefrompng( $file );
        echo " combine image `" . $file . "`\n";
        $index = count( $result[0] );
        $left = $index % $lineNum * $config["width"];
        $top = floor( $index / $lineNum ) * $config["height"];
        imagecopyresampled( $tarImg , $srcImg,
            $left, $top, $config["left"], $config["top"],
            $config["width"], $config["height"], $config["width"], $config["height"] );

        $id = $r['data'][0]["id"];
        echo "     get country ` $filename ` id = $id \n";
        $result[0][ $id ] = ".i-$id{background-position: -${left}px -${top}px;}";
    } else {
        array_push( $result[1] , $filename );
        echo "` $filename ` is not in database \n";
    }
});

echo "---- success total country" . count( $result[0] );
imagepng ($tarImg, $tar);
// echo css file
ksort( $result[0] );
file_put_contents( $tar . ".css" , join( "\n" , $result[0] )) ;