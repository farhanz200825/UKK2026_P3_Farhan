<?php

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd/m/Y H:i')
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '-';
        }
        
        try {
            if ($date instanceof \Carbon\Carbon) {
                return $date->format($format);
            }
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}