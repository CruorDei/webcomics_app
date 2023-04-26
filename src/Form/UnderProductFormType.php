<?php

namespace App\Form;

use App\Entity\UnderProduct;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnderProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', TypeTextType::class, [
                'attr' => [
                    'placeholder' => 'Chap - "entrer le numero ou le titre"',
                    'value' => 'Chap - ',
                    'class' => 'form-control'
                ],
                    
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, //eviter d'avoir une erreur apres verif dans l'entité
                'required' => false,
                'attr' => ['class' => 'form-control']                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UnderProduct::class,
        ]);
    }
}
