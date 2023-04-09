        <div id="overlay" class="overlay fixed w-full h-full left-0 top-0 bg-gray-900/90 transition-all duration-500 opacity-0 hidden z-10"></div>

        <div id="success_modal" class="fixed min-w-[300px] max-w-[440px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 bg-white rounded-md overflow-hidden transition-all duration-500 scale-0 opacity-0 p-2 z-20">asfawwfaw</div>

        <!-- <div id="error_modal" class="fixed min-w-[300px] max-w-[440px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 bg-white rounded overflow-hidden flex flex-col gap-y-6 transition-all duration-500 scale-0 opacity-0 p-8"></div> -->

        <div id="error__modal" class="opacity-0 invisible fixed w-full h-full top-0 left-0 flex items-center justify-center z-10 bg-gray-900/90 overflow-hidden backdrop-blur-sm transition-opacity duration-200"></div>

        <footer id="footer" class="bg-gray-900 p-4 mt-4">
            <div class="flex md:flex-row flex-col justify-between max-w-[768px] p-4 lg:p-8 mx-auto text-gray-100">
                <div class="text-sm pt-4">
                    <h3 class="text-base font-medium text-gray-200">Your Brand Name</h3>
                    <div class="flex flex-col ">
                        <a href="#" class="hover:underline">Start Trial</a>
                        <a href="#" class="hover:underline">Pricing</a>
                        <a href="#" class="hover:underline">Download</a>
                    </div>
                </div>

                <div class="text-sm pt-4">
                    <h3 class="text-base font-medium text-gray-200">Resources</h3>
                    <div class="flex flex-col">
                        <a href="#" class="hover:underline">Blog</a>
                        <a href="#" class="hover:underline">Help Center</a>
                        <a href="#" class="hover:underline">Release Notes</a>
                        <a href="#" class="hover:underline">Status</a>
                    </div>
                </div>

                <div class="text-sm pt-4">
                    <h3 class="text-base font-medium text-gray-200">Community</h3>
                    <div class="flex flex-col">
                        <a href="#" class="hover:underline">Twitter</a>
                        <a href="#" class="hover:underline">LinkedIn</a>
                        <a href="#" class="hover:underline">Facebook</a>
                        <a href="#" class="hover:underline">Instagram</a>
                    </div>
                </div>

                <div class="text-sm pt-4">
                    <img class="max-h-8" src="<?php echo $domain; ?>/src/img/logo.png">
                    <div class="flex flex-col">
                        <p>© Copyright 2022</p>
                        <p>Your Studio Design, Inc.</p>
                        <p>All rights reserved</p>
                    </div>
                </div>
            </div>
        </footer>


        <script src="<?php echo $domain; ?>/src/util.js"></script>
        <script src="<?php echo $domain; ?>/src/domUtil.js"></script>


<!-- // todo: -->
<!-- // change indexx to index -->
<?php if ($pageName == "index") { ?>
        <script src="<?php echo $domain; ?>/src/typewriter.js"></script>
<?php } ?>

<?php if (!$isLoggedIn && $pageName == "index") { ?>
        <script src="<?php echo $domain; ?>/src/auth.js"></script>
<?php } ?>

<!-- ok tested -->
<?php if ($pageName == "index" && $allowCreateTarget) { ?>
        <script src="<?php echo $domain; ?>/src/shorturl.js"></script>
<?php } ?>

<?php if ($isLoggedIn && $pageName == "index" && $allowReport) { ?>
        <script src="<?php echo $domain; ?>/src/urlReport.js"></script>
<?php } ?>

<?php if ($isLoggedIn) { ?>
        <script src="<?php echo $domain; ?>/src/userActions.js"></script>
<?php } ?>

<?php if ($isLoggedIn && ($pageName == "myurls" || $pageName == "index") && $roles["vip"] > $user_info["role"]) { ?>
        <script src="<?php echo $domain; ?>/src/checkout.js"></script>
<?php } ?>

<?php if (in_array($pageName, $allowedPage)) { ?>
        <script src="<?php echo $domain; ?>/src/urlActions.js"></script>
<?php } ?>

<?php if ($isLoggedIn && $pageName == "panel") { ?>
        <script src="<?php echo $domain; ?>/src/userUpdate.js"></script>
<?php } ?>

<?php if ($isLoggedIn && $pageName == "settings") { ?>
        <script src="<?php echo $domain; ?>/src/profileUpdate.js"></script>
<?php } ?>

<?php if ($isLoggedIn && $pageName == "stats") { ?>
        <script src="<?php echo $domain; ?>/src/js/chart.js"></script>
        <script src="<?php echo $domain; ?>/src/statsChart.js"></script>
<?php } ?>



    </body>
</html>