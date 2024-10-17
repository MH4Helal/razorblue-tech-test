<?php

function readCsvRemoveDuplicates($filename) {
    $rows = array_map('str_getcsv', file($filename));
    $header = array_shift($rows); 

    $uniqueData = [];
    foreach ($rows as $row) {
        $rowAssoc = array_combine($header, $row);
        $carReg = $rowAssoc['Car Registration'];
        $uniqueData[$carReg] = $rowAssoc;
    }

    return $uniqueData;
}

function exportCsvByFuelType($data, $outputDir) {
    $fuelTypes = [];

    foreach ($data as $row) {
        $fuel = $row['Fuel'];
        $fuelTypes[$fuel][] = $row;
    }

    // Export CSV
    foreach ($fuelTypes as $fuelType => $rows) {
        $filename = $outputDir . strtolower($fuelType) . "_vehicles.csv";
        $fp = fopen($filename, 'w');
        
        fputcsv($fp, array_keys($rows[0]));
        
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
    }
}

function getValidRegistrations($data) {
    $validPattern = '/^[A-Z]{2}[0-9]{2} [A-Z]{3}$/';
    $validVehicles = [];
    
    foreach ($data as $row) {
        if (preg_match($validPattern, $row['Car Registration'])) {
            $validVehicles[] = $row;
        }
    }

    return $validVehicles;
}

function countInvalidRegistrations($data) {
    $validPattern = '/^[A-Z]{2}[0-9]{2} [A-Z]{3}$/';
    $invalidCount = 0;

    foreach ($data as $row) {
        if (!preg_match($validPattern, $row['Car Registration'])) {
            $invalidCount++;
        }
    }

    return $invalidCount;
}

// Usage

$csvFile = './technical-test-data.csv';
$outputDir = './';

$data = readCsvRemoveDuplicates($csvFile);

exportCsvByFuelType($data, $outputDir);

$validVehicles = getValidRegistrations($data);

$invalidCount = countInvalidRegistrations($data);

echo "Number of vehicles with invalid registration: " . $invalidCount . "\n";
