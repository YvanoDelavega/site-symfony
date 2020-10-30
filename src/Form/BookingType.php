<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $tr)
    {
        $this->transformer = $tr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('startDate', DateType::class, $this->getConfig("Date d'arrivée", "Quel jour voulez vous venir ?",
            // ['widget'=>"single_text"]))
            // ->add('endDate', DateType::class, $this->getConfig("Date de départ", "Quel jour voulez vous repartir ?",
            // ['widget'=>"single_text"]))
            ->add('startDate', TextType::class, $this->getConfig("Date d'arrivée", "Quel jour voulez vous venir ?"))
            ->add('endDate', TextType::class, $this->getConfig("Date de départ", "Quel jour voulez vous repartir ?"))
            ->add('comment', TextareaType::class, $this->getConfig(false, "Si besoin, ajoutez un commentaire...", [
                "required" => false
            ]));

            $builder->get('startDate')->addModelTransformer($this->transformer);
            $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            "validation_groups" => ['Default', 'front']
        ]);
    }
}
