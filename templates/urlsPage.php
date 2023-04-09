
        <div class="p-8">
<?php if ($panelPage) { ?>
            <div class="flex flex-col justify-between gap-y-4 max-w-[<?php echo 1200; ?>px] mx-auto text-gray-100 rounded-md">                
<?php } else if ($myUrlPage) {  ?>
            <div class="flex flex-col justify-between gap-y-4 max-w-[<?php echo 1024; ?>px] mx-auto text-gray-100 rounded-md">
<?php } else { ?>
            <div class="flex flex-col justify-between gap-y-4 max-w-[<?php echo 768 ?>px] mx-auto text-gray-100 rounded-md">
<?php }  ?>
                <h3 class="text-xl md:text-2xl text-center text-black font-semibold shortlink">
                    <i class="fa fa-link text-black"></i>
<?php if ($panelPage) { ?>
                    <span>Hi <?php echo $user_info['user']; ?>, here are all URLs shorten on phpshort.</span>
<?php } else if ($myUrlPage) {  ?>
                    <span>Hi <?php echo $user_info['user']; ?>, here are all your URLs shorten.</span>
<?php } else { ?>
                    <span>Hi <?php echo $user_info['user']; ?>, here are all URLs created by Visitors.</span>
<?php }  ?>
                </h3>

                <div class="overflow-x-auto shadow-xl rounded-lg">
<?php if ($panelPage) { ?>
                <div class="pagination flex flex-col text-black text-sm text-left font-normal min-w-[<?php echo 1200; ?>px] ">
<?php } else if ($myUrlPage) {  ?>
                <div class="pagination flex flex-col text-black text-sm text-left font-normal min-w-[<?php echo 1024; ?>px] ">
<?php } else { ?>
                <div class="pagination flex flex-col text-black text-sm text-left font-normal min-w-[<?php echo 768; ?>px] ">
<?php }  ?>
<?php if ($allowSearch || $allowSort || $allowOrder) { ?>
                        <!-- Search URL form -->
                        <form id="search" class="pagination__urlSearch flex flex-row gap-x-2 px-6 py-4 bg-gray-200 text-gray-700 border-b border-gray-300">
                            <input type="hidden" name="_csrf" id="search__csrf" value="<?php echo $csrf; ?>" />
<?php if ($allowSearch) { ?>
                            <input name="search" type="text" id="search__url" placeholder="Search..." class="px-2 h-8 rounded-sm outline-none text-ellipsis" value="" />
<?php } ?>
<?php if ($allowSort) { ?>
                            <select id="search__urlSortBy" class="px-2 h-8 rounded-sm outline-none text-ellipsis">
<?php if (
    $allowCreatedOn ||
    $allowVisits ||
    ($validPageName && $allowReport) ||
    ($validPageName && $allowUpdate) ||
    ($panelPage && $allowDeleted)
) { ?>
                                <option value=''>Sort By</option>
<?php if ($allowCreatedOn) { ?>
                                <option value="created">created</option>
<?php } ?>
<?php if ($allowVisits) { ?>
                                <option value="views">views</option>
<?php } ?>
<?php if ($validPageName && $allowReport) { ?>
                                <option value="reports">reports</option>
<?php } ?>
<?php if ($validPageName && $allowUpdate) { ?>
                                <option value="updated">updated</option>
<?php } ?>
<?php if ($panelPage && $allowDeleted) { ?>
                                <option value="deleted">deleted</option>
<?php } ?>
<?php } ?>
                            </select>
<?php } ?>
<?php if ($allowOrder) { ?>
                            <select id="search__urlOrderBy" class="px-2 h-8 rounded-sm outline-none text-ellipsis">
                                <option value=''>Order By</option>
                                <option value="asc">asc</option>
                                <option value="desc">desc</option>
                            </select>
<?php } ?>
                            <input name="submit" type="submit" id="search__submit" placeholder="Search..." class="px-2 h-8 rounded-sm outline-none bg-gray-700 text-white hover:bg-sky-500" value="Submit" />
                      </form>
<?php } ?>

                        <!-- HEADER -->
                        <div class="pagination__header">
                            <div class="flex flex-row gap-x-4 p-4 bg-gray-200 text-gray-700 border-b border-gray-300">
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden">Sno.</div>
<?php if ($allowHunter) { ?>
                                <div class="font-medium grow-[3] basis-0 self-center overflow-hidden break-words">Short URL</div>
<?php } ?>
<?php if ($allowVisits) { ?>
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden text-right">Views</div>
<?php } ?>
<?php if ($allowCreatedOn) { ?>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Created</div>
<?php } ?>
<?php if ($validPageName && $allowTarget) { ?>
                                <div class="font-medium grow-[4] basis-0 self-center overflow-hidden break-words">Original URL</div>
<?php } ?>
<?php if ($validPageName && $allowReport) { ?>
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden text-right">Reports</div>
<?php } ?>
<?php if (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) { ?>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Updated</div>
<?php } ?>
<?php if (
    ($validPageName && $allowSecure) ||
    (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) || 
    ($validPageName && $allowDelete) || 
    ($validPageName && $allowStats)
    
) { ?>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Actions</div>
<?php } ?>
<?php if ($panelPage && $allowCreatedBy) { ?>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Usesrname</div>
<?php } ?>
<?php if ($panelPage && $allowDeleted) { ?>
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden text-right">Deleted</div>
<?php } ?>
                            </div>
                        </div>

                        <!-- PAGINATION -->
                        <div class="pagination__urls">
                        </div>

                        <div class="pagination__buttons flex flex-row gap-x-2 p-4 bg-gray-200 text-gray-100">
                            <button class="pagination__prev bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" disabled>Prev</button>
                            <button class="pagination__page bg-sky-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">01</button>
                            <button class="pagination__page bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">02</button>
                            <button class="pagination__page bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">03</button>
                            <button class="pagination__next bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500">Next</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>











<!-- pagination URL Template -->
<div id="urldataTemp" class="hidden">
    <div class="pagination__url">
        <ul class="pagination__urldata flex flex-row gap-x-4 p-4 bg-gray-50 text-gray-700 border-b border-gray-300">
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden">url__data__sno</li>
<?php if ($allowHunter) { ?>
            <li class="text-gray-700 grow-[3] basis-0 self-center overflow-hidden break-words">
                <a class="text-sky-700 hover:underline" href="url__data__hunterUrl" target="_blank">url__data__hunterUrl</a>
            </li>
<?php } ?>

<?php if ($allowVisits) { ?>
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden text-right">url__data__views</li>
<?php } ?>
<?php if ($allowCreatedOn) { ?>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">url__data__createdOn</li>
<?php } ?>
<?php if ($validPageName && $allowTarget) { ?>
            <li class="text-gray-700 grow-[4] basis-0 self-center overflow-hidden break-words">
                <a class="text-sky-700 hover:underline" href="url__data__targetUrl" target="_blank">url__data__targetUrl</a>
            </li>
<?php } ?>
<?php if ($validPageName && $allowReport) { ?>
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden text-right">url__data__report</li>
<?php } ?>
<?php if (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) { ?>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">url__data__updatedOn</li>
<?php } ?>
<?php if (
    ($validPageName && $allowSecure) ||
    (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) || 
    (($panelPage && $allowToDeleteOther) || ($myUrlPage && $allowDelete)) || 
    (($panelPage && $allowToCheckStats) || ($myUrlPage && $allowStats))
) { ?>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden flex flex-row gap-x-1">
<?php if ($validPageName && $allowSecure) { ?>
                <button class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-gray-500 text-white hover:bg-sky-500">
                    <i class="url__data__protected"></i>
                </button>
<?php } ?>
<?php if (($panelPage && $allowToUpdateOther) || ($myUrlPage && $allowUpdate)) { ?>
                <button onclick="renderUrlEditForm(event, 'url__data__urlId', 'url__data__targetUrl', 'url__data__hunterCode')" class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-yellow-500 text-white hover:bg-sky-500">
                    <i class="fa fa-pen fa-xs"></i>
                </button>
<?php } ?>
<?php if (($panelPage && $allowToDeleteOther) || ($myUrlPage && $allowDelete)) { ?>
                <button onclick="renderUrlDelForm(event, 'url__data__urlId', 'url__data__targetUrl', 'url__data__hunterCode')" class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-red-500 text-white hover:bg-sky-500">
                    <i class="fa fa-trash fa-xs"></i>
                </button>
<?php } ?>
<?php if (($panelPage && $allowToCheckStats) || ($myUrlPage && $allowStats)) { ?>
                <a  href="url__data__stats" target="_blank" class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-purple-500 text-white hover:bg-sky-500">
                    <i class="fa fa-clock fa-xs"></i>
                </a>
<?php } ?>
            </li>
<?php } ?>
<?php if ($panelPage && $allowCreatedBy) { ?>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">url__data__createdByUsername</li>
<?php } ?>
<?php if ($panelPage && $allowDeleted) { ?>
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden text-right">url__data__deleted</li>
<?php } ?>
        </ul>
    </div>
</div>




<?php if ($allowUpdate && ($panelPage ||  $myUrlPage)) { ?>
<!-- Update URL Form -->
<div id="editurlForm" class="hidden">
    <div class="pagination__urledit max-h-0 overflow-hidden transition-all duration-300">
<?php if ($allowUpdateTarget || $allowUpdateHunter || $$allowUpdateSecret) { ?>
        <form id="updateurl" class="w-80 px-6 py-4 flex flex-col bg-gray-100 text-gray-700 border-b border-gray-300 transition-all duration-500" onsubmit="updateUrlForm(event)">
            <input type="hidden" id="updateurl__xtoken" placeholder="csrf" value="<?php echo $csrf; ?>">
            <input type="hidden" id="updateurl__linkId" value="update__url__linkId" />
<?php if ($allowUpdateTarget) { ?>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">Target</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateurl__target" type="text" placeholder="New Password" value="update__url__target" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateurl__target">Blah blah blah blah</div>
            </div>
<?php } ?>
<?php if ($allowUpdateHunter) { ?>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">Custom</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateurl__custom" type="text" placeholder="your custom url" value="update__url__custom" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateurl__hunter">Blah blah blah blah</div>
            </div>
<?php } ?>
<?php if ($allowUpdateSecret) { ?>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">New Password</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateurl__password" type="text" placeholder="New Password" value="" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateurl__secret">Blah blah blah blah</div>
            </div>
<?php } ?>


            <input class="px-2 py-1 text-sm text-gray-100 bg-gray-500 border border-gray-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-gray-600" id="updateurl__submit" type="submit" placeholder="Submit" value="Update Url" />
        </form>
<?php } ?>
    </div>
</div>
<?php } ?>




<?php if ($allowDelete && ($panelPage ||  $myUrlPage)) { ?>
<!-- Delete URL Form -->
<div id="delurlForm" class="hidden">
    <div class="pagination__urldelete hidden">
        <div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10 bg-gray-900/60 overflow-hidden opacity-0 backdrop-blur-sm transition-opacity duration-200">
            <div class="bg-white flex flex-col gap-y-6 text-center text-gray-600 p-8 rounded-md scale-0 transition-all duration-500">
                <h1 class="text-lg font-medium  text-gray-800">Delete link?</h1>
                <div>
                    <p class="text-sm">Are you sure do you want to delete the link</p>
                    <p class="text-base font-medium"><?php echo $domain; ?>/delete__url__code</p>
                </div>
                <p id="deleteurl__error" class="hidden text-sm text-red-700"></p>
                <div class="flex flex-row gap-x-4 justify-center">
                    <button class="text-sm rounded-full px-6 py-2 outline-none bg-gray-500 text-white shadow-[0px_5px_6px_0_rgba(160,160,160,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(160,160,160,0.5)] transition-all duration-500" onclick="hideUrlDelForm(event)">Cancel</button>
                    <form id="deleteurl" action="#" onsubmit="deleteUrlForm(event)">
                        <input type="hidden" id="deleteurl__xtoken" value="<?php echo $csrf; ?>" />
                        <input type="hidden" id="deleteurl__linkId" value="delete__url__linkId" />
                        <input type="Submit" id="deleteurl__submit" class="text-sm rounded-full px-6 py-2 outline-none bg-red-500 text-white cursor-pointer shadow-[0px_5px_6px_0_rgba(168,45,45,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(168,45,45,0.5)] transition-all duration-500" value="Delete" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>




