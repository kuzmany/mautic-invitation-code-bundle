<?php
/*
 * @copyright   2019 MTCExtendee. All rights reserved

 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$containerType = (isset($type)) ? $type : 'text';
$defaultInputClass = (isset($inputClass)) ? $inputClass : 'input';
include __DIR__.'/../../../../app/bundles/FormBundle/Views/Field/field_helper.php';

$formName = !empty($formName) ? $formName : '';
$input = $view->render(
    'MauticFormBundle:Field:text.html.php',
    [
        'field'      => $field,
        'inForm'     => (isset($inForm)) ? $inForm : false,
        'type'       => 'text',
        'id'         => $id,
        'formId'     => (isset($formId)) ? $formId : 'preview',
        'formName'   => (isset($formName)) ? $formName : '',
        'inputClass' => 'input',
    ]
);
echo $input;
$realFormName = ltrim($formName, '_');
$formDomId    = 'mauticform'.$formName;
$fieldDomId   = 'mauticform'.$formName.'_'.$field['alias'];

if(!$inBuilder) {
    echo <<<HTML
<script async defer src="{$view['router']->generate(
        'mautic_invitation_code_validation',
        [
            'formName'   => $formName,
            'fieldAlias' => $field['alias']
        ],
        true
    )}"></script>
HTML;
}
?>
<style>
#<?php echo $formDomId ?> .mauticform-row { display:none }
#<?php echo $formDomId ?> #<?php echo $fieldDomId ?>, #<?php echo $formDomId ?>  .mauticform-button-wrapper { display:block;  }
</style>