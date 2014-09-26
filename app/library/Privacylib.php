<?php
class Privacylib {
    static public function privName($name) {
	list($lastname, $firstname) = explode(", ",$name);
	
	if(Auth::user()->admin || Auth::user()->username == 'aubreywade'){
	    $orderedname = $firstname . " " . $lastname;
	}else{
	    $orderedname = $firstname . " " . substr(md5($lastname), 0, 8);
	}
	
	
	$fullname = ucwords(strtolower($orderedname));
	return $fullname;
    }
    
    static public function stdCase($string) {
	return ucwords(strtolower($string));
    }
    
    static public function crimeType($string) {
	if($string == "M"){
	    return "Misdemeanor";
	}
	elseif($string == "I"){
	    return "Infraction";
	}
	elseif($string == "F"){
	    return "Felony";
	}
	else{
	    return "Crime Type Uknown ('$string')";
	}
    }
    
    static public function crimeTypeToBootstrap($string) {
	if($string == "M"){
	    return "warning";
	}
	elseif($string == "I"){
	    return "success";
	}
	elseif($string == "F"){
	    return "danger";
	}
	else{
	    return "default";
	}
    }
    
    static public function bailFormat($amt, $symbol="$"){
	if($amt == 0){
	    return "No bail.";
	}else{	
	    setlocale(LC_MONETARY, 'en_US');
	    $money = money_format('%i', $amt) . "\n";
	    $trimmed = trim($money, "USD ");
	    
	    return $symbol . $trimmed . " bail";
	}
    }
    
    static public function inchesToString($inches){
	$ftpart = floor($inches/12);
	
	$inchespart = $inches - ($ftpart * 12);
	
	return $ftpart . "' " . $inchespart . '"';
	
    }
    
    static public function codeToLink($code){
	//array of two to three letter conversions from Marin System to legislature lookup
	$abbrev = array(
	    'PC' => 'PEN', //penal code
	    'VC' => 'VEH', //vehical code
	    'HS' => 'HSC', //health/safety
	    'BP' => 'BPC', //business/professions
	);
	
	$body = substr($code, -2, 2);
	
	if(preg_match('/\d+/', $code, $match) && isset($match[0]) && array_key_exists($body, $abbrev)){
	    return '<a target="_blank" href="http://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=' . $abbrev[$body] . '&sectionNum=' . $match[0] . '">' . $code . '</a>';
	}
	else{
	    return $code;
	}
    }
}