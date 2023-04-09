<?php

    $allowCreateTarget = allowByWebSetting($webSettings["urlTarget"]) && allowByUserAction($userActions["urlTarget"]);
    $allowCreateHunter = allowByWebSetting($webSettings["urlHunter"]) && allowByUserAction($userActions["urlHunter"]);
    $allowCreateSecret = allowByWebSetting($webSettings["urlSecret"]) && allowByUserAction($userActions["urlSecret"]);

    $allowSearch = allowByWebSetting($webSettings["urlSearch"]) && allowByUserAction($userActions["urlSearch"]);
    $allowSort = allowByWebSetting($webSettings["urlSort"]) && allowByUserAction($userActions["urlSort"]);
    $allowOrder = allowByWebSetting($webSettings["urlOrder"]) && allowByUserAction($userActions["urlOrder"]);

    $allowHunter = allowByWebSetting($webSettings["hunterurl"]) && allowByUserAction($userActions["hunterurl"]);
    $allowTarget = allowByWebSetting($webSettings["targeturl"]) && allowByUserAction($userActions["targeturl"]);
    $allowVisits = allowByWebSetting($webSettings["views"]) && allowByUserAction($userActions["views"]);
    $allowCreatedOn = allowByWebSetting($webSettings["createdOn"]) &&  allowByUserAction($userActions["createdOn"]);
    $allowCreatedBy = allowByWebSetting($webSettings["createdBy"]) && allowByUserAction($userActions["createdBy"]);
    $allowDeleted = allowByWebSetting($webSettings["deleted"]) && allowByUserAction($userActions["deleted"]);
    $allowReport = allowByWebSetting($webSettings["report"]) && allowByUserAction($userActions["report"]);

    // for actions
    $allowSecure = allowByWebSetting($webSettings["protected"]) && allowByUserAction($userActions["protected"]);
    $allowStats = allowByWebSetting($webSettings["urlStats"]) && allowByUserAction($userActions["urlStats"]);
    $allowUpdate = allowByWebSetting($webSettings["urlUpdate"]) && allowByUserAction($userActions["urlUpdate"]);
    $allowDelete = allowByWebSetting($webSettings["urlDelete"]) && allowByUserAction($userActions["urlDelete"]);

    // for update url componenet
    $allowUpdateTarget = allowByWebSetting($webSettings["urlUpdateTarget"]) && allowByUserAction($userActions["urlUpdateTarget"]);
    $allowUpdateHunter = allowByWebSetting($webSettings["urlUpdateHunter"]) && allowByUserAction($userActions["urlUpdateHunter"]);
    $allowUpdateSecret = allowByWebSetting($webSettings["urlUpdateSecret"]) && allowByUserAction($userActions["urlUpdateSecret"]);

    // url report
    $allowUrlReport = allowByWebSetting($webSettings["urlReport"]) && allowByUserAction($userActions["urlReport"]);

    // for update user info
    $allowUpdateUsername = allowByWebSetting($webSettings["userUpdateUsername"]) && allowByUserAction($userActions["userUpdateUsername"]);
    $allowUpdateEmail = allowByWebSetting($webSettings["userUpdateEmail"]) && allowByUserAction($userActions["userUpdateEmail"]);
    $allowUpdateCountry = allowByWebSetting($webSettings["userUpdateCountry"]) && allowByUserAction($userActions["userUpdateCountry"]);
    $allowUpdateDob = allowByWebSetting($webSettings["userUpdateDob"]) && allowByUserAction($userActions["userUpdateDob"]);

    // adminMod
    $allowToUpdateOther = allowByUserAction($userActions["canUpdateLink"]);
    $allowToDeleteOther = allowByUserAction($userActions["canDeleteLink"]);
    $allowToCheckStats = allowByUserAction($userActions["canCheckStats"]);


    $scriptName = $_SERVER["SCRIPT_NAME"];
    $pageName = pathinfo($scriptName)["filename"];

    $allowedPage = ["index","myurls","panel"];
    $panelPage = $pageName == "panel";
    $myUrlPage = $pageName == "myurls";
    $validPageName = $panelPage || $myUrlPage;


    // ($panelPage && $allowToUpdateOther)
    // ($myUrlPage && $allowUpdate)

?>