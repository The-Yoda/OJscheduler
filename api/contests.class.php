<?php
class Contests{
	use Rest;
	private function getSchedule($oGet){
		error_log($oGet->asJson());
		$site = $oGet->getSite();
	//	$contestNames = $oGet->getContestNames();
		$aContestNames = json_decode($oGet->getContests(), true);
		$timeZone = $oGet->getTimeZone();
		$limit = $oGet->getLimit();
		$oSite = ObjectFactory::getSiteInstance("Topcoder");
//		$oSite = ObjectFactory::{"get" . $site . "Instance"}();
		$schedule = $oSite->getSchedule($aContestNames, $timeZone, $limit);
		return json_encode($schedule);
	}
}
