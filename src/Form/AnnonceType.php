<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{


  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getSetting("Title", "Write Title Ad"))
            ->add('slug', TextType::class, $this->getSetting("String URL", "Web Address Automatic", [
                'required' => false
            ]))
            ->add('coverImage', UrlType::class, $this->getSetting("URL FOR PICTURE", "PUT path of picture"))
            ->add('introduction', TextType::class, $this->getSetting("Introduction", "Write a global description for your Ad"))
            ->add('content', TextareaType::class, $this->getSetting("Details Description","Write a description"))
            ->add('rooms', IntegerType::class, $this->getSetting("Number of Rooms", "Number of Rooms .."))
            ->add('price', MoneyType::class, $this->getSetting("Price per Night","Write price of Night"))

            ->add(
                'images', 
                 CollectionType::class,
                 [
                     'entry_type'=> ImageType::class ,
                     'allow_add' => true, 
                     'allow_delete' => true
                 ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
