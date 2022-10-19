<?php

namespace App\Form\Documents;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewDocumentVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'File',
                'mapped' => false,
                'required' => true,
//                'constraints' => [
//                    new File([
//                        'mimeTypes' => [
//                            'application/word',
//                            'application/excel',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid office document',
//                    ])
//                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
