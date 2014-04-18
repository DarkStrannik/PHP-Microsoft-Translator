<?php

/**
 * trigger.php
 *
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @version $Id: $
 */

require_once '../lib/AzureMarketplaceAuthenticator.class.php';
require_once '../lib/MicrosoftTranslator.class.php';

try {
   $obj_translator_auth = new AzureMarketplaceAuthenticator('AZURE_KEY', 'AZURE_SECRET' ,'http://api.microsofttranslator.com');
   $obj_translator = new MicrosoftTranslator($obj_translator_auth);
   
   $str_from = isset($_POST['from']) ? $_POST['from'] : null;
   $str_to = isset($_POST['to']) ? $_POST['to'] : null;
   $str_text = isset($_POST['text']) ? substr($_POST['text'], 0, 64) : null;
   
   if (is_null($str_from) || is_null($str_to) || is_null($str_text)){
      throw new Exception("One of the parameters wasn't found");
   }
   $str_result = array('Result' => $obj_translator->translate($str_text, $str_from, $str_to));
   echo $str_result;
   
} catch (Exception $obj_exception) {
   echo json_encode(array('Error' => $obj_exception->getMessage()));
}
