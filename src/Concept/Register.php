<?php
namespace App\Concept;

use Slim\Slim;
use SlimFacades\Facade;
use SlimServices\ServiceManager;

class Register {

    /**
     * The Slim instance app
     * @var$app
     */
    public static $app;

    /**
     * Register facade application class
     * @method Facade
     * @access public
     * @mode static
     */
    public static function Facade(){
        Facade::setFacadeApplication( self::$app );
        Facade::registerAliases();
    }

    /**
     * Register smarty option and instance
     * @method Facade
     * @access public
     * @mode static
     * @params $appView
     */
    public static function Smarty( $appView ){

        /**
         * register all class Helper for
         * smarty template
         */
        $helperSmarty = Config::get( 'smarty.smarty_helper' );

        if ( sizeof( $helperSmarty ) ){
            foreach ( $helperSmarty as $smartyClass => $applicationClass ){
                $appView->getInstance()->registerClass( $smartyClass, $applicationClass  );
            }
        }

    }

    /**
     * Register all service provider
     */
    public static function ServiceProviders(){
        $services = new ServiceManager( self::$app );
        $services->registerServices(array(
            'Illuminate\Events\EventServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider'
        ));
    }

    /**
     * Add all middleware
     * @param Slim $application
     */
    public static function middleware( Slim $application ){
        if ( sizeof( Config::get( 'app.middleware' ) ) ){
            foreach ( Config::get( 'app.middleware' ) as $middleware ){
                $middleware = explode( '@', $middleware );
                $classMiddleware  = $middleware[0];
                $add   = ( isset( $middleware[1] ) ) ? new $classMiddleware( Config::get( $middleware[1] ) ) : new $classMiddleware;
                $application->add( $add );
            }
        }
    }
} 