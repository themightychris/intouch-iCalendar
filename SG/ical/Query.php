<?php // BUILD: Remove line

namespace sg\ical;

/**
 *
 * A collection of functions to query the events in a calendar.
 *
 * @package sg\ical
 * @author Morten Fangel (C) 2008
 * @author Michael Kahn 2013
 * @license http://creativecommons.org/licenses/by-sa/2.5/dk/deed.en_GB CC-BY-SA-DK
 */
class Query {
	/**
	 * Returns all events from the calendar between two timestamps
	 *
	 * Note that the events returned needs only slightly overlap.
	 *
	 * @param sg\ical\iCal |array $ical The calendar to query
	 * @param int $start
	 * @param int $end
	 * @return sg\ical\VEvent[]
	 */
	public static function Between(iCal $ical, $start, $end ) {
		if( $ical instanceof SG_iCalReader ) {
			$ical = $ical->getEvents();
		}
		if( !is_array($ical) ) {
			throw new Exception('sg\ical\Query::Between called with invalid input!');
		}

		$rtn = array();
		foreach( $ical AS $e ) {
			if( ($start <= $e->getStart() && $e->getStart() < $end)
			 || ($start < $e->getRangeEnd() && $e->getRangeEnd() <= $end) ) {
				$rtn[] = $e;
			}
		}
		return $rtn;
	}

	/**
	 * Returns all events from the calendar after a given timestamp
	 *
	 * @param SG_iCalReader|array $ical The calendar to query
	 * @param int $start
	 * @return sg\ical\VEvent[]
	 */
	public static function After( $ical, $start ) {
		if( $ical instanceof iCal ) {
			$ical = $ical->getEvents();
		}
		if( !is_array($ical) ) {
			throw new Exception('sg\ical\Query::After called with invalid input!');
		}

		$rtn = array();
		foreach( $ical AS $e ) {
			if($e->getStart() >= $start || $e->getRangeEnd() >= $start) {
				$rtn[] = $e;
			}
		}
		return $rtn;
	}

	/**
	 * Sorts the events from the calendar after the specified column.
	 * Column can be all valid entires that getProperty can return.
	 * So stuff like uid, start, end, summary etc.
	 * @param sg\ical\iCal|array $ical The calendar to query
	 * @param string $column
	 * @return sg\ical\VEvent[]
	 */
	public static function Sort( $ical, $column ) {
		if( $ical instanceof iCal ) {
			$ical = $ical->getEvents();
		}
		if( !is_array($ical) ) {
			throw new Exception('sg\ical\Query::Sort called with invalid input!');
		}

		$cmp = create_function('$a, $b', 'return strcmp($a->getProperty("' . $column . '"), $b->getProperty("' . $column . '"));');
		usort($ical, $cmp);
		return $ical;
	}
}

