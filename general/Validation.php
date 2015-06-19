<?php

class Validation {

    function IsEmpty($value)
    {
        if($value==NULL || $value=="" || strlen($value)==0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function IsNotValidEmail($email)
    {
        if( !preg_match( "/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})$/", $email))
          {
            return true;
          }
          else
          {
              return false;
          }
    }
    
    function IsNotValidLenght($value,$length)
    {
        if(strlen($value)>$length)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function IsNotMinimumValidLenght($value,$length)
    {
        if(strlen($value)<$length)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function IsStringNotEqual($str1,$str2)
    {
        if(strcmp($str1,$str2)!=0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function IsNotSelected($value)
    {
        if($value==-1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function IsNotValidImage($imageType)
    {
        if ((($imageType == "image/gif") || ($imageType == "image/jpeg") || ($imageType == "image/jpg") || ($imageType == "image/png")))
        {
            return FALSE;
        }
          else
          {
                return TRUE;
          }
    }
    
    function IsNotValidImageSize($imageSize)
    {
        if ((($imageSize < 1500000)))
        {
            return FALSE;
        }
          else
          {
                return TRUE;
          }
    }

    function IsNotNumeric($value)
    {
        if(is_numeric($value))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function IsNotNumber($value)
    {
        if(preg_match( "/^\d+(\.\d+)?$/", $value) == 1)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    function IsNotInteger($value) {
        if((preg_match( '/^\d*$/'  , $value) == 1 ))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function IsNotDate($value) {
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value) == 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>
