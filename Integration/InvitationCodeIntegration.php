<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

/**
 * Class InvitationCodeIntegration.
 */
class InvitationCodeIntegration extends AbstractIntegration
{
    const INTEGRATION_NAME = 'InvitationCode';

    public function getName()
    {
        return self::INTEGRATION_NAME;
    }

    public function getDisplayName()
    {
        return 'Invitation Code';
    }

    public function getAuthenticationType()
    {
        return 'none';
    }

    public function getRequiredKeyFields()
    {
        return [
        ];
    }

    public function getIcon()
    {
        return 'plugins/MauticInvitationCodeBundle/Assets/img/icon.png';
    }
}
