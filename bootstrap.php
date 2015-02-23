<?php
/*
 * Autoloader
 * When you create a subfolder structure matching the
 * namespaces of the containing classes, you will never even have to define an autoloader.
 * http://php.net/manual/en/function.spl-autoload-register.php#92514
 */
spl_autoload_extensions(".php");
spl_autoload_register();