<div class="page_element mt-2 p-4 bg-white rounded-md shadow-lg">
	<h3 class="text-lg text-gray-900 font-semibold pb-1 border-b database">
        <i class="fa fa-database"></i>
        <span>Database information</span>
    </h3>
	<div class="install_section">
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dbhost">
				<span>Database host</span>			
			</p>
			<input id="install_dbhost" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="localhost" />
			<p class="text-sm text-slate-500">Database host ex: localhost</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dbuser">
				<span>Database user</span>
			</p>
			<input id="install_dbuser" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="phplearner" />
			<p class="text-sm text-slate-500">Username to connect to the database</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dbpass">
				<span>Database pass</span>
			</p>
			<input id="install_dbpass" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="phplearner" />
			<p class="text-sm text-slate-500">Avoid using $ ' " ; in password</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dbname">
				<span>Database name</span>
			</p>
			<input id="install_dbname" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="learning" />
			<p class="text-sm text-slate-500">Name of the database</p>
		</div>
	</div>
</div>

<div class="page_element mt-2 p-4 bg-white rounded-md shadow-lg">
	<h3 class="text-lg text-gray-900 font-semibold pb-1 border-b system">
        <i class="fa fa-folder"></i>
        <span>System information</span>
    </h3>
	<div class="install_section">
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium">System folder</p>
			<p class="text-sm text-slate-500">Location /system</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium">database.php file</p>
			<p class="text-sm text-slate-500">Location /system/database.php</p>
		</div>
	</div>
</div>

<div class="page_element mt-2 p-4 bg-white rounded-md shadow-lg">
	<h3 class="text-lg text-gray-900 font-semibold pb-1 border-b site">
        <i class="fa fa-globe"></i>
        <span>Site information</span>
    </h3>
	<div class="install_section">
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dbhost url">
				<span>Installation url</span>
			</p>
			<input id="install_url" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="http://127.0.0.1/phpshort" />
			<p class="text-sm text-slate-500">Absolute path to your installation ex: http://www.domain.com. Must not end with /</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium tzone">
				<span>Default Timezone</span>
			</p>
			<select id="install_tzone" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full">
				<?php
					// include ("./backend/timezones.php");
					include("./../backend/timezones.php");
					foreach ($timezones as $key => $value) {
						# code...
						if ($value == "America/New_York") {
						echo "<option selected value='".$value."'>".$value."</option>";
						} else {
						echo "<option value='".$value."'>".$value."</option>";
						}
					}
				?>
			</select>
			<p class="text-sm text-slate-500">Default system timezone</p>
		</div>
	</div>
</div>


<div class="page_element mt-2 p-4 bg-white rounded-md shadow-lg">
	<h3 class="text-lg text-gray-900 font-semibold pb-1 border-b owner">
        <i class="fa fa-trophy"></i>
        <span>Owner account</span>
    </h3>
	<div class="install_section">
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium username">
				<span>Username</span>
			</p>
			<input id="install_username" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="IamAdmin" />
			<p class="text-sm text-slate-500">Max 18 characters a-z0-9</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium email">
				<span>Email</span>
			</p>
			<input id="install_email" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="IamAdmin@gmail.com" />
			<p class="text-sm text-slate-500">Must be a valid email.</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium password">
				<span>Password</span>
			</p>
			<input id="install_password" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="1234567890" />
			<p class="text-sm text-slate-500">Choose a secure password for your account</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium">Repeat password</p>
			<input id="install_rpassword" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="1234567890" />
			<p class="text-sm text-slate-500">Repeat previous password</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium country">
				<span>Country</span>
			</p>
			<select id="install_country" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full">
				<?php 
					// include ("./backend/countries.php");
					include("./../backend/countries.php");
					foreach ($world as $key => $value) {
						# code...
						if ($value == "United States") {
							echo "<option selected value='".$key."'>".$value."</option>";
						} else {
							echo "<option value='".$key."'>".$value."</option>";
						}
					}
				?>
			</select>
			<p class="text-sm text-slate-500">Choose your country</p>
		</div>
		<div class="element pt-4">
			<p class="text-gray-700 text-base font-medium dob">
				<span>Date of birth</span>
			</p>
			<input id="install_dob" class="text-sm text-gray-700 bg-gray-200 my-1 p-2 rounded-sm w-full" type="text" value="1996-02-29" />
			<p class="text-sm text-slate-500">Date of birth format YYYY-MM-DD</p>
		</div>
    </div>
    <button id="install_start" class="px-4 py-2 mt-4 text-sm text-sky-600 font-semibold rounded-md border border-sky-200 hover:text-white hover:bg-sky-600 focus:text-white focus:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2" onclick="runInstaller()">Install phpshort</button>
    <button id="install_wait" class="hidden px-4 py-2 mt-4 bg-sky-600 text-sm text-white font-semibold rounded-md border border-sky-200">Please wait...</button>
</div>

