#!/bin/sh

# Doc using apigen

mkdir -p docs
apigen -d docs/ -s intouch/ --title "intouch-iCalendar" --php no

