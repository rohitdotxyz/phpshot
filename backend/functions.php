<?php

function get_protocol(){
	$protocol = (
        !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
    ) ? "https://" : "http://";

	if ($protocol == "https://") {
		return true;
	} else {
		return false;
	}
}

// echo $actual_link = get_protocol()."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

function makeOutput($data, $encoded = true) {
    $output = stripslashes($data);

    if ($encoded) {
        $ecnodedOutput =  htmlspecialchars($output, ENT_QUOTES);
        return $ecnodedOutput;
    }

    return $output;
}


function makeResponse($status, $code, $data, $message) {
    $resp = array();
    $resp['status'] = $status;
    $resp['code'] = $code;
    $resp['data'] = $data;
    $resp['message'] = $message;
    return $resp;
}


function requestCheck($reqMethod, $allowedMethod) {
    if ($reqMethod == $allowedMethod) {
        return true;
    } else { 
        $error = makeOutput("Invalid $reqMethod request.");
        $responseData = makeResponse("error", 405, NULL, $error);
        $json = json_encode($responseData);
        echo $json;
        die();
        return false;
    }

    // disable trace and track
}


/* Builder Functions */
function validateDbHost($dbhost) {
    if (!is_string($dbhost)) {
        return 'is not valid data type.';
    }

    if ($dbhost == '') {
        return 'is required.';
    }

    return true;
}


function validateDbUserAndName($dbname) {
    if (!is_string($dbname)) {
        return 'is not valid data type.';
    }

    if ($dbname == '') {
        return 'is required.';
    }

    if (strlen($dbname) < 2) {
        return 'must be at least 2 characters long.';
    }

    if (strlen($dbname) > 32) {
        return 'must be 32 characters or fewer in length.';
    }

    if (!preg_match('/^([A-Za-z0-9\-\_]+)$/', $dbname)) {
        return 'must be alphanumeric and can contain one dash(-) or one underscore (_). 1';
    }

    // $matchCount = preg_match_all('/(\-|\_)/', $dbname, $matches, PREG_SET_ORDER, 0);
    // if ($matchCount > 1) {
    //     return 'must be alphanumeric and can contain one dash(-) or one underscore (_). 2';
    // }

    return true;
}


function validateDbPass($dbpass) {
    if (!is_string($dbpass)) {
        return 'is not valid data type.';
    }

    if ($dbpass == '') {
        return 'is required.';
    }

    if (strlen($dbpass) < 8) {
        return 'must be at least 8 characters long.';
    }

    if (strlen($dbpass) > 32) {
        return 'must be 32 characters or fewer in length.';
    }

    return true;
}


function dbInputFieldsCheck($userInp, $userErr) {
    if ($userInp["dbhost"] == "" || $userInp["dbuser"] == "" || $userInp["dbpass"] == "" || $userInp["dbname"] == "") {
        return "All fields are required.";
         false;
    }

    if ($userErr["dbhost"] != "" || $userErr["dbuser"] != "" || $userErr["dbpass"] != "" || $userErr["dbname"] != "") {
        return "Please correct the following errors.";
        return false;
    }

    return true;
}


function validateSiteUrl($url) {
    if (!is_string($url)) {
        return 'is not valid data type.';
    }

    if ($url == '') {
        return 'is required.';
    }
    
    if (!preg_match('/[A-Za-z0-9]$/', $url)) {
        return 'must not end with /.';
    }

    return true;
}



function validateSiteTzone($tzone, $tzones) {
    if (!is_string($tzone)) {
        return 'is not valid data type.';
    }

    if ($tzone == '') {
        return 'is required.';
    }

    if (!in_array($tzone, $tzones)) {
        return 'invalid timezone.';
    }

    return true;
}


function siteInputFieldsCheck($userInp, $userErr) {
    if ($userInp["url"] == "" || $userInp["tzone"] == "") {
        return "All fields are required.";
    }

    if ($userErr["url"] != "" || $userErr["tzone"] != "") {
        return "Please correct the following errors.";
    }

    return true;
}


/*  Registration Functions  */
function validateSlug($slug) {
    if (!is_string($slug)) {
        return 'is not vaid data type.';
    }

    if ($slug == '') {
        return 'is required.';
    }

    if (strlen($slug) < 2) {
        return 'must be at least 2 characters long.';
    }

    if (strlen($slug) > 16) {
        return 'must be 16 characters or fewer in length.';
    }

    if (!preg_match('/^([0-9a-zA-Z\_]+)([\-\.\']?[0-9a-zA-Z\_]+)?$/', $slug)) {
        return 'must be alphanumeric and can contain one of these (underscore , dash, and dot). 1';
    }

    $matchCount = preg_match_all('/[\-\.\'\_]/', $slug, $matches, PREG_SET_ORDER, 0);
    if ($matchCount > 1) {
        return 'must be alphanumeric and can contain one of these (underscore , dash, and dot). 1';
    }

    return true;
}


function validateUserEmail($useremail) {
    if (!is_string($useremail)) {
        return 'is not vaid data type.';
    }

    if ($useremail == '') {
        return 'is required.';
    }

    $useremail = filter_var($useremail, FILTER_SANITIZE_EMAIL);
    if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        return 'is not a valid email address.';
    }

    $useremail = strtolower($useremail);

    $splitEmail = explode('@', $useremail);
    $emailUsername = $splitEmail[0];
    if (strlen($emailUsername) > 64) {
        return 'can have 64 characters or fewer in length.';
    }

    if (!preg_match('/^([0-9a-zA-Z]+)((-|_|\.)?[0-9a-zA-Z]+)?+$/',$emailUsername)) {
        return 'invalid username. 1';
    }

    $emailDomain = $splitEmail[1];
    $allowedDomains = ['gmail.com','yahoo.com','outlook.com','hotmail.com','aol.com'];
    if (!in_array($emailDomain, $allowedDomains)) {
        return 'Please use an email address from a reputable email provider (like GMail or Outlook).';
    }

    return true;
}


function validateUserPass($userpasss) {
    if (!is_string($userpasss)) {
        return 'is not valid data type.';
    }

    if ($userpasss == '') {
        return 'is required.';
    }

    if (strlen($userpasss) < 8) {
        return 'Must be at least 8 characters long.';
    }

    if (strlen($userpasss) > 72) {
        return 'Must be 72 characters or fewer in length.';
    }

    return true;
}


function validateUserCountry($country,$world) {
    if (!is_string($country)) {
       return 'is not valid data type.';
    }

    if ($country == '') {
        return 'is required.';
    }

    if (!array_key_exists($country, $world)) {
        return 'invalid country.';
    }

    return true;
}


function validateUserDob($userdob) {
    if (!is_string($userdob)) {
        return 'is not valid data type.';
    }

    if ($userdob == '') {
        return 'is required.';
    }

    if (!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $userdob)) {
        return 'is invalid pattern error.';
    }

    $fullDate = explode('-', $userdob);
    $year = $fullDate[0];
    $month = $fullDate[1];
    $date = $fullDate[2];

    $howOldIs = date('Y') - $year;

    if ($howOldIs < 18) {
        return 'you are not old enough.';
    }

    if ($howOldIs > 100) {
        return 'you are too old.';
    }

    // month should be 1 to 12
    if ($month < 1 || $month > 12) {
        return 'has invalid month.';
    }

    if ($date < 1) {
        return 'has invalid date. 1';
    }

    $monthWithThirtyOneDays = [1,3,5,7,8,10,12];
    if (in_array($month, $monthWithThirtyOneDays) && $date > 31) {
        return 'has invalid date. 2';
    }

    $monthWithThirtyDays = [4,6,9,11];
    if (in_array($month, $monthWithThirtyDays) && $date > 30) {
        return 'has invalid date. 3';
    }

    $isLeapYear = ($year % 4 == 0) ? true : false;

    // feb month but not leap year
    if ($isLeapYear == false && $month == 2 && ($date > 28)) {
        return 'has invalid date. 4';
    }

    // feb month but leap year
    if ($isLeapYear == true && $month == 2 && $date > 29) {
        return 'has invalid date. 5';
    }

    if (checkdate($month, $date, $year) == false) {
        return 'is invalid chekcdate error.';
    }

    return true;
}


function userInputFieldsCheck($userInp,$userErr) {
    if ($userInp["username"] == "" || $userInp["email"] == "" || $userInp["password"] == "" || $userInp["country"] == "" || $userInp["dob"] == "") {
        return "All fields are required.";
    }

    if ($userErr["username"] != "" || $userErr["email"] != "" || $userErr["password"] != "" || $userErr["country"] != "" || $userErr["dob"] != "") {
        return "Please correct the following errors.";
    }

    return true;
}


function doesSlugExistInDb($slug,$con) {
    $selectUsernameQuery = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $con->prepare($selectUsernameQuery);

    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row === NULL) {
        return 1;
    } 
    else if ($row["user_name"] == $slug) {
        return "is taken. Try another one.";
    }
    else {
        return "something went wrong with username.";
    }
}


function doesEmailExistInDb($useremail,$con) {
    $selectEmailQuery = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $con->prepare($selectEmailQuery);

    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row === NULL) {
        return 1;
    } 
    else if ($row["user_email"] == $useremail) {
        return "is already used.";
    }
    else {
        return "something went wrong with email.";
    }
}


/*  Token Functions  */
function createAuthToken($data) {
    $header = tokenHeader(true,array('typ'=>'JWT','alg'=>'sha256'));
    $info = tokenData(true,$data);
    $sign = tokenSignature(true,$header,$info);

    $jwt = $header.'.'.$info.'.'.$sign;
    return $jwt;
}


function tokenHeader($token,$tokenHead) {
    $target = ['+','/','='];
    $hunter = ['-','_',''];

    if ($token) {
        $headerPayload = $tokenHead;
        $headerJson = json_encode($headerPayload);
        $headerBase64 = base64_encode($headerJson);
        $headerUrlSafe= str_replace($target,$hunter,$headerBase64);
        return $headerUrlSafe;
    }
    else {
        $headerUrlSafe = $tokenHead;
        $headerBase64 = str_replace($hunter,$target,$headerUrlSafe);
        $headerJson = base64_decode($headerBase64);
        $headerPayload = json_decode($headerJson,true);

        $headerData = array(
            "headerUrlSafe"=>$tokenHead,
            "headerBase64"=>$headerBase64,
            "headerJson"=>$headerJson,
            "headerPayload"=>$headerPayload
        );

        return $headerData;
    }
}


function tokenData($token,$tokenInfo) {
    $target = ['+','/','='];
    $hunter = ['-','_',''];

    if ($token) {
        $infoPayload = $tokenInfo;
        $infoJson = json_encode($infoPayload);
        $infoBase64 = base64_encode($infoJson);
        $infoUrlSafe = str_replace($target,$hunter,$infoBase64);
        return $infoUrlSafe;
    }
    else {
        $infoUrlSafe = $tokenInfo;
        $infoBase64 = str_replace($hunter,$target,$infoUrlSafe);
        $infoJson = base64_decode($infoBase64);
        $infoPayload = json_decode($infoJson,true);

        $data = array(
            "dataUrlSafe"=>$tokenInfo,
            "dataBase64"=>$infoBase64,
            "dataJson"=>$infoJson,
            "dataPayload"=>$infoPayload
        );

        return $data;
    }
}


function tokenSignature($token,$header,$info,$sign = NULL) {
    $target = ['+','/','='];
    $hunter = ['-','_',''];

    $signatureHashed = hash_hmac('sha256',$header.'.'.$info,SECRET,true);
    $signatureBase64 = base64_encode($signatureHashed);
    $signatureUrlSafe = str_replace($target,$hunter,$signatureBase64);

    if ($token) {
        return $signatureUrlSafe;
    }
    else {
        return $sign == $signatureUrlSafe;
    }
}


function decodeAuthToken($token) {
    $split = explode('.',$token);

    $headerUrlSafe = $split[0] ?? "";
    $userUrlSafe = $split[1] ?? "";
    $signatureUrlSafe = $split[2] ?? "";

    $decoded = array(
        "head" => $headerUrlSafe,
        "data" => $userUrlSafe,
        "sign" => $signatureUrlSafe
    );

    return $decoded;
}


function verifyAuthToken($decodedHeader,$decodedUser,$decodedSign) {
    $verifySign = tokenSignature(false,$decodedHeader,$decodedUser,$decodedSign);
    return $verifySign;
}


function createAccessToken($username,$expiresIn = 0) {
    $createdTime = time();
    $expiredTime = $createdTime + $expiresIn;
    $id = md5($createdTime.$username.SECRET);
    $uuid = md5ToUUID($id);
    $user = array('iat'=>$createdTime,'exp'=>$expiredTime,'user'=>$username,'uuid'=> $uuid);
    $accessToken = createAuthToken($user);
    return $accessToken;
}


function createRefreshToken($username,$password) {
    $createdTime = time();
    $id = md5($createdTime.$password.SECRET);
    $uuid = md5ToUUID($id);
    $user = array('iat'=>$createdTime,'user'=>$username,'uuid'=> $uuid);
    $resfreshToken = createAuthToken($user);
    return $resfreshToken;
}


function md5ToUUID($md5Str) {
    $uuid = "";
    for ($i=0;$i<strlen($md5Str);$i++) { 
        switch ($i) {
            case 8:
                $uuid = $uuid . "-" . $md5Str[$i];
                break;
            case 12:
                $uuid = $uuid . "-" . $md5Str[$i];
            break;
            case 16:
                $uuid = $uuid . "-" . $md5Str[$i];
            break;
            case 20:
                $uuid = $uuid . "-" . $md5Str[$i];
            break;
            case 20:
                $uuid = $uuid . "-" . $md5Str[$i];
                break;
            default:
                $uuid = $uuid . $md5Str[$i];
            break;
        }
    }
    return $uuid;
}


function setAuthCookie($key, $value, $cookiePath = "/", $expiresIn = 86400) {
    $name = $key;
    $value = $value;
    $expire = $expiresIn;
    $path = $cookiePath;
    $domain = $_SERVER["HTTP_HOST"];
    $secure = get_protocol();
    $httponly = true;

    setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}


function unsetAuthCookie($key, $value = "") {
    $name = $key;
    $value = $value;
    $expire = time() - 1000;
    $path = "/";
    $domain = $_SERVER["HTTP_HOST"];
    $secure = get_protocol();
    $httponly = true;

    setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}


// csrf token
function createCSRF($id) {
    $options = array("cost"=>8);
    $hashToken = password_hash($id.SECRET,PASSWORD_BCRYPT,$options);
    $removePrefixToken = explode("$2y$08$",$hashToken);
    $csrfToken = $removePrefixToken[1];
    return $csrfToken;
}

function verifyCSRF($id,$token) {
    $csrfToken = $token;
    $addPrefixToken = '$2y$08$'.$csrfToken;
    $verifyToken = password_verify($id.SECRET,$addPrefixToken);
    return $verifyToken;
}


/*  Short Url Functions  */
function validateUrlTarget($urlTarget) {
    $urlTarget = urldecode($urlTarget);

    if ($urlTarget == "") {
        return "is required.";
    }

    if (!filter_var($urlTarget, FILTER_VALIDATE_URL)) {
        return "is not valid url. 1";
    }

    if (!preg_match("/^[A-Za-z0-9\-._~:\/?@!$&()*+,;='#]+$/", $urlTarget)) {
        return "is not valid url. 2";
    }

    if (strlen($urlTarget) > 600) {
        return "must be 600 chars or fewer in length.";
    }

    $urlParts = parse_url($urlTarget);
    if(isset($urlParts["host"])) {
        $urlTarget = preg_replace("/^www\./", "", $urlParts["host"]);
    }

    if ($_SERVER["HTTP_HOST"] == $urlTarget) {
        return $_SERVER["HTTP_HOST"] . " urls are not allowed.";
    }

    return 1;
}


function isUrlStartsWithOurDomain($webDomain,$reportUrl) {

    $urlHost = urldecode($reportUrl);

    $urlParts = parse_url($urlHost);
    if(isset($urlParts["host"])) {
        $urlHost = preg_replace('/^www\./', '', $urlParts['host']);
    }

    if ($_SERVER["HTTP_HOST"] != $urlHost) {
        return 0;
    }

    return 1;
}

function validateUrlHunter($urlCode) {
    $urlCode = urldecode($urlCode);

    if ($urlCode == "") {
        return "is required";
    }

    $first = 0;
    $last = strlen($urlCode) - 1;

    if ($urlCode[$first] == "-" || $urlCode[$first] == "_" || $urlCode[$last] == "-" || $urlCode[$last] == "_") {
        return 'must begin and end with a letter.';
    }

    // isAlphaNumeric 
    if (!preg_match("/^[A-Za-z0-9]((-|_)?[A-Za-z0-9])*$/", $urlCode)) {
        return "must be alphanum and can contain (_), (-).";
    }

    if (strlen($urlCode) < 1 || strlen($urlCode) > 64) {
        return "must be between 1 and 64 characters.";
    }

    $dirContent = scandir("../");
    if (in_array($urlCode, $dirContent)) {
        return "is reserved name.";
    }

    return 1;
}


function validateReportMsg($reportMsg) {
    if ($reportMsg == '') {
        return 1;
    }

    if (!preg_match("/^[A-Za-z0-9\-._~:\/?@!$&()*+,;='#\s]+$/", $reportMsg)) {
        return "must contain some invalid chars.";
    }

    if (strlen($reportMsg) > 280) {
        return "must be 280 chars or fewer in length.";
    }

    return 1;
}


function validateUrlSecret($urlPassword) {
    if ($urlPassword == '') {
        return 1;
    }

    if (strlen($urlPassword) < 3 || strlen($urlPassword) > 24) {
        return "must be between 3 and 24 characters long.";
    }

    return 1;
}


function generateUrlCode() {
    $allowedChars = [
        "A","a","B","b","C","c","D","d","E","e","F","f","G","g","H","h","I","i","J","j","K","k","L","l","M","m",
        "N","n","O","o","P","p","Q","q","R","r","S","s","T","t","U","u","V","v","W","w","X","x","Y","y","Z","z",
        "0","1","2","3","4","5","6","7","8","9"
    ];

    shuffle($allowedChars);

    $min = 0; $max = 61;
    $urlCode = "";
    $loopStart = true;

    while($loopStart) {
        for ($codeLen=6; $codeLen > 0; $codeLen--) { 
            $randomNum = mt_rand($min, $max);
            $urlCode = $urlCode . $allowedChars[$randomNum];
        }

        $isUrlCodeUnique = doesUrlCodeExist($urlCode);
        if ($isUrlCodeUnique == 1) {
            $loopStart = false;
            break;
        }
        else {
            $urlCode = "";
        }
    }

    return $urlCode;
}


function doesUrlCodeExist($urlCode) {
    global $mysqli;

    $query = "SELECT * FROM urls WHERE url_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $urlCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->free_result();
    $stmt->close();

    if ($row === NULL) {
        return 1;
    } 
    else if ($row["url_code"] == $urlCode) {
        return "is already used.";
    }
    else {
        return "something went wrong with email.";
    }
}



// dates
function getBetweenDates($startDate, $finalDate, $formatDate, $byWhich) {
    $rangeArray = [];

    $startDate = strtotime($startDate);
    $finalDate = strtotime($finalDate);

    $days = [31,28,31,30,31,30,31,31,30,31,30,31];

    while($startDate <= $finalDate) {

        $year = date("Y", $startDate);
        $month = date("n", $startDate);
        $daysInMonth = $days[$month - 1];
        $daysInYear = 365;
        $daysInWeek =  7;

        $secInDay = 86400;

        $isLeapYear = ($year % 4 == 0) ? true : false;
        if ($isLeapYear == true && $month == 2) {
            $daysInMonth = 29;
            $daysInYear = 366;
        }

        switch ($byWhich) {
            case 'year':
                if ($isLeapYear == true) {
                    $daysInMonth = 29;
                    $daysInYear = 366;
                }
                $duration = $secInDay * $daysInYear;
                break;
            case 'month':
                $duration = $secInDay * $daysInMonth;
                break;
            case 'day':
                $duration = $secInDay;
                break;
            case 'hour':
                $duration = 3600;
                break;
            default:
                $duration = $secInDay;
                break;
        }

        $date = date($formatDate, $startDate);
        $rangeArray[] = $date;

        $startDate = $startDate + $duration;
    }

    return $rangeArray;
}




// admins
function allowed($rank) {
    global $uInfo;

    if ($uInfo["role"] >= $rank) {
        return true;
    } else {
        return false;
    }
}


function getRoleName($rank) {
    switch ($rank) {
        case 500:
            return "admin";
        case 400:
            return "mod";
        case 300:
            return "vip";
        case 200:
            return "user";
        default:
            // 100
            return "guest";
    }
}


function allowByWebSetting($setting) {
    if ($setting == 1) {
        return true;
    } else {
        return false;
    }
}


function allowByUserRole($userRole) {
    global $user_info;

    if ($user_info["role"] >= $userRole) {
        return true;
    } else {
        return false;
    }
}


function allowByUserAction($actionRank) {
    global $user_info;

    if ($user_info["role"] >= $actionRank) {
        return true;
    } else {
        return false;
    }
}


function isBelongToMe($id) {
    global $user_info;

    if ($user_info["id"] == $id) {
        return true;
    } else {
        return false;
    }
}


function isGreaterOrEqualRank($role) {
    global $user_info;

    if ($user_info["role"] <= $role) {
        return true;
    } else {
        return false;
    }
}




// while update
function validateUsername($username) {
    if (strlen($username) < 2) {
        return 'must be at least 2 characters long.';
    }

    if (strlen($username) > 16) {
        return 'must be 16 characters or fewer in length.';
    }

    if (!preg_match('/^([0-9a-zA-Z\_]+)([\-\.\']?[0-9a-zA-Z\_]+)?$/', $username)) {
        return 'must be alphanumeric and can contain one of these (underscore , dash, and dot). 1';
    }

    $matchCount = preg_match_all('/[\-\.\'\_]/', $username, $matches, PREG_SET_ORDER, 0);
    if ($matchCount > 1) {
        return 'must be alphanumeric and can contain one of these (underscore , dash, and dot). 1';
    }

    return true;
}

function validateEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'is not a valid email address.';
    }

    $email = strtolower($email);

    $splitEmail = explode('@', $email);

    // email username check
    $emailUsername = $splitEmail[0];
    if (strlen($emailUsername) > 64) {
        return 'can have 64 characters or fewer in length.';
    }

    if (!preg_match('/^([0-9a-zA-Z]+)((-|_|\.)?[0-9a-zA-Z]+)?+$/',$emailUsername)) {
        return 'invalid username. 1';
    }

    // email domain check
    $emailDomain = $splitEmail[1];
    $allowedDomains = ['gmail.com','yahoo.com','outlook.com','hotmail.com','aol.com'];
    if (!in_array($emailDomain, $allowedDomains)) {
        return 'Please use an email address from a reputable email provider (like GMail or Outlook).';
    }

    return true;
}

function validateDob($dob) {
    if (!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $dob)) {
        return 'is invalid pattern error.';
    }

    $fullDate = explode('-', $dob);
    $year = $fullDate[0];
    $month = $fullDate[1];
    $date = $fullDate[2];

    $howOldIs = date('Y') - $year;

    if ($howOldIs < 18) {
        return 'you are not old enough.';
    }

    if ($howOldIs > 100) {
        return 'you are too old.';
    }

    // month should be 1 to 12
    if ($month < 1 || $month > 12) {
        return 'has invalid month.';
    }

    if ($date < 1) {
        return 'has invalid date. 1';
    }

    $monthWithThirtyOneDays = [1,3,5,7,8,10,12];
    if (in_array($month, $monthWithThirtyOneDays) && $date > 31) {
        return 'has invalid date. 2';
    }

    $monthWithThirtyDays = [4,6,9,11];
    if (in_array($month, $monthWithThirtyDays) && $date > 30) {
        return 'has invalid date. 3';
    }

    $isLeapYear = ($year % 4 == 0) ? true : false;

    // feb month but not leap year
    if ($isLeapYear == false && $month == 2 && ($date > 28)) {
        return 'has invalid date. 4';
    }

    // feb month but leap year
    if ($isLeapYear == true && $month == 2 && $date > 29) {
        return 'has invalid date. 5';
    }

    if (checkdate($month, $date, $year) == false) {
        return 'is invalid chekcdate error.';
    }

    return true;
}


function validateCountry($country) {
    if (!array_key_exists($country, $world)) {
        return 'invalid country.';
    }

    return true;
}


function validatePassword($password) {
    if (strlen($password) < 8) {
        return 'Must be at least 8 characters long.';
    }

    if (strlen($password) > 72) {
        return 'Must be 72 characters or fewer in length.';
    }

    return true;
}





function getIp() {
    $ipSource  = array(
        "client" => "",
        "real" => "",
        "forwarded" => "",
        "forwardedfor" => "",
        "xforwarded" => "",
        "xforwardedfor" => "",
        "remote" => "",
        "cloud" => "",
    );

    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        $ipSource["client"] = trim($_SERVER["HTTP_CLIENT_IP"]);
    }

    if (isset($_SERVER["HTTP_X_REAL_IP"])) {
        $ipSource["real"] = trim($_SERVER["HTTP_X_REAL_IP"]);
    }

    if (isset($_SERVER["HTTP_FORWARDED"])) {
        $ipSource["forwarded"] = trim($_SERVER["HTTP_FORWARDED"]);
    }

    if (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        $ipSource["forwardedfor"] = trim($_SERVER["HTTP_FORWARDED_FOR"]);
    }

    if (isset($_SERVER["HTTP_X_FORWARDED"])) {
        $ipSource["xforwarded"] = trim($_SERVER["HTTP_X_FORWARDED"]);
    }

    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ipSource["xforwardedfor"] = trim($_SERVER["HTTP_X_FORWARDED_FOR"]);
    }

    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $ipSource["cloud"] = trim($_SERVER["HTTP_CF_CONNECTING_IP"]);
    }

    if (isset($_SERVER["REMOTE_ADDR"])) {
        $ipSource["remote"] = trim($_SERVER["REMOTE_ADDR"]);
    }


    $ip = array_filter($ipSource, function($v, $k) {
        // echo $v . $k . "\n";
        if (filter_var($v, FILTER_VALIDATE_IP)) {
            return $v;
        }
    }, ARRAY_FILTER_USE_BOTH);

    return $ip;
}










?>