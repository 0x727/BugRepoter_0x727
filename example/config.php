<?php

$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

$GLOBALS['version'] = "1.0.0";

$GLOBALS['FILE_SIZE_MAX'] = 5242880;
$GLOBALS['STORAGE_PATH'] = "";
$GLOBALS['ALONE'] = FALSE;

$GLOBALS['DOC_SERV_VIEWD'] = array(".pdf", ".djvu", ".xps", ".oxps");
$GLOBALS['DOC_SERV_EDITED'] = array(".docx", ".xlsx", ".csv", ".pptx", ".txt");
$GLOBALS['DOC_SERV_CONVERT'] = array(".docm", ".doc", ".dotx", ".dotm", ".dot", ".odt", ".fodt", ".ott", ".xlsm", ".xls", ".xltx", ".xltm", ".xlt", ".ods", ".fods", ".ots", ".pptm", ".ppt", ".ppsx", ".ppsm", ".pps", ".potx", ".potm", ".pot", ".odp", ".fodp", ".otp", ".rtf", ".mht", ".html", ".htm", ".xml", ".epub", ".fb2");

$GLOBALS['DOC_SERV_TIMEOUT'] = "120000";


$GLOBALS['DOC_SERV_SITE_URL'] = $http_type.$_SERVER['HTTP_HOST'].":8000/";

$GLOBALS['DOC_SERV_CONVERTER_URL'] = "ConvertService.ashx";
$GLOBALS['DOC_SERV_API_URL'] = "web-apps/apps/api/documents/api.js";
$GLOBALS['DOC_SERV_PRELOADER_URL'] = "web-apps/apps/api/documents/cache-scripts.html";
$GLOBALS['DOC_SERV_COMMAND_URL'] = "coauthoring/CommandService.ashx";

$GLOBALS['DOC_SERV_JWT_SECRET'] = "";
$GLOBALS['DOC_SERV_JWT_HEADER'] = "Authorization";

$GLOBALS['EXAMPLE_URL'] = "";

$GLOBALS['MOBILE_REGEX'] = "android|avantgo|playbook|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker|pocket|psp|symbian|treo|up\\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino";


$GLOBALS['ExtsSpreadsheet'] = array(".xls", ".xlsx", ".xlsm",
                                    ".xlt", ".xltx", ".xltm",
                                    ".ods", ".fods", ".ots", ".csv");

$GLOBALS['ExtsPresentation'] = array(".pps", ".ppsx", ".ppsm",
                                     ".ppt", ".pptx", ".pptm",
                                     ".pot", ".potx", ".potm",
                                     ".odp", ".fodp", ".otp");

$GLOBALS['ExtsDocument'] = array(".doc", ".docx", ".docm",
                                 ".dot", ".dotx", ".dotm",
                                 ".odt", ".fodt", ".ott", ".rtf", ".txt",
                                 ".html", ".htm", ".mht", ".xml",
                                 ".pdf", ".djvu", ".fb2", ".epub", ".xps", ".oxps");

if(strstr($_SERVER['HTTP_USER_AGENT'], "Node.js") || $GLOBALS['docx_template'] == "1"){
    $get_id = isset($_GET['1f2018903']) ? $_GET['1f2018903'] : isset($GLOBALS['1f2018903']) ? $GLOBALS['1f2018903'] : "";
    if(empty($get_id)){
        // 把url转换成数组
        parse_str($_SERVER["QUERY_STRING"],$_GET);
        $get_id = isset($_GET['1f2018903']) ? $_GET['1f2018903'] : isset($_REQUEST['1f2018903']) ? $_REQUEST['1f2018903'] : '';
        if(empty($get_id)){
            die("错误异常！");
        }
    } 
    @ini_set("session.save_path", dirname(dirname(__FILE__))."/runtime/session_tmp/");
    @ini_set("session.name", "BQ");//设置session名字
    @session_id($get_id);
    @session_start();
    $user_info_uuid = isset($_SESSION['user_info']['uuid']) ? $_SESSION['user_info']['uuid'] : '';
    if(empty($user_info_uuid)) die("请登录！");
} else {
    die("非法访问！");
}
?>