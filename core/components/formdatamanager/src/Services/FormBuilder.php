<?php

namespace FormDataManager\Services;

use FormDataManager\Model\Forms;
use MODX\Revolution\modX;

class FormBuilder
{
    const HEADER = '<form action="/assets/components/formdatamanager/request.php" id="" class="[+formClass+]" method="POST">';
    const FOOTER = '<button type="submit" name="formName" value="[+formName+]">[+button+]</button> 
                </form>';
    private array $fields = [];

    public function __construct(string $formName, array $formFields, array $config = []) {

    }
}