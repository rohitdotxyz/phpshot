<?php

    include('./../backend/database.php');
    if ($isInstalled == 1) {
        die();
    }

    include('./../backend/countries.php');
    include('./../backend/timezones.php');
    include('./../backend/functions.php');


    $userInputs = array(
        'dbhost' => '', 'dbuser' => '', 'dbpass' => '', 'dbname' => '',
        'url'=> '', 'tzone' => '',
        'username' => '', 'email' => '', 'role' => 500, 'password' => '',  'dob' => '', 'country' => ''
    );

    $userErrors = array(
        'database' => '', 'system' => '', 'site' => '', 'owner' => '',
        'dbhost' => '', 'dbuser' => '', 'dbpass' => '', 'dbname' => '',
        'url'=> '', 'tzone' => '',
        'username' => '', 'email' => '', 'password' => '',  'dob' => '', 'country' => ''
    );


    requestCheck($_SERVER['REQUEST_METHOD'], 'POST');


    // database
    if (isset($_POST['dbhost'])) {
        $userInputs['dbhost'] = trim($_POST['dbhost']);
    }

    if (isset($_POST['dbuser'])) {
        $userInputs['dbuser'] = trim($_POST['dbuser']);
    }

    if (isset($_POST['dbpass'])) {
        $userInputs['dbpass'] = trim($_POST['dbpass']);
    }

    if (isset($_POST['dbname'])) {
        $userInputs['dbname'] = trim($_POST['dbname']);
    }


    // site
    if (isset($_POST['url'])) {
        $userInputs['url'] = trim($_POST['url']);
    }

    if (isset($_POST['tzone'])) {
        $userInputs['tzone'] = trim($_POST['tzone']);
    }


    // user
    if (isset($_POST['username'])) {
        $userInputs['username'] = trim($_POST['username']);
    }

    if (isset($_POST['email'])) {
        $userInputs['email'] = trim($_POST['email']);
    }

    if (isset($_POST['password'])) {
        $userInputs['password'] = trim($_POST['password']);
    }

    if (isset($_POST['dob'])) {
        $userInputs['dob'] = trim($_POST['dob']);
    }

    if (isset($_POST['country'])) {
        $userInputs['country'] = trim($_POST['country']);
    }


    // system
    $isWindows = preg_match("/^Windows/", php_uname("s"));

    if ($isWindows != 1) {
        if (!is_dir("../backend") && !is_writable("../backend/database.php")) {
            $userErrors["system"] = "Please set 755 permission to /backend/database.php file.";
        }
    }


    // database validation
    $isValidDbHost = validateDbHost($userInputs['dbhost']);
    if (is_string($isValidDbHost)) {
        $userErrors['dbhost'] = $isValidDbHost;
    }

    $isValidDbUser = validateDbUserAndName($userInputs['dbuser']);
    if (is_string($isValidDbUser)) {
        $userErrors['dbuser'] = $isValidDbUser;
    }

    $isValidDbPass = validateDbPass($userInputs['dbpass']);
    if (is_string($isValidDbPass)) {
        $userErrors['dbpass'] = $isValidDbPass;
    }

    $isValidDbName = validateDbUserAndName($userInputs['dbname']);
    if (is_string($isValidDbName)) {
        $userErrors['dbname'] = $isValidDbName;
    }

    $allDbFieldsValid = dbInputFieldsCheck($userInputs, $userErrors);
    if (is_string($allDbFieldsValid)) {
        $userErrors['database'] = $allDbFieldsValid;
    }


    // site validation
    $isValidSiteUrl = validateSiteUrl($userInputs['url']);
    if (is_string($isValidSiteUrl)) {
        $userErrors['url'] = $isValidSiteUrl;
    }

    $isValidSiteTzone = validateSiteTzone($userInputs['tzone'], $timezones);
    if (is_string($isValidSiteTzone)) {
        $userErrors['tzone'] = $isValidSiteTzone;
    }

    $allSiteFieldsValid = siteInputFieldsCheck($userInputs, $userErrors);
    if (is_string($allSiteFieldsValid)) {
        $userErrors['site'] = $allSiteFieldsValid;
    }


    // owner validation
    $isValidUsername = validateSlug($userInputs['username']);
    if (is_string($isValidUsername)) {
        $userErrors['username'] = $isValidUsername;
    }

    $isValidUserEmail = validateUserEmail($userInputs['email']);
    if (is_string($isValidUserEmail)) {
        $userErrors['email'] = $isValidUserEmail;
    }

    $isValidUserPass = validateUserPass($userInputs['password']);
    if (is_string($isValidUserPass)) {
        $userErrors['password'] = $isValidUserPass;
    }

    $isValidUserCountry = validateUserCountry($userInputs['country'],$world);
    if (is_string($isValidUserCountry)) {
        $userErrors['country'] = $isValidUserCountry;
    }

    $isValidUserDob = validateUserDob($userInputs['dob']);
    if (is_string($isValidUserDob)) {
        $userErrors['dob'] = $isValidUserDob;
    }

    $allUserFieldsValid = userInputFieldsCheck($userInputs, $userErrors);
    if (is_string($allUserFieldsValid)) {
        $userErrors['owner'] = $allUserFieldsValid;
    }


    // simple error check
    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    //  make connection to db
    $db = @new mysqli($userInputs["dbhost"], $userInputs["dbuser"], $userInputs["dbpass"], $userInputs["dbname"]);
    if ($db->connect_error || $db->connect_errno) {
        $userErrors["database"] = "$db->connect_error $db->connect_errno";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // create tables if not exists
    $db->query("CREATE TABLE IF NOT EXISTS users (
        user_id int(10) UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT,
        user_name varchar(50) NOT NULL DEFAULT '',
        user_email varchar(100) NOT NULL DEFAULT '',
        user_role int(3) UNSIGNED NOT NULL DEFAULT 100,
        user_password varchar(50) NOT NULL,
        user_country varchar(60) NOT NULL DEFAULT '',
        expiresAt varchar(20) NOT NULL DEFAULT '',
        user_dob DATE,
        user_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id)
        #FOREIGN KEY (user_country) REFERENCES countries(country_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");


    $db->query("CREATE TABLE IF NOT EXISTS urls (
        url_id int(10) UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT,
        url_long varchar(600) NOT NULL DEFAULT '',
        url_code varchar(64) NOT NULL UNIQUE,
        url_password varchar(50) NOT NULL DEFAULT '',
        url_createdBy int(10) UNSIGNED NOT NULL,
        url_visits int(10) UNSIGNED DEFAULT 0,
        url_reports int(10) UNSIGNED DEFAULT 0,
        url_deleted int(1) UNSIGNED DEFAULT 0,
        url_createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        url_updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (url_id)
        #FOREIGN KEY (url_createdBy) REFERENCES users(user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");


    $db->query("CREATE TABLE IF NOT EXISTS visits (
        visit_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        visit_urlId int(10) UNSIGNED NOT NULL,
        visit_referer varchar(200) NOT NULL DEFAULT '',
        br_chrome varchar(1) NOT NULL DEFAULT 0,
        br_firefox varchar(1) NOT NULL DEFAULT 0,
        br_safari varchar(1) NOT NULL DEFAULT 0,
        br_edge varchar(1) NOT NULL DEFAULT 0,
        br_ie varchar(1) NOT NULL DEFAULT 0,
        br_opera varchar(1) NOT NULL DEFAULT 0,
        br_others varchar(1) NOT NULL DEFAULT 0,
        os_windows varchar(1) NOT NULL DEFAULT 0,
        os_linux varchar(1) NOT NULL DEFAULT 0,
        os_macos varchar(1) NOT NULL DEFAULT 0,
        os_android varchar(1) NOT NULL DEFAULT 0,
        os_ios varchar(1) NOT NULL DEFAULT 0,
        os_others varchar(1) NOT NULL DEFAULT 0,
        visit_country varchar(16) NOT NULL DEFAULT '',
        visit_ip varchar(60) NOT NULL DEFAULT '',
        visit_agent varchar(300) NOT NULL DEFAULT '',
        visit_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (visit_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");


    $db->query("CREATE TABLE IF NOT EXISTS reports (
        report_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        report_userId int(10) UNSIGNED NOT NULL DEFAULT 0,
        report_urlId int(10) UNSIGNED NOT NULL DEFAULT 0,
        report_comment varchar(600) NOT NULL DEFAULT '',
        PRIMARY KEY (report_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");


    $db->query("CREATE TABLE IF NOT EXISTS subscriptions (
        sub_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        sub_userId int(10) UNSIGNED NOT NULL,
        sub_At varchar(20) NOT NULL DEFAULT '',
        sub_expAt varchar(20) NOT NULL DEFAULT '',
        sub_invoice varchar(40) NOT NULL DEFAULT '',
        PRIMARY KEY (sub_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");


    // create encryption key
    $encryptionCode = session_create_id("phpshort-");
    // $encryptionCode = "phpshort-j71arp5jlttb2hot4ql6l96oc5";


    // escape input data
    // database data
    $userInputs["dbhost"] = $db->real_escape_string($userInputs["dbhost"]);
    $userInputs["dbuser"] = $db->real_escape_string($userInputs["dbuser"]);
    $userInputs["dbpass"] = $db->real_escape_string($userInputs["dbpass"]);
    $userInputs["dbname"] = $db->real_escape_string($userInputs["dbname"]);

    // site data
    $userInputs["url"] = $db->real_escape_string($userInputs["url"]);
    $userInputs["tzone"] = $db->real_escape_string($userInputs["tzone"]);

    // owner data
    $userInputs["username"] = $db->real_escape_string($userInputs["username"]);
    $userInputs["email"] = $db->real_escape_string($userInputs["email"]);
    $userInputs["role"] = $db->real_escape_string($userInputs["role"]);
    $userInputs["password"] = md5($userInputs["password"].$encryptionCode);
    $userInputs["dob"] = $db->real_escape_string($userInputs["dob"]);
    $userInputs["country"] = $db->real_escape_string($userInputs["country"]);


    // isUnique
    $isUniqeUsername = doesSlugExistInDb($userInputs["username"],$db);
    if ($isUniqeUsername != 1) {
        $userErrors["username"] = $isUniqeUsername;
    }

    $isUniqeEmail = doesEmailExistInDb($userInputs["email"],$db);
    if ($isUniqeEmail != 1) {
        $userErrors["email"] = $isUniqeEmail;
    }

    $allUserFieldsValidAfterDbQuery = userInputFieldsCheck($userInputs,$userErrors);
    if (is_string($allUserFieldsValidAfterDbQuery)) {
        $userErrors['owner'] = $allUserFieldsValidAfterDbQuery;
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // Insert Data into DB
    $insertUserQuery = "INSERT INTO users (user_name, user_email, user_role, user_password, user_country, user_dob) VALUES (?,?,?,?,?,?)";
    $stmt = $db->prepare($insertUserQuery);
    $stmt->bind_param("ssssss", $userInputs["username"], $userInputs["email"], $userInputs["role"], $userInputs["password"], $userInputs["country"], $userInputs["dob"]);
    $stmt->execute();


    // encode
    // database data
    $dbHost = makeoutput($userInputs["dbhost"]);
    $dbUser = makeoutput($userInputs["dbuser"]);
    $dbPass = $userInputs["dbpass"];
    $dbName = makeoutput($userInputs["dbname"]);

    // site data
    $siteUrl = makeoutput($userInputs["url"]);
    $siteTzone = makeoutput($userInputs["tzone"]);

    // owner data
    $adminId = $stmt->insert_id;
    $adminName = makeoutput($userInputs["username"]);
    $adminEmail = makeoutput($userInputs["email"]);
    $adminRole = makeOutput($userInputs["role"]);
    $adminPass = makeOutput($userInputs["password"]);
    $adminDob = makeoutput($userInputs["dob"]);
    $adminCountry = makeoutput($userInputs["country"]);


    $stmt->close();


    $databaseFile = "./../backend/database.php";
    $databaseContent = '
<?php
    // you can edit these lines to configure new setting for your website
    $dbhost = "' . $dbHost . '";
    $dbuser = "' . $dbUser . '";
    $dbpass = "' . $dbPass . '";
    $dbname = "' . $dbName . '";

    // webinfo
    $domain = "'.$siteUrl.'";
    $timezone = "'.$siteTzone.'";

    // Please do not modify this line post installation
    $secret = "' . $encryptionCode . '";
    $isInstalled = 1;
?>';


    $databaseOpen = fopen($databaseFile, "w");
    $databaseWrite = fwrite($databaseOpen, $databaseContent);
    fclose($databaseOpen);


    $data = array(
        'dbhost' => $dbHost,
        'dbuser' => $dbUser,
        'dbpass' => $dbPass,
        'dbname' => $dbName,
        'url'=> $siteUrl,
        'tzone' => $siteTzone,
        'id' => $adminId,
        'username' => $adminName,
        'email' => $adminEmail,
        'role' => $adminRole,
        'password' => $adminPass,
        'dob' => $adminDob,
        'country' => $adminCountry
    );


    $response = makeResponse('success', 201, $data, NULL);
    echo json_encode($response);

    $db->close();
    exit();
?>