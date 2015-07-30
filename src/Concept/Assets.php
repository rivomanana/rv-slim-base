<?php
namespace App\Concept;


use Symfony\Component\Yaml\Yaml;

class Assets {

    /**
     * pattern to render style and script
     * @var array
     */
    public static $_patterns = [
        'scripts' => '<script type="text/javascript" src="%s"></script>',
        'styles' => '<link href="%s" rel="stylesheet" media="screen" type="text/css" />'
    ];

    /**
     * all registered script
     * @var array
     */
    public static $_registeredScript = [];

    /**
     * all raw scripts or styles
     * @var array
     */
    public static $raw = [];

    /**
     * all registered styles
     * @var array
     */
    public static $_registeredStyles = [];

    /**
     * Render all scripts or styles defined
     * in config.yml file
     * @param $mode
     * @return string
     */
    public static function js( $mode = 'scripts' ){

        /**
         * Get all js or css in config.yml file
         */
        $allResources = self::getConfigList( $mode );

        /**
         * Check if $_registeredScript or
         * $_registeredStyles is not empty
         */
        $pt  = ( $mode == 'scripts' ) ? self::$_registeredScript : self::$_registeredStyles;
        if ( sizeof( $pt ) ){
            foreach ( $pt as $key=>$val )
                $allResources[$key] = $val;
        }

        /**
         * get all raw script or styles
         */
        if ( sizeof( self::$raw ) ){
            $sc  = self::$raw;
        }

        /**
         * render
         */
        foreach ( $allResources as $resource ){
            $resource .= ( ConceptApp::boot()->app()->config( 'mode' ) == 'development' ) ? '?_='.time() : '';
            $sc[] = sprintf( self::$_patterns[$mode], self::assetsUrl().$resource );
        }

        return implode( "\n", $sc );

    }

    /**
     * return styles defined
     * in config.yml file
     * @return string
     */
    public static function css(){
        return self::js( 'styles' );
    }

    /**
     * @return string
     */
    public static function assetsDirectory(){

        return Config::basePath( 'http/web/assets/' );
    }

    /**
     * @return string
     */
    public static function assetsUrl(){
        return Config::baseUrl( ).'http/web/assets/';
    }

    /**
     * set raw script or styles
     * @param null $content
     */
    public static function raw( $content = null ){
        if ( !is_null( $content ) ){
            /**
             * get fullpath
             */
            $fullPath  = Config::basePath( 'http/web/tpl/raw/' );
            $fullPath .= str_replace( '::', '/', $content );
            $fullPath .= '.tpl';

            /**
             * fetch template
             */
            self::$raw[] = \View::fetch( $fullPath );
        }
    }

    /**
     * Register an script or style before displaying
     * @param $mode
     * @param $script
     */
    public static function register( $mode, $script ){
        switch( $mode ){
            case 'scripts':
                self::$_registeredScript[ md5( $script ) ] = $script;
                break;
            case 'styles':
                self::$_registeredStyles[ md5( $script ) ] = $script;
                break;
        }
    }

    /**
     * @param string $mode
     * @return array
     */
    public static function getConfigList( $mode = 'scripts' ){
        $configFile = dirname(self::assetsDirectory()).'/config.yml';
        if ( file_exists( $configFile ) ){
            $all = Yaml::parse( file_get_contents( $configFile ) );
            return $all[ $mode ];
        }
        return [];
    }
} 