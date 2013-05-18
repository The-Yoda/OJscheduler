<?php
class Contests{
	use Rest;
	private function getSchedule($oGet){
		$aContestNames = json_decode($oGet->getContests(), true);
		$timeZone = $oGet->getTimeZone();
		$limit = $oGet->getLimit();
		foreach ($aContestNames as $site=>$contests) {
			$oSite = ObjectFactory::getSiteInstance($site);
//			$oSite = ObjectFactory::{"get" . $site . "Instance"}();
		    $schedule[$site] = $oSite->getSchedule($contests, $timeZone, $limit);
		}
		return json_encode($schedule);
	}
}
