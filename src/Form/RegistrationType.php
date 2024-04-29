<?php

namespace App\Form;

use App\Form\UserDetailsType;
use App\Entity\Registration;
use App\Entity\UserDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('details', UserDetailsType::class, [
                'label' => false,
                'attr' => ['class' => 'd-flex flex-column gap-3'],
                'data_class' => UserDetails::class
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class
        ]);
    }
}
