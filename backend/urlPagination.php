<?php

    include("./config.php");

    $userInputs = array(
        'userId' => 0, 'pageNum' => '',
        'searchText' => '', 'sortBy' => '', 'orderBy' => '',
        'limit' => 5, 'offset' => 0, 'pageName' => 'home', 
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    $allowSearch = allowByWebSetting($webSettings["urlSearch"]) && allowByUserAction($userActions["urlSearch"]);
    $allowSort = allowByWebSetting($webSettings["urlSort"]) && allowByUserAction($userActions["urlSort"]);
    $allowOrder = allowByWebSetting($webSettings["urlOrder"]) && allowByUserAction($userActions["urlOrder"]);


    $requiredFields = array(
        'userId' => false, 'pageNum' => true,
        'searchText' => $allowSearch, 'sortBy' => $allowSort, 'orderBy' => $allowOrder,
        'limit' => false, 'offset' => false, 'pageName' => false,
    );


    // keys setup
    foreach ($userInputs as $key => $value) {
        if (array_key_exists($key,$_POST) && $requiredFields[$key]) {
            $userInputs[$key] = trim($_POST[$key]);
        }
    }


    if (isset($_SERVER["HTTP_REFERER"])) {
        $userInputs["pageName"] = trim($_SERVER["HTTP_REFERER"]);
    }

    if (!is_numeric($userInputs["pageNum"]) || $userInputs["pageNum"] < 0) {
        $userInputs["pageNum"] = 1;
    }


    $allowHunter = allowByWebSetting($webSettings["hunterurl"]) && allowByUserAction($userActions["hunterurl"]);
    $allowTarget = allowByWebSetting($webSettings["targeturl"]) && allowByUserAction($userActions["targeturl"]);
    $allowVisits = allowByWebSetting($webSettings["views"]) && allowByUserAction($userActions["views"]);
    $allowCreatedOn = allowByWebSetting($webSettings["createdOn"]) &&  allowByUserAction($userActions["createdOn"]);
    $allowCreatedBy = allowByWebSetting($webSettings["createdBy"]) && allowByUserAction($userActions["createdBy"]);
    $allowDeleted = allowByWebSetting($webSettings["deleted"]) && allowByUserAction($userActions["deleted"]);
    $allowReport = allowByWebSetting($webSettings["report"]) && allowByUserAction($userActions["report"]);


    // for actions
    $allowProtected = allowByWebSetting($webSettings["protected"]) && allowByUserAction($userActions["protected"]);
    $allowStats = allowByWebSetting($webSettings["urlStats"]) && allowByUserAction($userActions["urlStats"]);
    $allowUpdate = allowByWebSetting($webSettings["urlUpdate"]) && allowByUserAction($userActions["urlUpdate"]);
    $allowDelete = allowByWebSetting($webSettings["urlDelete"]) && allowByUserAction($userActions["urlDelete"]);


    // adminMod
    $allowToUpdateOther = allowByUserAction($userActions["canUpdateLink"]);
    $allowToDeleteOther = allowByUserAction($userActions["canDeleteLink"]);
    $allowToCheckStats = allowByUserAction($userActions["canCheckStats"]);


    $userInputs["pageNum"] = $mysqli->real_escape_string($userInputs["pageNum"]);
    $userInputs["pageName"] = $mysqli->real_escape_string($userInputs["pageName"]);
    $userInputs["searchText"] = $mysqli->real_escape_string($userInputs["searchText"]);
    $userInputs["sortBy"] = $mysqli->real_escape_string($userInputs["sortBy"]);
    $userInputs["orderBy"] = $mysqli->real_escape_string($userInputs["orderBy"]);



    // targetId Will be decided using these pages
    $allowedPage = ["home","myurls","panel"];
    $userInputs["pageName"] = pathinfo($userInputs["pageName"], PATHINFO_FILENAME);
    $panelPage = $userInputs["pageName"] == "panel";
    $myUrlPage = $userInputs["pageName"] == "myurls";
    $validPageName = $panelPage || $myUrlPage;

    $allowSearchBy = "%".$userInputs["searchText"]."%";
    $allowSortBy = array();
    $allowOrderBy = array(
        "asc" => "ASC",
        "desc" => "DESC"
    );


    // build query
    $tableCols = array();
    $tableJoin = array();
    $whereCols = array();
    $whereVals = array();
    $whereTyps = array();
    $sortBy = array();
    $orderBy = array();


    if ($allowHunter) {
        array_push($tableCols,"urls.url_code as hunterCode");
    }

    if ($allowVisits) {
        array_push($tableCols,"urls.url_visits as totalVisits");
        $allowSortBy["views"] = "urls.url_visits";
    }

    if ($allowCreatedOn) {
        array_push($tableCols,"urls.url_createdAt as createdAt");
        $allowSortBy["created"] = "urls.url_createdAt";
    }

    if ($validPageName && $allowTarget) {
        array_push($tableCols,"urls.url_long as targetUrl");
    }

    // to show report count
    if ($validPageName && $allowReport) {
        array_push($tableCols,"urls.url_reports as report");
        $allowSortBy["reports"] = "urls.url_reports";
    }

    // it shows only id of user, who created url which again helpful to update or delete url
    if ($validPageName && $allowCreatedBy) {
        array_push($tableCols,"urls.url_createdBy as createdBy");
    }

    // to show key icon on frontend
    if ($validPageName && $allowProtected) {
        array_push($tableCols,"(CASE WHEN urls.url_password = '' THEN 0 ELSE 1 END) as protected");
    }

    // show username of creator of url
    if ($panelPage && $allowCreatedBy) {
        array_push($tableCols,"users.user_name as username");
        array_push($tableJoin,"LEFT JOIN users ON urls.url_createdBy = users.user_id");
    }

    // show updated date
    if (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) {
        array_push($tableCols,"urls.url_updatedAt as updatedAt");
        $allowSortBy["updated"] = "urls.url_updatedAt";
    }

    // url id is important to update or delete url
    // if ($validPageName && ($allowUpdate || $allowDelete)) {
    //     array_push($tableCols,"urls.url_id as linkId");
    // }

    if (
        (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) ||
        (($panelPage && $allowToDeleteOther) || ($myUrlPage && $allowDelete))
    ) {
        array_push($tableCols,"urls.url_id as linkId");
    }

    // to show deleted links
    if ($panelPage && $allowDeleted) {
        array_push($tableCols,"urls.url_deleted as deleted");
        $allowSortBy["deleted"] = "urls.url_deleted";
    }


    if ($panelPage) {

        if (allowByUserRole($roles["mod"])) {
            array_push($whereCols, "urls.url_createdBy >= ?");
            array_push($whereVals, 0);
        } else {
            array_push($whereCols, "urls.url_createdBy = ?");
            array_push($whereVals, $user_info["id"]);
        }

        array_push($whereTyps, "i");
    } else if ($myUrlPage) {
        array_push($whereCols, "urls.url_createdBy = ?");
        array_push($whereVals, $user_info["id"]);
        array_push($whereTyps, "i");
    } else {
        array_push($whereCols, "urls.url_createdBy = ?");
        array_push($whereVals, 0);
        array_push($whereTyps, "i");
    }


    // show or hide deleted url according to allowed role
    if ($allowDeleted && $panelPage) {
        array_push($whereCols, "AND urls.url_deleted >= 0");
    } else {
        array_push($whereCols, "AND urls.url_deleted = 0");
    }


    // %.$userInputs["searchText"].%
    if ($isLoggedIn && $allowSearch && $panelPage) {

        if (allowByUserRole($roles["mod"]) && $allowCreatedBy) {
            array_push($whereCols, "AND (urls.url_long LIKE ? OR urls.url_code LIKE ? OR users.user_name LIKE ?)");
            array_push($whereVals, $allowSearchBy);
            array_push($whereTyps, "s");
            array_push($whereVals, $allowSearchBy);
            array_push($whereTyps, "s");
            array_push($whereVals, $allowSearchBy);
            array_push($whereTyps, "s");
        } else {
            array_push($whereCols, "AND (urls.url_long LIKE ? OR urls.url_code LIKE ?)");
            array_push($whereVals, $allowSearchBy);
            array_push($whereTyps, "s");
            array_push($whereVals, $allowSearchBy);
            array_push($whereTyps, "s");
        }

    } else if ($isLoggedIn && $allowSearch && $myUrlPage) {
        array_push($whereCols, "AND (urls.url_long LIKE ? OR urls.url_code LIKE ?)");
        array_push($whereVals, $allowSearchBy);
        array_push($whereTyps, "s");
        array_push($whereVals, $allowSearchBy);
        array_push($whereTyps, "s");
    } else if ($allowSearch) {
        array_push($whereCols, "AND urls.url_code LIKE ?");
        array_push($whereVals, $allowSearchBy);
        array_push($whereTyps, "s");
    }


    //  sort and order by
    if ($allowSort && array_key_exists($userInputs["sortBy"], $allowSortBy)) {
        array_push($sortBy,$allowSortBy[$userInputs["sortBy"]]);
    } else {
        array_push($sortBy,"urls.url_createdAt");
    }

    if ($allowOrder && array_key_exists($userInputs["orderBy"], $allowOrderBy)) {
        array_push($orderBy,$allowOrderBy[$userInputs["orderBy"]]);
    } else {
        array_push($orderBy,"DESC");
    }


    // query Config
    $pagination = array();
    $pagination["tblCols"] = implode(", ", $tableCols);
    $pagination["tblJoin"] = implode(", ", $tableJoin);
    $pagination["whereCols"] = implode(" ", $whereCols);
    $pagination["whereVals"] = $whereVals;
    $pagination["whereTyps"] = implode("", $whereTyps);
    $pagination["sortBy"] = implode("", $sortBy);
    $pagination["orderBy"] = implode("", $orderBy);;
    $pagination["limit"] = 5;
    $pagination["offset"] = $pagination["limit"] * ($userInputs["pageNum"] - 1);



    // total urls
    $urlCountQuery = "SELECT COUNT(url_id) as totalurls
    FROM urls {$pagination["tblJoin"]}
    WHERE {$pagination["whereCols"]}";




    $urlCountStmt = $mysqli->prepare($urlCountQuery);
    $urlCountStmt->bind_param($pagination["whereTyps"],...$pagination["whereVals"]);
    $urlCountStmt->execute();
    $urlCountResult = $urlCountStmt->get_result();
    $urlCountRow = $urlCountResult->fetch_assoc();
    $urlCountStmt->close();


    // pagination config
    $totalURLs = $urlCountRow["totalurls"];
    $minPageNo = 1;
    $maxPageNo = ceil($totalURLs / $pagination["limit"]);
    $pagesGroup = 3;
    $centerPage = floor($pagesGroup / 2);
    $pageNo = $userInputs["pageNum"];


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

    if ($totalURLs == 0) {
        $prevPage = $minPageNo = $pageNo = $nextPage = $maxPageNo = 0;
    }


    $selectAllUrlQuery = "SELECT {$pagination["tblCols"]} 
    FROM urls {$pagination["tblJoin"]}
    WHERE {$pagination["whereCols"]}
    Order By {$pagination["sortBy"]} {$pagination["orderBy"]}
    LIMIT {$pagination["limit"]} OFFSET {$pagination["offset"]}";

    $selectAllUrlStmt = $mysqli->prepare($selectAllUrlQuery);
    $selectAllUrlStmt->bind_param($pagination["whereTyps"],...$pagination["whereVals"]);
    $selectAllUrlStmt->execute();
    $selectAllUrlResult = $selectAllUrlStmt->get_result();
    $selectAllUrlRow = $selectAllUrlResult->fetch_all(MYSQLI_ASSOC);
    $selectAllUrlStmt->close();

    $pagination["urlCountQuery"] = $urlCountQuery;
    $pagination["selectAllUrlQuery"] = $selectAllUrlQuery;


    $userData = array(
        "domain" => $domain,
        "totalURLs" => $totalURLs,
        "pagesData" => $selectAllUrlRow,
        "dataPerPage" => $pagination["limit"],
        "prevPage" => $prevPage,
        "firstPage" => $minPageNo,
        "currentPage" => $pageNo,
        "nextPage" => $nextPage,
        "lastPage" => $maxPageNo,
        "pagesNum" => $pages,
        // "query" => $pagination
    );


    $response =  makeResponse("success", 200, $userData, NULL);
    echo json_encode($response);

    $mysqli->close();
?>