<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['label' => 'Адрес электронной почты'])
            ->add('password', PasswordType::class, ['label' => 'Введите новый пароль'])
            ->add('firstname', TextType::class, ['label' => 'Фамилия'])
            ->add('lastname', TextType::class, ['label' => 'Имя'])
            ->add('patronimic', TextType::class, ['label' => 'Отчество'])
            ->add('phone', TextType::class, ['label' => 'Номер телефона'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
