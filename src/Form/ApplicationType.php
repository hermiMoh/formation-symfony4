<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
  
    class ApplicationType extends AbstractType  {


         /**
     *Allow to get base setting of fields inputs..
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getSetting($label,$placeholder, $options = []) {
        return array_merge_recursive( [
            'label' => $label, 
            'attr' => [
                'placeholder' => $placeholder
            ]
        ] , $options );
     }
    }



?>