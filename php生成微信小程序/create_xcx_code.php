<?php


/**
生成小程序太阳码功能

*/

//获取小程序access_token
function get_access_token($appid,$appsecret) {
 $url2 = 'https://api.weixin.qq.com/cgi-bin/token' . '?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret;
 $token = file_get_contents($url2);
 $res = json_decode($token);
 return $res;
}

function curl_post($url,$params=false){
            $httpInfo = [];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'hepeng509@.163com');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);
        if($response === false){
            return false;
        }
        $httpCode= curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
}


function get_mini_code($data,$page,$width=430,$auto_color=false,$line_color=array('r'=>'0','g'=>'0','b'=>'0'),$is_hyaline=false){
$token_info = get_access_token('wx017db2a02c5af6a9','90c7aaa48d094a9999216edb209fab75');
        $full_url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$token_info->access_token;
        $wx_data = array(
            'scene'=>'?v=1',
            'page'=>$page,
            'width'=>$width,
            'auto_color'=>$auto_color,
            'line_color'=>$line_color,
            'is_hyaline'=>$is_hyaline,
        );
        $wx_data = json_encode($wx_data);
        $res = curl_post($full_url,$wx_data);
        return $res;
}
//创建目录
function mkdir_chmod($path, $mode = 0777){
        if(is_dir($path)) {
            return true;
        }
        $result = mkdir($path, $mode, true);
        if($result) {
            $path_arr = explode('/',$path);
            $path_str = '';
            foreach($path_arr as $val){
                $path_str .= $val.'/';
                $a = chmod($path_str,$mode);
            }
        }
        return $result;
    }

//
function get_store_mini_code_path($vid,$file_path,$file_name=''){
            /* 小程序二维码保存目录 */
            mkdir_chmod($file_path);
            $filename = $file_path.$file_name;
            
            if(!file_exists($filename)){
                $data=[];
    
                $page = 'pages/index/index'; /* 小程序店铺页 */
                $res = get_mini_code($data,$page);
    
                $im = file_put_contents($filename, $res);
            }
            return $filename;
    }

//获取小程序图片路径
$filename = get_store_mini_code_path('', 'pic/', 'aa.png');

var_dump($filename);//输出图片路径