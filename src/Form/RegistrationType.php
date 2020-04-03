<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getSetting("FirstName", "your first name .."))
            ->add('lastName', TextType::class, $this->getSetting("LastName", "your last name .."))
            ->add('email', EmailType::class, $this->getSetting("Email", "Write your email"))
            ->add('picture', UrlType::class, $this->getSetting("Profile Picture", "Url of picture"))
            ->add('hash', PasswordType::class, $this->getSetting("Password ", "Write a good Password"))
            ->add('passwordConfirm', PasswordType::class, $this->getSetting("confirm Password ", "Confirm your Password Plz"))
            ->add('introduction', TextType::class, $this->getSetting("Introduction", "Introduce yourself.."))
            ->add('description', TextareaType::class, $this->getSetting("Description", "Introduce your self more..."))
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
