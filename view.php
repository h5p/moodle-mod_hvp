<?php

require_once("../../config.php");
require_once("lib.php");

$id = required_param('id', PARAM_INT);  
    
print 'Hello ' . $id . '!';
