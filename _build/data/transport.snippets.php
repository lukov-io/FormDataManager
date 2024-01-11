<?php
function getContent($filename): string
{
    $o = file_get_contents($filename);
    $o = trim(str_replace(array('<?php','?>'),'',$o));
    return $o;
}

$snippets = array();

$snippet = $modx->newObject('modSnippet');
$snippet->fromArray(array(
    'id' => 1,
    'name' => 'send-undelivered-requests',
    'description' => 'sends undeliverable applications',
    'snippet' => getContent($sources['elements'].'/snippets/send-undelivered-requests.php'),
),'',true,true);
$snippet->setProperties([]);
$snippets[] = $snippet;

return $snippets;