<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>PhpShort - A url shortener</title>
        <meta name="description" content="phpshort allows to reduce long links from Instagram, Facebook, YouTube, Twitter, Linked In and top sites on the Internet, just paste the long URL and click the Shorten URL button." />
        <meta name="keywords" content="short links, tinyurl, shortener, link shortener, branded urls, custom url shortener, free url shortener, click stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />

        <link rel="stylesheet" href="src/icons/css/all.min.css" />
        <script type="text/javascript" src="src/js/tailwind324.js"></script>
        <script type="text/javascript" src="src/js/jQuery341.js"></script>

        <script type="text/javascript" src="./installer/install.js"></script>
    </head>
    <body>
        <div class="relative flex min-h-screen flex-col bg-gray-200 py-12">
            <div class="relative lg:w-6/12 w-10/12 mx-auto">
                <div id="header" class="bg-gray-900 p-2 rounded-sm shadow-lg">
                    <img src="src/img/logo.png" class="h-8 mx-auto" alt="product logo" />
                    <span></span>
                </div>
                <div id="content">
<?php

if (isset($checkInstall)) {
    switch ($checkInstall) {
        case 2:
            include("installer/install.php");
            break;
        case 3:
            include("installer/connection.php");
            break;
        default:
            include("installer/ending.php");
            break;
    }
}
?>
                </div>
            </div>
        </div>
        <div class="saved_data"><span class="saved_span"></span></div>
    </body>
</html>