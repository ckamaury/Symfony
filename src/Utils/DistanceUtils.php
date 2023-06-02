<?php

namespace CkAmaury\Symfony\Utils;

class DistanceUtils{

    static function orthodromicInMiles(float $lat1,float $lon1,float $lat2,float $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            return $dist * 60 * 1.1515;
        }
    }

    static function orthodromicInKilometers(float $lat1,float $lon1,float $lat2,float $lon2) {
        return ConverterUtils::convertMilesToKilometers(
            self::orthodromicInMiles($lat1,$lon1,$lat2,$lon2));
    }
    static function orthodromicInNauticalMiles(float $lat1,float $lon1,float $lat2,float $lon2) {
        return ConverterUtils::convertMilesToNauticalMiles(
            self::orthodromicInMiles($lat1,$lon1,$lat2,$lon2));
    }

}