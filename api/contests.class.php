<?php
class Contests{
	use Rest;
	private function getSchedule($oGet){
		$site = $oGet->getSite();
		$contestNames = $oGet->getContestNames();
		$aContestNames = json_decode($contestNames, true)['contests'];
		$timeZone = $oGet->getTimeZone();
		$limit = $oGet->getLimit();
		$oSite = ObjectFactory::getSiteInstance("Topcoder");
//		$oSite = ObjectFactory::{"get" . $site . "Instance"}();
		$schedule = $oSite->getSchedule($aContestNames, $timeZone, $limit);
		return json_encode($schedule);
	}
}
