<?php

namespace CkAmaury\Symfony\Utils;

class ConverterUtils{

    static function convertKilometersToNauticalMiles(float $kilometers,int $round = 2){
        return round($kilometers / 1.852,$round);
    }
    static function convertNauticalMilesToKilometers(float $nautical_miles,int $round = 2){
        return round($nautical_miles * 1.852,$round);
    }

    static function convertKilometersToMiles(float $kilometers,int $round = 2){
        return round($kilometers / 1.609344,$round);
    }
    static function convertMilesToKilometers(float $miles,int $round = 2){
        return round($miles * 1.609344,$round);
    }

    static function convertNauticalMilesToMiles(float $nautical_miles,int $round = 2){
        return round($nautical_miles / 0.868976242,$round);
    }
    static function convertMilesToNauticalMiles(float $miles,int $round = 2){
        return round($miles * 0.868976242,$round);
    }

    static function convertMetersToFeet(float $meters,int $round = 2){
        return round($meters * 3.2808,$round);
    }
    static function convertFeetToMeters(float $feet,int $round = 2){
        return round($feet / 3.2808,$round);
    }

    static function convertKilogramsToPounds(float $kilograms,int $round = 2){
        return round($kilograms / 0.45359237,$round);
    }
    static function convertPoundsToKilograms(float $pounds,int $round = 2){
        return round($pounds * 0.45359237,$round);
    }

    static function convertMachInknots(float $mach,int $round = 2){
        return round($mach * 600,$round);
    }
}