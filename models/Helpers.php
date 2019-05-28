<?php

namespace app\models;

class Helpers
{
    /**
     * Return array of Boolean values
     *
     * @return array
     */
    public static function getBooleans()
    {
        return [
            1 => 'Yes',
            0 => 'No',
        ];
    }

    /**
     * Return array of Gender values
     *
     * @return array
     */
    public static function getGenders()
    {
        return [
            'M' => 'Male',
            'F' => 'Female',
        ];
    }

    /**
     * Return array of Height values
     *
     * @return array
     */
    public function getHeights()
    {
        return [
            '4.10' => "4ft 10in",
            '4.11' => "4ft 11in",
            '5.00' => "5ft 0in",
            '5.01' => "5ft 1in",
            '5.02' => "5ft 2in",
            '5.03' => "5ft 3in",
            '5.04' => "5ft 4in",
            '5.05' => "5ft 5in",
            '5.06' => "5ft 6in",
            '5.07' => "5ft 7in",
            '5.08' => "5ft 8in",
            '5.09' => "5ft 9in",
            '5.10' => "5ft 10in",
            '5.11' => "5ft 11in",
            '6.00' => "6ft 0in",
            '6.01' => "6ft 1in",
            '6.02' => "6ft 2in",
            '6.03' => "6ft 3in",
            '6.04' => "6ft 4in",
            '6.05' => "6ft 5in",
        ];
    }

    /**
     * Return array of Age values
     *
     * @return array
     */
    public function getAges()
    {
        return [
            '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29',
            '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41',
            '42', '43', '44', '45', '50', '55', '60', '65', '70', '75',
        ];
    }
}
