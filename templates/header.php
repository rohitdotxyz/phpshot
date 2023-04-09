
    <body class="bg-gray-100">
<div id="header" class="bg-gray-900 p-4">
            <div class="navbar flex flex-row items-center px-4">
                <img src="<?php echo $domain; ?>/src/img/logo.png" class="max-h-10 max-w-full" alt="product logo" />
                <ul class="grow flex flex-row justify-end gap-x-6 nav">

<?php if (!$isLoggedIn) { ?>
                    <li class="nav__item">
                        <button class="nav__btn whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline" data-tab="signin">Sign In</button>
                    </li>
                    <li class="nav__item">
                        <button class="nav__btn whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline" data-tab="signup">Sign Up</button>
                    </li>
<?php } ?>

<?php if ($isLoggedIn) { ?>
                    <li class="nav__item"><a href="<?php echo $domain; ?>/myurls.php" class="nav__link whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline">My URLs</a></li>
                    <li class="nav__item"><a href="<?php echo $domain; ?>#abuse" class="nav__link whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline" data-tab="report">Report Url</a></li>

                    <li id="menu-dropdown" class="nav__item relative z-10">
                        <button class="menu-dropdown__button whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="menu-dropdown__content hidden absolute rounded top-full right-0 bg-gray-600">
                            <li class="px-4 py-2"><a href="<?php echo $domain; ?>" class="nav__text whitespace-nowrap text-gray-300 rounded-sm">Hi <?php echo makeOutput($user_info['username']); ?></a></li>
                            <li class="px-4 py-2"><a href="<?php echo $domain; ?>/settings.php" class="nav__link whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline">Settings</a></li>

<?php if (allowByUserRole($roles["mod"])) { ?>
                            <li class="px-4 py-2"><a href="<?php echo $domain; ?>/panel.php" class="nav__link whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline">Admin</a></li>
<?php } ?>

                            <li class="px-4 py-2">
                                <form id="signout" method="POST" action="#" enctype="application/x-www-form-urlencoded">
                                    <input type="hidden" name="_csrf" id="signout__csrf" value="<?php echo $csrf; ?>">
                                    <button class="nav__btn whitespace-nowrap text-gray-300 rounded-sm hover:text-white hover:underline">Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
<?php } ?>


                    


                  

      
                </ul>
            </div>
        </div>

