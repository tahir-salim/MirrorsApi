<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                "name" => "Bahrain",
                "country_code" => "BH",
                "phone_code" => "+973",
                "is_active" => true
            ],
            [
                "name" => "Pakistan",
                "country_code" => "PK",
                "phone_code" => "+92",
                "is_active" => true
            ],
            [
                "name" => "Afghanistan",
                "country_code" => "AF",
                "phone_code" => "+93",
                "is_active" => true
            ],
            [
                "name" => "Aland Islands",
                "country_code" => "AX",
                "phone_code" => "+358",
                "is_active" => true
            ],
            [
                "name" => "Albania",
                "country_code" => "AL",
                "phone_code" => "+355",
                "is_active" => true
            ],
            [
                "name" => "Algeria",
                "country_code" => "DZ",
                "phone_code" => "+213",
                "is_active" => true
            ],
            [
                "name" => "AmericanSamoa",
                "country_code" => "AS",
                "phone_code" => "+1684",
                "is_active" => true
            ],
            [
                "name" => "Andorra",
                "country_code" => "AD",
                "phone_code" => "+376",
                "is_active" => true
            ],
            [
                "name" => "Angola",
                "country_code" => "AO",
                "phone_code" => "+244",
                "is_active" => true
            ],
            [
                "name" => "Anguilla",
                "country_code" => "AI",
                "phone_code" => "+1264",
                "is_active" => true
            ],
            [
                "name" => "Antarctica",
                "country_code" => "AQ",
                "phone_code" => "+672",
                "is_active" => true
            ],
            [
                "name" => "Antigua and Barbuda",
                "country_code" => "AG",
                "phone_code" => "+1268",
                "is_active" => true
            ],
            [
                "name" => "Argentina",
                "country_code" => "AR",
                "phone_code" => "+54",
                "is_active" => true
            ],
            [
                "name" => "Armenia",
                "country_code" => "AM",
                "phone_code" => "+374",
                "is_active" => true
            ],
            [
                "name" => "Aruba",
                "country_code" => "AW",
                "phone_code" => "+297",
                "is_active" => true
            ],
            [
                "name" => "Ascension Island",
                "country_code" => "AC",
                "phone_code" => "+247",
                "is_active" => true
            ],
            [
                "name" => "Australia",
                "country_code" => "AU",
                "phone_code" => "+61",
                "is_active" => true
            ],
            [
                "name" => "Austria",
                "country_code" => "AT",
                "phone_code" => "+43",
                "is_active" => true
            ],
            [
                "name" => "Azerbaijan",
                "country_code" => "AZ",
                "phone_code" => "+994",
                "is_active" => true
            ],
            [
                "name" => "Bahamas",
                "country_code" => "BS",
                "phone_code" => "+1242",
                "is_active" => true
            ],
            [
                "name" => "Bangladesh",
                "country_code" => "BD",
                "phone_code" => "+880",
                "is_active" => true
            ],
            [
                "name" => "Barbados",
                "country_code" => "BB",
                "phone_code" => "+1246",
                "is_active" => true
            ],
            [
                "name" => "Belarus",
                "country_code" => "BY",
                "phone_code" => "+375",
                "is_active" => true
            ],
            [
                "name" => "Belgium",
                "country_code" => "BE",
                "phone_code" => "+32",
                "is_active" => true
            ],
            [
                "name" => "Belize",
                "country_code" => "BZ",
                "phone_code" => "+501",
                "is_active" => true
            ],
            [
                "name" => "Benin",
                "country_code" => "BJ",
                "phone_code" => "+229",
                "is_active" => true
            ],
            [
                "name" => "Bermuda",
                "country_code" => "BM",
                "phone_code" => "+1441",
                "is_active" => true
            ],
            [
                "name" => "Bhutan",
                "country_code" => "BT",
                "phone_code" => "+975",
                "is_active" => true
            ],
            [
                "name" => "Bolivia",
                "country_code" => "BO",
                "phone_code" => "+591",
                "is_active" => true
            ],
            [
                "name" => "Bosnia and Herzegovina",
                "country_code" => "BA",
                "phone_code" => "+387",
                "is_active" => true
            ],
            [
                "name" => "Botswana",
                "country_code" => "BW",
                "phone_code" => "+267",
                "is_active" => true
            ],
            [
                "name" => "Brazil",
                "country_code" => "BR",
                "phone_code" => "+55",
                "is_active" => true
            ],
            [
                "name" => "British Indian Ocean Territory",
                "country_code" => "IO",
                "phone_code" => "+246",
                "is_active" => true
            ],
            [
                "name" => "Brunei Darussalam",
                "country_code" => "BN",
                "phone_code" => "+673",
                "is_active" => true
            ],
            [
                "name" => "Bulgaria",
                "country_code" => "BG",
                "phone_code" => "+359",
                "is_active" => true
            ],
            [
                "name" => "Burkina Faso",
                "country_code" => "BF",
                "phone_code" => "+226",
                "is_active" => true
            ],
            [
                "name" => "Burundi",
                "country_code" => "BI",
                "phone_code" => "+257",
                "is_active" => true
            ],
            [
                "name" => "Cambodia",
                "country_code" => "KH",
                "phone_code" => "+855",
                "is_active" => true
            ],
            [
                "name" => "Cameroon",
                "country_code" => "CM",
                "phone_code" => "+237",
                "is_active" => true
            ],
            [
                "name" => "Canada",
                "country_code" => "CA",
                "phone_code" => "+1",
                "is_active" => true
            ],
            [
                "name" => "Cape Verde",
                "country_code" => "CV",
                "phone_code" => "+238",
                "is_active" => true
            ],
            [
                "name" => "Cayman Islands",
                "country_code" => "KY",
                "phone_code" => "+1345",
                "is_active" => true
            ],
            [
                "name" => "Central African Republic",
                "country_code" => "CF",
                "phone_code" => "+236",
                "is_active" => true
            ],
            [
                "name" => "Chad",
                "country_code" => "TD",
                "phone_code" => "+235",
                "is_active" => true
            ],
            [
                "name" => "Chile",
                "country_code" => "CL",
                "phone_code" => "+56",
                "is_active" => true
            ],
            [
                "name" => "China",
                "country_code" => "CN",
                "phone_code" => "+86",
                "is_active" => true
            ],
            [
                "name" => "Christmas Island",
                "country_code" => "CX",
                "phone_code" => "+61",
                "is_active" => true
            ],
            [
                "name" => "Cocos (Keeling) Islands",
                "country_code" => "CC",
                "phone_code" => "+61",
                "is_active" => true
            ],
            [
                "name" => "Colombia",
                "country_code" => "CO",
                "phone_code" => "+57",
                "is_active" => true
            ],
            [
                "name" => "Comoros",
                "country_code" => "KM",
                "phone_code" => "+269",
                "is_active" => true
            ],
            [
                "name" => "Congo",
                "country_code" => "CG",
                "phone_code" => "+242",
                "is_active" => true
            ],
            [
                "name" => "Cook Islands",
                "country_code" => "CK",
                "phone_code" => "+682",
                "is_active" => true
            ],
            [
                "name" => "Costa Rica",
                "country_code" => "CR",
                "phone_code" => "+506",
                "is_active" => true
            ],
            [
                "name" => "Croatia",
                "country_code" => "HR",
                "phone_code" => "+385",
                "is_active" => true
            ],
            [
                "name" => "Cuba",
                "country_code" => "CU",
                "phone_code" => "+53",
                "is_active" => true
            ],
            [
                "name" => "Cyprus",
                "country_code" => "CY",
                "phone_code" => "+357",
                "is_active" => true
            ],
            [
                "name" => "Czech Republic",
                "country_code" => "CZ",
                "phone_code" => "+420",
                "is_active" => true
            ],
            [
                "name" => "Democratic Republic of the Congo",
                "country_code" => "CD",
                "phone_code" => "+243",
                "is_active" => true
            ],
            [
                "name" => "Denmark",
                "country_code" => "DK",
                "phone_code" => "+45",
                "is_active" => true
            ],
            [
                "name" => "Djibouti",
                "country_code" => "DJ",
                "phone_code" => "+253",
                "is_active" => true
            ],
            [
                "name" => "Dominica",
                "country_code" => "DM",
                "phone_code" => "+1767",
                "is_active" => true
            ],
            [
                "name" => "Dominican Republic",
                "country_code" => "DO",
                "phone_code" => "+1849",
                "is_active" => true
            ],
            [
                "name" => "Ecuador",
                "country_code" => "EC",
                "phone_code" => "+593",
                "is_active" => true
            ],
            [
                "name" => "Egypt",
                "country_code" => "EG",
                "phone_code" => "+20",
                "is_active" => true
            ],
            [
                "name" => "El Salvador",
                "country_code" => "SV",
                "phone_code" => "+503",
                "is_active" => true
            ],
            [
                "name" => "Equatorial Guinea",
                "country_code" => "GQ",
                "phone_code" => "+240",
                "is_active" => true
            ],
            [
                "name" => "Eritrea",
                "country_code" => "ER",
                "phone_code" => "+291",
                "is_active" => true
            ],
            [
                "name" => "Estonia",
                "country_code" => "EE",
                "phone_code" => "+372",
                "is_active" => true
            ],
            [
                "name" => "Eswatini",
                "country_code" => "SZ",
                "phone_code" => "+268",
                "is_active" => true
            ],
            [
                "name" => "Ethiopia",
                "country_code" => "ET",
                "phone_code" => "+251",
                "is_active" => true
            ],
            [
                "name" => "Falkland Islands (Malvinas)",
                "country_code" => "FK",
                "phone_code" => "+500",
                "is_active" => true
            ],
            [
                "name" => "Faroe Islands",
                "country_code" => "FO",
                "phone_code" => "+298",
                "is_active" => true
            ],
            [
                "name" => "Fiji",
                "country_code" => "FJ",
                "phone_code" => "+679",
                "is_active" => true
            ],
            [
                "name" => "Finland",
                "country_code" => "FI",
                "phone_code" => "+358",
                "is_active" => true
            ],
            [
                "name" => "France",
                "country_code" => "FR",
                "phone_code" => "+33",
                "is_active" => true
            ],
            [
                "name" => "French Guiana",
                "country_code" => "GF",
                "phone_code" => "+594",
                "is_active" => true
            ],
            [
                "name" => "French Polynesia",
                "country_code" => "PF",
                "phone_code" => "+689",
                "is_active" => true
            ],
            [
                "name" => "Gabon",
                "country_code" => "GA",
                "phone_code" => "+241",
                "is_active" => true
            ],
            [
                "name" => "Gambia",
                "country_code" => "GM",
                "phone_code" => "+220",
                "is_active" => true
            ],
            [
                "name" => "Georgia",
                "country_code" => "GE",
                "phone_code" => "+995",
                "is_active" => true
            ],
            [
                "name" => "Germany",
                "country_code" => "DE",
                "phone_code" => "+49",
                "is_active" => true
            ],
            [
                "name" => "Ghana",
                "country_code" => "GH",
                "phone_code" => "+233",
                "is_active" => true
            ],
            [
                "name" => "Gibraltar",
                "country_code" => "GI",
                "phone_code" => "+350",
                "is_active" => true
            ],
            [
                "name" => "Greece",
                "country_code" => "GR",
                "phone_code" => "+30",
                "is_active" => true
            ],
            [
                "name" => "Greenland",
                "country_code" => "GL",
                "phone_code" => "+299",
                "is_active" => true
            ],
            [
                "name" => "Grenada",
                "country_code" => "GD",
                "phone_code" => "+1473",
                "is_active" => true
            ],
            [
                "name" => "Guadeloupe",
                "country_code" => "GP",
                "phone_code" => "+590",
                "is_active" => true
            ],
            [
                "name" => "Guam",
                "country_code" => "GU",
                "phone_code" => "+1671",
                "is_active" => true
            ],
            [
                "name" => "Guatemala",
                "country_code" => "GT",
                "phone_code" => "+502",
                "is_active" => true
            ],
            [
                "name" => "Guernsey",
                "country_code" => "GG",
                "phone_code" => "+44",
                "is_active" => true
            ],
            [
                "name" => "Guinea",
                "country_code" => "GN",
                "phone_code" => "+224",
                "is_active" => true
            ],
            [
                "name" => "Guinea-Bissau",
                "country_code" => "GW",
                "phone_code" => "+245",
                "is_active" => true
            ],
            [
                "name" => "Guyana",
                "country_code" => "GY",
                "phone_code" => "+592",
                "is_active" => true
            ],
            [
                "name" => "Haiti",
                "country_code" => "HT",
                "phone_code" => "+509",
                "is_active" => true
            ],
            [
                "name" => "Holy See (Vatican City State)",
                "country_code" => "VA",
                "phone_code" => "+379",
                "is_active" => true
            ],
            [
                "name" => "Honduras",
                "country_code" => "HN",
                "phone_code" => "+504",
                "is_active" => true
            ],
            [
                "name" => "Hong Kong",
                "country_code" => "HK",
                "phone_code" => "+852",
                "is_active" => true
            ],
            [
                "name" => "Hungary",
                "country_code" => "HU",
                "phone_code" => "+36",
                "is_active" => true
            ],
            [
                "name" => "Iceland",
                "country_code" => "IS",
                "phone_code" => "+354",
                "is_active" => true
            ],
            [
                "name" => "India",
                "country_code" => "IN",
                "phone_code" => "+91",
                "is_active" => true
            ],
            [
                "name" => "Indonesia",
                "country_code" => "ID",
                "phone_code" => "+62",
                "is_active" => true
            ],
            [
                "name" => "Iran",
                "country_code" => "IR",
                "phone_code" => "+98",
                "is_active" => true
            ],
            [
                "name" => "Iraq",
                "country_code" => "IQ",
                "phone_code" => "+964",
                "is_active" => true
            ],
            [
                "name" => "Ireland",
                "country_code" => "IE",
                "phone_code" => "+353",
                "is_active" => true
            ],
            [
                "name" => "Isle of Man",
                "country_code" => "IM",
                "phone_code" => "+44",
                "is_active" => true
            ],
            [
                "name" => "Israel",
                "country_code" => "IL",
                "phone_code" => "+972",
                "is_active" => true
            ],
            [
                "name" => "Italy",
                "country_code" => "IT",
                "phone_code" => "+39",
                "is_active" => true
            ],
            [
                "name" => "Ivory Coast / Cote d'Ivoire",
                "country_code" => "CI",
                "phone_code" => "+225",
                "is_active" => true
            ],
            [
                "name" => "Jamaica",
                "country_code" => "JM",
                "phone_code" => "+1876",
                "is_active" => true
            ],
            [
                "name" => "Japan",
                "country_code" => "JP",
                "phone_code" => "+81",
                "is_active" => true
            ],
            [
                "name" => "Jersey",
                "country_code" => "JE",
                "phone_code" => "+44",
                "is_active" => true
            ],
            [
                "name" => "Jordan",
                "country_code" => "JO",
                "phone_code" => "+962",
                "is_active" => true
            ],
            [
                "name" => "Kazakhstan",
                "country_code" => "KZ",
                "phone_code" => "+77",
                "is_active" => true
            ],
            [
                "name" => "Kenya",
                "country_code" => "KE",
                "phone_code" => "+254",
                "is_active" => true
            ],
            [
                "name" => "Kiribati",
                "country_code" => "KI",
                "phone_code" => "+686",
                "is_active" => true
            ],
            [
                "name" => "Korea, Democratic People's Republic of Korea",
                "country_code" => "KP",
                "phone_code" => "+850",
                "is_active" => true
            ],
            [
                "name" => "Korea, Republic of South Korea",
                "country_code" => "KR",
                "phone_code" => "+82",
                "is_active" => true
            ],
            [
                "name" => "Kosovo",
                "country_code" => "XK",
                "phone_code" => "+383",
                "is_active" => true
            ],
            [
                "name" => "Kuwait",
                "country_code" => "KW",
                "phone_code" => "+965",
                "is_active" => true
            ],
            [
                "name" => "Kyrgyzstan",
                "country_code" => "KG",
                "phone_code" => "+996",
                "is_active" => true
            ],
            [
                "name" => "Laos",
                "country_code" => "LA",
                "phone_code" => "+856",
                "is_active" => true
            ],
            [
                "name" => "Latvia",
                "country_code" => "LV",
                "phone_code" => "+371",
                "is_active" => true
            ],
            [
                "name" => "Lebanon",
                "country_code" => "LB",
                "phone_code" => "+961",
                "is_active" => true
            ],
            [
                "name" => "Lesotho",
                "country_code" => "LS",
                "phone_code" => "+266",
                "is_active" => true
            ],
            [
                "name" => "Liberia",
                "country_code" => "LR",
                "phone_code" => "+231",
                "is_active" => true
            ],
            [
                "name" => "Libya",
                "country_code" => "LY",
                "phone_code" => "+218",
                "is_active" => true
            ],
            [
                "name" => "Liechtenstein",
                "country_code" => "LI",
                "phone_code" => "+423",
                "is_active" => true
            ],
            [
                "name" => "Lithuania",
                "country_code" => "LT",
                "phone_code" => "+370",
                "is_active" => true
            ],
            [
                "name" => "Luxembourg",
                "country_code" => "LU",
                "phone_code" => "+352",
                "is_active" => true
            ],
            [
                "name" => "Macau",
                "country_code" => "MO",
                "phone_code" => "+853",
                "is_active" => true
            ],
            [
                "name" => "Madagascar",
                "country_code" => "MG",
                "phone_code" => "+261",
                "is_active" => true
            ],
            [
                "name" => "Malawi",
                "country_code" => "MW",
                "phone_code" => "+265",
                "is_active" => true
            ],
            [
                "name" => "Malaysia",
                "country_code" => "MY",
                "phone_code" => "+60",
                "is_active" => true
            ],
            [
                "name" => "Maldives",
                "country_code" => "MV",
                "phone_code" => "+960",
                "is_active" => true
            ],
            [
                "name" => "Mali",
                "country_code" => "ML",
                "phone_code" => "+223",
                "is_active" => true
            ],
            [
                "name" => "Malta",
                "country_code" => "MT",
                "phone_code" => "+356",
                "is_active" => true
            ],
            [
                "name" => "Marshall Islands",
                "country_code" => "MH",
                "phone_code" => "+692",
                "is_active" => true
            ],
            [
                "name" => "Martinique",
                "country_code" => "MQ",
                "phone_code" => "+596",
                "is_active" => true
            ],
            [
                "name" => "Mauritania",
                "country_code" => "MR",
                "phone_code" => "+222",
                "is_active" => true
            ],
            [
                "name" => "Mauritius",
                "country_code" => "MU",
                "phone_code" => "+230",
                "is_active" => true
            ],
            [
                "name" => "Mayotte",
                "country_code" => "YT",
                "phone_code" => "+262",
                "is_active" => true
            ],
            [
                "name" => "Mexico",
                "country_code" => "MX",
                "phone_code" => "+52",
                "is_active" => true
            ],
            [
                "name" => "Micronesia, Federated States of Micronesia",
                "country_code" => "FM",
                "phone_code" => "+691",
                "is_active" => true
            ],
            [
                "name" => "Moldova",
                "country_code" => "MD",
                "phone_code" => "+373",
                "is_active" => true
            ],
            [
                "name" => "Monaco",
                "country_code" => "MC",
                "phone_code" => "+377",
                "is_active" => true
            ],
            [
                "name" => "Mongolia",
                "country_code" => "MN",
                "phone_code" => "+976",
                "is_active" => true
            ],
            [
                "name" => "Montenegro",
                "country_code" => "ME",
                "phone_code" => "+382",
                "is_active" => true
            ],
            [
                "name" => "Montserrat",
                "country_code" => "MS",
                "phone_code" => "+1664",
                "is_active" => true
            ],
            [
                "name" => "Morocco",
                "country_code" => "MA",
                "phone_code" => "+212",
                "is_active" => true
            ],
            [
                "name" => "Mozambique",
                "country_code" => "MZ",
                "phone_code" => "+258",
                "is_active" => true
            ],
            [
                "name" => "Myanmar",
                "country_code" => "MM",
                "phone_code" => "+95",
                "is_active" => true
            ],
            [
                "name" => "Namibia",
                "country_code" => "NA",
                "phone_code" => "+264",
                "is_active" => true
            ],
            [
                "name" => "Nauru",
                "country_code" => "NR",
                "phone_code" => "+674",
                "is_active" => true
            ],
            [
                "name" => "Nepal",
                "country_code" => "NP",
                "phone_code" => "+977",
                "is_active" => true
            ],
            [
                "name" => "Netherlands",
                "country_code" => "NL",
                "phone_code" => "+31",
                "is_active" => true
            ],
            [
                "name" => "Netherlands Antilles",
                "country_code" => "AN",
                "phone_code" => "+599",
                "is_active" => true
            ],
            [
                "name" => "New Caledonia",
                "country_code" => "NC",
                "phone_code" => "+687",
                "is_active" => true
            ],
            [
                "name" => "New Zealand",
                "country_code" => "NZ",
                "phone_code" => "+64",
                "is_active" => true
            ],
            [
                "name" => "Nicaragua",
                "country_code" => "NI",
                "phone_code" => "+505",
                "is_active" => true
            ],
            [
                "name" => "Niger",
                "country_code" => "NE",
                "phone_code" => "+227",
                "is_active" => true
            ],
            [
                "name" => "Nigeria",
                "country_code" => "NG",
                "phone_code" => "+234",
                "is_active" => true
            ],
            [
                "name" => "Niue",
                "country_code" => "NU",
                "phone_code" => "+683",
                "is_active" => true
            ],
            [
                "name" => "Norfolk Island",
                "country_code" => "NF",
                "phone_code" => "+672",
                "is_active" => true
            ],
            [
                "name" => "North Macedonia",
                "country_code" => "MK",
                "phone_code" => "+389",
                "is_active" => true
            ],
            [
                "name" => "Northern Mariana Islands",
                "country_code" => "MP",
                "phone_code" => "+1670",
                "is_active" => true
            ],
            [
                "name" => "Norway",
                "country_code" => "NO",
                "phone_code" => "+47",
                "is_active" => true
            ],
            [
                "name" => "Oman",
                "country_code" => "OM",
                "phone_code" => "+968",
                "is_active" => true
            ],
            [
                "name" => "Palau",
                "country_code" => "PW",
                "phone_code" => "+680",
                "is_active" => true
            ],
            [
                "name" => "Palestine",
                "country_code" => "PS",
                "phone_code" => "+970",
                "is_active" => true
            ],
            [
                "name" => "Panama",
                "country_code" => "PA",
                "phone_code" => "+507",
                "is_active" => true
            ],
            [
                "name" => "Papua New Guinea",
                "country_code" => "PG",
                "phone_code" => "+675",
                "is_active" => true
            ],
            [
                "name" => "Paraguay",
                "country_code" => "PY",
                "phone_code" => "+595",
                "is_active" => true
            ],
            [
                "name" => "Peru",
                "country_code" => "PE",
                "phone_code" => "+51",
                "is_active" => true
            ],
            [
                "name" => "Philippines",
                "country_code" => "PH",
                "phone_code" => "+63",
                "is_active" => true
            ],
            [
                "name" => "Pitcairn",
                "country_code" => "PN",
                "phone_code" => "+872",
                "is_active" => true
            ],
            [
                "name" => "Poland",
                "country_code" => "PL",
                "phone_code" => "+48",
                "is_active" => true
            ],
            [
                "name" => "Portugal",
                "country_code" => "PT",
                "phone_code" => "+351",
                "is_active" => true
            ],
            [
                "name" => "Puerto Rico",
                "country_code" => "PR",
                "phone_code" => "+1939",
                "is_active" => true
            ],
            [
                "name" => "Qatar",
                "country_code" => "QA",
                "phone_code" => "+974",
                "is_active" => true
            ],
            [
                "name" => "Reunion",
                "country_code" => "RE",
                "phone_code" => "+262",
                "is_active" => true
            ],
            [
                "name" => "Romania",
                "country_code" => "RO",
                "phone_code" => "+40",
                "is_active" => true
            ],
            [
                "name" => "Russia",
                "country_code" => "RU",
                "phone_code" => "+7",
                "is_active" => true
            ],
            [
                "name" => "Rwanda",
                "country_code" => "RW",
                "phone_code" => "+250",
                "is_active" => true
            ],
            [
                "name" => "Saint Barthelemy",
                "country_code" => "BL",
                "phone_code" => "+590",
                "is_active" => true
            ],
            [
                "name" => "Saint Helena, Ascension and Tristan Da Cunha",
                "country_code" => "SH",
                "phone_code" => "+290",
                "is_active" => true
            ],
            [
                "name" => "Saint Kitts and Nevis",
                "country_code" => "KN",
                "phone_code" => "+1869",
                "is_active" => true
            ],
            [
                "name" => "Saint Lucia",
                "country_code" => "LC",
                "phone_code" => "+1758",
                "is_active" => true
            ],
            [
                "name" => "Saint Martin",
                "country_code" => "MF",
                "phone_code" => "+590",
                "is_active" => true
            ],
            [
                "name" => "Saint Pierre and Miquelon",
                "country_code" => "PM",
                "phone_code" => "+508",
                "is_active" => true
            ],
            [
                "name" => "Saint Vincent and the Grenadines",
                "country_code" => "VC",
                "phone_code" => "+1784",
                "is_active" => true
            ],
            [
                "name" => "Samoa",
                "country_code" => "WS",
                "phone_code" => "+685",
                "is_active" => true
            ],
            [
                "name" => "San Marino",
                "country_code" => "SM",
                "phone_code" => "+378",
                "is_active" => true
            ],
            [
                "name" => "Sao Tome and Principe",
                "country_code" => "ST",
                "phone_code" => "+239",
                "is_active" => true
            ],
            [
                "name" => "Saudi Arabia",
                "country_code" => "SA",
                "phone_code" => "+966",
                "is_active" => true
            ],
            [
                "name" => "Senegal",
                "country_code" => "SN",
                "phone_code" => "+221",
                "is_active" => true
            ],
            [
                "name" => "Serbia",
                "country_code" => "RS",
                "phone_code" => "+381",
                "is_active" => true
            ],
            [
                "name" => "Seychelles",
                "country_code" => "SC",
                "phone_code" => "+248",
                "is_active" => true
            ],
            [
                "name" => "Sierra Leone",
                "country_code" => "SL",
                "phone_code" => "+232",
                "is_active" => true
            ],
            [
                "name" => "Singapore",
                "country_code" => "SG",
                "phone_code" => "+65",
                "is_active" => true
            ],
            [
                "name" => "Sint Maarten",
                "country_code" => "SX",
                "phone_code" => "+1721",
                "is_active" => true
            ],
            [
                "name" => "Slovakia",
                "country_code" => "SK",
                "phone_code" => "+421",
                "is_active" => true
            ],
            [
                "name" => "Slovenia",
                "country_code" => "SI",
                "phone_code" => "+386",
                "is_active" => true
            ],
            [
                "name" => "Solomon Islands",
                "country_code" => "SB",
                "phone_code" => "+677",
                "is_active" => true
            ],
            [
                "name" => "Somalia",
                "country_code" => "SO",
                "phone_code" => "+252",
                "is_active" => true
            ],
            [
                "name" => "South Africa",
                "country_code" => "ZA",
                "phone_code" => "+27",
                "is_active" => true
            ],
            [
                "name" => "South Georgia and the South Sandwich Islands",
                "country_code" => "GS",
                "phone_code" => "+500",
                "is_active" => true
            ],
            [
                "name" => "South Sudan",
                "country_code" => "SS",
                "phone_code" => "+211",
                "is_active" => true
            ],
            [
                "name" => "Spain",
                "country_code" => "ES",
                "phone_code" => "+34",
                "is_active" => true
            ],
            [
                "name" => "Sri Lanka",
                "country_code" => "LK",
                "phone_code" => "+94",
                "is_active" => true
            ],
            [
                "name" => "Sudan",
                "country_code" => "SD",
                "phone_code" => "+249",
                "is_active" => true
            ],
            [
                "name" => "Suriname",
                "country_code" => "SR",
                "phone_code" => "+597",
                "is_active" => true
            ],
            [
                "name" => "Svalbard and Jan Mayen",
                "country_code" => "SJ",
                "phone_code" => "+47",
                "is_active" => true
            ],
            [
                "name" => "Sweden",
                "country_code" => "SE",
                "phone_code" => "+46",
                "is_active" => true
            ],
            [
                "name" => "Switzerland",
                "country_code" => "CH",
                "phone_code" => "+41",
                "is_active" => true
            ],
            [
                "name" => "Syrian Arab Republic",
                "country_code" => "SY",
                "phone_code" => "+963",
                "is_active" => true
            ],
            [
                "name" => "Taiwan",
                "country_code" => "TW",
                "phone_code" => "+886",
                "is_active" => true
            ],
            [
                "name" => "Tajikistan",
                "country_code" => "TJ",
                "phone_code" => "+992",
                "is_active" => true
            ],
            [
                "name" => "Tanzania, United Republic of Tanzania",
                "country_code" => "TZ",
                "phone_code" => "+255",
                "is_active" => true
            ],
            [
                "name" => "Thailand",
                "country_code" => "TH",
                "phone_code" => "+66",
                "is_active" => true
            ],
            [
                "name" => "Timor-Leste",
                "country_code" => "TL",
                "phone_code" => "+670",
                "is_active" => true
            ],
            [
                "name" => "Togo",
                "country_code" => "TG",
                "phone_code" => "+228",
                "is_active" => true
            ],
            [
                "name" => "Tokelau",
                "country_code" => "TK",
                "phone_code" => "+690",
                "is_active" => true
            ],
            [
                "name" => "Tonga",
                "country_code" => "TO",
                "phone_code" => "+676",
                "is_active" => true
            ],
            [
                "name" => "Trinidad and Tobago",
                "country_code" => "TT",
                "phone_code" => "+1868",
                "is_active" => true
            ],
            [
                "name" => "Tunisia",
                "country_code" => "TN",
                "phone_code" => "+216",
                "is_active" => true
            ],
            [
                "name" => "Turkey",
                "country_code" => "TR",
                "phone_code" => "+90",
                "is_active" => true
            ],
            [
                "name" => "Turkmenistan",
                "country_code" => "TM",
                "phone_code" => "+993",
                "is_active" => true
            ],
            [
                "name" => "Turks and Caicos Islands",
                "country_code" => "TC",
                "phone_code" => "+1649",
                "is_active" => true
            ],
            [
                "name" => "Tuvalu",
                "country_code" => "TV",
                "phone_code" => "+688",
                "is_active" => true
            ],
            [
                "name" => "Uganda",
                "country_code" => "UG",
                "phone_code" => "+256",
                "is_active" => true
            ],
            [
                "name" => "Ukraine",
                "country_code" => "UA",
                "phone_code" => "+380",
                "is_active" => true
            ],
            [
                "name" => "United Arab Emirates",
                "country_code" => "AE",
                "phone_code" => "+971",
                "is_active" => true
            ],
            [
                "name" => "United Kingdom",
                "country_code" => "GB",
                "phone_code" => "+44",
                "is_active" => true
            ],
            [
                "name" => "United States",
                "country_code" => "US",
                "phone_code" => "+1",
                "is_active" => true
            ],
            [
                "name" => "Uruguay",
                "phone_code" => "+598",
                "country_code" => "UY",
                "is_active" => true
            ],
            [
                "name" => "Uzbekistan",
                "country_code" => "UZ",
                "phone_code" => "+998",
                "is_active" => true
            ],
            [
                "name" => "Vanuatu",
                "country_code" => "VU",
                "phone_code" => "+678",
                "is_active" => true
            ],
            [
                "name" => "Venezuela, Bolivarian Republic of Venezuela",
                "country_code" => "VE",
                "phone_code" => "+58",
                "is_active" => true
            ],
            [
                "name" => "Vietnam",
                "country_code" => "VN",
                "phone_code" => "+84",
                "is_active" => true
            ],
            [
                "name" => "Virgin Islands, British",
                "country_code" => "VG",
                "phone_code" => "+1284",
                "is_active" => true
            ],
            [
                "name" => "Virgin Islands, U.S.",
                "country_code" => "VI",
                "phone_code" => "+1340",
                "is_active" => true
            ],
            [
                "name" => "Wallis and Futuna",
                "country_code" => "WF",
                "phone_code" => "+681",
                "is_active" => true
            ],
            [
                "name" => "Yemen",
                "country_code" => "YE",
                "phone_code" => "+967",
                "is_active" => true
            ],
            [
                "name" => "Zambia",
                "country_code" => "ZM",
                "phone_code" => "+260",
                "is_active" => true
            ],
            [
                "name" => "Zimbabwe",
                "country_code" => "ZW",
                "phone_code" => "+263",
                "is_active" => true
            ],
        ];

        DB::table('countries')->insert($countries);
    }
}
