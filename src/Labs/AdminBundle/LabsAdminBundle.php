<?php

namespace Labs\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LabsAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
