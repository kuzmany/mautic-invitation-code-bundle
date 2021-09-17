<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InvitationCodeType extends AbstractType
{
    const HIDE_UNTIL_VALIDATE = 'hide_until_validate';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::HIDE_UNTIL_VALIDATE,
            YesNoButtonGroupType::class,
            [
                'label' => 'mautic.invitationcode.form.properties.hide_until_validate',
                'attr'  => [
                    'tooltip' => 'mautic.invitationcode.form.properties.hide_until_validate.tooltip',
                ],
                'data'=>isset($options['data'][self::HIDE_UNTIL_VALIDATE]) ? $options['data'][self::HIDE_UNTIL_VALIDATE] : false
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
