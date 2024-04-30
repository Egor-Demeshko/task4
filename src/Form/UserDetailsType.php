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
use Traversable;

class UserDetailsType extends AbstractType implements DataMapperInterface
{
    private const NAME = 'name';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::NAME, TextType::class, [
                'data_class' => UserDetails::class,
                ...UserType::STYLES
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
            ->setDataMapper($this);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }


    public function mapDataToForms(mixed $viewData, Traversable $forms)
    {
        if (null === $viewData) {
            return;
        }

        if (!$viewData instanceof UserDetails) {
            throw new UnexpectedTypeException($viewData, UserDetails::class);
        }

        $formsArr = iterator_to_array($forms);
        $formsArr[self::NAME]->setData($viewData->getName());
    }

    public function mapFormsToData(Traversable $forms, mixed &$viewData)
    {
        $forms = iterator_to_array($forms);

        $viewData->setUser($forms['user']->getData());

        $viewData->setName($forms[self::NAME]->getData());
        $dateTimeImmutable = new DateTimeImmutable(\date('Y-m-d H:i:s'));

        $viewData->setRegistratedAt($dateTimeImmutable);
        $viewData->setStatus('active');
    }
}
