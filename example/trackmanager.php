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

require_once( dirname(__FILE__) . '/jwtmanager.php' );
require_once( dirname(__FILE__) . '/common.php' );
require_once( dirname(__FILE__) . '/config.php' );


// read request body
function readBody() {
    $result["error"] = 0;

    // get the body of the post request and check if it is correct
    if (($body_stream = file_get_contents('php://input')) === FALSE) {
        $result["error"] = "Bad Request";
        return $result;
    }

    $data = json_decode($body_stream, TRUE); // json_decode - PHP 5 >= 5.2.0

    // check if the response is correct
    if ($data === NULL) {
        $result["error"] = "Bad Response";
        return $result;
    }

    sendlog("   InputStream data: " . serialize($data), "webedior-ajax.log");

    // check if the document token is enabled
    if (isJwtEnabled()) {
        sendlog("   jwt enabled, checking tokens", "webedior-ajax.log");

        $inHeader = false;
        $token = "";
        $jwtHeader = $GLOBALS['DOC_SERV_JWT_HEADER'] == "" ? "Authorization" : $GLOBALS['DOC_SERV_JWT_HEADER'];

        if (!empty($data["token"])) {  // if the document token is in the data
            $token = jwtDecode($data["token"]);  // decode it
        } elseif (!empty(apache_request_headers()[$jwtHeader])) {  // if the Authorization header exists
            $token = jwtDecode(substr(apache_request_headers()[$jwtHeader], strlen("Bearer ")));  // decode its part after Authorization prefix
            $inHeader = true;
        } else {  // otherwise, an error occurs
            sendlog("   jwt token wasn't found in body or headers", "webedior-ajax.log");
            $result["error"] = "Expected JWT";
            return $result;
        }
        if (empty($token)) {  // invalid signature error
            sendlog("   token was found but signature is invalid", "webedior-ajax.log");
            $result["error"] = "Invalid JWT signature";
            return $result;
        }

        $data = json_decode($token, true);
        if ($inHeader) $data = $data["payload"];
    }

    return $data;
}

// file saving process
function processSave($data, $fileName, $userAddress) {
    $downloadUri = $data["url"];
    if ($downloadUri === null) {
        $result["error"] = 1;
        return $result;
    }

    $curExt = strtolower('.' . pathinfo($fileName, PATHINFO_EXTENSION));  // get current file extension
    $downloadExt = strtolower('.' . pathinfo($downloadUri, PATHINFO_EXTENSION));  // get the extension of the downloaded file
    $newFileName = $fileName;

    // convert downloaded file to the file with the current extension if these extensions aren't equal
    if ($downloadExt != $curExt) {
        $key = GenerateRevisionId($downloadUri);

        try {
            sendlog("   Convert " . $downloadUri . " from " . $downloadExt . " to " . $curExt, "webedior-ajax.log");
            $convertedUri;  // convert file and give url to a new file
            $percent = GetConvertedUri($downloadUri, $downloadExt, $curExt, $key, FALSE, $convertedUri);
            if (!empty($convertedUri)) {
                $downloadUri = $convertedUri;
            } else {
                sendlog("   Convert after save convertedUri is empty", "webedior-ajax.log");
                $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
                $newFileName = GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);  // get the correct file name if it already exists
            }
        } catch (Exception $e) {
            sendlog("   Convert after save ".$e->getMessage(), "webedior-ajax.log");
            $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
            $newFileName = GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);
        }
    }

    $saved = 1;

    if (!(($new_data = file_get_contents($downloadUri)) === FALSE)) {
        $storagePath = getStoragePath($newFileName, $userAddress);  // get the file path
        $histDir = getHistoryDir($storagePath);  // get the path to the history direction
        $verDir = getVersionDir($histDir, getFileVersion($histDir));  // get the path to the file version

        mkdir($verDir);  // if the path doesn't exist, create it

        rename(getStoragePath($fileName, $userAddress), $verDir . DIRECTORY_SEPARATOR . "prev" . $curExt);  // get the path to the previous file version and rename the storage path with it
        file_put_contents($storagePath, $new_data, LOCK_EX);  // save file to the storage directory

        if ($changesData = file_get_contents($data["changesurl"])) {
            file_put_contents($verDir . DIRECTORY_SEPARATOR . "diff.zip", $changesData, LOCK_EX);  // save file changes to the diff.zip archive
        }

        $histData = $data["changeshistory"];
        if (empty($histData)) {
            $histData = json_encode($data["history"], JSON_PRETTY_PRINT);
        }
        if (!empty($histData)) {
            file_put_contents($verDir . DIRECTORY_SEPARATOR . "changes.json", $histData, LOCK_EX);  // write the history changes to the changes.json file
        }
        file_put_contents($verDir . DIRECTORY_SEPARATOR . "key.txt", $data["key"], LOCK_EX);  // write the key value to the key.txt file

        $forcesavePath = getForcesavePath($newFileName, $userAddress, false);  // get the path to the forcesaved file version
        if ($forcesavePath != "") {  // if the forcesaved file version exists
            unlink($forcesavePath);  // remove it
        }

        $saved = 0;
    }

    $result["error"] = $saved;

    return $result;
}

// file force saving process
function processForceSave($data, $fileName, $userAddress) {
    $downloadUri = $data["url"];
    if ($downloadUri === null) {
        $result["error"] = 1;
        return $result;
    }

    $curExt = strtolower('.' . pathinfo($fileName, PATHINFO_EXTENSION));  // get current file extension
    $downloadExt = strtolower('.' . pathinfo($downloadUri, PATHINFO_EXTENSION));  // get the extension of the downloaded file
    $newFileName = false;

    // convert downloaded file to the file with the current extension if these extensions aren't equal
    if ($downloadExt != $curExt) {
        $key = GenerateRevisionId($downloadUri);

        try {
            sendlog("   Convert " . $downloadUri . " from " . $downloadExt . " to " . $curExt, "webedior-ajax.log");
            $convertedUri;  // convert file and give url to a new file
            $percent = GetConvertedUri($downloadUri, $downloadExt, $curExt, $key, FALSE, $convertedUri);
            if (!empty($convertedUri)) {
                $downloadUri = $convertedUri;
            } else {
                sendlog("   Convert after save convertedUri is empty", "webedior-ajax.log");
                $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
                $newFileName = true;
            }
        } catch (Exception $e) {
            sendlog("   Convert after save ".$e->getMessage(), "webedior-ajax.log");
            $newFileName = true;
        }
    }

    $saved = 1;

    if (!(($new_data = file_get_contents($downloadUri)) === FALSE)) {
        $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
        $isSubmitForm = $data["forcesavetype"] == 3;  // SubmitForm

        if ($isSubmitForm) {
            if ($newFileName){
                $fileName = GetCorrectName($baseNameWithoutExt . "-form" . $downloadExt, $userAddress);  // get the correct file name if it already exists
            } else {
                $fileName = GetCorrectName($baseNameWithoutExt . "-form" . $curExt, $userAddress);
            }
            $forcesavePath = getStoragePath($fileName, $userAddress);
        } else {
            if ($newFileName){
                $fileName = GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);
            }
            // create forcesave path if it doesn't exist
            $forcesavePath = getForcesavePath($fileName, $userAddress, false);
            if ($forcesavePath == "") {
                $forcesavePath = getForcesavePath($fileName, $userAddress, true);
            }
        }

        file_put_contents($forcesavePath, $new_data, LOCK_EX);

        if ($isSubmitForm) {
            $uid = $data["actions"][0]["userid"];  // get the user id
            createMeta($fileName, $uid, "Filling Form", $userAddress);  // create meta data for the forcesaved file
        }

        $saved = 0;
    }

    $result["error"] = $saved;

    return $result;
}

// create a command request
function commandRequest($method, $key){
    $documentCommandUrl = $GLOBALS['DOC_SERV_SITE_URL'].$GLOBALS['DOC_SERV_COMMAND_URL'];

    $arr = [
        "c" => $method,
        "key" => $key
    ];

    $headerToken = "";
    $jwtHeader = $GLOBALS['DOC_SERV_JWT_HEADER'] == "" ? "Authorization" : $GLOBALS['DOC_SERV_JWT_HEADER'];

    if (isJwtEnabled()) {  // check if a secret key to generate token exists or not
        $headerToken = jwtEncode([ "payload" => $arr ]);  // encode a payload object into a header token
        $arr["token"] = jwtEncode($arr);  // encode a payload object into a body token
    }

    $data = json_encode($arr);

    $opts = array('http' => array(
        'method'  => 'POST',
        'header'=> "Content-type: application/json\r\n" .
            (empty($headerToken) ? "" : $jwtHeader.": Bearer $headerToken\r\n"),  // add a header Authorization with a header token and Authorization prefix in it
        'content' => $data
    ));

    if (substr($documentCommandUrl, 0, strlen("https")) === "https") {
        $opts['ssl'] = array( 'verify_peer'   => FALSE );
    }

    $context = stream_context_create($opts);
    $response_data = file_get_contents($documentCommandUrl, FALSE, $context);

    return $response_data;
}

?>