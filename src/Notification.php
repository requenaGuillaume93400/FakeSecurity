<?php 

class Notification
{    
  
    // Set Notification code for required fields, @return int
    public function setNotif(String $parameter): int
    {
      if(isset($_GET[$parameter])){
        return intval($_GET[$parameter]); // Get error or success code
      }
      return 1; // Empty
    }
    

    // Set Notification code for optionnal fields, @return int
    public function setOptionnalNotif(String $parameter): int
    {
      if(isset($_GET[$parameter])){
        if(intval($_GET[$parameter]) !== 1){
          return intval($_GET[$parameter]); // Get error or success code
        }
      }
      return 200; // Success
    }


    // Set a success message if success parameter is define in URL, @return String or null
    public function setSuccess(array $listMessage) //: String or null
    {
      if(isset($_GET['success'])){       
        
        switch(intval($_GET['success'])){
          case '1':
            return $listMessage[0];

          case '2':
            return $listMessage[1];
        }        
      }
      return null;
    }
    

    // Set a failure message if fail parameter is define in URL, @return String or null
    public function setFail(String $message) //: String or null
    {
      if(isset($_GET['fail']) && intval($_GET['fail']) === 1){
        return $message;
      }
      return null;
    }

}