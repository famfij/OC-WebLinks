<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 02/11/2015
 * Time: 17:26
 */

namespace WebLinks\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('url', 'text');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'link';
    }
}