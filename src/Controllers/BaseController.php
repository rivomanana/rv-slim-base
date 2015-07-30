<?php
namespace App\Controllers;


use App\Concept\Assets;
use App\Concept\Config;
use App\Concept\ConceptApp;
use App\Concept\Lang;
use App\Concept\Token;

class BaseController {

    protected $provider = null;

    /**
     * List of script and styles
     * @var array
     */
    protected $mediaScripts = [
        'scripts' => [],
        'styles' => []
    ];

    /**
     * Slim applications
     * @var $app
     */
    protected $slimApp;

    /**
     * Path to template directory
     * @var $tplDirectory
     */
    protected $tplDirectory = null;

    /**
     * Base url for assets
     * @var $assetsUrl
     */
    protected $assetsUrl  = null;

    /**
     * All variable to assign in tpl
     * within the render
     * @var $htmlVar
     */
    protected $htmlVar  = [];

    /**
     * Set if path is manager
     * @var bool
     */
    public $managerPath = false;

    /**
     * Instance all needed params for
     * all controllers
     */
    public function __construct(){
        /**
         * instance app
         */
        $this->slimApp = ConceptApp::boot()->app();

        /**
         * Check if manager mode
         */
        $this->managerPath = ConceptApp::boot()->isBackEnd();

        /**
         * Set directory TPL
         */
        $this->setTplDirectory();

        /**
         * Set assets Url
         */
        $this->setAssetsUrl();

        /**
         * set default html var
         * namespaced by config
         */
        $this->htmlVar['config']['site_url']  = Config::baseUrl();
        $this->htmlVar['config']['assets_url']  = $this->assetsUrl;
        $this->htmlVar['config']['base_path'] = Config::basePath();
        $this->htmlVar['config']['tplPath'] = $this->tplDirectory;
        $this->htmlVar['config']['manager'] = $this->managerPath;


    }

    /**
     * get current required role
     * @return string
     */
    public function role(){
       return ( $this->managerPath ) ? 'administrator' : 'user';
    }

    /**
     * Set tpl directory
     */
    private function setTplDirectory(){
        $this->tplDirectory = Config::basePath( 'http/web/tpl/' );
    }

    /**
     * Set assets url
     */
    private function setAssetsUrl(){
        $baseUrl = Config::baseUrl();

        $this->assetsUrl = $baseUrl.'http/web/assets/';
    }

    public function render( $templates, $htmlVar = [] ){
        /**
         * set html variable
         */
        if ( sizeof( $htmlVar ) )
            foreach ( $htmlVar as $var=>$value )
                $this->htmlVar[$var] = $value;

        /**
         * get templates
         */
        $templates = $this->tplDirectory.str_replace( '::', '/', $templates ).'.tpl';

        /**
         * Register script
         * and styles
         */
        foreach ( $this->mediaScripts as $type => $resources ){
            if ( sizeof( $resources ) ){
                foreach ( $resources as $resource )
                    Assets::register( $type, $resource );
            }
        }

        /**
         * Render template
         */
        return $this->slimApp->render( $templates, $this->htmlVar );
    }

    /**
     * @return null
     */
    public function tplDirectory(){
        return $this->tplDirectory;
    }

    /**
     * Check if data as posted and validate
     * fields with rules specified in rules.yml
     * @param string $rule
     * @param array $unset
     * @return array
     */
    public function posts( $rule = '', array $unset = [] ){
        if ( \Request::isPost() ){

            $results = [ 'valid' => false ];

            /**
             * get all posts
             */
            $posts = \Request::post();

            /**
             * unset unused fields if
             * needed
             */
            if ( sizeof( $unset ) ){
                foreach( $unset as $fields )
                    unset( $posts[$fields] );
            }

            /**
             * get rules
             */
            $rules = ( $rule ) ? Config::get( 'rules.'.$rule ) : [];

            /**
             * use GUMP library to validate
             * and sanitize fields
             */
            $validator = new \GUMP();
            $posts     = $validator->sanitize( $posts );
            $validator->validation_rules( $rules );
            $validated = $validator->run( $posts );

            /**
             * check validations result
             */
            if ( !$validated ){
                $results['error'] = $validator->errors();
                $results['data']  = $posts;
            } else {
                $results['valid'] = true;
                $results['data']  = $posts;
            }

            return $results;
        }
        return [];
    }

    /**
     * @return null
     */
    public function assetsUrl(){
        return $this->assetsUrl;
    }

    /**
     * return an instance of service provider
     * @return mixed
     */
    public function provider(){
        if ( !is_null( $this->provider ) ){
            $classProvider = 'App\Services\\'.$this->provider;
            return new $classProvider( $this->slimApp );
        }

    }

    /**
     * set header response to JSON
     * validate fields
     * @param $rule
     * @param array $message
     * @param array $unset
     * @return \stdClass
     */
    public function validate( $rule, array $message = [], array $unset = []  ){
        /**
         * prepare header for json response
         */
        $response = $this->slimApp->response();
        $response['Content-Type'] = 'application/json';
        $response->status( 200 );

        /**
         * Create stdclass for results output
         */
        $results = new \stdClass();

        $errors  = [];

        /**
         * get all posts
         */
        $data  = $this->posts( $rule, $unset );

        /**
         * build error list text
         */
        if ( isset( $data['error'] ) and sizeof( $data['error'] ) ){
            foreach( $data['error'] as $error ){
                $e[str_replace( 'validate_', '', $error['rule'] )] = ( isset( $message[ str_replace( 'validate_', '', $error['rule'] ) ] ) ) ?
                            Lang::get( $message[ str_replace( 'validate_', '', $error['rule'] ) ] ) :
                            '#'.str_replace( 'validate_', '', $error['rule'] );

            }
            $data['error_message'] = implode( "<br />", $e );

            /**
             * build error list fields
             */
            $errors  = $this->buildsErrorFields( $data['error'] );

        }


        /**
         * Check if valid data submitted
         */
        if ( !$data['valid'] ){
            $state = false;
            $message = $data['error_message'];
        } else if ( !Token::checkCRF( $data['data']['token'] ) ){
            /**
             * Check token CRF
             */
            $state = false;
            $message = Lang::get( 'error.badToken' );

        } else {
            $state = true;
            $message = null;
        }

        $results->valid = $state;
        $results->message = $message;
        $results->_ = Token::crf( 'log-to-manager' );
        $results->data = $data['data'];
        $results->errors = $errors;

        return $results;
    }

    /**
     * Create array containing error per fields
     * @param $errors
     * @return array
     */
    private function buildsErrorFields( $errors ){

        $list = [];

        foreach ( $errors as $fieldsError ){
            $keyLang  = str_replace( 'validate_', '', $fieldsError['rule'] );
            $list[ $fieldsError['field'] ][] = Lang::get( 'errorFields.'.$fieldsError['field'].'_'.$keyLang );
        }

        return $list;

    }
} 