<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer ;
    public function __construct(FrenchToDateTimeTransformer $transformer)  {
            $this->transformer = $transformer ;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getSetting("Start Date", "
            the date you expect to arrive"))
            ->add('endDate', TextType::class, $this->getSetting("End Date", "
            the date you leave"))
            
            ->add('comment', TextareaType::class, $this->getSetting(false, "If you have a comment, feel free to share it !", ["required" => false]))
         
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer)  ;
        $builder->get('endDate')->addModelTransformer($this->transformer)  ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                'Defualt', 
                'front'
            ]
        ]);
    }
}
