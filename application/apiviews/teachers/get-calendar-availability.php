<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');

/* rounding off the minutes to 00, 15, 30, 45 */
$minutes = date('i', ceil(time() / (15 * 60)) * (15 * 60));
$currentDateTime = strtotime(MyDate::formatDate(date('Y-m-d H:' . $minutes . ':00'), 'Y-m-d H:i:s', $timezone));

/* get slots starting date according to book before setting */
$bookingBeforeDate = strtotime('+ ' . $bookingBefore . ' Hours', $currentDateTime);

/* default slots difference */
$slotsDuration = 15;

/* converting scheduled dates to strtotime to avoid multiple time conversion in loops */
foreach ($scheduledData as $key => $scheduled) {
    $scheduledData[$key]['start'] = strtotime($scheduled['start']);
    $scheduledData[$key]['end'] = strtotime($scheduled['end']);
}
$slots = [];
foreach ($userAvailability as $avail) {
    $startDateTime = $slotStartDate = strtotime($avail['start']);
    $endDateTime = strtotime($avail['end']);
    if ($bookingBeforeDate  < $endDateTime) {
        if ($bookingBeforeDate > $startDateTime) {
            $startDateTime = $bookingBeforeDate;
        }
        while ($startDateTime <= $endDateTime) {
            $end = strtotime('+ ' . $duration . ' minutes', $startDateTime);

            /* check if slot is already booked */
            $isScheduled = false;
            foreach ($scheduledData as $scheduled) {
                if (($startDateTime >= $scheduled['start'] && $startDateTime < $scheduled['end']) || ($end > $scheduled['start'] && $end <= $scheduled['end'])) {
                    $isScheduled = true;
                    break;
                }
            }
            /* skip if already booked */
            if ($isScheduled) {
                /* set next slot start date */
                $startDateTime = strtotime('+ ' . $slotsDuration . ' minutes', $startDateTime);
                continue;
            }

            /* skip if slot end time is greater than the availbility end time. For eg, slot is of 30 mins but left 15 mins till end time */
            if ($end > $endDateTime) {
                /* set next slot start date */
                $startDateTime = strtotime('+ ' . $slotsDuration . ' minutes', $startDateTime);
                break;
            }

            /* skip if the slots are of next day */
            if (($startDateTime < strtotime($startTime) || $startDateTime > strtotime($endTime)) || $end < strtotime($startTime) || $end > strtotime('+ 1 minutes', strtotime($endTime))) {
                /* set next slot start date */
                $startDateTime = strtotime('+ ' . $slotsDuration . ' minutes', $startDateTime);
                continue;
            }

            /* add to slots array */
            $slots[] = [
                'start' => date('Y-m-d H:i:s', $startDateTime),
                'end' => date('Y-m-d H:i:s', $end),
            ];
            /* set next slot start date */
            $startDateTime = strtotime('+ ' . $slotsDuration . ' minutes', $startDateTime);
        }
    }
}

$availability = [];
foreach ($slots as $k => $slot) {
    $key = date('Y-m-d', strtotime($slot['start']));
    $availability[$key]['date'] = $key;
    $availability[$key]['slots'][] =  $slot;
}
if (empty($availability)) {
    $availability[] = [
        'date' => MyDate::formatDate($startTime, 'Y-m-d', $timezone),
        'slots' => []
    ];
}
$data['calendarDays'] = $calendarDays;
$data['availability'] = array_values($availability);
$data['msg'] = Label::getLabel('API_TEACHER_AVAILABILITY');
MyUtility::dieJsonSuccess($data);
