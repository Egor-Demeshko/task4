<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Traversable;

class UserDetailsType extends AbstractType //implements DataMapperInterface
{
    private const NAME = 'name';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::NAME, TextType::class, [
                'data_class' => UserDetails::class,
                ...UserType::STYLES,
            ])
            ->add('user', UserType::class, [
                'label' => false,
                'attr' => ['class' => 'd-flex flex-column gap-3'],
                'data_class' => User::class,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Register'
            ])
            ->addEventListener(FormEvents::SUBMIT, function ($event) {
                $detailObj = $event->getData();

                if (!isset($detailObj)) {
                    return $event;
                }

                // set addtional data to details_obj
                $detailObj->setRegistratedAt(new DateTimeImmutable(\date('Y-m-d H:i:s')));
                $detailObj->setStatus('active');
            });
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
