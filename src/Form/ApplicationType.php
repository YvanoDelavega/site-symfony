<?php 

namespace App\Form;
use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
     /**
     * permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfig($label, $placeholder, $options = [])
    {
        // on utilise array_merge-recursive plutot que array_merge pour ne pas écraser l'attribut 'attr' ou 'label' si ces parametres
        // sont également définis dans $options
        return array_merge_recursive( [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}

?>