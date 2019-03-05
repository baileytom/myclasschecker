<!DOCTYPE html>
<html lang='en'>

  <head>
    <title><?php if(isset($pgName) && is_string($pgName)){echo $pgName;}else{echo 'Default title';} ?></title>
    <!-- META TAGS -->
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
    <meta charset='utf-8'>
    <!-- BOOTSTRAP --> 
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js'></script>
    <!-- CSS -->
    <link rel='stylesheet' type='text/css' href='css/styles.css'>
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">
    <link rel="icon" href="/favicon.png" type="image/png">
  </head>
  

  <body>
  <?php include_once("includes/analytics.php");?>
  <!-- NAV BAR -->
  <nav class='navbar navbar-default'>
    <div class='container-fluid'>
      <div class='navbar-header'>
        <a class='navbar-brand' href='http://myclasschecker.com/home.php'>myclasschecker</a>
      </div>
      <ul class='nav navbar-nav'>
        <?php
          $currentpage = basename($_SERVER['PHP_SELF']);

          $pages = array(
          'home.php' => 'Classes',
          'account.php' => 'Other',
          #'contact.php' => 'Contact',
          #'about.php' => 'About',
          'logout.php' => 'Log out');


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
