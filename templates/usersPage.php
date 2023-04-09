
        <div class="p-8">
            <div class="flex flex-col justify-between gap-y-4 max-w-[<?php echo 1200; ?>px] mx-auto text-gray-100 rounded-md">
                <h3 class="text-xl md:text-2xl text-center text-black font-semibold shortlink">
                    <i class="fa fa-link text-black"></i>
                    <span>Hi <?php echo $user_info['user']; ?>, here are all Users Registered on phpshort.</span>
                </h3>

                <div class="overflow-x-auto shadow-xl rounded-lg">
                <div class="pagination flex flex-col text-black text-sm text-left font-normal min-w-[<?php echo 1200; ?>px] ">
                        <!-- Search URL form -->
                        <form id="search" class="pagination__userSearch flex flex-row gap-x-2 px-6 py-4 bg-gray-200 text-gray-700 border-b border-gray-300">
                            <input type="hidden" name="_csrf" id="search__csrf" value="<?php echo $csrf; ?>" />
                            <input name="search" type="text" id="search__user" placeholder="Search..." class="px-2 h-8 rounded-sm outline-none text-ellipsis" value="" />
                            <select id="search__userSortBy" class="px-2 h-8 rounded-sm outline-none text-ellipsis">
                                <option value=''>Sort By</option>
                                <option value="roles">roles</option>
                                <option value="created">created</option>
                                <option value="updated">updated</option>
                            </select>
                            <select id="search__userOrderBy" class="px-2 h-8 rounded-sm outline-none text-ellipsis">
                                <option value=''>Order By</option>
                                <option value="asc">asc</option>
                                <option value="desc">desc</option>
                            </select>
                            <input name="submit" type="submit" id="search__submit" placeholder="Search..." class="px-2 h-8 rounded-sm outline-none bg-gray-700 text-white hover:bg-sky-500" value="Submit" />
                      </form>

                        <!-- HEADER -->
                        <div class="pagination__header">
                            <div class="flex flex-row gap-x-4 p-4 bg-gray-200 text-gray-700 border-b border-gray-300">
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden">Sno.</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Username</div>
                                <div class="font-medium grow-[1] basis-0 self-center overflow-hidden">Roles</div>
                                <div class="font-medium grow-[3] basis-0 self-center overflow-hidden">Email</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Country</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">DOB</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Created</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Updated</div>
                                <div class="font-medium grow-[2] basis-0 self-center overflow-hidden">Actions</div>
                            </div>
                        </div>

                        <!-- PAGINATION -->
                        <div class="pagination__users">
                        </div>

                        <div class="pagination__userbuttons flex flex-row gap-x-2 p-4 bg-gray-200 text-gray-100">
                            <button class="pagination__userprev bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" disabled>Prev</button>
                            <button class="pagination__userpage bg-sky-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">01</button>
                            <button class="pagination__userpage bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">02</button>
                            <button class="pagination__userpage bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm">03</button>
                            <button class="pagination__usernext bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500">Next</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>











<!-- pagination URL Template -->
<div id="userdataTemp" class="hidden">
    <div class="pagination__user">
        <ul class="pagination__userdata flex flex-row gap-x-4 p-4 bg-gray-50 text-gray-700 border-b border-gray-300">
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden">user__data__sno.</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">user__data__username</li>
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden">user__data__role</li>
            <li class="text-gray-700 grow-[3] basis-0 self-center overflow-hidden">user__data__email</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">user__data__country</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">user__data__dob</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">user__data__created</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden">user__data__updated</li>
            <li class="text-gray-700 grow-[2] basis-0 self-center overflow-hidden flex flex-row gap-x-1">
                <button onclick="renderUserEditForm(event, 'user__data__id', 'user__data__username', 'user__data__roleName', 'user__data__email', 'user__data__country', 'user__data__dob')" class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-yellow-500 text-white hover:bg-sky-500">
                    <i class="fa fa-pen fa-xs"></i>
                </button>
                <button onclick="renderUserDelForm(event, 'user__data__id', 'user__data__username')" class="flex justify-center items-center text-sm w-6 h-6 rounded outline-none bg-red-500 text-white hover:bg-sky-500">
                    <i class="fa fa-trash fa-xs"></i>
                </button>
            </li>
        </ul>
    </div>
</div>




<!-- Update URL Form -->
<div id="edituserForm" class="hidden">
    <div class="pagination__useredit max-h-0 overflow-hidden transition-all duration-300">
        <form id="updateuser" class="w-80 px-6 py-4 flex flex-col bg-gray-100 text-gray-700 border-b border-gray-300 transition-all duration-500" onsubmit="updateUserForm(event)">
            <input type="hidden" id="updateuser__xtoken" placeholder="csrf" value="<?php echo $csrf; ?>" class="updateuser__xtoken" />
            <input type="hidden" id="updateuser__id" value="update__user__id" class="updateuser__id" />
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">Username</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateuser__username" type="text" placeholder="username" value="update__user__username" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateuser__username">Blah blah blah blah</div>
            </div>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">Email</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateuser__email" type="text" placeholder="email" value="update__user__email" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateuser__email">Blah blah blah blah</div>
            </div>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">Role</div>
                <select id="updateuser__role" placeholder="role" class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none">
                update__user__role
                </select>
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateuser__role">
                    Blah blah blah blah
                </div>
            </div>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">country</div>
                <select id="updateuser__country" placeholder="country" class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none">
                update__user__country
                </select>
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateuser__country">
                    Blah blah blah blah
                </div>
            </div>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">New Password</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateuser__password" type="text" placeholder="New Password" />
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-full transition-all ease-out duration-500 updateuser__password">Blah blah blah blah</div>
            </div>
            <div class="grow relative z-[1] mb-4">
                <div class="px-2 py-1 text-sm border border-transparent">DOB</div>
                <input class="relative z-[2] w-full px-2 py-1 text-sm border border-gray-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none" id="updateuser__dob" type="text" placeholder="YYY-MM-DD" value="update__user__dob"/>
                <div class="absolute z-[1] w-full left-0 top-0 px-2 pt-1 pb-2 text-sm text-red-900 bg-red-100 border border-red-200 rounded overflow-hidden whitespace-nowrap text-ellipsis outline-none opacity-0 translate-y-fusll transition-all ease-out duration-500 updateuser__dob">Blah blah blah blah</div>
            </div>
            <p id="updateuser__error" class="hidden text-sm text-red-700 updateuser__update"></p>
            <input class="px-2 py-1 mb-4 text-sm text-gray-100 bg-gray-500 border border-gray-500 rounded outline-none text-ellipsis cursor-pointer hover:bg-gray-600" id="updateuser__submit" type="submit" placeholder="Submit" value="Update User" />
        </form>
    </div>
</div>




<!-- Delete URL Form -->
<div id="deluserForm" class="hidden">
    <div class="pagination__userdelete hidden">
        <div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10 bg-gray-900/60 overflow-hidden opacity-0 backdrop-blur-sm transition-opacity duration-200">
            <div class="bg-white flex flex-col gap-y-6 text-center text-gray-600 p-8 rounded-md scale-0 transition-all duration-500">
                <h1 class="text-lg font-medium  text-gray-800">Delete User?</h1>
                <div>
                    <p class="text-sm">Are you sure do you want to delete this user</p>
                    <p class="text-base font-medium">delete__user__username</p>
                </div>
                <p id="deleteuser__error" class="hidden text-sm text-red-700"></p>
                <div class="flex flex-row gap-x-4 justify-center">
                    <button class="text-sm rounded-full px-6 py-2 outline-none bg-gray-500 text-white shadow-[0px_5px_6px_0_rgba(160,160,160,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(160,160,160,0.5)] transition-all duration-500" onclick="hideUserDelForm(event)">Cancel</button>
                    <form id="deleteurl" action="#" onsubmit="deleteUserForm(event)">
                        <input type="hidden" id="deleteuser__xtoken" value="<?php echo $csrf; ?>" />
                        <input type="hidden" id="deleteuser__userId" value="delete__user__id" />
                        <input type="Submit" id="deleteuser__submit" class="text-sm rounded-full px-6 py-2 outline-none bg-red-500 text-white cursor-pointer shadow-[0px_5px_6px_0_rgba(168,45,45,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(168,45,45,0.5)] transition-all duration-500" value="Delete" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


