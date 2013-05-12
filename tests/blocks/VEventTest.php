<?php
require_once dirname(__FILE__).'/../common.php';
require_once 'PHPUnit/Framework/TestCase.php';

class VEventTest extends PHPUnit_Framework_TestCase {
	public function testParsingOfStartTimeWithTzidSet() {
		$ical = new sg\ical\iCal();
		$timezone['tzid'] = 'America/New_York';
		$timezone['daylight'] = array(
			'tzoffsetfrom' => '-0500',
			'tzoffsetto' => '-0400',
			'tzname' => 'EDT',
			'dtstart' => '19700308T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=3;BYDAY=2SU',
		);
		$timezone['standard'] = array(
			'tzoffsetfrom' => '-0400',
			'tzoffsetto' => '-0500',
			'tzname' => 'EST',
			'dtstart' => '19701101T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=11;BYDAY=1SU',
		);
		$timezone2['tzid'] = 'Europe/Copenhagen';
		$timezone2['daylight'] = array(
			'tzoffsetfrom' => '+0100',
			'tzoffsetto' => '+0200',
			'tzname' => 'CEST',
			'dtstart' => '19700329T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU',
		);
		$timezone2['standard'] = array(
			'tzoffsetfrom' => '+0200',
			'tzoffsetto' => '+0100',
			'tzname' => 'CET',
			'dtstart' => '19701025T030000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU',
		);
		$ical->addTimeZone(new sg\ical\VTimeZone($timezone));
		$ical->addTimeZone(new sg\ical\VTimeZone($timezone2));
		$data['uid'] = new sg\ical\Line('uid');
		$data['dtstart'] = new sg\ical\Line('DTSTART;TZID=Europe/Copenhagen:20091023T2100');

		date_default_timezone_set('America/New_York');
		
		$event = new sg\ical\VEvent($data, $ical);
		$this->assertEquals(strtotime('20091023T1500'), $event->getStart());
	}

	public function testParsingOfStartTimeAndEndTimeOverDaylightChange() {
		$timezone2['tzid'] = 'Europe/Copenhagen';
		$timezone2['daylight'] = array(
			'tzoffsetfrom' => '+0100',
			'tzoffsetto' => '+0200',
			'tzname' => 'CEST',
			'dtstart' => '19700329T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU',
		);
		$timezone2['standard'] = array(
			'tzoffsetfrom' => '+0200',
			'tzoffsetto' => '+0100',
			'tzname' => 'CET',
			'dtstart' => '19701025T030000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU',
		);

		$ical = new sg\ical\iCal();
		$ical->addTimeZone(new sg\ical\VTimeZone($timezone2));
		$data['uid'] = new sg\ical\Line('uid');
		$data['dtstart'] = new sg\ical\Line('DTSTART;TZID=Europe/Copenhagen:20091023T2100');
		$data['dtend'] = new sg\ical\Line('DTEND;TZID=Europe/Copenhagen:20091030T140000');

		date_default_timezone_set('America/New_York');
		$event = new sg\ical\VEvent($data, $ical);
		$this->assertEquals(strtotime('20091023T150000'), $event->getStart());
		$this->assertEquals(strtotime('20091030T090000'), $event->getEnd());
	}

	public function testParsingOfEndTimeWithTzidSetAndUntilSetUnderRRULE() {
		$ical = new sg\ical\iCal();
		$timezone['tzid'] = 'America/New_York';
		$timezone['daylight'] = array(
			'tzoffsetfrom' => '-0500',
			'tzoffsetto' => '-0400',
			'tzname' => 'EDT',
			'dtstart' => '19700308T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=3;BYDAY=2SU',
		);
		$timezone['standard'] = array(
			'tzoffsetfrom' => '-0400',
			'tzoffsetto' => '-0500',
			'tzname' => 'EST',
			'dtstart' => '19701101T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=11;BYDAY=1SU',
		);
		$timezone2['tzid'] = 'Europe/Copenhagen';
		$timezone2['daylight'] = array(
			'tzoffsetfrom' => '+0100',
			'tzoffsetto' => '+0200',
			'tzname' => 'CEST',
			'dtstart' => '19700329T020000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU',
		);
		$timezone2['standard'] = array(
			'tzoffsetfrom' => '+0200',
			'tzoffsetto' => '+0100',
			'tzname' => 'CET',
			'dtstart' => '19701025T030000',
			'rrule' => 'FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU',
		);
		$ical->addTimeZone(new sg\ical\VTimeZone($timezone));
		$ical->addtimeZone(new sg\ical\VTimeZone($timezone2));
		$data['uid'] = new sg\ical\Line('uid');
		//this takes place after Copenhagens change back to standard time
		$data['dtstart'] = new sg\ical\Line('DTSTART;TZID=Europe/Copenhagen:20091027T100000');
		$data['rrule'] = new sg\ical\Line('RRULE:FREQ=DAILY;UNTIL=20091030T130000Z');

		date_default_timezone_set('America/New_York');
		$event = new sg\ical\VEvent($data, $ical);
		$this->assertEquals(strtotime('20091030T090000'), $event->getProperty('laststart'));
	}
}
