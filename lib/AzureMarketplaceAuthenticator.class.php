<?php

/**
 * AzureMarketplaceAuthenticator.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @version $Id: $
 */

/**
 * AzureMarketplaceAuthenticator
 *
 * Description:
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 */
class AzureMarketplaceAuthenticator {
      
   /**
    * Grant type
    */
   const GRANT_TYPE = 'client_credentials';
   
   /**
    * OAuth query URL
    */
   const QUERY_URL = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13';
   
   /**
    * The application scope
    *
    * @var String
    */
   private $str_application_scope;
   
   /**
    * Client ID from your application in Azure DataMarket
    *
    * @var String
    */
   private $str_client_id;
   
   /**
    * Client Secret from your application in Azure DataMarket
    *
    * @var String
    */
   private $str_client_secret;
   
   /**
    * The file where the token is stored
    *
    * @var String
    */
   private $str_token_file;
   
   /**
    * All arguments are required for ANY operation, which is why they are in
    * the constructor.
    *
    * @param String $str_client_id
    * @param String $str_client_secret 
	 * @param String $str_application_scope 
    */
   public function __construct($str_client_id, $str_client_secret, $str_application_scope) {
	
      $this->str_client_id = $str_client_id;
      $this->str_client_secret = $str_client_secret;
      $this->str_application_scope = $str_application_scope;
		
      $this->str_token_file = sys_get_temp_dir() . sha1($this->str_client_id . $this->str_client_secret);
		
   }
   
   /**
    * Retrieve the OAuth token to be used in Microsoft Datamarket Applications
    *
    * @return String
    */
   public function get_token(){
      
      if ($this->token_has_expired()) {
         $str_token = $this->request_new_token();
      } else {
         $str_token = $this->get_current_token_data();
      }
      
      return $str_token;
      
   }
   
   /**
    * Has the token expired?
    *
    * @return Boolean 
    */
   private function token_has_expired() {
	
      if (file_exists($this->str_token_file)){
         if ($this->get_current_expiry() <= time()){
            return TRUE;
         } else {
            return FALSE;
         }
      } else {
         return TRUE;
      }
		
   }
   
   /**
    * Gets a new token, stores and returns it
    *
    * @return String
    */
   private function request_new_token() {
      $obj_connection = curl_init();
      $arr_query_bits = array (
         'grant_type' => self::GRANT_TYPE,
         'scope' => $this->str_application_scope,
         'client_id' => $this->str_client_id,
         'client_secret' => $this->str_client_secret
      );
      $str_query = http_build_query($arr_query_bits);
      
      curl_setopt($obj_connection, CURLOPT_URL, self::QUERY_URL);
      curl_setopt($obj_connection, CURLOPT_HEADER, 0);
      curl_setopt($obj_connection, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($obj_connection, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($obj_connection, CURLOPT_POSTFIELDS, $str_query);
      curl_setopt($obj_connection, CURLOPT_POST, TRUE);
		curl_setopt($obj_connection, CURLOPT_SSL_VERIFYPEER, FALSE);
      
      $str_response = curl_exec($obj_connection);
      $obj_response = json_decode($str_response);
      
      if (is_null($obj_response)){
         throw new Exception("Response wasn't VALID  - {$str_response}");
      }
      
      if (isset($obj_response->error)){
         throw new Exception($obj_response->error_description);
      }
      
      $this->store_token_response($obj_response);
      return $obj_response->access_token;
   }
   
   /**
    * Reads the token file.
    * Forces a re-read using clearstatcache for thread safety
    *
    * @return String
    */
   private function read_token_file() {
      $str_token = file_get_contents($this->str_token_file);
      clearstatcache();
      return $str_token;
   }
   
   /**
    * Stores the token and expiry time as a json encoded string
    *
    * @param StdClass $obj_response 
    */
   private function store_token_response($obj_response){
      $arr_token_data = array(
        'expires' => time() + ($obj_response->expires_in - 10),
        'data' => $obj_response->access_token
      );
      file_put_contents($this->str_token_file, json_encode($arr_token_data));
   }
   
   /**
    * Gets the token from the current file
    *
    * @return String 
    */
   private function get_current_token_data(){
      $arr_token = json_decode($this->read_token_file());
      return $arr_token->data;
   }
   
   /**
    * Gets the expiry time from the current file
    *
    * @return String
    */
   private function get_current_expiry(){
      $arr_token = json_decode($this->read_token_file());
      return $arr_token->expires;
   }
   
}