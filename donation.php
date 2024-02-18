<?php
class Donation {
    public $id;
    public $donorName;
    public $amount;
    public $charityId;
    public $date;
    public $time;

    public function __construct($id, $donorName, $amount, $charityId, $date, $time) {
        $this->id = $id;
        $this->donorName = $donorName;
        $this->amount = $amount;
        $this->charityId = $charityId;
        $this->date = $date;
        $this->time = $time;
    }

    private static $donations = [];

    public static function initialize($donationsData) {
        foreach ($donationsData as $donationData) {
            $donation = new self(
                $donationData['id'],
                $donationData['donorName'],
                $donationData['amount'],
                $donationData['charityId'],
                $donationData['date'],
                $donationData['time']
            );
            self::$donations[] = $donation;
        }
    }

    public static function getAllDonations() {
        return self::$donations;
    }

    public static function addDonation($donation) {
        self::$donations[] = $donation;
    }

    public static function viewDonations() {
        foreach (self::$donations as $donation) {
            echo "ID: {$donation->id}, Donor Name: {$donation->donorName}, Amount: {$donation->amount}, Charity ID: {$donation->charityId}, Date: {$donation->date}, Time: {$donation->time}\n";
        }
    }
}
?>