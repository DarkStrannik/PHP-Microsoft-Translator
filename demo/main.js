/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function compare(a, b){
   if (a == b){
      return true;
   } else {
      return false;
   }
}

$(document).ready(function(){
   
   $('#loading')
      .hide()
      .ajaxStart(function() {
         $(this).show();
      })
      .ajaxStop(function() {
         $(this).hide();
      });
   
   // the submit click event
   $('#submit').click(function(event){
      event.preventDefault();
      $('#text').val($('#text').val().substring(0,64));
      $(this).attr('disabled','disabled');
      if (compare($('#from').val(), $('#to').val())){
         alert("TO and FROM languages must be different");
         $('#submit').removeAttr('disabled');
      } else {
         if ($('#text').val() == ''){
            alert('TEXT must have content');
            $('#submit').removeAttr('disabled');
         } else {
            $.ajax({
               type: 'POST',
               url: "trigger.php",
               data: {
                 from: $('#from').val(),
                 to: $('#to').val(),
                 text: $('#text').val()
               },
               dataType: 'json',
               success: function(msg){
                  if (msg.Result){
                     alert(msg.Result);
                  } else if (msg.Error){
                     alert("Error: " + msg.Error);
                  }
                 $('#submit').removeAttr('disabled');
               },
               error: function(msg){
                  alert("Oops, something went wrong with the AJAX call. Try again?");
                  $('#submit').removeAttr('disabled');
               }
            });
         }
      }
   });
   
   
})