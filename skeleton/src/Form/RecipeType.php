<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\FormEvents;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('slug', TextType::class, [
                "required" => false
            ])
            ->add('description')
            ->add('duration')
            ->add("save", SubmitType::class,
                [ "label" => "Enregistrer"])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDate(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new  AsciiSlugger();
            $data['slug'] = strtolower( $slugger->slug($data['titre']));
            $event->setData($data);	
        }
    }

    public function autoDate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if ( ! $data instanceof Recette) {
            return;
        }

        if (! $data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
    }	
}
