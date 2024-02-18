<?php 

function validateId($id, $entities) {
    if (!is_numeric($id)) {
        return "ID must be numeric.";
    }

    foreach ($entities as $entity) {
        if ($entity->id == $id) {
            return "ID already exists.";
        }
    }

    return true;
}

function validateDonationId($donationId, $donations) {
    if (!is_numeric($donationId)) {
        return "Donation ID must be numeric.";
    }

    foreach ($donations as $donation) {
        if ($donation->id == $donationId) {
            return "Donation ID already exists.";
        }
    }

    return true;
}

function validateIdExists($id, $entities) {
    foreach ($entities as $entity) {
        if ($entity->id == $id) {
            return true;
        }
    }
    return "Charity ID does not exist.";
}

function validateName($name) {
    $name = trim($name);
    if ($name === '') {
        return "Name cannot be empty.";
    }

    if (!preg_match("/^[\p{L} '-]+$/u", $name)) {
        return "Name must contain only letters, spaces, hyphens, and apostrophes.";
    }

    return true;
}

function validateDonorName($name) {
    $name = trim($name);
    if ($name === '') {
        return "Donor name cannot be empty.";
    }

    if (!preg_match("/^[\p{L} '-]+$/u", $name)) {
        return "Donor name must contain only letters, spaces, hyphens, and apostrophes.";
    }

    return true;
}

function validateAmount($amount) {
    if (!is_numeric($amount) || $amount <= 0) {
        return "Amount must be a positive number.";
    }
    return true;
}

function validateCharityId($charityId, $charities) {
    foreach ($charities as $charity) {
        if ($charity->id == $charityId) {
            return true;
        }
    }
    return "Charity ID does not exist.";
}

function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return true;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if ($d && $d->format('Y-m-d') === $date) {
        return true;
    } else {
        return "Date must be in the format YYYY-MM-DD.";
    }
}

function validateTime($time) {
    $t = DateTime::createFromFormat('H:i', $time);
    if ($t && $t->format('H:i') === $time) {
        return true;
    } else {
        return "Time must be in the format HH:MM.";
    }
}

function validateIdUniqueness($id, $existingIds) {
    if (!ctype_digit($id)) {
        return "ID must be numeric.";
    }

    if (in_array($id, $existingIds, true)) {
        return "ID already exists.";
    }

    return true;
}

?>