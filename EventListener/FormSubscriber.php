<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\FormBundle\Event\FormBuilderEvent;
use Mautic\FormBundle\FormEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\MauticInvitationCodeBundle\Integration\InvitationCodeIntegration;
use MauticPlugin\MauticInvitationCodeBundle\InvitationCodeEvents;
use MauticPlugin\MauticInvitationCodeBundle\Service\InvitationCodeClient;

class FormSubscriber extends CommonSubscriber
{
    const FIELD_NAME = 'plugin.invitationcode';

    /**
     * @var InvitationCodeClient
     */
    protected $invitationcodeClient;

    /**
     * @var string
     */
    protected $siteKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var boolean
     */
    private $invitationcodeIsConfigured = false;

    /**
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(
        IntegrationHelper $integrationHelper
    ) {
        $integrationObject     = $integrationHelper->getIntegrationObject(InvitationCodeIntegration::INTEGRATION_NAME);
        if ($integrationObject instanceof AbstractIntegration) {
                $this->invitationcodeIsConfigured = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_ON_BUILD         => ['onFormBuild', 0],
        ];
    }

    /**
     * @param FormBuilderEvent $event
     */
    public function onFormBuild(FormBuilderEvent $event)
    {
        if (!$this->invitationcodeIsConfigured) {
            return;
        }
        $event->addFormField(self::FIELD_NAME, [
            'label'          => 'mautic.plugin.actions.invitationcode',
            'formType'       =>  'invitationcode',
            'template'       => 'MauticInvitationCodeBundle:Integration:invitationcode.html.php',
            'builderOptions' => [
            ],
        ]);

    }
}
