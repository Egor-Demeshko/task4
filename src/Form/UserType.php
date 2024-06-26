<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Controller\Utils\FormUtils;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    const STYLES = [
        'attr' => ['class' => 'form-control'],
        'row_attr' => ['class' => "form__line"],
        'label_attr' => ['class' => 'form__label']
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            /**/
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $form = $event->getForm();
                /** @var bool */
                $isParent = (bool) $form?->getParent();
                if ($isParent) {

                    $form->add('email', EmailType::class, [
                        'required' => false,
                        ...self::STYLES
                    ])
                        ->add('password', RepeatedType::class, [
                            'type' => PasswordType::class,
                            'invalid_message' => 'The password fields must match.',
                            'options' => [
                                ...UserType::STYLES
                            ],
                            'required' => true,
                            'first_options' => [
                                'label' => 'Enter your password'
                            ],
                            'second_options' => ['label' => 'Repeat Password'],
                        ]);
                }
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $obj = $event->getData();

                $obj->setRoles(['ROLE_USER']);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'trim' => true
        ]);
    }
}
