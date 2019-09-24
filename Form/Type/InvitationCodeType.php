<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InvitationCodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'hide_until_validate',
            'yesno_button_group',
            [
                'label' => 'mautic.invitationcode.form.properties.hide_until_validate',
                'attr'  => [
                    'tooltip' => 'mautic.invitationcode.form.properties.hide_until_validate.tooltip',
                ],
                'data'=>isset($options['data']['hide_until_validate']) ? $options['data']['hide_until_validate'] : false
            ]
        );

    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'invitationcode';
    }
}
