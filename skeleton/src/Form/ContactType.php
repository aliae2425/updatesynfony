<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom',
                    'attr' => [
                        'placeholder' => 'Votre nom',
                    ],
                    "constraints" => [
                        new Length(
                            min: 3,
                            max: 255,
                            minMessage: "Le nom doit contenir au moins 3 caractères",
                            maxMessage: "Le nom doit contenir au plus 255 caractères"
                        )
                    ]
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'label' => 'Email',
                    'attr' => [
                        'placeholder' => 'Votre email',
                    ],
                    "constraints" => [
                            new Length(
                                min: 3,
                                max: 255,
                                minMessage: "L'email doit contenir au moins 3 caractères",
                                maxMessage: "L'email doit contenir au plus 255 caractères"
                            ), 
                            new Email(
                                message: "L'email n'est pas valide"
                            )
                        ]
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                options: [
                    'label' => 'Message',
                    'attr' => [
                        'placeholder' => 'Votre message',
                    ],
                    "constraints" => [
                        new Length(
                            min: 10,
                            minMessage: "Le message doit contenir au moins 10 caractères"
                        )
                    ]
                ]
            )
            ->add('destinataire',
                ChoiceType::class,
                [
                    'label' => 'Destinataire',
                    'choices' => [
                        'Service commercial' => "contact@com.418.com",
                        'Service technique' => "contact@tech.418.com",
                        'Service administratif' => "contact@admin.418.com",
                        "Service client" => "contact@418.com"
                        ]
                ])
            ->add("save", SubmitType::class, ["label" => "Envoyer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
