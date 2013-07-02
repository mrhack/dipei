<?php
/**
 * User: wangfeng
 * Date: 13-7-1
 * Time: 下午10:53
 */
require_once __DIR__ . '/Bootstrap.php';
require_once __DIR__ . '/faker/src/autoload.php';

$locals = array('zh_CN' => 100, 'en_US' => 100);
foreach ($locals as $local => $count) {
    while ($count--) {
        $faker = \Faker\Factory::create($local);
        $userModel = UserModel::getInstance();
        $rateModel = RateModel::getInstance();
        $translateModel = TranslationModel::getInstance();
        $locationModel = LocationModel::getInstance();

        $randomName = $faker->name;
        $randomEmail = $faker->email;
        $randomSex = rand(1, 3);
        $randomTel = $faker->phoneNumber;

        $uid = $userModel->createUser(array(
            'n' => $randomName,
            'em' => $randomEmail,
            'pw' => '123'
        ));

        $locationCount = LocationModel::getInstance()->count();

        $randomBirth = array(
            'y' => rand(1970, 2013),
            'm' => rand(1, 12),
            'd' => rand(1, 25),
        );

        $randomImages = array();
        $randCount = rand(0, 5);
        while ($randCount--) {
            $randLocation = LocationModel::getInstance()->fetchOne(array('_id' => rand(1, $locationCount)));
            $randomImages = array_merge($randomImages, $randLocation['ims']);
        }

        $randomLangs = array();
        $randCount = rand(1, 3);
        while ($randCount--) {
            $randomLanguage = array_rand(Constants::$LANGUAGES);
            $randomFamiliar = array_rand(Constants::$LANGS_FAMILIAR);
            $randomLangs[Constants::$LANGUAGES[$randomLanguage]] = Constants::$LANGS_FAMILIAR[$randomFamiliar];
        }

        $user = array(
            '_id' => $uid,
            'sx' => $randomSex,
            'l_t' => Constants::$LEPEI_TYPES[array_rand(Constants::$LEPEI_TYPES)],
            'b' => $randomBirth,
            'lid' => rand(7, $locationCount),
            'as' => rand(0, 2),
            'dsc' => '',
            'ims' => $randomImages,
            'lk' => rand(0, 1000),
            'ls' => $randomLangs,
            'vc' => rand(0, 10000),
            'cts' => array(
                Constants::CONTACT_EMAIL => $randomEmail,
                Constants::CONTACT_QQ => rand(100000, 1000000),
                Constants::CONTACT_TEL => $randomTel,
                Constants::CONTACT_WEIXIN => rand(100000, 100000)
            )
        );
        $randomLoc=$locationModel->fetchOne(array('_id'=>rand(1,$locationCount)));
        $user['h'] = $randomLoc['ims'][0];

        //random projects
        $projects = array();
        $randCount = rand(1, 2);
        while ($randCount--) {
            $locationName = $translateModel->translateWord($translateModel->fetchOne(array('_id' => $user['lid'])), $local);
            $randomTitle = $locationName . rand(1, 2);
            if ($local == 'zh_CN') {
                $randomTitle .= '日行';
            } else {
                $randomTitle .= 'day travel';
            }
            $project = array(
                't' => $randomTitle,
                'p' => rand(100, 10000),
                'pu' => array_rand(Constants::$MONEYS),
                'lk' => rand(0, 1000),
            );
            $project['bp'] = intval($rateModel->convertRate($project['p'], $project['pu']) * 1000000);
            $randCount2 = rand(1, 7);
            while ($randCount2--) {
                $project['tm'][] = Constants::$TRAVEL_THEMES[array_rand(Constants::$TRAVEL_THEMES)];
                $project['ts'][] = Constants::$TRAVEL_SERVICES[array_rand(Constants::$TRAVEL_SERVICES)];
            }
            $project['tm'] = array_unique($project['tm']);
            $project['ts'] = array_unique($project['ts']);
            $randCount2 = rand(1, 3);
            $locations = $locationModel->fetch(array('pt' => $user['lid'], '$limit' => 20));
            if (empty($locations)) {
                $locations = $locationModel->fetch(array('$limit' => 20));
            }
            while ($randCount2--) {
                $randomDesc = $faker->text;
                $randCount3 = rand(4, 7);
                $randomLines = array();
                while ($randCount3--) {
                    $randLid = array_rand($locations);
                    $randomLines[] = $randLid;
                    unset($locations[$randLid]);
                }
                $day = array(
                    'dsc' => $randomDesc,
                    'ls' => $randomLines
                );
                $project['ds'][] = $day;
            }
            $projects[] = $project;
        }

        $user['ps'] = $projects;
        $userModel->updateUser($user);
    }
}
