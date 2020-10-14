<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnonceType extends AbstractType
{
    /**
     * permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfig($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfig("Titre", "Tapez un titre pour votre annonce"))
            ->add('slug', TextType::class, $this->getConfig("Adresse web", "Tapez l'adresse (automatique)"))
            ->add('coverImage', UrlType::class, $this->getConfig("URL de l'image principale", "donnez une photo qui donne envie"))
            ->add('introduction', TextType::class, $this->getConfig("Introduction", "Donnez une description de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfig("Description détaillée", "donnez une bonne raison de venir chez vous"))
            ->add('rooms', IntegerType::class, $this->getConfig("Nombre de chambre", "le nom de chambre"))
            ->add('price', MoneyType::class, $this->getConfig("Prix par nuit", "indiquez le prix que vous voulez pour une nuit"))
            //  ->add('save', SubmitType::class, [
            //           'label' => 'créer la nouvelle annonce',
            //           'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
