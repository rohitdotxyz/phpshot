<?php

require_once("./backend/config.php");

$page_info = array(
    'name'=> 'redirect',
    'role'=> 100,
    'state' => 0,
);

// loading head tag element
include('./templates/headLoad.php');

?>

<body class=bg-gray-100>

    <form id="urlprotect" class="fixed w-full h-full top-0 left-0 flex items-center justify-center scale-50 opacity-0 invisible transition-all duration-300">
        <div class="min-w-[300px] max-w-[350px] bg-white flex flex-col gap-y-4 text-gray-600 p-4 rounded-md" action="#" method="POST" enctype="application/x-www-form-urlencoded">
            <img class="mx-auto max-h-8" src="http://127.0.0.1/phpshort/src/img/logo.png">

            <div class="text-lg text-center font-semibold">
                <i class="fa fa-link text-black"></i>
                <span> Protected URL</span>
            </div>

            <input type="hidden" name="_csrf" id="urlprotect__csrf" value="<?php echo $csrf; ?>" />
            
            <div class="flex flex-row gap-x-4 text-sm">
                <div class="grow basis-0 shrink">
                    <p>Custom</p>
                    <input id="urlprotect__custom" class="text-gray-200 bg-gray-700 px-2 h-8 rounded-sm outline-none text-ellipsis w-full" type="text" placeholder="custom url code" value="url__protect__custom" />
                </div>
                <div class="grow basis-0 shrink">
                    <p>Password</p>
                    <input id="urlprotect__password" class="text-gray-200 bg-gray-700 px-2 h-8 rounded-sm outline-none text-ellipsis w-full" type="text" placeholder="password" value="url__protect__password" />
                </div>
            </div>

            <input id="urlprotect__submit" type="submit" value="Submit" class="text-gray-200 bg-gray-700 px-2 h-8 text-sm rounded-sm outline-none text-ellipsis w-full hover:bg-sky-500 disabled:bg-gray-500" />
        </div>
    </form>

    <div id="error__modal" class="fixed w-full h-full top-0 left-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm opacity-0 invisible transition-all duration-300 overflow-hidden">
    </div>


    <script src="./src/js/jQuery341.js"></script>
    <script src="./src/util.js"></script>
    <script src="./src/domUtil.js"></script>
    <!-- <script src="./countries.js"></script> -->
    <script src="./src/redirect.js"></script>
</body>
</html>