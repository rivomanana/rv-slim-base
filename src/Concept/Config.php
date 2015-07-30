<?php
namespace App\Concept;

use Symfony\Component\Yaml\Yaml;

class Config {

    public static function get( $configName ){
        if ( !empty( $configName ) ){
            $property = explode( '.', $configName );
            $configList = Yaml::parse( file_get_contents( self::basePath().'/config/'.$property[0].'.yml' ) );
            if ( isset( $property[1] ) )
                return $configList[ $property[1] ];

            return $configList;

        }
    }

    public static function basePath( $slug = '' ){
        $base_dir = dirname( dirname( dirname( __FILE__ ) ) );
        return ( !empty( $slug ) ) ? $base_dir.DIRECTORY_SEPARATOR.$slug : $base_dir;
    }

    public static function baseUrl(){
        /**
         * set default sapi is undefined
         */
        $sapi= 'undefined';

        /**
         * get script name
         */
        if (!strstr($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) && ($sapi= @ php_sapi_name()) == 'cgi') {
            $script_name= $_SERVER['PHP_SELF'];
        } else {
            $script_name= $_SERVER['SCRIPT_NAME'];
        }

        /**
         * check if in manager mode
         */
        $checkUrl = explode( "/manager", str_replace("\\", "/", dirname( $script_name ) ) );

        if ( count( $checkUrl ) > 1 )
            array_pop( $checkUrl );
        $url= implode( "manager", $checkUrl );
        reset( $checkUrl );
        $checkUrl= explode( "manager", str_replace( "\\", "/", dirname( __FILE__ ) ) );

        unset ( $checkUrl );
        $base_url = $url . ( substr( $url, -1 ) != "/" ? "/" : "" );

        /**
         * Build the site url
         */
        $site_url= ((isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == self::get( 'app.httpsPort' )) ? 'https://' : 'http://';
        $site_url .= $_SERVER['HTTP_HOST'];
        if ($_SERVER['SERVER_PORT'] != 80)
            $site_url= str_replace(':' . $_SERVER['SERVER_PORT'], '', $site_url); // remove port from HTTP_HOST  
        $site_url .= ($_SERVER['SERVER_PORT'] == 80 || (isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == self::get( 'app.httpsPort' )) ? '' : ':' . $_SERVER['SERVER_PORT'];

        /**
         * return full baseUtl
         */
        return $site_url.$base_url;
    }
} 