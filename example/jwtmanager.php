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

require_once( dirname(__FILE__) . '/config.php' );

// check if a secret key to generate token exists or not
function isJwtEnabled() {
    return !empty($GLOBALS['DOC_SERV_JWT_SECRET']);
}

// encode a payload object into a token using a secret key
function jwtEncode($payload) {
    $header = [
        "alg" => "HS256",  // the hashing algorithm
        "typ" => "JWT"  // the token type
    ];
    // three parts of token
    $encHeader = base64UrlEncode(json_encode($header));  // header
    $encPayload = base64UrlEncode(json_encode($payload));  // payload
    $hash = base64UrlEncode(calculateHash($encHeader, $encPayload));  // signature

    return "$encHeader.$encPayload.$hash";
}

// decode a token into a payload object using a secret key
function jwtDecode($token) {
    if (!isJwtEnabled()) return "";

    $split = explode(".", $token);
    if (count($split) != 3) return "";

    $hash = base64UrlEncode(calculateHash($split[0], $split[1]));

    if (strcmp($hash, $split[2]) != 0) return "";
    return base64UrlDecode($split[1]);
}

// generate a hash code based on a key using the HMAC method
function calculateHash($encHeader, $encPayload) {
    return hash_hmac("sha256", "$encHeader.$encPayload", $GLOBALS['DOC_SERV_JWT_SECRET'], true);
}

// encode a string into the base64 value
function base64UrlEncode($str) {
    return str_replace("/", "_", str_replace("+", "-", trim(base64_encode($str), "=")));
}

// decode a base64 value into the string
function base64UrlDecode($payload) {
    $b64 = str_replace("_", "/", str_replace("-", "+", $payload));
    switch (strlen($b64) % 4) {
        case 2:
            $b64 = $b64 . "=="; break;
        case 3:
            $b64 = $b64 . "="; break;
    }
    return base64_decode($b64);
}

?>