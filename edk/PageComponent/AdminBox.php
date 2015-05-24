<?php

namespace EDK\PageComponent;

/**
 * Overload the box object to force every admin page to use the new options menu
 * @package EDK
 */
class AdminBox extends Box
{
    function generate()
    {
        return Options::genAdminMenu();
    }
}