<?php
namespace App\Concept;


use Slim\Slim;
use Slim\Views\Smarty;

class ConceptApp {

    /**
     * @var $_instance
     * @access public
     * @mode static
     */
    public static $_instance = null;

    /**
     * @access private
     * @var $app
     */
    private $app;

    /**
     * @access private
     * @var $view
     */
    private $view;

    /**
     * @access private
     * @var $routers
     */
    private $routers;

    /**
     * Translation object
     * @var $translation
     */


    public function __construct(){
        $this->app = new Slim([
            'path' => __DIR__,
            'view' => new Smarty(),
            "database.default" => Config::get( 'db.default' ),
            "database.connections" => Config::get( 'db.connections' )
        ]);

        $this->view = $this->app->view();
        $this->view->parserDirectory = Config::basePath( 'vendor' ).'/smarty';
        $this->view->parserExtensions = Config::basePath( 'vendor' ).'/slim/views/SmartyPlugins';
        $this->view->parserCompileDirectory = Config::basePath( 'http' ).'/cache/smarty/compile';
        $this->view->parserCacheDirectory = Config::basePath( 'http' ).'/cache/smarty/cache';

        $viewInstance = $this->app->view()->getInstance();
        if ( $this->app->config( 'mode' ) == 'development' ){
            $viewInstance->force_compile  = true;
            $viewInstance->force_cache    = false;
        } else {
            $viewInstance->force_compile  = false;
            $viewInstance->force_cache    = true;
        }

        $this->routers = $this->app->router();

        Register::$app = $this->app;

        $this->Register( $this->app );
    }


    public static function boot(){
        if ( is_null( self::$_instance ) )
            self::$_instance = new ConceptApp();

        return self::$_instance;
    }

    public function app(){
        return $this->app;
    }

    /**
     * @return Slim router
     */
    public function router(){
        return $this->routers;
    }

    /**
     * return vew object
     * @return mixed
     */
    public function view(){
        return $this->view;
    }

    /**
     * Register utils extra applications
     * @param Slim $app
     */
    private function Register( Slim $app ){
        /**
         * Facade
         */
        Register::Facade();

        /**
         * Smarty
         */
        $this->view = Register::Smarty( $this->app()->view() );

        /**
         * Service Provider
         */
        Register::ServiceProviders();

        /**
         * Register middleware
         */
        Register::middleware( $app );
    }

    /**
     * Check if backend or front end
     * @return bool
     */
    public function isBackEnd(){
        $route  = $this->app()->request()->getPath().'/';
        if ( preg_match( '/\/'.Config::get('app.managerSlugPAth').'\//', $route ) )
            return true;

        return false;

    }



} 