<?php

/**
 * MicrosoftTranslator.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @version $Id: $
 */

class MicrosoftTranslator {
   
   /**
    * The WSDL URL
    */
   const STR_WSDL_URL = 'http://api.microsofttranslator.com/V2/Soap.svc';

   /**
    * Constructor - requires an instance of AzureMarketplaceAuthenticator, prepares
    * a SoapClient with the client credentials
    *
    * @param AzureMarketplaceAuthenticator $obj_auth 
    */
   public function __construct(AzureMarketplaceAuthenticator $obj_auth){
      $str_auth_header = "Authorization: Bearer ". $obj_auth->get_token();
      $arr_context = array(
          'http' =>array(
            'header' => $str_auth_header
        )
      );
      /**
       * This is here because of a bug:
       * https://bugs.php.net/bug.php?id=49853
       */
      ini_set('user_agent', 'PHP-SOAP/' . PHP_VERSION . "\r\n" . $str_auth_header);
      $obj_context = stream_context_create($arr_context);
      $arr_options = array (
         'soap_version' => 'SOAP_1_2',
         'encoding' => 'UTF-8',
         'exceptions' => true,
         'trace' => true,
         'cache_wsdl' => 'WSDL_CACHE_NONE',
         'stream_context' => $obj_context
      );
      $this->obj_soap_connection = new SoapClient(self::STR_WSDL_URL, $arr_options);
   }
   
   /**
    * Performs a translation request, returns the translated text
    *
    * @param string $str_source_text
    * @param string $str_source_language
    * @param string $str_target_language
    * @return string
    */
   public function translate($str_source_text = NULL, $str_source_language = NULL, $str_target_language = NULL) {
      if (is_null($str_source_text) || is_null($str_source_language) || is_null($str_target_language)){
         throw new Exception("Invalid argument");
      }
      $arr_request_arguments = array (
         'appId' => '', // no longer used, but pass it anyway
         'text' => $str_source_text,
         'from' => $str_source_language,
         'to' => $str_target_language
      );
      $obj_soap_response = $this->obj_soap_connection->Translate($arr_request_arguments);
      return $obj_soap_response->TranslateResult;
   }
   
}