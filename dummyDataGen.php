<?php

// Generate User DATA
// INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_role`, `user_password`, `user_country`, `user_dob`)
// DUMMY Users Data
/*
INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_role`, `user_password`, `user_country`, `user_dob`) VALUES
(1, 'IamAdmin', 'IamAdmin@gmail.com', 500, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-02-29'),
(2, 'max', 'max@gmail.com', 400, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-06-01'),
(3, 'fire', 'fire@gmail.com', 300, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-06-08'),
(4, 'blue', 'blue@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-01-10'),
(5, 'king', 'king@gmail.com', 500, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1998-05-27'),
(6, 'alex', 'alex@gmail.com', 400, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-08-15'),
(7, 'alpha', 'alpha@gmail.com', 300, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-09-12'),
(8, 'beta', 'beta@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1995-06-29'),
(9, 'gamma', 'gamma@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1999-09-21'),
(10, 'delta', 'delta@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1996-08-26'),
(11, 'bravo', 'bravo@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '1993-04-26'),
(12, 'charlie', 'charlie@gmail.com', 200, '1dc9aff4d6e30440bc3ff2220df0d472', 'US', '2000-11-23')
*/

// Generate URL DATA
// INSERT INTO urls (url_long, url_code, url_createdBy)

/*
$urls = [
	"https://www.travelnation.co.uk/tours/tailor-made-holidays/ultimate-2-month-holiday-india-nz-cook-islands-australia-usa-canada",
	"https://www.youtube.com/watch?v=jTEdfi0MW_w&ab_channel=MirchiMurga",
	"https://www.youtube.com/watch?v=BxuY9FET9Y4&ab_channel=CharliePuth",
	"https://www.youtube.com/watch?v=JWbnEt3xuos&list=PLTB0eCoUXErb7pV0Sj1hXWrxerSZX1qgh&index=3&ab_channel=TheViralFever",
	"https://www.youtube.com/watch?v=rBLCjz8as0E&ab_channel=SonyMusicSouth",
	"https://www.youtube.com/watch?v=4UAGIwrktmw&ab_channel=NetflixIndia",
	"https://www.youtube.com/watch?v=94tGB7DR7E0&ab_channel=7Blab",
	"https://www.youtube.com/watch?v=oXK267Nzb-o&ab_channel=DhavalSakaria",
	"https://www.youtube.com/watch?v=MZPQv-e8VqI&ab_channel=TheAdsWorld",
	"https://www.youtube.com/watch?v=EYNSaSpVcYA&ab_channel=PidiliteIndia",
	"https://www.youtube.com/watch?v=kJofDnUYPhw&ab_channel=AdsForum",
	"https://www.youtube.com/watch?v=sAzlWScHTc4&ab_channel=T-Series",
	"https://www.youtube.com/watch?v=KYH7eKurMN8&ab_channel=ShemarooComedy",
	"https://www.youtube.com/watch?v=KOnFBHqztbM&ab_channel=GauravMadaan",
	"https://www.youtube.com/watch?v=YVvXdTCWrjw&ab_channel=TheAadarGuy",
	"https://www.youtube.com/watch?v=em2pxabiHbw&ab_channel=CharlieAditz",
	"https://www.thelallantop.com/entertainment/post/bheed-movie-opening-day-collection-starring-rajkummar-rao-and-directed-by-anubhav-sinha",
	"https://hindi.news18.com/news/entertainment/bollywood-dilip-kumar-and-raj-kumar-work-together-after-long-time-subhash-ghai-blockbuster-movie-saudagar-the-movie-created-history-5652317.html",
	"https://www.aajtak.in/visualstories/entertainment/43-year-old-urvashi-dholakia-aka-tv-komolika-got-married-second-time-controversy-first-husband-twins-son-tmovk-28001-25-03-2023",
	"https://www.aajtak.in/visualstories/science/drugs-alcohol-do-not-make-you-creative-study-finds-tstr-28009-25-03-2023",
	"https://hindi.news18.com/news/entertainment/lal-chhadi-maidan-khadi-song-actress-rajshree-left-bollywood-suddenly-who-got-fame-from-brahmachari-and-gunahon-ka-devta-movies-with-jeetendra-and-shammi-kapoor-5652161.html",
	"https://hindi.news18.com/webstories/lifestyle/health-black-grapes-benefits/",
	"https://hindi.news18.com/news/entertainment/bollywood-nanda-who-started-working-at-early-age-in-bollywood-made-shashi-kapoor-star-but-actress-yearning-for-love-all-life-remain-unmarried-5651335.html",
	"https://www.thelallantop.com/entertainment/post/five-dance-steps-of-salman-khan-will-make-you-dance-like-hell-including-hud-hud-dabang-dinka-chika-from-ready-jalwa-from-wanted",
	"https://www.aajtak.in/visualstories/education/ias-preparation-tips-in-hindi-by-dr-vikas-divyakirti-elbs-28008-25-03-2023",
	"https://www.thelallantop.com/education/post/kendriya-vidyalaya-admission-to-start-soon-know-details-about-admission-and-the-process",
	"https://www.aajtak.in/entertainment/bollywood-news/story/akash-thosar-reveals-why-he-did-not-sign-movies-after-sairat-tmovp-1661263-2023-03-25",
	"https://hindi.news18.com/webstories/local18/ghaziabad-beautiful-plant-nursery-in-ghaziabad-is-an-attraction-for-people-it-has-colorful-flowers-with-fragrance-l18w/",
	"https://hindi.news18.com/photogallery/entertainment/bhojpuri-dinesh-lal-nirahua-actress-amrapali-dubey-wants-to-marry-salman-khan-and-she-express-he-love-for-him-5652355.html",
	"https://www.aajtak.in/entertainment/news/story/shefali-jariwala-wants-to-become-mother-of-child-ratan-rajput-away-from-showbiz-from-last-3-years-why-tmovk-1661030-2023-03-24",
	"https://www.thelallantop.com/technology/post/ashneer-grover-launched-fantasy-sports-app-crickpe-ahead-of-the-ipl",
	"https://hindi.news18.com/photogallery/entertainment/south-cinema-pushpa-actress-anasuya-bharadwaj-raising-the-temperature-with-her-saree-look-see-pics-5653017.html",
	"https://www.aajtak.in/entertainment/television/story/yeh-rishta-kya-kehlata-hai-fame-lataa-saberwal-diagnosed-with-throat-nodules-voice-bos-actress-shares-post-tmovk-1660830-2023-03-24",
	"https://www.thelallantop.com/technology/post/income-tax-department-launched-ais-app-for-tds-and-other-information",
	"https://hindi.news18.com/news/entertainment/web-series-sunil-grover-sapna-pabbi-starrer-united-kacche-puts-comedic-spin-on-immigrants-experience-upcoming-series-trailer-out-5649487.html"
];

$urlCodes = [];
$insertUrlColumns = [];

function generateUrlCodeLocal() {
	global $urlCodes;
	 
    $allowedChars = [
        "A","a","B","b","C","c","D","d","E","e","F","f","G","g","H","h","I","i","J","j","K","k","L","l","M","m",
        "N","n","O","o","P","p","Q","q","R","r","S","s","T","t","U","u","V","v","W","w","X","x","Y","y","Z","z",
        "0","1","2","3","4","5","6","7","8","9"
    ];

    shuffle($allowedChars);

    $min = 0; $max = 61;
    $urlCode = "";
    $loopStart = true;

    while($loopStart) {
        for ($codeLen=6; $codeLen > 0; $codeLen--) { 
            $randomNum = mt_rand($min, $max);
            $urlCode = $urlCode . $allowedChars[$randomNum];
        }

        $uniqueUrl = in_array($urlCode, $urlCodes);
        if ($uniqueUrl == 1) {
            $urlCode = "";
        } else {
            $loopStart = false;
            break;
        }
    }

    return $urlCode;
}

foreach ($urls as $key => $value) {
	# code...
	$urlColumns = array(
		"url_long" =>  "'". $value . "'",
		"url_code" => "'" . generateUrlCodeLocal() . "'",
		"url_createdBy" => "'" . mt_rand(0, 12) . "'"
	);	

	array_push($insertUrlColumns, $urlColumns);
}

$readyData = array();
for($i = 0; $i <= count($insertUrlColumns) - 1; $i++) {
$text = "(" . implode(",",$insertUrlColumns[$i]) . ")";
    array_push($readyData, $text);
}

echo "<pre>";
echo implode(",",$readyData);
echo "</pre>";
*/

// Generate URL Visit DATA
// INSERT INTO visits (visit_urlId, visit_referer, br_chrome, br_firefox, br_safari, br_edge, br_ie, br_opera, br_others, os_windows, os_linux, os_macos, os_android, os_ios, os_others, visit_country, visit_time) 
/*
include("./system/countries.php");

$referer = [
    "rohitdotxyz.w3spaces.com",
    "cdpn.io",
    "null.jsbin.com",
    "fiddle.jshell.net",
    "l.facebook.com",
    "l.instagram.com",
    "kutt.it",
    "mail.google.com",
    "direct"
];

$browsersList = ["br_ie", "br_firefox", "br_chrome", "br_opera", "br_safari", "br_edge", "br_others"];
$osList = ["os_windows", "os_linux", "os_macos", "os_android", "os_ios", "os_others"];
$countriesWithIndex = array_keys($world);


function genRandomDates() {
    // month should be 1 to 12

    date_default_timezone_set("Asia/Kolkata");
    $today = date("Y-m-d-H-i-s", time());
    $dateExplode = explode("-", $today);

    $year = $dateExplode[0];

    $month = mt_rand(1,$dateExplode[1]);
    if ($month < 10) { $month = 0 . $month; }

    $day = mt_rand(1, $dateExplode[2]);
    if ($day < 10) { $day = 0 . $day; }

    $hour = mt_rand(0,$dateExplode[3]);
    if ($hour < 10) { $hour = 0 . $hour; }

    $min = mt_rand(0,$dateExplode[4]);
    if ($min < 10) { $min = 0 . $min; }

    $sec = mt_rand(0,$dateExplode[5]);
    if ($sec < 10) { $sec = 0 . $sec; }

    $isLeapYear = ($year % 4 == 0) ? true : false;

    // feb month but not leap year
    if ($isLeapYear == false && $month == 2 && $day > 28) {
        $day = 28;
    }

    // feb month but leap year
    if ($isLeapYear == true && $month == 2 && $day > 29) {
        $day = 29;
    }

    // [Jan, Mar, Jul, August, Oct, Dec]
    $monthWithThirtyOneDays = [1,3,5,7,8,10,12];
    if (in_array($month, $monthWithThirtyOneDays) && $day > 31) {
        $day = 31;
    }

    // [Apr, Jun, Sept, Nov]
    $monthWithThirtyDays = [4,6,9,11];
    if (in_array($month, $monthWithThirtyDays) && $day > 30) {
        $day = 30;
    }


    $date = "$year-$month-$day $hour:$min:$sec";
    return $date;
}

// build dummy data
$insertUrlVisitColumns = array();

for($i = 1; $i <= 100; $i++) {

    $urlVisitColumn = array(
        'visit_urlId' => '1',
        'visit_referer' => '',
        'br_chrome' => '0', 'br_firefox' => '0', 'br_safari' => '0', 'br_edge' => '0', 'br_ie' => '0', 'br_opera' => '0', 'br_others' => '0',
        'os_windows' => '0', 'os_linux' => '0', 'os_macos' => '0', 'os_android' => '0', 'os_ios' => '0', 'os_others' => '0',
        'visit_country' => '',
        //  'visit_ip' => '', 'visit_agent' => '',
        'visit_time' => ''
    );

    $urlId = mt_rand(1, 5);
    $refer =  $referer[mt_rand(0, count($referer) - 1)];
    $brName = $browsersList[mt_rand(0, count($browsersList) - 1)];
    $osName = $osList[mt_rand(0, count($osList) - 1)];
    $country = $countriesWithIndex[mt_rand(0,count($countriesWithIndex) - 1)];

    $urlVisitColumn["visit_urlId"] = "'" . $urlId . "'";
    $urlVisitColumn["visit_referer"] = "'" . $refer . "'";
    $urlVisitColumn[$brName] = "'". 1 ."'";
    $urlVisitColumn[$osName] = "'" . 1 . "'";
    $urlVisitColumn["visit_country"] = "'" . $country . "'";
    $urlVisitColumn["visit_time"] = "'" . genRandomDates() . "'";

    array_push($insertUrlVisitColumns, $urlVisitColumn);
}


$readyData = array();

for($i = 0; $i <= count($insertUrlVisitColumns) - 1; $i++) {
    $text = "(" . implode(",",$insertUrlVisitColumns[$i]) . ")";
    array_push($readyData, $text);
}

echo "<pre>";
echo implode(",",$readyData);
echo "</pre>";

// 2023-03-17 09:47:11
*/




?>