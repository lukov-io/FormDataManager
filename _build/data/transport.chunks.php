<?php
$chunks = array();

$chunk = $modx->newObject('modChunk');
$chunk->fromArray([
    'id' => 0,
    'name' => "email-template",
    'description' => 'this chunk used to send Email',
    'snippet' => file_get_contents($sources['elements'] . '/chunks/templaterequest.chunk.tpl'),
    'static' => false,
    'source' => 1,
], '', true, true);
$chunks[] = $chunk;

return $chunks;