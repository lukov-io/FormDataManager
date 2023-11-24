<?php

namespace FormDataManager\Interfaces;

use xPDO\Om\xPDOSimpleObject;

interface HandlerInterface
{
    public function run(xPDOSimpleObject $object): bool;
}