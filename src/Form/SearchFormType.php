<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = new Request();
        $builder
            ->setMethod('POST')
            ->add('query',TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Search...']
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-dark']
            ])
            ->getForm();

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}