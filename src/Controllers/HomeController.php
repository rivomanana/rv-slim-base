<?php



namespace App\Controllers;


class HomeController extends BaseController {

    public function getHome(){
        return $this->render( 'pages::home' );
    }

} 