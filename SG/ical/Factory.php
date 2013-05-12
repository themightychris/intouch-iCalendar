<?php // BUILD: Remove line

namespace sg\ical;

/**
 * A simple Factory for converting a section/data pair into the
 * corrosponding block-object. If the section isn't known a simple
 * ArrayObject is used instead.
 *
 * @package sg\ical
 * @author Morten Fangel (C) 2008
 * @author Michael Kahn 2013
 * @license http://creativecommons.org/licenses/by-sa/2.5/dk/deed.en_GB CC-BY-SA-DK
 */
class Factory {
	/**
	 * Returns a new block-object for the section/data-pair. The list
	 * of returned objects is:
	 *
	 * vcalendar => SG_iCal_VCalendar
	 * vtimezone => SG_iCal_VTimeZone
	 * vevent => SG_iCal_VEvent
	 * * => ArrayObject
	 *
	 * @param $ical sg\ical\iCal The reader this section/data-pair belongs to
	 * @param $section string
	 * @param sg\ical\Line[]
	 */
	public static function factory( iCal $ical, $section, $data ) {
		switch( $section ) {
			case "vcalendar":
				return new SG_iCal_VCalendar(SG_iCal_Line::Remove_Line($data), $ical );
			case "vtimezone":
				return new SG_iCal_VTimeZone(SG_iCal_Line::Remove_Line($data), $ical );
			case "vevent":
				return new SG_iCal_VEvent($data, $ical );

			default:
				return new ArrayObject(Line::Remove_Line((array) $data) );
		}
	}
}
