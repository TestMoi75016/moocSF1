<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType // C'est dans cette classe que je dois définir tous les champs du formulaire
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //'currentPassword' représente le champs mais ce n'est pas le "nom du champ" mais le "nom de l'attribut ds 'l'entity User' sur lequel je suis "mappé". En effet il y'a pas d'attribut  "current_password" ds cette entité
            ->add('currentPassword', PasswordType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Mot de passe actuel',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.', // Message erreur si mdp pas identiques
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'], //label du champ password
                'second_options' => ['label' => 'Confirmation du mot de passe'], //label du champs comfirmation du password
                'mapped' => false, // ainsi le champ password n'est plus lié à l'entité.
            ]) // typage du mot de passe
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
