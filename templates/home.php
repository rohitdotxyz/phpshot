
        <div class="hero mb-4 px-4">
            <div class="flex flex-col justify-center items-center gap-y-4 p-4 lg:px-8 py-16 max-w-[768px] mx-auto">
                <div class="text-2xl lg:text-4xl text-white font-semibold mb-4 lg:mb-8 shortlink">
                    <h3>
                        <i class="fa fa-link text-black"></i>
                        <span>URL Shortener For</span>
                    </h3>
                    <div class="tw text-center text-xl lg:text-2xl">
                        <span id="demo" class="text-black"></span>
                        <span id="cursor">&nbsp;</span>
                    </div>
                </div>


<?php if ($allowCreateTarget) { ?>
                <h3 class="text-xl lg:text-2xl text-white font-semibold border-b-4 shortlink">
                    <i class="fa fa-link"></i>
                    <span>Paste the URL to be shortened</span>
                </h3>

                <div class="space-y-4 w-full">
                    <form id="shorturl" class="flex flex-col gap-y-4">
                        <input class="hidden opacity-0 invisible" id="shorturl__xtoken" type="hidden" placeholder="csrf token" value="<?php echo $csrf; ?>" />
                        <div class="flex gap-x-4">
                            <div class="grow relative z-[1] target">
                                <div class="px-4 py-2 text-sm font-medium text-gray-100 border border-transparent">Target</div>
                                <input class="relative z-[2] w-full px-4 py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="shorturl__target" type="text"  placeholder="Paste long url and shorten it" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-4 pt-2 pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500">Blah blah blah blah</div>
                            </div>
                            <input class="self-end px-4 py-2 text-sm text-gray-100 bg-transparent border border-gray-700 rounded outline-none text-ellipsis hover:bg-gray-700/50" id="shorturl__submit" type="submit" placeholder="Submit" value="Submit" />
                        </div>
<?php if ($allowCreateHunter || $allowCreateSecret) { ?>
                        <div class="flex items-center gap-4">
                            <input class="px-4 py-2 accent-gray-700 rounded-md h-5 w-5" id="shorturl__advance" type="checkbox" />
                            <div class="py-1 md:py-2 text-sm font-medium text-gray-100 border border-transparent">Advance Options</div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4 hidden" id="shorturl__options">
<?php if ($allowCreateHunter) { ?>
                            <div class="grow relative z-[1] hunter">
                                <div class="px-4 py-2 text-sm font-medium text-gray-100 border border-transparent">Custom</div>
                                <input class="relative z-[2] w-full px-4 py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="shorturl__hunter" type="text"  placeholder="custom url code" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-4 pt-2 pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500" >Blah blah blah blah</div>
                            </div>
<?php } ?>
<?php if ($allowCreateSecret) { ?>
                            <div class="grow relative z-[1] secret">
                                <div class="px-4 py-2 text-sm font-medium text-gray-100 border border-transparent">Password</div>
                                <input class="relative z-[2] w-full px-4 py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="shorturl__secret" type="text"  placeholder="Password for url" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-4 pt-2 pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500" >Blah blah blah blah</div>
                            </div>
<?php } ?>
                        </div>
<?php } ?>
                    </form>

                    <div id="generated" class="flex gap-4 hidden">
                        <a id="generated__link" class="w-full px-2 py-1 md:px-4 md:py-2 text-sm font-medium text-gray-100 border border-transparent rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" href="http://phpshot.000webhostapp.com/InNrZ7">http://phpshot.000webhostapp.com/InNrZ7</a>
                        <input class="self-end px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-transparent border border-gray-700 rounded outline-none text-ellipsis hover:bg-gray-700/50 disabled:bg-gray-700/50" id="generated__copy" type="submit" placeholder="Submit" value="Copy">
                    </div>
                </div>

                <div class="text-sm text-gray-100">
                    <p>Phpshort → By clicking Shorten, you agree to Phpshort's Terms of Use, Privacy Policy and Cookie Policy</p>
                    <p>phpshort is a free tool to shorten a URL or reduce a link Use our URL Shortener to create a shortened link making it easy to remember.</p>
                </div>
<?php } ?>
            </div>
        </div>

        <!-- Auth Modal -->
        <div class="auth-forms fixed w-full h-full top-0 left-0 flex items-center justify-center z-10 bg-gray-900/80 overflow-hidden backdrop-blur-sm transition-opacity duration-500 opacity-0 invisible">
            <div class="min-w-[325px] max-w-[768px] bg-white md:w-10/12 rounded overflow-hidden transition-all duration-500 opacity-0 scale-150">
                <div class="flex flex-row">
                    <div class="md:w-6/12 split signin hidden"></div>
                    <div class="md:w-6/12 split signup"></div>
                    <div class="w-full md:w-6/12 p-4 flex flex-col gap-y-4">
                        <img class="mx-auto max-h-8" src="<?= $domain; ?>/src/img/logo.png">
                        <h1 class="text-xl text-center text-black-900 signin hidden">Join Us its free</h1>
                        <h1 class="text-xl text-center text-black-900 signup">Welcome to phpshort</h1>

                        <ul class="flex flex-row gap-x-2 tabs">
                            <li class="grow basis-1/2 group tab" data-tab="signin">
                                <a class="block px-2 py-1 text-sm text-gray-100 text-center bg-blue-500 border border-blue-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-blue-600 group-[.tab--active]:bg-blue-600" href="#signin">
                                    <i class="fas fa-user"></i>
                                    <span>Sign In</span>
                                </a>
                            </li>
                            <li class="grow basis-1/2 group tab tab--active" data-tab="signup">
                                <a class="block px-2 py-1 text-sm text-gray-100 text-center bg-blue-500 border border-blue-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-blue-600 group-[.tab--active]:bg-blue-600" href="#signup">
                                    <i class="fa fa-user-plus"></i>
                                    <span>Sign Up</span>
                                </a>
                            </li>
                        </ul>

                        <form class="flex flex-col gap-y-2 signin hidden" method="POST" id="signin" action="#" enctype="application/x-www-form-urlencoded">
                            <input type="hidden" name="_csrf" id="signin__csrf" value="<?php echo $csrf; ?>">

                            <div class="grow relative z-[1] username">
                                <div class="px-2 py-1 md:px-4 md:py-2 text-sm border border-transparent">Username</div>
                                <input class="relative z-[2] w-full px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signin__username" type="text" placeholder="ussername" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 md:px-4 md:pt-2 md:pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signin__username">Blah blah blah blah</div>
                            </div>

                            <div class="grow relative z-[1] password">
                                <div class="px-2 py-1 md:px-4 md:py-2 text-sm border border-transparent">Password</div>
                                <input class="relative z-[2] w-full px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signin__password" type="text" placeholder="password" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 md:px-4 md:pt-2 md:pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signin__password">Blah blah blah blah</div>
                            </div>

                            <input class="px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-blue-500 border border-blue-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-blue-600" id="signin__submit" type="submit" placeholder="Submit" value="Submit" />
                        </form>

                        <form class="flex flex-col gap-y-2 signup" method="POST" id="signup" action="#" enctype="application/x-www-form-urlencoded">
                            <input type="hidden" name="_csrf" id="signup__csrf" value="<?php echo $csrf; ?>">

                            <div class="grow relative z-[1] username">
                                <div class="px-2 py-1 text-sm border border-transparent">Username</div>
                                <input class="relative z-[2] w-full px-2 py-1 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signup__username" type="text" placeholder="ussername" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signup__username">Blah blah blah blah</div>
                            </div>

                            <div class="grow relative z-[1] password">
                                <div class="px-2 py-1 text-sm border border-transparent">Password</div>
                                <input class="relative z-[2] w-full px-2 py-1 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signup__password" type="text" placeholder="password" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signup__password">Blah blah blah blah</div>
                            </div>

                            <div class="grow relative z-[1] email">
                                <div class="px-2 py-1 text-sm border border-transparent">Email</div>
                                <input class="relative z-[2] w-full px-2 py-1 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signup__email" type="text" placeholder="email" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signup__email">Blah blah blah blah</div>
                            </div>

                            <div class="grow relative z-[1] country">
                                <div class="px-2 py-1 text-sm border border-transparent">Country</div>
                                <select id="signup__country" class="relative z-[2] w-full px-2 py-1 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none">
                                <?php 
                                    include("./backend/countries.php");
                                    foreach ($world as $key => $value) {
                                        if ($key == "US") {
                                            echo "<option class='text-xs' selected value='".$key."'>".$value."</option>";
                                        } else {
                                            echo "<option class='text-xs' value='".$key."'>".$value."</option>";
                                        }
                                    }
                                ?>
                                </select>
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signup__country">Blah blah blah blah</div>
                            </div>


                            <div class="grow relative z-[1] dob">
                                <div class="px-2 py-1 text-sm border border-transparent">Date of birth</div>
                                <input class="relative z-[2] w-full px-2 py-1 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="signup__dob" type="text" placeholder="YYYY-MM-DD" value="" />
                                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 signup__dob">Blah blah blah blah</div>
                            </div>

                            <input class="px-2 py-1 text-sm text-gray-100 bg-blue-500 border border-blue-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-blue-600" id="signup__submit" type="submit" placeholder="Submit" value="Submit" />
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <?php include("./templates/urlsPage.php"); ?>


        <!-- site counter -->
        <div class="p-4">
            <div class="bg-white flex flex-col justify-between gap-y-4 max-w-[768px] p-4 lg:p-8 mx-auto text-gray-100 rounded-md">
                <div class="text-xl md:text-2xl text-center text-black font-semibold">
                    <h3>Our Stats</h3>
                </div>

                <div class="flex flex-col gap-y-4 sm:flex-row sm:gap-x-4">
                    <div class="grow basis-1/3 text-white text-center bg-gray-700 test relative z-[1] p-4 counter-bg rounded-sm">
                        <h1 class="text-6xl mb-4"><i class="fas fa-users"></i></h1>
                        <div class="font-medium">Users Joined</div>
                        <div class="font-medium">129+</div>
                    </div>

                    <div class="grow basis-1/3 text-white text-center bg-gray-700 relative z-[1] p-4 counter-bg rounded-sm">
                        <h1 class="text-6xl mb-4"><i class="fa fa-link"></i></h1>
                        <div class="font-medium">Urls Shorten</div>
                        <div class="font-medium">273+</div>
                    </div>

                    <div class="grow basis-1/3 text-white text-center bg-gray-700 relative z-[1] p-4 counter-bg rounded-sm">
                        <h1 class="text-6xl mb-4"><i class="fas fa-users"></i></h1>
                        <div class="font-medium">Urls Clicked</div>
                        <div class="font-medium">398143+</div>
                    </div>
                </div>
            </div>
        </div>


<?php if ($allowUrlReport) { ?>
        <!-- URL Report form -->
        <div class="p-4" id="abuse">
            <div class="bg-white flex flex-col justify-between gap-y-4 max-w-[768px] p-4 lg:p-8 mx-auto rounded-md">
                <div class="text-xl text-center text-black font-semibold">
                    <h3>
                        <i class="fa fa-link text-black"></i>
                        <span>Report URLs containing Scam/Abuse/Malware</span>
                    </h3>
                </div>

                <form id="report" class="flex flex-col gap-y-4">
                    <input class="hidden opacity-0 invisible" id="report__xtoken" name="report__csrf" type="hidden" placeholder="csrf token" value="<?php echo $csrf; ?>">
                    <div class="flex gap-x-4">
                        <div class="grow relative z-[1] reportUrl">
                            <div class="px-2 py-1 md:px-4 md:py-2 text-sm font-medium text-gray-900 border border-transparent">Report Url</div>
                            <input name="report__url" class="relative z-[2] w-full px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="report__url" type="text" placeholder="<?php echo $domain; ?>/urlcode" value="">
                            <div class="report__reportUrl absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 md:px-4 md:pt-2 md:pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500">Blah blah blah blah</div>
                        </div>
                        <input class="self-end px-2 py-1 md:px-4 md:py-2 text-sm text-gray-900 bg-transparent border border-gray-700 rounded outline-none text-ellipsis hover:bg-gray-700 hover:text-gray-100" id="report__submit" type="submit" placeholder="Submit" value="Submit">
                    </div>

                    <div class="flex items-center gap-4">
                        <input name="report__adv"  class="px-2 py-1 md:px-4 md:py-2 accent-gray-700 rounded-md h-5 w-5" id="report__advance" type="checkbox">
                        <div class="py-1 md:py-2 text-sm font-medium text-gray-900 border border-transparent">Add Comment</div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 hidden" id="report__options">
                        <div class="grow relative z-[1] reportMsg">
                            <div class="px-2 py-1 md:px-4 md:py-2 text-sm font-medium text-gray-900 border border-transparent">Comment</div>
                            <input name="report__msg" class="relative z-[2] w-full px-2 py-1 md:px-4 md:py-2 text-sm text-gray-100 bg-gray-700 border border-gray-700 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="report__comment" type="text" placeholder="Comment" value="">
                            <div class="report__reportMsg absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 md:px-4 md:pt-2 md:pb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500">Blah blah blah blah</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php } ?>