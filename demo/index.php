<?php

/**
 * index.php
 *
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @version $Id: $
 */
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Microsoft Translator Demo</title>
      <style type="text/css" media="screen">
         body {
            font-family: Arial;
         }
         form {
            width: 500px;
         }
         .separated {
            margin-top: 10px;
            display: block;
         }
         
         #loading {
            padding: 4px 0 0 4px;
            float: left;
         }
         
         .smalltext {
            padding: 2px;
            font-size: 10px;
         }
         
         #submit {
            padding-right: 3px;
            float: left;
         }
         
         textarea {
            resize: none;
         }
      </style>
   </head>
   <body>
      <form name="form1">
         <fieldset>
            <legend>Translation Example</legend>
            <label>
               From: 
               <select name="from" id="from">
                  <option value="en">English</option>
                  <option value="fr">French</option>
                  <option value="de">German</option>
                  <option value="it">Italian</option>
               </select>
            </label>
            <label>
               To: 
               <select name="to" id="to">
                  <option value="en">English</option>
                  <option selected="selected" value="fr">French</option>
                  <option value="de">German</option>
                  <option value="it">Italian</option>
               </select>
            </label>
            <label class="separated">
               <textarea name="text" id="text" cols="40" rows="7">Hello world!</textarea>
            </label>
            <div class="smalltext">* All text is truncated to 64 characters.</div>
            <input id="submit" nam="submit" type="submit" value="Get Translation" /><div id="loading"><img src="spinner.gif" alt="Doing AJAXY stuff" /></div>
         </fieldset>
      </form>
		<script type="text/javascript" src="jquery.js"></script>
      <script type="text/javascript" src="main.js"></script>
   </body>
</html>
