<?php

use App\Concept\ConceptApp;
use App\Concept\Config;

$conceptApp = ConceptApp::boot()->app();

require_once Config::basePath('src').'/routes.php';

App::run();

 