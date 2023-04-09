
const world = {
    "AW": "Aruba",
    "AF": "Afghanistan",
    "AO": "Angola",
    "AI": "Anguilla",
    "AL": "Albania",
    "AX": "Aland",
    "AD": "Andorra",
    "AE": "United Arab Emirates",
    "AR": "Argentina",
    "AM": "Armenia",
    "AS": "American Samoa",
    "AQ": "Antarctica",
    "TF": "French Southern and Antarctic Lands",
    "AG": "Antigua and Barbuda",
    "AU": "Australia",
    "AT": "Austria",
    "AZ": "Azerbaijan",
    "BI": "Burundi",
    "BE": "Belgium",
    "BJ": "Benin",
    "BF": "Burkina Faso",
    "BD": "Bangladesh",
    "BG": "Bulgaria",
    "BH": "Bahrain",
    "BS": "The Bahamas",
    "BA": "Bosnia and Herzegovina",
    "UM": "United States Minor Outlying Islands",
    "BL": "Saint Barthelemy",
    "BY": "Belarus",
    "BZ": "Belize",
    "BM": "Bermuda",
    "BO": "Bolivia",
    "BR": "Brazil",
    "BB": "Barbados",
    "BN": "Brunei",
    "BT": "Bhutan",
    "BW": "Botswana",
    "CF": "Central African Republic",
    "CA": "Canada",
    "CH": "Switzerland",
    "CL": "Chile",
    "CN": "China",
    "CI": "Ivory Coast",
    "CM": "Cameroon",
    "CD": "Democratic Republic of the Congo",
    "CG": "Republic of Congo",
    "CK": "Cook Islands",
    "CO": "Colombia",
    "KM": "Comoros",
    "CV": "Cape Verde",
    "CR": "Costa Rica",
    "CU": "Cuba",
    "CW": "Curaçao",
    "KY": "Cayman Islands",
    "CY": "Cyprus",
    "CZ": "Czech Republic",
    "DE": "Germany",
    "DJ": "Djibouti",
    "DM": "Dominica",
    "DK": "Denmark",
    "DO": "Dominican Republic",
    "DZ": "Algeria",
    "EC": "Ecuador",
    "EG": "Egypt",
    "ER": "Eritrea",
    "ES": "Spain",
    "EE": "Estonia",
    "ET": "Ethiopia",
    "FI": "Finland",
    "FJ": "Fiji",
    "FK": "Falkland Islands",
    "FR": "France",
    "FO": "Faroe Islands",
    "FM": "Federated States of Micronesia",
    "GA": "Gabon",
    "GB": "United Kingdom",
    "GE": "Georgia",
    "GG": "Guernsey",
    "GH": "Ghana",
    "GI": "Gibraltar",
    "GN": "Guinea",
    "GM": "Gambia",
    "GW": "Guinea Bissau",
    "GQ": "Equatorial Guinea",
    "GR": "Greece",
    "GD": "Grenada",
    "GL": "Greenland",
    "GT": "Guatemala",
    "GU": "Guam",
    "GY": "Guyana",
    "HK": "Hong Kong S.A.R.",
    "HM": "Heard Island and McDonald Islands",
    "HN": "Honduras",
    "HR": "Croatia",
    "HT": "Haiti",
    "HU": "Hungary",
    "ID": "Indonesia",
    "IM": "Isle of Man",
    "IN": "India",
    "IO": "British Indian Ocean Territory",
    "IE": "Ireland",
    "IR": "Iran",
    "IQ": "Iraq",
    "IS": "Iceland",
    "IL": "Israel",
    "IT": "Italy",
    "JM": "Jamaica",
    "JE": "Jersey",
    "JO": "Jordan",
    "JP": "Japan",
    "KZ": "Kazakhstan",
    "KE": "Kenya",
    "KG": "Kyrgyzstan",
    "KH": "Cambodia",
    "KI": "Kiribati",
    "KN": "Saint Kitts and Nevis",
    "KR": "South Korea",
    "KW": "Kuwait",
    "LA": "Laos",
    "LB": "Lebanon",
    "LR": "Liberia",
    "LY": "Libya",
    "LC": "Saint Lucia",
    "LI": "Liechtenstein",
    "LK": "Sri Lanka",
    "LS": "Lesotho",
    "LT": "Lithuania",
    "LU": "Luxembourg",
    "LV": "Latvia",
    "MO": "Macao S.A.R",
    "MF": "Saint Martin",
    "MA": "Morocco",
    "MC": "Monaco",
    "MD": "Moldova",
    "MG": "Madagascar",
    "MV": "Maldives",
    "MX": "Mexico",
    "MH": "Marshall Islands",
    "MK": "Macedonia",
    "ML": "Mali",
    "MT": "Malta",
    "MM": "Myanmar",
    "ME": "Montenegro",
    "MN": "Mongolia",
    "MP": "Northern Mariana Islands",
    "MZ": "Mozambique",
    "MR": "Mauritania",
    "MS": "Montserrat",
    "MU": "Mauritius",
    "MW": "Malawi",
    "MY": "Malaysia",
    "NA": "Namibia",
    "NC": "New Caledonia",
    "NE": "Niger",
    "NF": "Norfolk Island",
    "NG": "Nigeria",
    "NI": "Nicaragua",
    "NU": "Niue",
    "NL": "Netherlands",
    "NO": "Norway",
    "NP": "Nepal",
    "NR": "Nauru",
    "NZ": "New Zealand",
    "OM": "Oman",
    "PK": "Pakistan",
    "PA": "Panama",
    "PN": "Pitcairn Islands",
    "PE": "Peru",
    "PH": "Philippines",
    "PW": "Palau",
    "PG": "Papua New Guinea",
    "PL": "Poland",
    "PR": "Puerto Rico",
    "KP": "North Korea",
    "PT": "Portugal",
    "PY": "Paraguay",
    "PS": "Palestine",
    "PF": "French Polynesia",
    "QA": "Qatar",
    "RO": "Romania",
    "RU": "Russia",
    "RW": "Rwanda",
    "EH": "Western Sahara",
    "SA": "Saudi Arabia",
    "SD": "Sudan",
    "SS": "South Sudan",
    "SN": "Senegal",
    "SG": "Singapore",
    "GS": "South Georgia and South Sandwich Islands",
    "SH": "Saint Helena",
    "SB": "Solomon Islands",
    "SL": "Sierra Leone",
    "SV": "El Salvador",
    "SM": "San Marino",
    "SO": "Somalia",
    "PM": "Saint Pierre and Miquelon",
    "RS": "Republic of Serbia",
    "ST": "Sao Tome and Principe",
    "SR": "Suriname",
    "SK": "Slovakia",
    "SI": "Slovenia",
    "SE": "Sweden",
    "SZ": "Swaziland",
    "SX": "Sint Maarten",
    "SC": "Seychelles",
    "SY": "Syria",
    "TC": "Turks and Caicos Islands",
    "TD": "Chad",
    "TG": "Togo",
    "TH": "Thailand",
    "TJ": "Tajikistan",
    "TM": "Turkmenistan",
    "TL": "East Timor",
    "TO": "Tonga",
    "TT": "Trinidad and Tobago",
    "TN": "Tunisia",
    "TR": "Turkey",
    "TV": "Tuvalu",
    "TW": "Taiwan",
    "TZ": "United Republic of Tanzania",
    "UG": "Uganda",
    "UA": "Ukraine",
    "UY": "Uruguay",
    "US": "United States of America",
    "UZ": "Uzbekistan",
    "VA": "Vatican",
    "VC": "Saint Vincent and the Grenadines",
    "VE": "Venezuela",
    "VG": "British Virgin Islands",
    "VI": "United States Virgin Islands",
    "VN": "Vietnam",
    "VU": "Vanuatu",
    "WF": "Wallis and Futuna",
    "WS": "Samoa",
    "YE": "Yemen",
    "ZA": "South Africa",
    "ZM": "Zambia",
    "ZW": "Zimbabwe",
    "iso-blank--12": "Ashmore and Cartier Islands",
    "iso-blank--27": "Bajo Nuevo Bank (Petrel Is.)",
    "iso-blank--44": "Clipperton Island",
    "iso-blank--46": "Cyprus No Mans Area",
    "iso-blank--54": "Coral Sea Islands",
    "iso-blank--58": "Northern Cyprus",
    "iso-blank--70": "Dhekelia Sovereign Base Area",
    "iso-blank--105": "Indian Ocean Territories",
    "iso-blank--117": "Baykonur Cosmodrome",
    "iso-blank--118": "Siachen Glacier",
    "iso-blank--126": "Kosovo",
    "iso-blank--178": "Spratly Islands",
    "iso-blank--195": "Scarborough Reef",
    "iso-blank--199": "Serranilla Bank",
    "iso-blank--207": "Somaliland",
    "iso-blank--239": "US Naval Base Guantanamo Bay",
    "iso-blank--249": "Akrotiri Sovereign Base Area"
}


const roles = {
    500: 'Admin',
    400: 'Mod',
    300: 'VIP',
    200: 'User',
    100: 'Guest'
}


function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
        var c = cookieArray[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


// string of classes
function isClassExist(elm, cls) {
    if (!elm || !cls) {
        return undefined;
    }

    const elmClasses = elm.className.split(" ");
    const inputClasses = [...new Set(cls.split(" "))];

    if (inputClasses.length <= 0 || elmClasses.length <= 0) {
        return false;
    }

    const isTrue = inputClasses.filter(function (inputClass) {
        return elmClasses.includes(inputClass);
    })

    return (isTrue.length == inputClasses.length);
}

function addClasses(elm, cls) {
    if (!elm || !cls) {
        return undefined;
    }

    const elmClasses = elm.className.split(" ");
    let inputClasses = [...new Set(cls.split(" "))];

    if (inputClasses.length <= 0) {
        return false;
    }

    inputClasses = inputClasses.filter(function (inputClass) {
        return !elmClasses.includes(inputClass);
    })

    const newClasses = elmClasses.concat(inputClasses).join(" ")
    elm.className = newClasses;
    return true;
}


function delClasses(elm, cls) {
    if (!elm || !cls) {
        return undefined;
    }

    const elmClasses = elm.className.split(" ");
    let inputClasses = [...new Set(cls.split(" "))];

    if (inputClasses.length <= 0 || elmClasses.length <= 0) {
        return false;
    }

    inputClasses.forEach(function (inputClass) {
        if (elmClasses.includes(inputClass)) {
            elmClasses.splice(elmClasses.indexOf(inputClass), 1);
        };
    })

    const newClasses = elmClasses.join(" ");
    elm.className = newClasses;

    return true;
}