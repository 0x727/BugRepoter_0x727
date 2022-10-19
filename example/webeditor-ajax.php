<?php
/**
 *
 * (c) Copyright Ascensio System SIA 2021
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

/**
 * WebEditor AJAX Process Execution.
 */
require_once( dirname(__FILE__) . '/config.php' );
require_once( dirname(__FILE__) . '/ajax.php' );
require_once( dirname(__FILE__) . '/common.php' );
require_once( dirname(__FILE__) . '/functions.php' );
require_once( dirname(__FILE__) . '/jwtmanager.php' );
require_once( dirname(__FILE__) . '/trackmanager.php' );

// define tracker status
$_trackerStatus = array(
    0 => 'NotFound',
    1 => 'Editing',
    2 => 'MustSave',
    3 => 'Corrupted',
    4 => 'Closed',
    6 => 'MustForceSave',
    7 => 'CorruptedForceSave'
);

// check if type value exists
if (isset($_GET["type"]) && !empty($_GET["type"])) {
    $response_array;
    @header( 'Content-Type: application/json; charset==utf-8');
    @header( 'X-Robots-Tag: noindex' );
    @header( 'X-Content-Type-Options: nosniff' );

    // set headers that prevent caching in all the browsers
    nocache_headers();

    // write the request result to the log file
    sendlog(serialize($_GET), "webedior-ajax.log");

    $type = $_GET["type"];

    // switch case for type value
    switch($type) {
        case "download":
            $response_array = download();
            $response_array['status'] = 'success';
            die (json_encode($response_array));
        case "convert":
            $response_array = convert();
            $response_array['status'] = 'success';
            die (json_encode($response_array));
        case "track":
            $response_array = track();
            $response_array['status'] = 'success';
            die (json_encode($response_array));
        default:
            $response_array['status'] = 'error';
            $response_array['error'] = '404 Method not found';
            die(json_encode($response_array));
    }
}

// tracking file changes
function track() {
    sendlog("Track START", "webedior-ajax.log");
    sendlog("   _GET params: " . serialize( $_GET ), "webedior-ajax.log");

    $result["error"] = 0;

    // get the body of the post request and check if it is correct
    $data = readBody();
    if ($data["error"]){
        return $data;
    }

    global $_trackerStatus;
    $status = $_trackerStatus[$data["status"]];  // get status from the request body

    $userAddress = $_GET["userAddress"];
    $fileName = basename($_GET["fileName"]);
    sendlog("test: " . json_encode($status), "webedior-ajax.log");
    switch ($status) {
        case "Editing":  // status == 1
            if ($data["actions"] && $data["actions"][0]["type"] == 0) {   // finished edit
                $user = $data["actions"][0]["userid"];  // the user who finished editing
                if (array_search($user, $data["users"]) === FALSE) {
                    $commandRequest = commandRequest("forcesave", $data["key"]);  // create a command request with the forcasave method
                    sendlog("   CommandRequest forcesave: " . serialize($commandRequest), "webedior-ajax.log");
                }
            }
            break;
        case "MustSave":  // status == 2
        case "Corrupted":  // status == 3
            $result = processSave($data, $fileName, $userAddress);
            break;
        case "MustForceSave":  // status == 6
        case "CorruptedForceSave":  // status == 7
            $result = processForceSave($data, $fileName, $userAddress);
            break;
    }

    sendlog("Track RESULT: " . serialize($result), "webedior-ajax.log");
    return $result;
}

// converting a file
function convert() {
    $post = json_decode(file_get_contents('php://input'), true);
    $fileName = basename($post["filename"]);
    $filePass = $post["filePass"];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $internalExtension = trim(getInternalExtension($fileName),'.');

    // check if the file with such an extension can be converted
    if (in_array("." + $extension, $GLOBALS['DOC_SERV_CONVERT']) && $internalExtension != "") {

        $fileUri = $post["fileUri"];
        if ($fileUri == NULL || $fileUri == "") {
            $fileUri =  $fileUri=serverPath(TRUE) . '/'
                . "webeditor-ajax.php"
                . "?type=download"
                . "&fileName=" . urlencode($fileName)
                . "&userAddress=" . getClientIp()
                . "&1f2018903=" .session_id();
        }
        $key = getDocEditorKey($fileName);
        $newFileUri;
        $result;
        $percent;

        try {
            // convert file and get the percentage of the conversion completion
            $percent = GetConvertedUri($fileUri, $extension, $internalExtension, $key, TRUE, $newFileUri, $filePass);
        }
        catch (Exception $e) {
            $result["error"] = "error: " . $e->getMessage();
            return $result;
        }
        if ($percent != 100)
        {
            $result["step"] = $percent;
            $result["filename"] = $fileName;
            $result["fileUri"] = $fileUri;
            return $result;
        }

        // get file name without extension
        $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($extension) - 1);

        // get the correct file name with an index if the file with such a name already exists
        $newFileName = GetCorrectName($baseNameWithoutExt . "." . $internalExtension);

        if (($data = file_get_contents(str_replace(" ","%20",$newFileUri))) === FALSE) {
            $result["error"] = 'Bad Request';
            return $result;
        } else {
            file_put_contents(getStoragePath($newFileName), $data, LOCK_EX);  // write data to the new file
            $user = getUser($_GET["user"]);
            createMeta($newFileName, $user->id, $user->name);  // and create meta data for this file
        }

        // delete the original file and its history
        $stPath = getStoragePath($fileName);
        unlink($stPath);
        delTree(getHistoryDir($stPath));

        $fileName = $newFileName;
    }

    $result["filename"] = $fileName;
    return $result;
}

// download a file
function download() {
    try {
        $fileName = basename($_GET["fileName"]);  // get the file name
        $userAddress = $_GET["userAddress"];

        if (isJwtEnabled()) {
            $jwtHeader = $GLOBALS['DOC_SERV_JWT_HEADER'] == "" ? "Authorization" : $GLOBALS['DOC_SERV_JWT_HEADER'];
            if (!empty(apache_request_headers()[$jwtHeader])) {
                $token = jwtDecode(substr(apache_request_headers()[$jwtHeader], strlen("Bearer ")));
                if (empty($token)) {
                    http_response_code(403);
                    die("Invalid JWT signature");
                }
            }
        }

        $filePath = getForcesavePath($fileName, $userAddress, false);  // get the path to the forcesaved file version
        if ($filePath == "") {
            $filePath = getStoragePath($fileName, $userAddress);  // get file from the storage directory
        }
        downloadFile($filePath);  // download this file
    } catch (Exception $e) {
        sendlog("Download ".$e->getMessage(), "webedior-ajax.log");
        $result["error"] = "error: File not found";
        return $result;
    }
}

// download the specified file
function downloadFile($filePath) {
    if (file_exists($filePath)) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        // write headers to the response object
        @header('Content-Length: ' . filesize($filePath));
        @header('Content-Disposition: attachment; filename*=UTF-8\'\'' . urldecode(basename($filePath)));
        @header('Content-Type: ' . mime_content_type($filePath));

        if ($fd = fopen($filePath, 'rb')) {
            while (!feof($fd)) {
                print fread($fd, 1024);
            }
            fclose($fd);
        }
        exit;
    }
}

// delete all the elements from the directory
function delTree($dir) {
    if (!file_exists($dir) || !is_dir($dir)) return;

    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

?>