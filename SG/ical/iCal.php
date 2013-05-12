<?php

namespace sg\ical;

require_once __DIR__ . '/Factory.php';
require_once __DIR__ . '/Duration.php';
require_once __DIR__ . '/Freq.php';
require_once __DIR__ . '/Line.php';
require_once __DIR__ . '/Parser.php';
require_once __DIR__ . '/Query.php';
require_once __DIR__ . '/Recurrence.php';
require_once __DIR__ . '/VCalendar.php';
require_once __DIR__ . '/VEvent.php';
require_once __DIR__ . '/VTimeZone.php';

define('SG_ICALREADER_VERSION', '0.7.0');

/**
 * A simple iCal parser. Should take care of most stuff for ya
 * http://github.com/fangel/SG-iCalendar
 *
 * Roadmap:
 *  * Finish FREQUENCY-parsing.
 *  * Add API for recurring events
 *
 * A simple example:
 * <?php
 * $ical = new SG_iCalReader("http://example.com/calendar.ics");
 * foreach( $ical->getEvents() As $event ) {
 *   // Do stuff with the event $event
 * }
 * ?>
 *
 * @package SG
 * @author Morten Fangel (C) 2008
 * @author xonev (C) 2010
 * @author Tanguy Pruvot (C) 2010
 * @license http://creativecommons.org/licenses/by-sa/2.5/dk/deed.en_GB CC-BY-SA-DK
 */
class iCal {

	//objects
	public $information; //VCalendar
	public $timezones;   //VTimeZone

	protected $events; //VEvent[]

	/**
	 * Constructs a new iCalReader. You can supply the url now, or later using setUrl
	 * @param $url string
	 */
	public function __construct($url = false) {
		$this->setUrl($url);
	}

	/**
	 * Sets (or resets) the url this reader reads from.
	 * @param $url string
	 */
	public function setUrl( $url = false ) {
		if( $url !== false ) {
			SG_iCal_Parser::Parse($url, $this);
		}
	}

	/**
	 * Returns the main calendar info. You can then query the returned
	 * object with ie getTitle().
	 * @return sg\ical\VCalendar
	 */
	public function getCalendarInfo() {
		return $this->information;
	}

	/**
	 * Sets the calendar info for this calendar
	 * @param sg\ical\VCalendar $info
	 */
	public function setCalendarInfo( VCalendar $info ) {
		$this->information = $info;
	}


	/**
	 * Returns a given timezone for the calendar. This is mainly used
	 * by VEvents to adjust their date-times if they have specified a
	 * timezone.
	 *
	 * If no timezone is given, all timezones in the calendar is
	 * returned.
	 *
	 * @param $tzid string
	 * @return SG_iCal_VTimeZone
	 */
	public function getTimeZoneInfo( $tzid = null ) {
		if( $tzid == null ) {
			return $this->timezones;
		} else {
			if ( !isset($this->timezones)) {
				return null;
			}
			foreach( $this->timezones AS $tz ) {
				if( $tz->getTimeZoneId() == $tzid ) {
					return $tz;
				}
			}
			return null;
		}
	}

	/**
	 * Adds a new timezone to this calendar
	 * @param SG_iCal_VTimeZone $tz
	 */
	public function addTimeZone( VTimeZone $tz ) {
		$this->timezones[] = $tz;
	}

	/**
	 * Returns the events found
	 * @return array
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * Adds a event to this calendar
	 * @param SG_iCal_VEvent $event
	 */
	public function addEvent( VEvent $event ) {
		$this->events[] = $event;
	}
}

/**
 * For legacy reasons, we keep the name iCalReader..
 */
class iCalReader extends iCal {}
