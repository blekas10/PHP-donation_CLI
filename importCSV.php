<?php 
function checkCSVStructure($filePath) {
    $fileHandle = fopen($filePath, 'r');
    if ($fileHandle === false) {
        echo "Failed to open the file: $filePath\n";
        return false;
    }

    $errors = [];
    $headers = fgetcsv($fileHandle);
    if ($headers === false || $headers[0] !== 'charities_id' || $headers[1] !== 'charities_name' || $headers[2] !== 'charities_representativeEmail') {
        $errors[] = "CSV file does not have the required headers (charities_id, charities_name, charities_representativeEmail).";
    }

    $lineNumber = 1;

    while (($row = fgetcsv($fileHandle)) !== false) {
        $lineNumber++;
        if (count($row) < 3) {
            $errors[] = "Error on line $lineNumber: Each row must have at least 3 columns.";
        }
    }

    fclose($fileHandle);

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo $error . "\n";
        }
        return false;
    } else {
        echo "CSV structure is correct.\n";
        return true;
    }
}

function importCharitiesFromCSV($filePath) {
    $fileHandle = fopen($filePath, 'r');
    if ($fileHandle === false) {
        echo "Failed to open the file: $filePath\n";
        return;
    }

    $existingCharities = Charity::getAllCharities();
    $existingCharityIds = array_column($existingCharities, 'id');

    fgetcsv($fileHandle);
    $lineNumber = 1;
    $importCount = 0;

    while (($row = fgetcsv($fileHandle)) !== false) {
        $lineNumber++;

        if (count($row) < 3) {
            echo "Error on line $lineNumber: Not enough data.\n";
            continue;
        }

        [$id, $name, $email] = array_map('trim', $row);

        $idValidation = validateIdUniqueness($id, $existingCharityIds);
        if ($idValidation !== true) {
            echo "Error on line $lineNumber: {$idValidation}\n";
            continue;
        }

        $nameValidation = validateName($name);
        if ($nameValidation !== true) {
            echo "Error on line $lineNumber: {$nameValidation}\n";
            continue;
        }

        $emailValidation = validateEmail($email);
        if ($emailValidation !== true) {
            echo "Error on line $lineNumber: {$emailValidation}\n";
            continue;
        }

        $charity = new Charity($id, $name, $email);
        Charity::addCharity($charity);
        $importCount++;
    }

    fclose($fileHandle);

    if ($importCount > 0) {
        echo "$importCount charities were successfully imported.\n";
    } else {
        echo "No charities were imported.\n";
    }

    saveDataToFile(Charity::getAllCharities(), Donation::getAllDonations());
}
?>