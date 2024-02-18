<?php

function loadDataFromFile() {
    $filePath = 'data.json';
    if (!file_exists($filePath)) {
        return ['charities' => [], 'donations' => []];
    }
    $json_data = file_get_contents($filePath);
    return json_decode($json_data, true);
}

function saveDataToFile($charities, $donations) {
    $data = ['charities' => $charities, 'donations' => $donations];
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('data.json', $json_data);
}


require_once 'charity.php';
require_once 'donation.php';
require_once 'validation.php';
require_once 'importCSV.php';

$data = loadDataFromFile();
Charity::initialize($data['charities']); // Assuming you have an initialize method
Donation::initialize($data['donations']); // Assuming you have an initialize method

function displayMenu() {
    echo "\n";
    echo "1. View Charities\n";
    echo "2. Add Charity\n";
    echo "3. Edit Charity\n";
    echo "4. Delete Charity\n";
    echo "5. Add Donation\n";
    echo "6. View Donations\n";
    echo "7. Import Charities from CSV\n";
    echo "8. Exit\n";
    echo "Choose an option: ";
}

while (true) {
    displayMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case '1':
            $allCharities = Charity::getAllCharities();
            if (empty($allCharities)) {
                echo "There are no charities to display.\n";
            } else {
                Charity::viewCharities();
            }
            break;

        case '2':
            $id = '';
            while (true) {
                if ($id === '') {
                    echo "Enter Charity ID (or type 'back' to return to the menu): ";
                    $id = trim(fgets(STDIN));

                    if (strtolower($id) === 'back') {
                        break;
                    }

                    $validationResult = validateId($id, Charity::getAllCharities());
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $id = '';
                        continue;
                    }
                }

                $name = '';
                while ($name === '') {
                    echo "Enter Charity Name: ";
                    $name = trim(fgets(STDIN));
                    $validationResult = validateName($name);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $name = '';
                        continue;
                    }
                }

                $email = '';
                while ($email === '') {
                    echo "Enter Representative Email: ";
                    $email = trim(fgets(STDIN));
                    $validationResult = validateEmail($email);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $email = '';
                        continue;
                    }
                }

                $charity = new Charity($id, $name, $email);
                Charity::addCharity($charity);
                echo "Charity added successfully!\n";
                saveDataToFile(Charity::getAllCharities(), Donation::getAllDonations());
                break;
            }
            break;

        case '3':
            while (true) {
                echo "Enter Charity ID to edit (or type 'back' to return to the menu): ";
                $id = trim(fgets(STDIN));
        
                if (strtolower($id) === 'back') {
                    break;
                }
        
                $idValidationResult = validateIdExists($id, Charity::getAllCharities());
                if ($idValidationResult !== true) {
                    echo $idValidationResult . "\n";
                    continue;
                }
    
                $name = '';
                while ($name === '') {
                    echo "Enter new Charity Name: ";
                    $name = trim(fgets(STDIN));
                    $nameValidationResult = validateName($name);
                    if ($nameValidationResult !== true) {
                        echo $nameValidationResult . "\n";
                        $name = ''; 
                        continue; 
                    }
                }
        
                $email = '';
                while ($email === '') {
                    echo "Enter new Representative Email: ";
                    $email = trim(fgets(STDIN));
                    $emailValidationResult = validateEmail($email);
                    if ($emailValidationResult !== true) {
                        echo $emailValidationResult . "\n";
                        $email = '';
                        continue;
                    }
                }
        
                Charity::editCharity($id, $name, $email);
                echo "Charity edited successfully!\n";
                saveDataToFile(Charity::getAllCharities(), Donation::getAllDonations());
                break;
            }
            break;
            
        case '4':
            while (true) {
                echo "Enter Charity ID to delete (or type 'back' to return to the menu): ";
                $id = trim(fgets(STDIN));
        
                if (strtolower($id) === 'back') {
                    break;
                }
        
                $idValidation = validateIdExists($id, Charity::getAllCharities());
                if ($idValidation !== true) {
                    echo $idValidation . "\n";
                    continue;
                }
        
                echo "Are you sure you want to delete this charity? Type 'yes' to confirm: ";
                $confirmation = trim(fgets(STDIN));
                if (strtolower($confirmation) === 'yes') {
                    Charity::deleteCharity($id);
                    echo "Charity deleted successfully!\n";
                    saveDataToFile(Charity::getAllCharities(), Donation::getAllDonations());
                } else {
                    echo "Deletion cancelled.\n";
                }
        
                break;
            }
            break;

        case '5':
            $donationId = '';
            while (true) {
                if ($donationId === '') {
                    echo "Enter Donation ID (or type 'back' to return to the menu): ";
                    $donationId = trim(fgets(STDIN));
        
                    if (strtolower($donationId) === 'back') {
                        break;
                    }
                    $validationResult = validateDonationId($donationId, Donation::getAllDonations());
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $donationId = '';
                        continue;
                    }
                }
        
                $donorName = '';
                while ($donorName === '') {
                    echo "Enter Donor Name: ";
                    $donorName = trim(fgets(STDIN));
                    $validationResult = validateDonorName($donorName);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $donorName = '';
                        continue;
                    }
                }
        
                $amount = '';
                while ($amount === '') {
                    echo "Enter Donation Amount: ";
                    $amount = trim(fgets(STDIN));
                    $validationResult = validateAmount($amount);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $amount = '';
                        continue;
                    }
                }
        
                $charityId = '';
                while ($charityId === '') {
                    echo "Enter Charity ID for Donation: ";
                    $charityId = trim(fgets(STDIN));
                    $validationResult = validateCharityId($charityId, Charity::getAllCharities());
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $charityId = '';
                        continue;
                    }
                }
        
                $date = '';
                while ($date === '') {
                    echo "Enter Date (YYYY-MM-DD): ";
                    $date = trim(fgets(STDIN));
                    $validationResult = validateDate($date);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $date = '';
                        continue;
                    }
                }
        
                $time = '';
                while ($time === '') {
                    echo "Enter Time (HH:MM): ";
                    $time = trim(fgets(STDIN));
                    $validationResult = validateTime($time);
                    if ($validationResult !== true) {
                        echo $validationResult . "\n";
                        $time = '';
                        continue;
                    }
                }
        
                $donation = new Donation($donationId, $donorName, $amount, $charityId, $date, $time);
                Donation::addDonation($donation);
                echo "Donation added successfully!\n";
                saveDataToFile(Charity::getAllCharities(), Donation::getAllDonations());
                break; 
            }
            break;

        case '6':
            $allDonations = Donation::getAllDonations();
            if (empty($allDonations)) {
                echo "There are no donations to display.\n";
            } else {
                Donation::viewDonations();
            }
            break;

        case '7':
            echo "Enter the path to the CSV file (or type 'back' to return to the menu): ";
            $filePath = trim(fgets(STDIN));

            if (strtolower($filePath) === 'back') {
                break;
            }

            if (checkCSVStructure($filePath)) {
                importCharitiesFromCSV($filePath);
            } else {
                echo "CSV structure check failed. Import aborted.\n";
            }
            break;

        case '8':
            echo "Exiting program.\n";
            exit();

        default:
            echo "Invalid option. Please try again.\n";
    }
}
?>