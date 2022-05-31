<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',ChoiceType::class, [
                'label' => 'Статус заказа',
                'choices'  => [
                    'Не подтвержден' => 'not confirmed',
                    'Подтвержден' => 'confirmed'
                ],
                ])
            ->add('date_create', DateType::class, [
                'label' => 'Дата создания/подтверждения:',
                'attr' => array('class' => 'row g-3', 'style'=>"margin: 10px 0 10px 0"),
                'widget' => 'single_text'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'fio' => 'Не указано'
        ]);
        $resolver->setAllowedTypes('fio', 'string');
    }
}
