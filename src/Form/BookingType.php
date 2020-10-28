<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, $this->getConfig("Date d'arrivée", "Quel jour voulez vous venir ?",
            ['widget'=>"single_text"]))
            ->add('endDate', DateType::class, $this->getConfig("Date de départ", "Quel jour voulez vous repartir ?",
            ['widget'=>"single_text"]))
            ->add('comment', TextareaType::class, $this->getConfig(false, "Si besoin, ajoutez un commentaire...", [
                "required" => false
            ]))
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
