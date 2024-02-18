<?php
class Charity {
    public $id;
    public $name;
    public $representativeEmail;

    public function __construct($id, $name, $representativeEmail) {
        $this->id = $id;
        $this->name = $name;
        $this->representativeEmail = $representativeEmail;
    }

    private static $charities = [];

    public static function initialize($charitiesData) {
        self::$charities = [];
        foreach ($charitiesData as $charityData) {
            $charity = new self(
                $charityData['id'],
                $charityData['name'],
                $charityData['representativeEmail']
            );
            self::$charities[] = $charity;
        }
    }

    public static function getAllCharities() {
        return self::$charities;
    }

    public static function addCharity($charity) {
        self::$charities[] = $charity;
    }

    public static function viewCharities() {
        foreach (self::$charities as $charity) {
            echo "ID: {$charity->id}, Name: {$charity->name}, Representative Email: {$charity->representativeEmail}\n";
        }
    }

    public static function editCharity($id, $newName, $newEmail) {
        foreach (self::$charities as $key => $charity) {
            if ($charity->id === $id) {
                self::$charities[$key]->name = $newName;
                self::$charities[$key]->representativeEmail = $newEmail;
                return;
            }
        }
        echo "Charity with ID $id not found.\n";
    }

    public static function deleteCharity($id) {
        foreach (self::$charities as $key => $charity) {
            if ($charity->id === $id) {
                unset(self::$charities[$key]);
                return;
            }
        }
        echo "Charity with ID $id not found.\n";
    }
    public static function idExists($id) {
        foreach (self::$charities as $charity) {
            if ($charity->id === $id) {
                return true;
            }
        }
        return false;
    }
}
?>