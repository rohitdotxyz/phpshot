<?php
if ($checkInstall != 1) {
	header('location: ./');
	die();
}

include("./templates/uiConfig.php");

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $webSettings['title']; ?></title>
		<meta name="description" content="<?php echo $webSettings['description']; ?>" />
		<meta name="keywords" content="<?php echo $webSettings['keywords']; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
		<link rel="shortcut icon" type="image/png" href="defaultImg/icon.png" />

		<!-- css -->
		<link rel="stylesheet" href="<?php echo $domain; ?>/src/icons/css/all.min.css" />
		<link rel="stylesheet" href="<?php echo $domain; ?>/src/css/signin.css" />

		<!-- js -->
		<script src="<?php echo $domain; ?>/src/js/jQuery341.js"></script>
		<script src="<?php echo $domain; ?>/src/js/tailwind324.js"></script>

		<script>
			// page information
			const pageTitle = "<?php echo $webSettings['title'] . ' | ' .  $page_info['name']; ?>";
			document.title = pageTitle;

			const domain = "<?php echo $domain; ?>";
		</script>
	</head>
