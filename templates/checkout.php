<?php if ($roles["vip"] > $user_info["role"]) { ?>
        <div class="p-4">
            <div class="bg-white flex flex-col gap-y-4  max-w-[768px] p-4 lg:p-8 mx-auto rounded-md">
                <div class="grow flex flex-row gap-y-4 justify-between items-center ">
                    <div class="product">
                        <h4 class="text-xl font-medium leading-8">PhpShort</h4>
                        <p class="text-sm text-gray-700">URL shortening service for long urls.</p>
                        <ol class="m-4 list-decimal">
                            <li class="text-sm text-gray-700">Can Create Custom Short URL</li>
                            <li class="text-sm text-gray-700">Can Protect URL</li>
                            <li class="text-sm text-gray-700">Can Check Stats</li>
                        </ol>
                    </div>
                    <div class="pricing">
                        <div class="text-3xl text-gray-800">Rs100</div>
                        <div class="text-sm text-gray-600">/MONTH</div>
                    </div>
                </div>
<?php if ($isLoggedIn) { ?>
                <form action="#" id="subs">
                    <input type="hidden" id="subs__xtoken" value="<?php echo $csrf; ?>" />
                    <input type="Submit" id="subs__submit" class="text-sm rounded-full px-6 py-2 outline-none bg-blue-500 text-white cursor-pointer shadow-[0px_5px_6px_0_rgba(45,111,168,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(45,111,168,0.5)] transition-all duration-500" value="Checkout" />
                </form>
<?php } ?>
            </div>
        </div>
<?php } ?>