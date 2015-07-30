<?php
namespace App\Concept;


use Sinergi\Token\StringGenerator;

class Token {

    /**
     * generate new token
     * by uniqId and time requested
     * @param null $namespace
     * @param int $length
     * @return string
     */
    public static function generate( $namespace = null, $length = 25 ){
        $uniqValue  = uniqid(rand(), true).time();
        $uniqValue .= ( !is_null( $namespace ) ) ? $namespace : '';

        return strtoupper( sha1( $uniqValue ) ).StringGenerator::randomAlnum( $length, true );
    }

    /**
     * generate token and store in session to prevent
     * malicius attack
     * @param null $key
     * @return null
     */
    public static function crf( $key = null ){
        $token  = self::generate( $key );
        Sessions::token( 'current_token', $token );
        return Sessions::get( 'current_token' );
    }

    /**
     * Check token submitted
     * @param $token
     * @return bool
     */
    public static function checkCRF( $token ){
        if ( !$token )
            return false;
        return ( Sessions::get( 'current_token' ) != trim($token) ) ? false : true;
    }
} 