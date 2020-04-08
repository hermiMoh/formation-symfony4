<?php

namespace App\Form\DataTransformer  ;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface  {
        
    public function transform($date)  {

        if ($date === null)  {
            return '' ;
        }
            return $date->format('d/m/Y');

    }

    public function reverseTransform($frenchDate)  {
        // French Date 07/04/2020
        if ($frenchDate === null)  {
            // Exception
            throw new TransformationFailedException ("You Must choose a date !!!") ;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate) ;

        if ($date === false)  {
            //Exception
            throw new TransformationFailedException ("Wrong Date Format  !!!") ;
        }

        return $date ;
    }


}
?>