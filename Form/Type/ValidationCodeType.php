<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved

 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\ButtonGroupType;
use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Mautic\LeadBundle\Form\Type\LeadFieldsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidationCodeType extends AbstractType
{

    const INVITATION_MODE             = 'html_mode';

    const MULTIPLE                    = 'multiple';

    const MULTIPLE_CODE_FIELD         = 'multiple_code_field';

    const MULTIPLE_LIMIT_FIELD        = 'multiple_limit_field';

    const MULTIPLE_VALIDATION_MESSAGE = 'multiple_validation_message';

    const ONE_TO_MANY                 = 'one_to_many';

    const ONE_TO_ONE                  = 'one_to_one';

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * FormFieldTelType constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::MULTIPLE,
            YesNoButtonGroupType::class,
            [
                'label' => 'mautic.invitationcode.form.validation.multiple',
                'data'=>isset($options['data'][self::MULTIPLE]) ? $options['data'][self::MULTIPLE] : false
            ]
        );

        $builder->add(
            self::MULTIPLE_VALIDATION_MESSAGE,
            TextType::class,
            [
                'label'       => 'mautic.form.field.form.validationmsg',
                'required'    => false,
                'attr'        => [
                    'class'   => 'form-control',
                ],
                'required'    => false,
            ]
        );

        $builder->add(
            self::MULTIPLE_CODE_FIELD,
            LeadFieldsType::class,
            [
                'label'                 => 'mautic.invitationcode.form.validation.multiple_code_field',
                'label_attr'            => ['class' => 'control-label'],
                'multiple'              => false,
                'with_company_fields'   => true,
                'with_tags'             => true,
                'with_utm'              => true,
                'empty_value'           => 'mautic.core.select',
                'attr'                  => [
                    'class'    => 'form-control',
                    'data-show-on' => '{"formfield_validation_multiple_1": "checked"}',
                ],
                'required'    => true,
                'constraints' => [
                    new NotBlank(
                        ['message' => 'mautic.core.value.required']
                    ),
                ],
            ]
        );

        $builder->add(
            self::MULTIPLE_LIMIT_FIELD,
            LeadFieldsType::class,
            [
                'label'                 => 'mautic.invitationcode.form.validation.multiple_limit_field',
                'label_attr'            => ['class' => 'control-label'],
                'multiple'              => false,
                'with_company_fields'   => true,
                'with_tags'             => true,
                'with_utm'              => true,
                'empty_value'           => 'mautic.core.select',
                'attr'                  => [
                    'class'    => 'form-control',
                    'data-show-on' => '{"formfield_validation_multiple_1": "checked"}',
                ],
                'required'    => true,
                'constraints' => [
                    new NotBlank(
                        ['message' => 'mautic.core.value.required']
                    ),
                ],
            ]
        );

    }
}
