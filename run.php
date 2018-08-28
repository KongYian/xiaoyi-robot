<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/8/23
 * Time: 0:22
 */
$config = require_once 'config.php';

require_once "aip_speech/AipSpeech.php";

require_once 'robot.php';


while (true){
    exec('arecord -D plughw:1,0 -d 5 -r 16000 -f S16_LE hi.pcm');

    $appId  =   $config['baidu_speech']['appId'];
    $appKey  =   $config['baidu_speech']['appKey'];
    $appSecret  =   $config['baidu_speech']['appSecret'];
    $client = new AipSpeech($appId,$appKey,$appSecret);
    $res = $client->asr(file_get_contents('hi.pcm'),'wav',16000);
    echo '<-----录音解析结果----->'.PHP_EOL;
    print_r($res);
    echo '<--------------------->';
    if($res['err_no'] != 0){
        $content = '小易没能听清你讲什么，请再说一遍吧';
    }else{
        $content = '语音解析结果是'.$res['result'][0];
    }

    if(strstr($res['result'][0],'拍照')){
        echo '拍照---->';
        exec('fswebcam --no-banner -r 640*480 demo.jpg');
    }else{
        $reply = tuling($res['result'][0]);

        $speach =  $client->synthesis($reply['data'],'zh',1,[
            'vol'=>5,
            'per'=>0
        ]);

        if(!is_array($speach)){
            file_put_contents('audio.mp3', $speach);
            exec('play audio.mp3');
        }else{
        }
    }


}

