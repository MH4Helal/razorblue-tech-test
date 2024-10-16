<?php

function ipInRange($ip, $range) {
    // Check if it's a single IP
    if (filter_var($range, FILTER_VALIDATE_IP)) {
        return $ip === $range;
    }

    // Check if it's an IP range
    if (strpos($range, '-') !== false) {
        list($start, $end) = explode('-', $range);
        return ip2long($ip) >= ip2long(trim($start)) && ip2long($ip) <= ip2long(trim($end));
    }

    // Check if it's a CIDR range
    if (strpos($range, '/') !== false) {
        list($base, $prefix) = explode('/', $range);
        $baseLong = ip2long(trim($base));
        $prefix = (int)$prefix;
        $mask = -1 << (32 - $prefix);
        $network = $baseLong & $mask;

        return (ip2long($ip) & $mask) === $network;
    }

    return false;
}

function isIpInRanges($ip, $ranges) {
    foreach ($ranges as $range) {
        if (ipInRange($ip, $range)) {
            return true;
        }
    }
    return false;
}



// Example of how you might retrieve the ranges from a database
/*
$ipToCheck = ADD NEEDED IP HERE;

function getIpRangesFromDatabase() {
    // Assuming you have a PDO connection $pdo
    $stmt = $pdo->query("SELECT ip_range FROM ip_ranges");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$storedIpRanges = getIpRangesFromDatabase();

if (isIpInRanges($ipToCheck, $storedIpRanges)) {
    echo "$ipToCheck is within the allowed ranges.";
} else {
    echo "$ipToCheck is NOT within the allowed ranges.";
}
*/