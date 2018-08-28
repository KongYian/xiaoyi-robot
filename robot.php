<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/8/26
 * Time: 21:22
 */

/**
 * description:
 * @param string $info
 * @param array $config
 * @return array
 */
function tuling($info = '',$config = []){
    $tuling = [
        'appkey'=>$config['tuling_robot']['appkey'],
        'apihost'=>$config['tuling_robot']['apihost'],
    ];
    $param = [
        'key'=>$tuling['appkey'],
        'info'=>$info,
        'userid'=>1
    ];
    $res = httpPost($tuling['apihost'],$param);
    $notfound = '抱歉，未找到相关信息';
    if($res != false){
        $response = json_decode($res,true);
        $code = $response['code'];
        switch ($code){
            case 100000:{
                $out = outHandler('text',$response['text']);break;
            }
            case 200000:{
                $out = outHandler('pic',$response['url']);break;
            }
            case 302000:{
                if(count($response['list'])>0){
                    $out = outHandler('news',$response['list']);
                }else{
                    $out = outHandler('err',$notfound);
                }
                break;
            }
            case 308000:{
                if(count($response['list'])>0){
                    $out = outHandler('cook',$response['list']);
                }else{
                    $out = outHandler('err',$notfound);
                }
                break;
            }
            default:
                $out = outHandler('err',$notfound);
        }
        return $out;
    }
}

function httpPost($query_url,$params){
    $headers = [
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        "Accept-Encoding:gzip, deflate",
        "Accept-Language:zh-CN,zh;q=0.9",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $query_url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}

function outHandler($type,$data){
    return [
        'type'=>$type,
        'data'=>$data
    ];
}
