<?php
/* Program: Login.php
 * Desc:    Main application script for the User Login
 *          application. It provides two options: 
 *          (1) login using an existing username and 
 *          (2) register a new username. 
 */
session_start();                                          #8
switch (@$_POST['Button'])                                #9
{
  case "Log in":                                         #11
    include("dogs.inc");                                 #12
    $cxn = mysqli_connect($host,$user,$passwd,$dbname)
             or die("Query died: connect");              #14
    $sql = "SELECT loginName FROM Member 
              WHERE loginName='$_POST[fusername]'";
    $result = mysqli_query($cxn,$sql)
                or die("Query died: fusername: ".mysqli_error($cxn));
    $num = mysqli_num_rows($result);                     #19
    if($num > 0)  //login name was found                 #20
    {
      $sql = "SELECT loginName FROM Member 
              WHERE loginName='$_POST[fusername]'
              AND password=md5('$_POST[fpassword]')";
      $result2 = mysqli_query($cxn,$sql)
                   or die("Query died: fpassword");      #26
      $num2 = mysqli_num_rows($result2);                 #27
      if($num2 > 0)  //password matches                  #28
      {
        $_SESSION['auth']="yes";                         #30
        $_SESSION['logname'] = $_POST['fusername'];      #31
        $sql = "INSERT INTO Login (loginName,loginTime)
                VALUES ('$_SESSION[logname]',NOW())";
        $result = mysqli_query($cxn,$sql)
                   or die("Query died: insert");         #35
        header("Location: SecretPage.php");              #36
      }
      else  // password does not match                   #38
      {
        $message_1="The Login Name, '$_POST[fusername]' 
                exists, but you have not entered the 
                correct password! Please try again.";
        $fusername = strip_tags(trim($_POST['fusername']));
        include("login_form.inc");
      }                                                  #45
    }                                                    #46
    else  // login name not found                        #47
    {
      $message_1 = "The User Name you entered does not 
                    exist! Please try again.";
      include("login_form.inc");
    }
  break;                                                 #53

  case "Register":                                       #55
   /* Check for blanks */
    foreach($_POST as $field => $value)                  #57
    {
      if($field != "fax")                                #59
      {
        if(empty($value))
        {
          $blanks[] = $field;
        }
        else
        {
          $good_data[$field] = strip_tags(trim($value));
        }
      }
    }                                                    #70
    if(isset($blanks))                                   #71
    {
      $message_2 = "The following fields are blank. 
            Please enter the required information:  ";
      foreach($blanks as $value)
      {
        $message_2 .="$value, ";
      }
      extract($good_data);                               #79
      include("login_form.inc");
      exit();                                            #81
    }                                                    #82
  /* validate data */
    foreach($_POST as $field => $value)                  #84
    {
      if(!empty($value))                                 #86
      {
        if(preg_match("/name/i",$field) and
          !preg_match("/user/i",$field) and 
          !preg_match("/log/i",$field))
        {
          if (!preg_match("/^[A-Za-z' -]{1,50}$/",$value)) 
          {
             $errors[] = "$value is not a valid name. "; 
          }
        }
        if(preg_match("/street/i",$field) or 
           preg_match("/addr/i",$field) or
           preg_match("/city/i",$field))
        {
          if(!preg_match("/^[A-Za-z0-9.,' -]{1,50}$/", 
                          $value))
          {
            $errors[] = "$value is not a valid address 
                          or city.";
          }
        }
        if(preg_match("/state/i",$field))
        {
          if(!preg_match("/^[A-Z][A-Z]$/",$value))
          {
            $errors[] = "$value is not a valid state code.";
          }
        }
        if(preg_match("/email/i",$field))
        {
          if(!preg_match("/^.+@.+\\..+$/",$value))
          {
            $errors[]="$value is not a valid email address.";
          }
        }
        if(preg_match("/zip/i",$field))
        {
          if(!preg_match("/^[0-9]{5}(\-[0-9]{4})?$/",$value))
          {
            $errors[] = "$value is not a valid zipcode. ";
          }
        }
        if(preg_match("/phone/i",$field) or 
           preg_match("/fax/i",$field))
        {
          if(!preg_match("/^[0-9)(xX -]{7,20}$/",$value))
          {
            $errors[]="$value is not a valid phone number.";
          }
        }
      } // end if not empty                             #138
    } 
    foreach($_POST as $field => $value)                 #140
    {
      $$field = strip_tags(trim($value));
    }
    if(@is_array($errors))                              #144
    { 
      $message_2 = "";
      foreach($errors as $value)
      {
        $message_2 .= $value." Please try again<br />";
      }
      include("login_form.inc");
      exit();
    } // end if errors are found                        #153

   /* check to see if user name already exists */
    include("dogs.inc");                                #156
    $cxn = mysqli_connect($host,$user,$passwd,$dbname)
             or die("Couldn't connect to server");
    $sql = "SELECT loginName FROM Member 
                WHERE loginName='$loginName'";          #160
    $result = mysqli_query($cxn,$sql)
                or die("Query died: loginName.");
    $num = mysqli_num_rows($result);                    #163
    if($num > 0)                                        #164
    {
      $message_2 = "$loginName already used. Select 
                       another User Name.";
      include("login_form.inc");
      exit();
    } // end if user name already exists
    else // Add new member to database                  #171
    {   
      $sql = "INSERT INTO Member (loginName,createDate,
                password,firstName,lastName,street,city,
                state,zip,phone,fax,email) VALUES
              ('$loginName',NOW(),md5('$password'),
               '$firstName', '$lastName','$street','$city',
               '$state','$zip','$phone','$fax','$email')";
      mysqli_query($cxn,$sql);                          #179
      $_SESSION['auth']="yes";                          #180
      $_SESSION['logname'] = $loginName;                #181
      /* send email to new Customer */
      $emess = "You have successfully registered. ";
      $emess .= "Your new user name and password are: ";
      $emess .= "\n\n\t$loginName\n\t";
      $emess .= "$password\n\n";
      $emess .= "We appreciate your interest. \n\n";
      $emess .= "If you have any questions or problems,";
      $emess .= " email service@ourstore.com";          #189
      $subj = "Your new customer registration";         #190
     # $mailsend=mail("$email","$subj","$emess");       #191
      header("Location: New_member.php");               #192
    } 
  break;                                                #194

  default:                                              #195
    include("login_form.inc");
}  
?>
