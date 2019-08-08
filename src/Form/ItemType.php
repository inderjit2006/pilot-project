<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $array = array( "Place on top of the list" => "top", "Place on the bottom of the list" => "bottom");

        $builder
            ->add('item_name', null, [
                    'label' => 'Item name',
                    'attr' => ['class' => 'form-control']
                ])
            ->add('color_code', null, [ 
                    'attr' => ['class' => 'form-control' ],
                    'empty_data' => '#8db3e2',
                    'label' => 'Color Code'
                ])
            ->add('placement', ChoiceType::class, [ 'choices' => $array, 'expanded'=>true ])
            ->add('list_id', HiddenType::class,[ 'attr' => [ 'id' => 'hidd_list_id' ], 'mapped' => false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
