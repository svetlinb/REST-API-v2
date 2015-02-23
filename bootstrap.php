<?php
/*
 * Autoloader
 * There is no need of our implementation 'cos built in function "spl_autoload_register"
 * will do it for us.
 */
spl_autoload_extensions(".php");
spl_autoload_register();