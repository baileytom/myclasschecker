<!DOCTYPE html>
<html lang='en'>

  <head>
    <title><?php if(isset($pgName) && is_string($pgName)){echo $pgName;}else{echo 'Default title';} ?></title>
    <!-- META TAGS -->
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
    <meta charset='utf-8'>
    <meta name='description' content="myclasschecker will notify you when a class you're interested in is open."/>
    <meta name='keywords' content="gvsu,classchecker,coursicle,myclasschecker,class,checker,grand,valley,state,university,grand valley,course"/>
    <!-- BOOTSTRAP --> 
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js'></script>
    <!-- CSS -->
    <link rel='stylesheet' type='text/css' href='css/styles.css'>
    <link rel="shortcut icon" href="/favicon.png" type="image/png">
    <link rel="icon" href="/favicon.png" type="image/png">
  <?php include_once("includes/analytics.php");?>
  </head>
  

  <body>

  <!-- NAV BAR -->
  <nav class='navbar navbar-default'>
    <div class='container-fluid'>
      <div class='navbar-header'>
        <a class='navbar-brand' href='http://myclasschecker.com'>myclasschecker</a>
      </div>
      <ul class='nav navbar-nav'>
        <?php
          $currentpage = basename($_SERVER['PHP_SELF']);

          $pages = array(
        // 'http://myclasschecker.com' => 'Home',
          'login.php' => 'Log in',
          'signup.php' => 'Sign up');

          foreach ($pages as $pagestring => $text) {
            $active = ($pagestring == $currentpage? ' class="active"' : '');
            echo '<li' . $active . "><a href='" . $pagestring . "'>" . $text . '</a></li>';
          }
        ?>
      </ul>
    </div>
  </nav>

  <!-- MAIN PAGE AREA -->
  
  <div class='container'>
