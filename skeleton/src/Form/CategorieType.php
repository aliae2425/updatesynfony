<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name',
             TextType::class, 
             [
                 "label" => "Nom",
                 "required" => true,
                    "constraints" => [
                        new Length(
                            min: 3,
                            max: 255,
                            minMessage: "Le nom doit contenir au moins 3 caractères",
                            maxMessage: "Le nom doit contenir au plus 255 caractères"
                        )
                    ]
             ])
            ->add('slug', TextType::class, 
            [
                "required" => false,
                "constraints" => [
                    new Length(
                        min: 3,
                        max: 255,
                        minMessage: "Le slug doit contenir au moins 3 caractères",
                        maxMessage: "Le slug doit contenir au plus 255 caractères"
                    ),
                ]
            ])
            ->add('submit', SubmitType::class, 
            [
                "label" => "Enregistrer"
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new  AsciiSlugger();
            $data['slug'] = strtolower( $slugger->slug($data['Name']));
            $event->setData($data);	
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
