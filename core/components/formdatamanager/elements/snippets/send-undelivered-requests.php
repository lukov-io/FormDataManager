<?php
$modx->setLogLevel(0);
$namespace = 'FormDataManager\Model\\';
$FormDataManager = $modx->services->get("FormDataManager");
$handler = $FormDataManager->getHandler();
$forms = $modx->getCollection($namespace . 'Forms');

/* iterate */


$output = "Done\n";

foreach ($forms as $form) {
    $handler->process($form);
    if ($handler->hasErrors()) {
        $output .= $form->get('formName') . " has errors\n";
    }
}
$modx->setLogLevel(3);
return $output;
