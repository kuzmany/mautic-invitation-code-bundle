<?php

return [
    'name'        => 'Invitation code',
    'description' => 'Invitation code for Mautic',
    'version'     => '1.0',
    'author'      => 'MTCExtendee',

    'routes' => [
        'public' => [
            'mautic_invitation_code_validation' => [
                'path'       => '/invitation/code/generate/{formName}/{fieldAlias}',
                'controller' => 'MauticInvitationCodeBundle:Js:generateInvitationCode',
            ],
        ],
    ],

    'services' => [
        'events' => [
            'mautic.invitationcode.event_listener.form_subscriber' => [
                'class'     => \MauticPlugin\MauticInvitationCodeBundle\EventListener\FormSubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.form.validation.invitationcode.subscriber' => [
                'class'     => \MauticPlugin\MauticInvitationCodeBundle\EventListener\FormValidationSubscriber::class,
                'arguments' =>  [
                    'mautic.lead.model.lead'
                ]
            ],
        ],
        'forms' => [
            'mautic.form.type.invitationcode' => [
                'class' => \MauticPlugin\MauticInvitationCodeBundle\Form\Type\InvitationCodeType::class,
                'alias' => 'invitationcode',
            ],
            'mautic.form.type.validationcode' => [
                'class' => \MauticPlugin\MauticInvitationCodeBundle\Form\Type\ValidationCodeType::class,
                'alias' => 'validationcode',
                'arguments' =>  ['translator']
            ],
        ],
        'models' => [

        ],
        'integrations' => [
            'mautic.integration.invitationcode' => [
                'class'     => \MauticPlugin\MauticInvitationCodeBundle\Integration\InvitationCodeIntegration::class,
                'arguments' => [
                ],
            ],
        ],
        'controllers' => [
            'mautic.invitationcode.controller.js' => [
                'class'     => \MauticPlugin\MauticInvitationCodeBundle\Controller\JsController::class,
                'arguments' => [
                    'mautic.helper.ip_lookup',
                    'templating.helper.assets',
                ],
            ],
        ],
    ],
    'parameters' => [
    ],
];
