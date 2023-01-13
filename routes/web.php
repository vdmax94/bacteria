<?php

use Core\Router;


//router for IndexPage
$router->add("^$", [
    'controller' => 'Main',
    'action' => 'index']
);

//Router for controller/id/action (index/show/edit/create/delete)
$router->add('^(?P<controller>[a-z]+)/?(?P<id>\d+)?/?(?P<action>[a-z]+)?$');

//Router for controller/supertaxon/id/  (default action - index. Another don't require)
//(for showing the all items of concrete taxon that belong to concrete supertaxon)
// for example - classes/divisio/2 (show the all classes that belong to divisio which id = 2)
$router->add('^(?P<controller>[a-z]+)/?(?P<supertaxon>[a-z]+)/?(?P<id>\d+)?$');