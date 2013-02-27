<?php
//define("VENDOR_PATH", __DIR__ . "/../../thirdpartylib/");
require VENDOR_PATH . "domparser/simple_html_dom.php";
//$a = new Topcoder();
//var_dump($a->getSchedule(array("Srm")), "PST", 10);

//var_dump($a->toTimeZone("Asia/Kolkata", "2013-02-06 23:00:00"));

class Topcoder{

	public function getSchedule($aContestNames, $timeZone, $limit){
//		echo "JDJFDK";print_r( $aContestNames);
//		$aContestNames = $oContestNames->asArray();
		foreach ($aContestNames as $contestName){
			$aContests[$contestName] = self::{"parse" . $contestName}($timeZone, $limit);
		}
		//print_r($aContests);
		return $aContests;
	}

	public function parseSrm($timeZone, $limit) {
		$year = self::getFormattedCurrentYear();
		$month = self::getFormattedCurrentMonth();
		$currentMonthYear = $month . '_' . $year;
		$start_html = file_get_html('http://community.topcoder.com/tc?module=Static&d1=calendar&d2=' . $currentMonthYear);
		$availableMonths = array();

		foreach ($start_html->find('select[name=month]') as $result) {
			foreach ($result->find('option') as $option)
				$availableMonths[] = $option->value;
		}
var_dump($availableMonths);
		$currentMonthIndex = array_search($currentMonthYear, $availableMonths);
		$aSchedule = array();
		for ($month = $currentMonthIndex; $month < sizeof($availableMonths); $month++)
	//	$month = $currentMonthIndex;
			{
			$mainhtml = file_get_html('http://community.topcoder.com/tc?module=Static&d1=calendar&d2=' . $availableMonths[$month]);
			foreach ($mainhtml->find('td[class=value]') as $result)
			{
				foreach ($result->find('div[class=srm]') as $div)
					foreach ($div->find('strong') as $strong)
					{
						foreach ($strong->find('a') as $a) {
							$aUTCSchedule = self::parseFromLink($a);
							$aSchedule [$a->innertext] = self::convertTo($timeZone, $aUTCSchedule);
						}
					}
			}
		}
		return $aSchedule;
	}
	
	 function convertTo($timeZone, $aUTCSchedule){
		//		return $aUTCSchedule;
		$aSchedule['RegistrationTime'] = self::toTimeZone($timeZone, $aUTCSchedule['RegistrationTime']);
		$aSchedule['ContestTime'] = self::toTimeZone($timeZone, $aUTCSchedule['ContestTime']); 
		return $aSchedule;		
	}

	private function parseFromLink($a){
		if (substr($a->href, 0, 4) == "http") 
			$html = file_get_html($a->href);
		else{
			$html = file_get_html('http://community.topcoder.com' . $a->href);
			$currentSrm = $a->innertext;
		}
		foreach ($html->find('td[class=statText]') as $result){
			foreach( $result->find('b') as $b){
				$aSchedule[] = self::sanitize($b->innertext);
			}
		}
		return self::getUTCTime($aSchedule);
	}

	function toTimeZone($timeZone, $dateTime){
		$utc_date = DateTime::createFromFormat(
			'Y-m-d H:i:s', 
			$dateTime, 
			new DateTimeZone('UTC')
		);
		try {
			$utc_date->setTimeZone(new DateTimeZone($timeZone));
			return $utc_date->format('Y-m-d H:i:s');
		} catch(Exception $e){
			echo $e->getMessage();
		}
	}

	private function getUTCTime($datetime){
		date_default_timezone_set('UTC');
		$date = self::formatDate($datetime[0][0]);
		$timezone = $datetime[1][2];
		$registrationTime = self::formatTime($datetime[1]);
		$contestTime = self::formatTime($datetime[2]);
		$aSchedule['RegistrationTime'] = date('Y-m-d H:i:s',strtotime($date . ' ' . $registrationTime . ' ' . $timezone));
		$aSchedule['ContestTime'] = date('Y-m-d H:i:s',strtotime($date . ' ' . $contestTime . ' ' . $timezone));
		//	echo toTimeZone("Asia/Kolkata", $aSchedule['ContestTime']);
		return $aSchedule;
	}

	private function formatDate($date){
		$date = explode(".", $date);
		$month = $date[0];
		$day = $date[1];
		$year = $date[2];
		return $year . "-" . $month . "-" . $day;	
	}	

	private function formatTime($time){
		$hour = $time[0];
		$min = $time[1];
		return date("H:i", strtotime($hour . " " . $min));
	}

	private function sanitize($sValue){
		$sValue = preg_replace('/[^a-zA-Z0-9.: ]/', '', $sValue);
		$sValue = preg_replace('!\s+!', ' ', $sValue);
		return explode(' ', rtrim(ltrim($sValue)));
	}

	private function getFormattedCurrentMonth(){
		return substr(strtolower(date('F')), 0, 3);
	}

	private function getFormattedCurrentYear(){
		return substr(date('Y'), -2);
	}
}
?>
