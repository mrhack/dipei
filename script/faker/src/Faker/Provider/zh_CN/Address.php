<?php

namespace Faker\Provider\zh_CN;

class Address extends \Faker\Provider\Address
{
    protected static $cityPrefix = array('North', 'East', 'West', 'South', 'New', 'Lake', 'Port');
    protected static $citySuffix = array('town', 'ton', 'land', 'ville', 'berg', 'burgh', 'borough', 'bury', 'view', 'port', 'mouth', 'stad', 'furt', 'chester', 'mouth', 'fort', 'haven', 'side', 'shire');
    protected static $buildingNumber = array('#####', '####', '###');
    protected static $streetSuffix = array(
        'Alley', 'Avenue', 'Branch', 'Bridge', 'Brook', 'Brooks', 'Burg', 'Burgs', 'Bypass', 'Camp', 'Canyon', 'Cape', 'Causeway', 'Center', 'Centers', 'Circle', 'Circles', 'Cliff', 'Cliffs', 'Club', 'Common', 'Corner', 'Corners', 'Course', 'Court', 'Courts', 'Cove', 'Coves', 'Creek', 'Crescent', 'Crest', 'Crossing', 'Crossroad', 'Curve', 'Dale', 'Dam', 'Divide', 'Drive', 'Drive', 'Drives', 'Estate', 'Estates', 'Expressway', 'Extension', 'Extensions', 'Fall', 'Falls', 'Ferry', 'Field', 'Fields', 'Flat', 'Flats', 'Ford', 'Fords', 'Forest', 'Forge', 'Forges', 'Fork', 'Forks', 'Fort', 'Freeway', 'Garden', 'Gardens', 'Gateway', 'Glen', 'Glens', 'Green', 'Greens', 'Grove', 'Groves', 'Harbor', 'Harbors', 'Haven', 'Heights', 'Highway', 'Hill', 'Hills', 'Hollow', 'Inlet', 'Inlet', 'Island', 'Island', 'Islands', 'Islands', 'Isle', 'Isle', 'Junction', 'Junctions', 'Key', 'Keys', 'Knoll', 'Knolls', 'Lake', 'Lakes', 'Land', 'Landing', 'Lane', 'Light', 'Lights', 'Loaf', 'Lock', 'Locks', 'Locks', 'Lodge', 'Lodge', 'Loop', 'Mall', 'Manor', 'Manors', 'Meadow', 'Meadows', 'Mews', 'Mill', 'Mills', 'Mission', 'Mission', 'Motorway', 'Mount', 'Mountain', 'Mountain', 'Mountains', 'Mountains', 'Neck', 'Orchard', 'Oval', 'Overpass', 'Park', 'Parks', 'Parkway', 'Parkways', 'Pass', 'Passage', 'Path', 'Pike', 'Pine', 'Pines', 'Place', 'Plain', 'Plains', 'Plains', 'Plaza', 'Plaza', 'Point', 'Points', 'Port', 'Port', 'Ports', 'Ports', 'Prairie', 'Prairie', 'Radial', 'Ramp', 'Ranch', 'Rapid', 'Rapids', 'Rest', 'Ridge', 'Ridges', 'River', 'Road', 'Road', 'Roads', 'Roads', 'Route', 'Row', 'Rue', 'Run', 'Shoal', 'Shoals', 'Shore', 'Shores', 'Skyway', 'Spring', 'Springs', 'Springs', 'Spur', 'Spurs', 'Square', 'Square', 'Squares', 'Squares', 'Station', 'Station', 'Stravenue', 'Stravenue', 'Stream', 'Stream', 'Street', 'Street', 'Streets', 'Summit', 'Summit', 'Terrace', 'Throughway', 'Trace', 'Track', 'Trafficway', 'Trail', 'Trail', 'Tunnel', 'Tunnel', 'Turnpike', 'Turnpike', 'Underpass', 'Union', 'Unions', 'Valley', 'Valleys', 'Via', 'Viaduct', 'View', 'Views', 'Village', 'Village', 'Villages', 'Ville', 'Vista', 'Vista', 'Walk', 'Walks', 'Wall', 'Way', 'Ways', 'Well', 'Wells'
    );
    protected static $postcode = array('#####', '#####-####');
    protected static $state = array(
        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'NewHampshire', 'NewJersey', 'NewMexico', 'NewYork', 'NorthCarolina', 'NorthDakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'RhodeIsland', 'SouthCarolina', 'SouthDakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'WestVirginia', 'Wisconsin', 'Wyoming'
    );
    protected static $stateAbbr = array(
        'AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY', 'AE', 'AA', 'AP'
    );
    protected static $city=array(
        "万宁","万象","万隆","三亚","三明","三沙","上海","上饶","东京","东方",
        "东莞","东营","中卫","中山","临夏","临汾","临沂","临沧","临高","丹东",
        "丽水","丽江","乌海","乐东","乐山","九江","云浮","京都","亳州","仙桃",
        "仰光","伊春","伊犁","会安","伦敦","佛山","佬沃","保亭","保定","保山",
        "信阳","儋州","光州","六安","兰州","兴安","内江","冲绳","凉山","利马",
        "剑桥","包头","北京","北海","十堰","南京","南充","南宁","南平","南昌",
        "南迪","南通","南阳","博乐","厦门","台州","台湾","合肥","吉安","吉林",
        "吕梁","吴忠","周口","和田","咸宁","咸阳","哈密","唐山","商丘","商洛",
        "喀什","嘉兴","四平","固原","塔城","大同","大庆","大理","大连","大阪",
        "天水","天津","天门","太原","奇旺","威海","娄底","孝感","孟买","宁德",
        "宁波","安庆","安康","安曼","安阳","安顺","定安","定西","宜宾","宜昌",
        "宜春","宝鸡","宣城","宿州","宿迁","宿雾","尼斯","屯昌","山南","岳阳",
        "崇左","巢湖","巴中","巴斯","巴黎","常州","常德","平凉","平壤","广元",
        "广安","广州","庆州","庆阳","庞贝","廊坊","延安","延边","开封","开罗",
        "张掖","徐州","德宏","德州","德阳","忻州","怀化","怒江","恩施","悉尼",
        "惠州","成都","戛纳","扬州","承德","抚州","抚顺","拉萨","揭阳","文山",
        "文昌","新乡","新余","无锡","日惹","日照","昆明","昌吉","昌江","昌都",
        "昭通","晋中","晋城","普洱","曲靖","曼谷","朔州","朝阳","本溪","札幌",
        "来宾","杭州","松原","林芝","果洛","枣庄","柏林","柳州","株洲","桂林",
        "梅州","梧州","楚雄","榆林","槟城","武威","武汉","毕节","永州","汉中",
        "汉堡","汕头","汕尾","江门","池州","沈阳","沙巴","沧州","河内","河池",
        "河源","泉州","泰安","泰州","泸州","洛桑","洛阳","济南","济宁","济源",
        "海东","海北","海口","海牙","海西","海防","淄博","淮北","淮南","淮安",
        "深圳","清莱","清迈","清远","温州","渭南","湖州","湘潭","湘西","湛江",
        "滁州","滨州","漯河","漳州","潍坊","潜江","潮州","澄迈","澳门","濮阳",
        "烟台","焦作","牛津","玉林","玉树","玉溪","珀斯","珠海","琼中","琼海",
        "甘南","甘孜","甲米","白城","白山","白沙","白银","百色","益阳","盐城",
        "盘锦","眉山","神户","福州","科隆","第戎","米兰","红河","约克","纽约",
        "绍兴","绥化","绵阳","罗马","聊城","肇庆","自贡","舟山","芜湖","芽庄",
        "苏州","苏瓦","茂名","荆州","荆门","莆田","莱芜","菏泽","萍乡","营口",
        "蒲甘","蚌埠","衡水","衡阳","衢州","襄阳","西宁","西安","许昌","贵港",
        "贵阳","贺州","资阳","赣州","赤峰","辽源","辽阳","达州","运城","迪庆",
        "迪拜","通化","通辽","遂宁","遵义","邢台","那曲","邯郸","邵阳","郑州",
        "郴州","鄂州","酒泉","里昂","重庆","金华","金昌","金边","釜山","钦州",
        "铁岭","铜仁","铜川","铜陵","银川","锦州","镇江","长崎","长春","长沙",
        "长治","阜新","阜阳","阳江","阳泉","阿坝","阿里","陇南","陵水","随州",
        "雅典","雅安","青岛","鞍山","韶关","顺化","首尔","香港","马累","马赛",
        "鸡西","鹤壁","鹤岗","鹰潭","黄冈","黄南","黄山","黄石","黑河","黔南",
        "龙岩","暹粒-吴哥窟","七台河","三门峡","五家渠","五指山","亚喀巴","伊斯那","伯尔尼","但尼丁",
        "佳木斯","停泊岛","六盘水","兰卡威","内罗毕","凯恩斯","利雅得","北领地","华盛顿","卑尔根",
        "博卡拉","卢克索","卢加诺","卢塞恩","双鸭山","吉隆坡","名古屋","吐鲁番","哈尔滨","嘉峪关",
        "圣淘沙","埃德夫","基督城","堪培拉","塞班岛","墨尔本","夏威夷","夏慕尼","多伦多","大溪地",
        "太阳城","奥克兰","奥斯陆","威尼斯","安卡拉","巴厘岛","巴拉望","巴斯克","布拉格","平顶山",
        "库尔勒","开普敦","张家口","张家界","惠灵顿","慕尼黑","攀枝花","斋普尔","新加坡","新德里",
        "日内瓦","日喀则","旧金山","普吉岛","景德镇","曼德勒","格拉茨","民丹岛","汉诺威","沙捞越",
        "波士顿","波尔多","洛杉矶","济州岛","海南州","渥太华","温哥华","热浪岛","热那亚","爱丁堡",
        "牡丹江","瓜里尔","瓦莱塔","皇后镇","皮皮岛","石嘴山","石家庄","石河子","神农架","科伦坡",
        "秦皇岛","纳库鲁","维也纳","维罗纳","芝加哥","芭堤雅","苏梅岛","苏黎世","茵莱湖","莫斯科",
        "莱比锡","葫芦岛","蒙巴萨","蒙特勒","西雅图","迈阿密","连云港","都柏林","里斯本","长滩岛",
        "防城港","阿克苏","阿勒泰","阿图什","阿拉善","阿拉尔","阿斯旺","阿格拉","雅加达","马六甲",
        "马尼拉","马德里","马鞍山","驻马店","鹿特丹","黔东南","黔西南","龙目岛","乌兰察布","乌特勒支",
        "乌鲁木齐","亚历山大","伊兹密尔","伊尔比德","佛罗伦萨","克久拉霍","克拉玛依","加尔各答","加德满都","北爱尔兰",
        "华欣七岩","卡尔卡松","呼伦贝尔","呼和浩特","哥本哈根","因特拉肯","图木舒克","圣彼得堡","塞维利亚","墨西哥城",
        "复活节岛","大兴安岭","奥林匹亚","安塔利亚","安波塞利","安特卫普","巴塞罗那","巴彦淖尔","巴西利亚","布达佩斯",
        "布里斯班","布鲁塞尔","曼彻斯特","格拉斯哥","格拉纳达","沙芙豪森","法兰克福","波罗斯岛","特拉维夫","琅勃拉邦",
        "瓦伦西亚","科尔多瓦","红海沿岸","维多利亚","罗凡涅米","罗托鲁瓦","耶路撒冷","胡志明市","苏滋达里","萨尔茨堡",
        "蒙特利尔","西双版纳","西哈努克","赫尔辛基","那不勒斯","鄂尔多斯","采尔马特","锡林郭勒","阿布戴尔","阿德莱德",
        "马丘比丘","马赛马拉","黄金海岸","齐齐哈尔","伊斯坦布尔","加那利群岛","哈尔施塔特","圣托里尼岛","夏威夷群岛","弗拉基米尔",
        "弗里曼特尔","拉斯维加斯","斯德哥尔摩","斯特拉斯堡","比勒陀利亚","米科诺斯岛","约翰内斯堡","茵斯布鲁克","蒙特卡蒂尼","贝尔法斯特",
        "里约热内卢","阿姆斯特丹","雷克亚未克","斯里巴加湾市","马斯特里赫特","布宜诺斯艾利斯","普罗旺斯埃克斯","波罗奔尼萨半岛"
    );

    protected static $streetNameFormats = array(
        '{{firstName}} {{streetSuffix}}',
        '{{lastName}} {{streetSuffix}}'
    );
    protected static $streetAddressFormats = array(
        '{{buildingNumber}} {{streetName}}',
        '{{buildingNumber}} {{streetName}} {{secondaryAddress}}',
    );
    protected static $addressFormats = array(
        "{{streetAddress}}\n{{city}}, {{stateAbbr}} {{postcode}}",
    );
    protected static $secondaryAddressFormats = array('Apt. ###', 'Suite ###');

    public function __construct($generator)
    {
        parent::__construct($generator);
        self::$country = array(
            '法国', '西班牙', '美国', '中国', '意大利', '英国', '德国', '乌克兰', '土耳其', '墨西哥', '马来西亚', '奥地利', '俄罗斯', '加拿大', '香港', '希腊', '波兰', '泰国', '澳门', '葡萄牙', '沙特阿拉伯', '荷兰', '埃及', '克罗地亚', '南非', '匈牙利', '瑞士', '日本', '新加坡', '爱尔兰共和国', '摩洛哥', '阿拉伯联合酋长国', '比利时', '突尼斯', '捷克', '阿根廷', '印尼', '瑞典', '保加利亚', '澳大利亚', '巴西', '印度',
            '丹麦', '韩国', '巴林', '越南', '多米尼加共和国', '挪威', '台湾', '波多黎各', '法国', '美国', '西班牙', '中国', '意大利', '英国', '土耳其', '德国', '马来西亚', '墨西哥', '坦桑尼亚', '南非', '突尼斯', '莫桑比克', '津巴布韦', '阿尔及利亚', '博茨瓦纳', '肯尼亚', '斯威士兰', '毛里求斯', '埃及', '沙特阿拉伯', '阿拉伯联合酋长国', '叙利亚', '巴林', '约旦', '以色列', '黎巴嫩', '卡塔尔', '也门', '美国', '墨西哥', '加拿大', '阿根廷', '巴西', '多米尼加共和国', '波多黎各', '智利', '古巴', '哥伦比亚', '中国', '马来西亚', '香港', '泰国', '澳门', '韩国', '新加坡', '日本', '印尼', '澳大利亚', '法国', '西班牙', '意大利', '英国', '土耳其', '德国', '奥地利', '乌克兰', '俄罗斯', '希腊', '法国', '美国', '中国', '西班牙', '意大利', '英国', '土耳其', '德国', '马来西亚', '墨西哥', '摩洛哥', '南非', '突尼斯', '津巴布韦', '莫桑比克', '阿尔及利亚', '博茨瓦纳', '尼日利亚', '肯尼亚', '纳米比亚', '埃及', '沙特阿拉伯', '叙利亚', '阿拉伯联合酋长国', '黎巴嫩', '巴林', '约旦', '以色列', '卡塔尔', '阿曼', '美国', '墨西哥', '加拿大', '阿根廷', '巴西', '多米尼加共和国', '波多黎各', '智利', '古巴', '哥伦比亚', '中国', '马来西亚', '香港', '泰国', '澳门', '新加坡', '韩国', '日本',
            '印尼', '澳大利亚', '法国', '西班牙', '意大利', '英国', '土耳其', '德国', '奥地利', '乌克兰', '俄罗斯', '希腊'
        );

    }

    /**
     * @Override
     */
    public function city()
    {
        return static::randomElement(static::$city);
    }

    /**
     * @example 'East'
     */
    public static function cityPrefix()
    {
        return static::randomElement(static::$cityPrefix);
    }

    /**
     * @example 'Appt. 350'
     */
    public static function secondaryAddress()
    {
        return static::numerify(static::randomElement(static::$secondaryAddressFormats));
    }

    /**
     * @example 'California'
     */
    public static function state()
    {
        return static::randomElement(static::$state);
    }

    /**
     * @example 'CA'
     */
    public static function stateAbbr()
    {
        return static::randomElement(static::$stateAbbr);
    }

}