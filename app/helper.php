<?php 
if (!function_exists('formatInr')) {
    function formatInr($amount)
    {
        // Round based on standard rounding rules (≥ .50 goes up, < .50 goes down)
        $rounded = round($amount); // No decimal places after rounding

        // Format in Indian style
        $num = (string)$rounded;
        $lastThree = substr($num, -3);
        $restUnits = substr($num, 0, -3);

        if ($restUnits != '') {
            $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
            $formatted = $restUnits . ',' . $lastThree;
        } else {
            $formatted = $lastThree;
        }

        // Append .00 for standard rupee display
        return $formatted . '.00';
    }
}

?>