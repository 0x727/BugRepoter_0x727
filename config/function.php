<?php
/**
 * POST 请求
 * $url String url地址
 * $post_data Array 需要提交的内容
 */
function send_post($url, $post_data) {
  	$postdata = http_build_query($post_data);
  	$options = array(
    	'http' => array(
      		'method' => 'POST',
      		'header' => 'Content-type:application/x-www-form-urlencoded',
     		'content' => $postdata,
      		'timeout' => 15 * 60 // 超时时间（单位:s）
    	)
  	);
  	$context = stream_context_create($options);
  	$result = file_get_contents($url,false, $context);
  	return $result;
}

/**
 * 随机获取 一串8位密钥
 */
function code() {
	$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$rand = $code[rand(0,25)].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
	for ($a = md5( $rand, true ),$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',$d = '',$f = 0;$f < 8;$g = ord( $a[ $f ] ),$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],$f++);
	return $d;
}

/**
 * 获取ip地址
 */
function getip() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = filterWords($_SERVER['REMOTE_ADDR']);
    else $ip = "unknown";
    return ($ip);
}

/**
 * XSS代码防护
 */
function xss_clean($data){
 	$data=str_replace(array('&','<','>'),array('&amp;','&lt;','&gt;'),$data);
 	$data=preg_replace('/(&#*\w+)[\x00-\x20]+;/u','$1;',$data);
 	$data=preg_replace('/(&#x*[0-9A-F]+);*/iu','$1;',$data);
	$data=html_entity_decode($data,ENT_COMPAT,'UTF-8');
	$data=preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu','$1>',$data);
	$data=preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu','$1=$2nojavascript...',$data);
	$data=preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu','$1=$2novbscript...',$data);
	$data=preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u','$1=$2nomozbinding...',$data);
	$data=preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i','$1>',$data);
	$data=preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i','$1>',$data);
	$data=preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu','$1>',$data);
	$data=preg_replace('#</*\w+:\w[^>]*+>#i','',$data);
	do{
		$old_data=$data;
		$data=preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i','',$data);
	}while($old_data!==$data);
 	return $data;
}

/**
 * 过滤参数
 * @param string $str 接受的参数
 * @return string
 */
function filterWords($str)
{
    $farr = array(
            "/<(\\/?)(script|i?frame|html|body|title|link|meta|object|svg|onabort|onactivate|onafterprint|onafterupdate|onanimationend|onanimationiteration|onanimationstart|onautocomplete|onautocompleteerror|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onbegin|onblur|onbounce|oncancel|oncanplay|oncanplaythrough|oncellchange|onchange|onclick|onclose|oncompassneedscalibration|oncontextmenu|oncontrolselect|oncopy|oncuechange|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondevicelight|ondevicemotion|ondeviceorientation|ondeviceproximity|ondrag|ondragdrop|ondragend|ondragenter|ondragexit|ondragleave|ondragover|ondragstart|ondrop|ondurationchange|onemptied|onend|onended|onerror|onerrorupdate|onexit|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onformchange |onforminput |ongesturechange|ongestureend|ongesturestart|onhashchange|onhelp|oninput|oninvalid|onkeydown|onkeypress|onkeyup|onlanguagechange|onlayoutcomplete|onload|onloadeddata|onloadedmetadata|onloadstart|onlosecapture|onmediacomplete|onmediaerror|onmessage|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onmozfullscreenchange|onmozfullscreenerror|onmozpointerlockchange|onmozpointerlockerror|onmsgesturechange|onmsgesturedoubletap|onmsgesturehold|onmsgesturerestart|onmsinertiastart|onmspointercancel|onmspointerdown|onmspointerenter|onmspointerhover|onmspointerleave|onmspointermove|onmspointerout|onmspointerover|onmspointerup|onoffline|ononline|onorientationchange|onoutofsync|onpagehide|onpageshow|onpaste|onpause|onplay|onplaying|onpopstate|onprogress|onpropertychange|onratechange|onreadystatechange|onreceived|onrepeat|onreset|onresize|onresizeend|onresizestart|onresume|onreverse|onrowdelete|onrowenter|onrowexit|onrowinserted|onrowsdelete|onrowsinserted|onscroll|onsearch|onseek|onseeked|onseeking|onselect|onselectionchange|onselectstart|onshow|onstalled|onstart|onstop|onstorage|onsubmit|onsuspend|onsynchrestored|ontimeerror|ontimeupdate|ontoggle|ontouchcancel|ontouchend|ontouchmove|ontouchstart|ontrackchange|ontransitionend|onunload|onurlflip|onuserproximity|onvolumechange|onwaiting|onwebkitanimationend|onwebkitanimationiteration|onwebkitanimationstart|onwebkitmouseforcechanged|onwebkitmouseforcedown|onwebkitmouseforceup|onwebkitmouseforcewillbegin|onwebkittransitionend|onwebkitwillrevealbottom|onwheel|onzoom\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            "/select|insert|update|delete|join|union|into|load_file|outfile|dump|count|asc|create|where/is"
    );
    if(is_string($str)){
      $str = @preg_replace($farr,'',$str);
      $str = strFilter($str);
      return $str;
    } else {
      $array = [];
      foreach ($str as $k => $v) {
        if(is_array($v)){
          $array[] = $v;
          unset($str[$k]);
        }
      }
      $bb = [];
      foreach ($array as $k) {
        foreach ($k as $key => $value) {
          $bb[$key] = $value;
        }
      }
      $str = @preg_replace($farr,'',$str);
      foreach ($str as $k => $v) {
        $str[$k] = strFilter($v);
      }
      $bb = @preg_replace($farr,'',$bb);
      foreach ($bb as $k => $v) {
        $str[$k] = strFilter($v);
      }
      return $str;
    }
}

/**
 * @name 字符串替换，并且转换实体html
 * @param $str 字符串
 * @return string
 */
function strFilter($str){
    $str = str_replace('<', '《', $str);
    $str = str_replace('>', '》', $str);
    $str = htmlspecialchars($str);
    return trim($str);
}

/**
 * 验证输入的邮件地址是否合法
 * @param   string      $email      需要验证的邮件地址
 * @return bool
 */
function is_email($email)
{
  $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
  if (strpos($email, '@') !== false && strpos($email, '.') !== false)
  {
    if (preg_match($chars, $email))
    {
        return true;
    }else{
        return false;
    }
  }else{
    return false;
  }
}

/**
 * 验证输入的手机号码是否合法
 * @param string $mobile_phone
 * @return bool
 */
function is_mobile_phone($mobile_phone)
{
  $chars = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/";
  if(preg_match($chars, $mobile_phone))
  {
    return true;
  }
  return false;
}

/**
* @name 获取当前域名
* @return string
*/
function get_curl()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type.$_SERVER['HTTP_HOST'];
}

/**
* @name 加密解密 
* @param $string 内容
* @param $operation ENCODE 加密 DECODE 解密
* @param $key 加密值
* @param $expiry 时间
* @return string
*/
function AuthCode($string, $operation='DECODE', $key='', $expiry=0)
{
  $ckey_length = 4;
  $key = md5($key ? $key : "11xxAA");
  $keya = md5(substr($key, 0, 16));
  $keyb = md5(substr($key, 16, 16));
  $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
  $cryptkey = $keya.md5($keya.$keyc);
  $key_length = strlen($cryptkey);
  $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
  $string_length = strlen($string);
  $result = '';
  $box = range(0, 255);
  $rndkey = array();
  for($i = 0; $i <= 255; $i++)
  {
      $rndkey[$i] = ord($cryptkey[$i % $key_length]);
  }
  for($j = $i = 0; $i < 256; $i++)
  {
      $j = ($j + $box[$i] + $rndkey[$i]) % 256;
      $tmp = $box[$i];
      $box[$i] = $box[$j];
      $box[$j] = $tmp;
  }
  for($a = $j = $i = 0; $i < $string_length; $i++)
  {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $tmp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $tmp;
      $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
  }

  if($operation == 'DECODE')
  {
      if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
      {
          return substr($result, 26);
      }
      else
      {
          return '';
      }
  }
  else
  {
      return $keyc.str_replace('=', '', base64_encode($result));
  }
}

/**
 * 以get方式提交请求
 * @param $url
 * @return bool|mixed
 */
function httpGet($url){
    $curl = curl_init();
    if (stripos($url, "https://") !== 0) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    list($content, $status) = array(curl_exec($curl), curl_getinfo($curl), curl_close($curl));
    return (intval($status["http_code"]) === 200) ? $content : false;
}

/**
* 发起http post请求(REST API), 并获取REST请求的结果
* @param string $url
* @param string $param
* @return - http response body if succeeds, else false.
*/
function request_post($url = '', $param = '',$header = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    // curl_setopt($curl, CURLOPT_HEADER, $header);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($param))
    );
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

/**
* 代理发起http post请求(REST API), 并获取REST请求的结果
* @param string $url
* @param string $param
* @return - http response body if succeeds, else false.
*/
function proxy_request_post($url = '', $param = '',$header = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($param))
    );
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

/**
* @name 读取配置文件 
*/
function get_system_config()
{
  return include dirname(__FILE__)."/system.php";
}

/**
* @name 递归 
* @param $array 数组
* @param $pid
* @return array
*/
function get_attr($array,$pid = 0){  
   $tree = array();
    foreach($array as $k => $v)
    {
        if($v['pid'] == $pid)
        {
            $v['field'] = '';
            $v['spread'] = 'true';
            $v['children'] = get_attr($array, $v['id']);
            $tree[] = $v;    
        }
    }
    return $tree;
}

/**
* @name 验证身份证 
* @param $id 身份证 号码
* @return bool
*/
function is_idcard($id)
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if(!preg_match($regx, $id))
    {
        return false;
    }
    if(15==strlen($id)) //检查15位
    {
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
 
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth))
        {
            return false;
        } else {
            return true;
        }
    }
    else//检查18位
    {
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth)) //检查生日日期是否正确
        {
            return false;
        }
        else
        {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ( $i = 0; $i < 17; $i++ )
            {
                $b = intval($id[$i]);
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id,17, 1))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}

/**
* @name 生成uuid 
* @return String
*/
function uuid()  
{  
    $chars = md5(uniqid(mt_rand(), true));  
    $uuid = substr ( $chars, 0, 8 ) . '-'
            . substr ( $chars, 8, 4 ) . '-' 
            . substr ( $chars, 12, 4 ) . '-'
            . substr ( $chars, 16, 4 ) . '-'
            . substr ( $chars, 20, 12 );  
    return $uuid ;  
}

/**
* @name 遍历分类
* @return String
*/
function getSortedCategory($data,$pid=0,$html="|---",$level=0)
{
  $temp = array();
  foreach ($data as $k => $v) {
    if($v['pid'] == $pid){
      $str = str_repeat($html, $level);
      $v['html'] = $str;
      $temp[] = $v;
      $temp = array_merge($temp,getSortedCategory($data,$v['id'],'|---',$level+1));
    }
  }
  return $temp;
}

/**
 * @param $filePath //下载文件的路径
 * @param int $readBuffer //分段下载 每次下载的字节数 默认1024bytes
 * @param array $allowExt //允许下载的文件类型
 * @return void
 */
function downloadFile($filePath, $name = "", $readBuffer = 1024, $allowExt = ['docx','zip'])
{
    //检测下载文件是否存在 并且可读
    if (!is_file($filePath) && !is_readable($filePath)) {
        return false;
    }
    //检测文件类型是否允许下载
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowExt)) {
        return false;
    }
    //设置头信息
    //声明浏览器输出的是字节流
    header('Content-Type: application/octet-stream');
    //声明浏览器返回大小是按字节进行计算
    header('Accept-Ranges:bytes');
    //告诉浏览器文件的总大小
    $fileSize = filesize($filePath);//坑 filesize 如果超过2G 低版本php会返回负数
    header('Content-Length:' . $fileSize); //注意是'Content-Length:' 非Accept-Length
    //声明下载文件的名称
    if(empty($name)){
      $name = basename($filePath);
    }
    header('Content-Disposition:attachment;filename=' . $name);//声明作为附件处理和下载后文件的名称
    //获取文件内容
    $handle = fopen($filePath, 'rb');//二进制文件用‘rb’模式读取
    while (!feof($handle) ) { //循环到文件末尾 规定每次读取（向浏览器输出为$readBuffer设置的字节数）
        echo fread($handle, $readBuffer);
    }
    fclose($handle);//关闭文件句柄
}

/**
* @name 获取顶级域名
* @return String
*/
function getTopHost($url){
  $url = strtolower($url);  //首先转成小写
  $hosts = parse_url($url);
  $host = $hosts['host'];
  //查看是几级域名
  $data = explode('.', $host);
  $n = count($data);
  //判断是否是双后缀
  $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
  if(($n > 2) && preg_match($preg,$host)){
    //双后缀取后3位
    $host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
  }else{
    //非双后缀取后两位
    $host = $data[$n-2].'.'.$data[$n-1];
  }
  return $host;
}

/**
* @name 随机生成字母+数组
* @param $len 需要生成的数字
* @param $chars 自定义随机字符串
* @return string
*/
function getRandomString($len, $chars=null)
{
  if (is_null($chars)){
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  }  
  mt_srand(10000000*(double)microtime());
  for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
      $str .= $chars[mt_rand(0, $lc)];  
  }
  return $str;
}

/**
  * 判断IP输入是否合法
  * @param type $ip IP地址
  * @return int 等于1是输入合法  0 输入不合法
  */
function checkIp($ip)
{
  $arr = explode('.',$ip);
  if(count($arr) != 4){
      return false;
  }else{
      for($i = 0;$i < 4;$i++){
          if(($arr[$i] <'0') || ($arr[$i] > '255')){
              return false;
          }
      }
  }
  return true;
}

/**
  * 判断数据库账户密码是否正常
  * @param type $host IP地址
  * @param type $db 数据库
  * @param type $db_user 数据库账户
  * @param type $db_pwd 数据库密码
  * @return bool
  */
function try_mysql($host,$db,$db_user,$db_pwd){
  $mysql_conf = array(
    'host'    => explode(':',$host)[0], 
    'port'    => explode(':',$host)[1],
    'db'      => $db, 
    'db_user' => $db_user, 
    'db_pwd'  => $db_pwd, 
    );
  
  $pdo = new PDO("mysql:host=" . $mysql_conf['host'] . "; port=". $mysql_conf['port'] . "; dbname=" . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
  
  if($pdo){
    return true;
  } else {
    return false;
  }
}

/**
  * 文件加密
  * @param type $data 内容
  * @param type $key 秘钥
  * @param type $name 文件头
  * @return string
  */
function binary_encode($data,$key='xzkaa11111diosdixcqwiozohxi1239',$name='')
{
  if(empty($name)) $name='default_1234567890abcdefg.jpgifbmne';
  $data=$name.'!'.$key .'@'.$data;
  return gzcompress(RC4($data,$key));
}

/**
  * 文件解密
  * @param type $data 内容
  * @param type $key 秘钥
  * @return string
  */
function binary_decode($data,$key='xzkaa11111diosdixcqwiozohxi1239')
{
  $data=RC4(gzuncompress($data),$key);
  $reg = "#^(.+)!(".$key.")@#im";
  preg_match_all($reg,$data,$res);
  if(!isset($res[0][0])||!isset($res[1][0])){return;}
  $isfile=$res[1][0]!=='default_1234567890abcdefg.jpgifbmne';
  $data=str_replace($res[0][0],'',$data);
  return $isfile?[$data,$res[1][0]]:$data;
}

/**
  * rc4加密/解密
  * @param type $data 内容
  * @param type $pwd 密码
  * @return string
  */
function RC4($data,$pwd){
  $cipher='';
  $key[]="";
  $box[]="";
  $pwd_length=strlen($pwd);
  $data_length=strlen($data);
  for($i=0;$i<256;$i++){
    $key[$i]=ord($pwd[$i%$pwd_length]);
    $box[$i]=$i;
  }
  for($j=$i=0;$i<256;$i++) {
    $j=($j+$box[$i]+$key[$i])%256;
    $tmp=$box[$i];
    $box[$i]=$box[$j];
    $box[$j]=$tmp;
  }
  for($a=$j=$i=0;$i<$data_length;$i++) {
    $a=($a+1)%256;
    $j=($j+$box[$a])%256;
    $tmp=$box[$a];
    $box[$a]=$box[$j];
    $box[$j]=$tmp;
    $k=$box[(($box[$a]+$box[$j])%256)];
    $cipher.=chr(ord($data[$i]) ^ $k);
  }
  return $cipher;
}

/**
  * 循环删除文件
  * @param type $url 内容
  * @return bool
  */
function img_unlik($url = [])
{
  foreach ($url as $k => $v) {
    if(file_exists($v)){
      @unlink($v);
    }
  }
}