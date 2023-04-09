<?php

    include("./config.php");
    include("./countries.php");

    $userInputs = array(
        'pageNum' => '',
        'searchText' => '',
        'sortBy' => '',
        'orderBy' => '',
    );

    $requiredFields = array(
        'pageNum' => true,
        'searchText' => true,
        'sortBy' => true,
        'orderBy' => true,
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    // keys setup
    foreach ($userInputs as $key => $value) {
        if (array_key_exists($key,$_POST) && $requiredFields[$key]) {
            $userInputs[$key] = trim($_POST[$key]);
        }
    }


    if (!is_numeric($userInputs["pageNum"]) || $userInputs["pageNum"] < 0) {
        $userInputs["pageNum"] = 1;
    }

    $sortBy = array(
        "created" => "user_joined",
        "updated" => "user_updated",
        "roles" => "user_role"
    );

    if (array_key_exists($userInputs["sortBy"],$sortBy)) {
        $userInputs["sortBy"] = $sortBy[$userInputs["sortBy"]];
    } else {
        $userInputs["sortBy"] = "user_joined";
    }

    $orderBy = array(
        "asc" => "ASC",
        "desc" => "DESC"
    );

    if (array_key_exists($userInputs["orderBy"],$orderBy)) {
        $userInputs["orderBy"] = $orderBy[$userInputs["orderBy"]];;
    } else {
        $userInputs["orderBy"] = "DESC";
    }

    $userInputs["pageNum"] = $mysqli->real_escape_string($userInputs["pageNum"]);
    $userInputs["searchText"] = $mysqli->real_escape_string($userInputs["searchText"]);
    $userInputs["sortBy"] = $mysqli->real_escape_string($userInputs["sortBy"]);
    $userInputs["orderBy"] = $mysqli->real_escape_string($userInputs["orderBy"]);


    $searchBy = "%".$userInputs["searchText"]."%";
    $searchByRole = "%".$userInputs["searchText"]."%";
    $searchByCountry = "%".$userInputs["searchText"]."%";

    if (array_key_exists($userInputs["searchText"],$roles)) {
        $searchByRole = "%".$roles[$userInputs["searchText"]]."%";
    }

    if (in_array($userInputs["searchText"],$world)) {
        $worldKey = array_search($userInputs["searchText"],$world);
        $searchByCountry = "%".$worldKey."%";
    }


    // total users
    $userCountQuery = "SELECT COUNT(user_id) as totalusers FROM users";
    $userCountStmt = $mysqli->prepare($userCountQuery);
    $userCountStmt->execute();
    $userCountResult = $userCountStmt->get_result();
    $userCountRow = $userCountResult->fetch_assoc();
    $userCountStmt->close();


    // pagination config
    $totalUsers = $userCountRow["totalusers"];
    $pageNo = $userInputs["pageNum"];
    $limit = 5;
    $offset = ($limit * ($userInputs["pageNum"] - 1)) ?? 0;

    $minPageNo = 1;
    $maxPageNo = ceil($totalUsers / $limit);
    $pagesGroup = 3;
    $centerPage = floor($pagesGroup / 2);



    $lastPageInSet = $pageNo + $centerPage;
    if ($lastPageInSet < $pagesGroup) {
        $lastPageInSet = $pagesGroup;
    }

    if ($lastPageInSet > $maxPageNo) {
        $lastPageInSet = $maxPageNo;
    }

    $firstPageInSet = ($lastPageInSet - $pagesGroup) + 1;
    if ($firstPageInSet < $minPageNo) {
        $firstPageInSet = $minPageNo;
    }

    $pages = array();
    for ($i = $firstPageInSet; $i <= $lastPageInSet; $i++) {
        array_push($pages,$i);
    }

    if ($pageNo <= $minPageNo) {
        $prevPage = $minPageNo;
    } else {
        $prevPage = $pageNo - 1;
    }

    if ($pageNo >= $maxPageNo) {
        $nextPage = $maxPageNo;
    } else {
        $nextPage = $pageNo + 1;
    }

    if ($totalUsers == 0) {
        $prevPage = $minPageNo = $pageNo = $nextPage = $maxPageNo = 0;
    }


    $selectAllUserQuery = "SELECT user_id as id, user_name as username, user_email as email, user_role as role, user_country as country, user_dob as dob, user_joined as created, user_updated as updated FROM users
    WHERE user_name LIKE ? OR user_email LIKE ? OR user_role LIKE ? OR user_country LIKE ?
    Order By {$userInputs["sortBy"]} {$userInputs["orderBy"]}
    LIMIT $limit OFFSET $offset";


    $selectAllUserStmt = $mysqli->prepare($selectAllUserQuery);
    $selectAllUserStmt->bind_param("ssss",$searchBy,$searchBy,$searchByRole,$searchByCountry);
    $selectAllUserStmt->execute();
    $selectAllUserResult = $selectAllUserStmt->get_result();
    $selectAllUserRow = $selectAllUserResult->fetch_all(MYSQLI_ASSOC);
    $selectAllUserStmt->close();

    $pagination["userCountQuery"] = $userCountQuery;
    $pagination["selectAllUserQuery"] = $selectAllUserQuery;


    $userData = array(
        "totalUsers" => $totalUsers,
        "pagesData" => $selectAllUserRow,
        "dataPerPage" => $limit,
        "prevPage" => $prevPage,
        "firstPage" => $minPageNo,
        "currentPage" => $pageNo,
        "nextPage" => $nextPage,
        "lastPage" => $maxPageNo,
        "pagesNum" => $pages,
        "query" => $selectAllUserQuery
    );


    $response =  makeResponse("success", 200, $userData, [$searchBy,$searchBy,$searchByRole,$searchByCountry]);
    echo json_encode($response);
    $mysqli->close();
?>