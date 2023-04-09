
<div class="p-4">
    <div class="bg-white flex flex-col justify-between gap-y-4 max-w-[768px] p-4 mx-auto text-gray-100 rounded-md">

    
<?php if (
    $allowUpdateUsername || $allowUpdateEmail || $allowUpdateCountry || $allowUpdateDob
) { ?>
        <h3 class="text-xl text-black font-semibold">Personal Information</h3>
        <form id="updateuser" class="px-6 py-4 bg-gray-100 text-gray-700 border border-gray-300">
            <input type="hidden" id="updateuser__xtoken" placeholder="csrf..." value="<?php echo $csrf; ?>" >
            <input type="hidden" id="updateuser__id" placeholder="csrf..." value="<?php echo $user_info['id']; ?>" >
<?php if ($allowUpdateUsername) { ?>
            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">username</div>
                <input type="text" id="updateuser__username" placeholder="username..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="<?php echo makeoutput($user_info['user']); ?>">
                <div class="absolute left-0 top-0 z-[1] updateuser__username text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>
<?php } ?>

<?php if ($allowUpdateEmail) { ?>
            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">email</div>
                <input type="text" id="updateuser__email" placeholder="email..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="<?php echo makeoutput($user_info['email']); ?>">
                <div class="absolute left-0 top-0 z-[1] updateuser__email text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>
<?php } ?>

<?php if ($allowUpdateCountry) { ?>
            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">country</div>
                <select id="updateuser__country" placeholder="username..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700">
                <?php 
                    include("./backend/countries.php");
                    foreach ($world as $key => $value) {
                        # code...
                        if ($user_info['country'] == $key) {
                            echo "<option class='text-xs' selected value='".$key."'>".$value."</option>";
                        } else {
                            echo "<option class='text-xs' value='".$key."'>".$value."</option>";
                        }
                    }
                ?>
                </select>
                <div class="absolute left-0 top-0 z-[1] updateuser__country text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>
<?php } ?>

<?php if ($allowUpdateDob) { ?>
            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">date of birth</div>
                <input type="text" id="updateuser__dob" placeholder="yyyy-mm-dd" class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="<?php echo $user_info['dob']; ?>">
                <div class="absolute left-0 top-0 z-[1] updateuser__dob text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>
<?php } ?>

            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">Password</div>
                <input type="text" id="updateuser__password" placeholder="Password..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="">
                <div class="absolute left-0 top-0 z-[1] updateuser__password text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>

            <div class="relative z-[1] mb-4">
                <input type="submit" id="updateuser__submit" placeholder="Submit..." class="w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis bg-gray-700 text-gray-100 hover:bg-sky-500" value="Update Info">
            </div>
        </form>
<?php } ?>
        <h3 class="text-xl text-black font-semibold">Change Password</h3>

        <form id="updatepass" class="px-6 py-4 bg-gray-100 text-gray-700 border border-gray-300">
            <input type="hidden" id="updatepass__xtoken" placeholder="csrf..." value="<?php echo $csrf; ?>" >

            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">Old Password</div>
                <input type="text" id="updatepass__cpassword" placeholder="Old Password..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="">
                <div class="absolute left-0 top-0 z-[1] updatepass__oldpass text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>

            <div class="relative z-[1] mb-4">
                <div class="px-3 py-1.5 border border-transparent">New Password</div>
                <input type="text" id="updatepass__npassword" placeholder="New Password..." class="relative z-[2] text-base border border-gray-300 w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis text-gray-700" value="">
                <div class="absolute left-0 top-0 z-[1] updatepass__newpass text-sm w-3/4 px-3 pt-2.5 pb-3 border border-red-200 rounded-t-sm outline-none text-ellipsis bg-red-100 text-red-900 opacity-0 translate-y-full transition-all ease-out duration-500">
                    Blah blah blah blah
                </div>
            </div>

            <div class="relative z-[1] mb-4">
                <input type="submit" id="updatepass__submit" placeholder="Submit..." class="w-full px-3 py-1.5 rounded-sm outline-none text-ellipsis bg-gray-700 text-gray-100 hover:bg-sky-500" value="Update Password">
            </div>
        </form>
    </div>
</div>


