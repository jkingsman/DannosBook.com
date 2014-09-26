<?php
class BookingList {
    private $html = NULL;
    private $loadeddom; 
    
    function __construct() {
	$this->loadeddom = new simple_html_dom();
    }
    
    //loads html from an URL
    public function loadFromURL($url) {
	$this->html = file_get_contents($url);
	$this->loadeddom->load($this->html);
    }
    
    //loads HTML from a given string
    public function loadFromString($string) {
	$this->html = $string;
	$this->loadeddom->load($this->html);
    }
    
    //loads html from a given file
    public function loadFromFile($location) {
	$this->html = File::get($location);
	$this->loadeddom->load($this->html);
    }
    
    //loads html from cache, and erases. 
    public function loadFromCache() {
	$cache = Cache::get('bookinglist', "NODATA");
	if($cache == "NODATA"){
	    return FALSE;
	}
	else{
	    $this->html = $cache;
	    $this->loadeddom->load($this->html);
	}
    }
    
    //returns int count of array elements
    public function countCache() {
	$cache = Cache::get('bookinglist', "NODATA");
	if($cache == "NODATA"){
	    return 0;
	}
	else{
	    return count($this->getNamesFromLinks());
	}
    }
    
    //saves html to the cache
    public function saveToCache() {
	Cache::forever('bookinglist', $this->html);
    }
    
    //clears html from the cache
    public function forgetCache() {
	Cache::forget('bookinglist');
    }
    
    //shows the html
    public function getHTML(){
	return $this->html;
    }
    
    //returns an array of names from the currently loaded html
    public function getNamesFromLinks(){
	//what we find right before the name
	$namedelim = 'bookinglog.cfm?name=';
	
	//how long that indicator is
	$forwardclip = strlen($namedelim);
	

	// Find all links
	$namelist = array();
	foreach($this->loadeddom->find('a') as $link){
	    //this means it's a name link
	    if(strpos($link->href, $namedelim)){
		//get the name by only saving what's after the marker
	        $bookingname = substr($link->href, strrpos($link->href, $namedelim)+$forwardclip);
	        $namelist[] = $bookingname;
	    }
	}
	
	//the list is alphabetical, so this gives us a leg up.
	sort($namelist);
	return $namelist;
    }
    
    //returns data for the person identified by $name
    public function getDataByName($name){	
	$personData = array();
	$chargeData = array();
	
	foreach($this->loadeddom->find('td[width]') as $elem){
	    if(str_contains($elem->innertext, $name) &&
		$elem->getAttribute('width') == "35%" &&
		$elem->getAttribute('class') == "subbody"){
		//get their name and work from there
		Log::info('debug: found their name');
		//this is just the rhythm of the page - skipping beats (td's)
		//God I'm so sorry for this.
		
		//row 1 -- name and booking date
		$row1 = $elem->parent();
		$personData['name'] = $row1->firstChild()->nextSibling()->nextSibling()->innertext; //name
		$personData['bookingdate'] = $row1->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //booking date
		
		//row 2 -- address and latest charge date
		$row2 = $elem->parent()->nextSibling();
		$personData['address'] = $row2->firstChild()->nextSibling()->nextSibling()->innertext; //address
		$personData['latestchargedate'] = $row2->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //latest charge date
		
		//row 3 -- gender and arrest date
		$row3 = $elem->parent()->nextSibling()->nextSibling();
		$personData['gender'] = $row3->firstChild()->nextSibling()->nextSibling()->innertext; //gender
		$personData['arrestdate'] = $row3->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //arrest date
		
		//row 4 -- DOB and arrest agency
		$row4 = $elem->parent()->nextSibling()->nextSibling()->nextSibling();
		$personData['birthdate'] = $row4->firstChild()->nextSibling()->nextSibling()->innertext; //DOB
		$personData['arrestagency'] = $row4->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //arrest agency
		
		//row 5 -- Occupation and arrest location !!NOTE WE SKIP AN EXTRA SIBLING BECAUSE OF A COMMENT!!
		$row5 = $elem->parent()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling();
		$personData['occupation'] = $row5->firstChild()->nextSibling()->nextSibling()->innertext; //occupation
		$personData['arrestlocation'] = $row5->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //arrest location
		
		//row 6 -- height and hair color
		$row6 = $elem->parent()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling();
		$personData['height'] = $row6->firstChild()->nextSibling()->nextSibling()->innertext; //height
		$personData['haircolor'] = $row6->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //hair color
		
		//row 7 -- weight and eye color
		$row7 = $elem->parent()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling();
		$personData['weight'] = $row7->firstChild()->nextSibling()->nextSibling()->innertext; //weight
		$personData['eyecolor'] = $row7->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext; //eye color
		
		//row 8 -- jail id
		$row8 = $elem->parent()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling();
		$personData['jail_id'] = $row8->firstChild()->nextSibling()->nextSibling()->innertext; //jailid
		Log::info('debug: domcrawl finished');
	    }
	}
	
	foreach($this->loadeddom->find('a[class]') as $elem){
	    if(str_contains($elem->href, $name) &&
		$elem->getAttribute('class') == "bluelinkunder"){
		Log::info('debug: appearance link found');
		//we've found our court appearance link. We can traverse up from here.
		//First, add it to the array
		    $personData['courtlink'] = $elem->href;
		
		    $currElem = $elem->parent()->parent()->prev_sibling();
		    $reachedEnd = 0;
		    while($reachedEnd == 0){
			Log::info('debug: loading charge');
			$chargeData[] = array (
			    'charge' => $currElem->firstChild()->nextSibling()->innertext,
			    'desc' => $currElem->firstChild()->nextSibling()->nextSibling()->innertext,
			    'type' => $currElem->firstChild()->nextSibling()->nextSibling()->nextSibling()->innertext,
			    'bail' => preg_replace('/[^0-9.]/', '', $currElem->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext),
			    'auth' => $currElem->firstChild()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->nextSibling()->innertext,
			  );		
			
			$currElem = $currElem->prev_sibling();
			if(str_contains($currElem->innertext, "Arial, Helvetica, sans-serif")){
			    //this means the next prev_sibling has a font tag, i.e. no more charge rows
			    $reachedEnd = 1;
			}
		    }		
	    }
	}
	
	Log::info('debug: done with scraping; formatting');
	
	//format as appropriate
	foreach($personData as $key=>$value){
	    $personData[$key] = $this->tidyWhiteSpace($value);
	    if(str_contains($key, 'date')){
		$personData[$key] = $this->strToEpoch($personData[$key]);
	    }
	}
	
	foreach($chargeData as $key=>$array){
	    foreach($chargeData[$key] as $twodkey=>$twodarray){
		$chargeData[$key][$twodkey] = $this->tidyWhiteSpace($twodarray);
	    }
	}
	
	//package and return
	$finalarray=array('person'=>$personData, 'charges'=>$chargeData);
	return $finalarray;
    }
    
    //replace all duplicate whitespaces with a single space character for array values, and trip the space from the beginning and end if it exists
    private function tidyWhiteSpace($string){
	return trim(preg_replace('!\s+!', ' ', str_replace("</font>", "", $string)));
    }
    
    //replace array values with string 'date' in the key to unix epochs
    private function strToEpoch($string){
	$date = date_create($string);
	return date_format($date, 'Y-m-d H:i:s');
    }
    
}