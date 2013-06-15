<?php
/**
 * User: wangfeng
 * Date: 13-6-15
 * Time: 上午11:58
 */
require_once 'Bootstrap.php';

$mapPath = 'datas/money_symbol_desc.json';

function fetchCurrencies()
{
    global $mapPath;
    $str = file_get_contents('http://www.x-rates.com/themes/bootstrap/t_js/output.6.js');
    if (preg_match('/currenciesArray\s*=\s*([^\]]+])/ui', $str, $match)) {
        $rawCurrencies = json_decode($match[1], true);
        $currencies = array();
        foreach ($rawCurrencies as $k => $currency) {
            $arr = explode(',', $currency);
            $currencies[$arr[0]] = $arr[1];
        }
        file_put_contents($mapPath, json_encode($currencies));
        getLogger(__FILE__)->info("fetch currencies from $str",$currencies);
    } else {
        getLogger(__FILE__)->error("can not fetch currencies from $str");
        exit(1);
    }
}

//fetchCurrencies();
if (!file_exists($mapPath)) {
    fetchCurrencies();
}
$map = array_flip(json_decode(file_get_contents($mapPath), true));
$retry=0;
while($retry<3){
    $url='http://www.x-rates.com/table/?from=EUR&amount=1.00';
    $resource = file_get_contents($url);
    if(empty($resource)){
        getLogger(__FILE__)->warn("retry fetch rate $url $retry");
       $retry++;
    }else{
        getLogger(__FILE__)->info("fetch rate $url success", array('content'=>$resource));
        break;
    }
}
if(empty($resource)){
    getLogger(__FILE__)->error("unalbe to fetch rate");
    exit(1);
}
preg_match_all(<<<EXP
/<table[^>]+?
      class\s*=\s*['"].*?ratesTable.*?['"][^>]+>
      ([\s\S]+?)
      <\/table>/x
EXP
    , $resource, $rateTableMatches
);
if (empty($rateTableMatches)) {
    getLogger(__FILE__)->error('parse rate table failed');
    exit(1);
}

$rates=array();
foreach ($rateTableMatches[1] as $rateTableMatch) {
    preg_match_all(<<<EXP
/<tr>\s*
            <td[^>]*>([\w\s]+)<\/td>\s*
            <td[^>]*>\s*<a[^>]*>([\.\d]+)\s*<\/a>\s*<\/td>
            [\s\S]+?
            <\/tr>/x
EXP
        , $rateTableMatch, $rateMatches
    );
    if(empty($rateMatches)){
        getLogger(__FILE__)->error('parse rate entry failed', $rateTableMatches);
        exit(1);
    }
    for($i=0;$i<count($rateMatches[0]);$i++){
        $rates[$map[$rateMatches[1][$i]]] = $rateMatches[2][$i];
    }
}
getLogger(__FILE__)->info('fetch rate success', $rates);
//TODO save rate to config or db
//$moneyMap = json_decode(file_get_contents('money.json'),true);
//$passCnt=0;
//$unPassCnt=0;
//foreach($map as $k=>$v){
//    if(!isset($moneyMap[$k])){
//        $unPassCnt++;
//        echo "warning: $k not found\n";
//        var_dump($map[$k]);
//    }else{
//        $passCnt++;
//    }
//}
//echo "pass $passCnt unpass $unPassCnt";

