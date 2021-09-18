<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved

 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\FormBundle\Event as Events;
use Mautic\FormBundle\FormEvents;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\MauticInvitationCodeBundle\Form\Type\InvitationCodeType;
use MauticPlugin\MauticInvitationCodeBundle\Form\Type\ValidationCodeType;

class FormValidationSubscriber extends CommonSubscriber
{

    /**
     * @var LeadModel
     */
    private $leadModel;

    /**
     * FormValidationSubscriber constructor.
     *
     * @param LeadModel $leadModel
     */
    public function __construct(LeadModel $leadModel)
    {
        $this->leadModel = $leadModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_ON_BUILD    => ['onFormBuilder', 0],
            FormEvents::ON_FORM_VALIDATE => ['onFormValidate', 0],
        ];
    }

    /**
     * Add a simple email form.
     *
     * @param Events\FormBuilderEvent $event
     */
    public function onFormBuilder(Events\FormBuilderEvent $event)
    {
        $event->addValidator(
            'validatecode.validation',
            [
                'eventName' => FormEvents::ON_FORM_VALIDATE,
                'fieldType' => FormSubscriber::FIELD_NAME,
                'formType'  => ValidationCodeType::class,
            ]
        );
    }

    /**
     * Custom validation     *.
     *
     * @param Events\ValidationEvent $event
     */
    public function onFormValidate(Events\ValidationEvent $event)
    {
        $leadField = $event->getField()->getLeadField();

        if (!$leadField) {
            $this->setBasicFailedValidation($event);

            return;
        }

        if (!empty($event->getField()->getValidation()[ValidationCodeType::MULTIPLE])) {

            $value = $event->getValue();

            $fieldLimit = !empty($event->getField()->getValidation()[ValidationCodeType::MULTIPLE_LIMIT_FIELD]) ? $event->getField()->getValidation()[ValidationCodeType::MULTIPLE_LIMIT_FIELD] : null;
            $fieldToCheck = $event->getField()->getValidation()[ValidationCodeType::MULTIPLE_CODE_FIELD];

            $contacts = $this->leadModel->getEntities([
                'filter'         => [
                    'force' => [
                        [
                            'column' => 'l.'.$fieldToCheck,
                            'expr'   => 'eq',
                            'value'  => $value,
                        ],
                        [
                            'column' => 'l.date_identified',
                            'expr'   => 'isNotNull',
                        ],
                    ],
                ],
                'hydration_mode' => 'HYDRATE_ARRAY',
            ]);
            if (!$contacts) {
                $this->setBasicFailedValidation($event);
                return;
            }
            /** @var Lead $contact */
            $contact = reset($contacts);
            $limit = !empty($contact->getFieldValue($fieldLimit)) ? $contact->getFieldValue($fieldLimit) : 1;

            $contactsSubmittedCode = $this->leadModel->getEntities([
                'filter'         => [
                    'force' => [
                        [
                            'column' => 'l.'.$leadField,
                            'expr'   => 'eq',
                            'value'  => $value,
                        ],
                        [
                            'column' => 'l.date_identified',
                            'expr'   => 'isNotNull',
                        ],
                    ],
                ],
                'hydration_mode' => 'HYDRATE_ARRAY',
            ]);
            if (count($contactsSubmittedCode) >= $limit) {
                $this->setMultipleFailedValidation($event);
                return;
            }

        } else {
            $this->basicCodeValidation($event);
        }

    }

    /**
     * @param Events\ValidationEvent $event
     */
    private function setMultipleFailedValidation(Events\ValidationEvent $event)
    {
        $field = $event->getField();

        if (!empty($field->getValidation()[ValidationCodeType::MULTIPLE_VALIDATION_MESSAGE])) {
            $event->failedValidation($field->getValidation()[ValidationCodeType::MULTIPLE_VALIDATION_MESSAGE]);
        } else {
            $event->failedValidation($this->translator->trans('mautic.invitationcode.form.code.invalid'));
        }
    }

    /**
     * @param Events\ValidationEvent $event
     */
    private function setBasicFailedValidation(Events\ValidationEvent $event)
    {
        $field = $event->getField();

        if (!empty($field->getValidationMessage())) {
            $event->failedValidation($field->getValidationMessage());
        } else {
            $event->failedValidation($this->translator->trans('mautic.invitationcode.form.code.invalid'));
        }
    }

    /**
     * @param Events\ValidationEvent $event
     */
    public function basicCodeValidation(Events\ValidationEvent $event)
    {
        $field = $event->getField();
        $value = $event->getValue();

        if (!$field->getLeadField()) {
            $this->setBasicFailedValidation($event);

            return;
        }

        $contacts = $this->leadModel->getEntities([
            'filter'         => [
                'force' => [
                    [
                        'column' => 'l.'.$field->getLeadField(),
                        'expr'   => 'eq',
                        'value'  => $value,
                    ],
                ],
            ],
            'hydration_mode' => 'HYDRATE_ARRAY',
        ]);
        if (!count($contacts)) {
            $this->setBasicFailedValidation($event);
        }
    }
}
