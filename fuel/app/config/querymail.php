<?php

$sStartDateWtd0 = \Service\Date::mySQLDateFromString('-7 day');
$sEndDateWtd0 = \Service\Date::mySQLDateFromString('-1 day');
$sStartDateWtd1 = \Service\Date::mySQLDateFromString('-7 day', \Service\Date::strtotime($sStartDateWtd0));
$sEndDateWtd1 = \Service\Date::mySQLDateFromString('-7 day', \Service\Date::strtotime($sEndDateWtd0));
// MTD dates
if (\Service\Date::phpdate('j') == 1) {
    $sStartDateMtd0 = \Service\Date::mySQLDateFromString('first day of last month');
    $sEndDateMtd0 = \Service\Date::mySQLDateFromString('last day of last month');
} else {
    $sStartDateMtd0 = \Service\Date::mySQLDateFromString('first day of this month');
    $sEndDateMtd0 = \Service\Date::mySQLDateFromString('-1 day');
}
$sStartDateMtd1 = \Service\Date::mySQLDateFromString('first day of last month', \Service\Date::strtotime($sStartDateMtd0));
$sEndDateMtd1 = \Service\Date::mySQLDateFromString('-1 month', \Service\Date::strtotime($sEndDateMtd0));
if (\Service\Date::phpdate('n', \Service\Date::strtotime($sEndDateMtd1)) == \Service\Date::phpdate('n', \Service\Date::strtotime($sEndDateMtd0))) {
    $sEndDateMtd1 = \Service\Date::mySQLDateFromString('last day of last month', \Service\Date::strtotime($sEndDateMtd0));
}

# billing dates
$iCurrentDay = date('j');
if ($iCurrentDay < 16) {
    $billingStart = \Service\Date::strtotime('first day of this month 00:00:00');
    $billingEnd = \Service\Date::strtotime('first day of this month 23:59:59');
    $billingEnd = \Service\Date::strtotime('+14 day', $billingEnd);
} else {
    $billingStart = \Service\Date::strtotime('first day of this month 00:00:00');
    $billingStart = \Service\Date::strtotime('+15 day', $billingStart);
    $billingEnd = \Service\Date::strtotime('last day of this month 23:59:59');
}
$sManualBillingStart = \Service\Date::strtotime('first day of this month 00:00:00');
$sManualBillingEnd = \Service\Date::strtotime('last day of this month 23:59:59');

$aParams = array(
    'mtd_start_0' => $sStartDateMtd0,
    'mtd_end_0' => $sEndDateMtd0,
    'wtd_start_0' => $sStartDateWtd0,
    'wtd_end_0' => $sEndDateWtd0,
    'mtd_start_1' => $sStartDateMtd1,
    'mtd_end_1' => $sEndDateMtd1,
    'wtd_start_1' => $sStartDateWtd1,
    'wtd_end_1' => $sEndDateWtd1,
    'bimonthly_start' => $billingStart,
    'bimonthly_end' => $billingEnd,
    'monthly_start' => $sManualBillingStart,
    'monthly_end' => $sManualBillingEnd,
);
for ($i = 0; $i < 10; $i++) {
    $aParams['date_' . $i] = date('Y-m-d', strtotime("-$i day"));
}

return array(
    'queryvar' => $aParams,
	'colors' => [
		'#3C87CE',
		'#EBD938',
		'#33B46C',
		'#9A4A94',
		'#C38132',
		'#E82933',
		'#42989A',
		'#8FD68F'
	]
);
