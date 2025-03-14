<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Имя',
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'label' => 'Фамилия',
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => 'Телефон',
                'attr' => [
                    'placeholder' => '+7(000)000-00-00',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Почта',
            ])
            ->add('Education', ChoiceType::class, [
                'required' => true,
                'label' => 'Образование',
                'choices' => [
                    Customer\EducationType::HIGHER->value => Customer\EducationType::HIGHER,
                    Customer\EducationType::SPECIAL->value => Customer\EducationType::SPECIAL,
                    Customer\EducationType::SECONDARY->value => Customer\EducationType::SECONDARY,
                ],
            ])
            ->add('pdProcessingPermission', CheckboxType::class, [
                'label' => 'Разрешение ОПДн',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
