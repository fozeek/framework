<?php

namespace Core\Component\Filter;

use Core\Resource\Component;

/*
	Class :		StringComponent
	Lien : 		site/library/components/string.class.php
	Déscription :
				Permet de gérer les strings
*/
class Filter extends Component {
    
    public static function sanitize($string) {
		$string = strtolower($string);
		$string = str_replace(array(
				" ",
				"'",
				",",
				"?",
				"!",
				":",
				";",
				"é",
				"è",
				"ê",
				"à",
				"â",
				"ù",
				"û",
				"ï",
				"î",
				"ô",
				"ö",
				"--",
			), array(
				"-",
				"-",
				"-",
				"-",
				"-",
				"-",
				"-",
				"e",
				"e",
				"e",
				"a",
				"a",
				"u",
				"u",
				"i",
				"i",
				"o",
				"o",
				"-",
			), $string);
		$string = preg_replace("/(-)+/i", "-", $string);
		$string = rtrim($string, "-");
		$string = ltrim($string, "-");
		return $string;
    }
}
?>
