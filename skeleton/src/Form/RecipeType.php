<?php

namespace App\Form;

use App\Entity\Recette;
use PhpParser\Node\Expr\New_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{

    public function __construct(private FormListenerFactory $FormFactory){

    
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('slug', TextType::class, [
                "required" => false,
                "constraints" => new Sequentially([
                    New Length(
                        min: 3,
                        max: 255,
                        minMessage: "Le slug doit contenir au moins 3 caractères",
                        maxMessage :"Le slug doit contenir au plus 255 caractères"
                    ),
                    new Regex( 
                        "/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
                        "Le slug ne doit contenir que des lettres minuscules, des chiffres et des tirets"
                    )
                ])
            ])
            ->add('description')
            ->add('duration')
            ->add("save", SubmitType::class,
                [ "label" => "Enregistrer"])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->FormFactory->autoSlug("titre"))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->FormFactory->Timestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }

}
