<?php

/*
 * @copyright   2019 MTCExtendee. All rights reserved

 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticInvitationCodeBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Mautic\CoreBundle\Helper\IpLookupHelper;
use Mautic\CoreBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\Response;

class JsController extends CommonController
{
    /**
     * @var IpLookupHelper
     */
    private $ipLookupHelper;

    /**
     * @var AssetsHelper
     */
    private $assetsHelper;

    /**
     * PublicController constructor.
     *
     * @param IpLookupHelper $ipLookupHelper
     * @param AssetsHelper   $assetsHelper
     */
    public function __construct(IpLookupHelper $ipLookupHelper, AssetsHelper $assetsHelper)
    {
        $this->ipLookupHelper = $ipLookupHelper;
        $this->assetsHelper   = $assetsHelper;
    }

    /**
     * @param string $formName
     *
     * @return Response
     */
    public function generateInvitationCodeAction($formName, $fieldAlias)
    {
        $realFormName = ltrim($formName, '_');
        $formDomId    = 'mauticform'.$formName;
        $js           = <<<JS
       
    if (typeof MauticFormCallback == 'undefined') {
        var MauticFormCallback = {};
    }

    var formAlias = '{$formDomId}';
    var fieldAlias = '{$fieldAlias}';
    
    MauticFormCallback['{$realFormName}'] = {};
    MauticFormCallback['{$realFormName}']['step2'] =  false;
    MauticFormCallback['{$realFormName}'] = {
        onResponseEnd: function (response) {
            if (typeof response.validationErrors[fieldAlias] === 'undefined') {
                showStep2();
                MauticFormCallback['{$realFormName}']['step2'] = true;
            }
            else {
                hideStep2();
            }
        },
        onValidateField: function (data) {
            return true;
        }
    };

    function showStep2 () {
        var nodeList = document.querySelectorAll("#" + formAlias + " .mauticform-row:not(.mauticform-button-wrapper)");
        [].forEach.call(nodeList, function (node) {
            node.style.display='block';
                if(!MauticFormCallback['{$realFormName}']['step2']){
                    node.querySelector(".mauticform-errormsg").style.display ='none';
        }
        });
        
        var obj =  document.querySelector("#" + formAlias + "_" + fieldAlias + "");
          obj.querySelector(".mauticform-errormsg").style.display ='none';
        obj.style.display = 'none';
        
    }

    function hideStep2 () {
        var nodeList = document.querySelectorAll("#" + formAlias + " .mauticform-row:not(.mauticform-button-wrapper)");
        [].forEach.call(nodeList, function (node) {
            node.style.display='none';

        });
        var obj =  document.querySelector("#" + formAlias + "_" + fieldAlias + "");
        obj.style.display = 'block';

    }

    

JS;

        return new Response(
            $js,
            200,
            [
                'Content-Type' => 'application/javascript',
            ]
        );
    }
}
