<?php
echo shortNumber("440018");
function shortNumber($number) {
    if ($number >= 1000000000) {
        return round($number / 1000000000, 2) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 2) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 2) . 'K';
    }
    return $number;
}
?>