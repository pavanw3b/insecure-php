<?php
require_once('includes/constants.php');
require_once ('includes/functions.php');

// If user not authenticated, logout
if(!isAuthenticated()) {
    header("Location: index.php");
}


$error_msg = "";
$success = false;

if(isset($_POST["submit"])) {
    $name = $_POST['name'];
    $email_address = $_POST['email_address'];
    $location = $_POST['location'];
    $since = $_POST['since'];
    $contact = $_POST['contact'];
    $gst_no = $_POST['gst_no'];
    $name = $_POST['name'];

    if(empty($name))
      $error_msg .="Customer Name can't be empty. ";

    if(empty($email_address))
      $error_msg .="Customer email can't be empty. ";

    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if($connection->connect_error) {
        die("Connection failed:" . $connection->connect_error);
    }

    $statement = $connection->prepare("SELECT ID from customer where name=?");
    $statement->bind_param("s", $name);
    $statement->execute();
    $statement->bind_result($id);
    
    if($statement->fetch()) {
        $error_msg .= "A Customer already exists with the same name. ";
    }

    $statement->close();
    $statement = $connection->prepare("SELECT ID from customer where email_address=?");
    $statement->bind_param("s", $email_address);
    $statement->execute();
    $statement->bind_result($id);

    if($statement->fetch()) {
        $error_msg .= "A Customer already exists with the same email address. ";
    } else {

        $query = "INSERT INTO customer(name,email_address,location,contact_number,since,GST_NO) VALUES('".mysqli_real_escape_string($connection, $name)."','".mysqli_real_escape_string($connection, $email_address)."','".mysqli_real_escape_string($connection, $location)."','".mysqli_real_escape_string($connection, $contact)."','".$since."','".mysqli_real_escape_string($connection, $gst_no)."')";
        
        $result = mysqli_query($connection, $query) or die(mysqli_error($con) . "<br />The query is<br />" . $query);
        $success = "A new Customer created with ID: ".$connection->insert_id;
    }


}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Create a new Customer<?php echo $TITLE; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php require_once('includes/sidebar.php'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>


            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hey <?php echo $_SESSION['name']; ?></span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
              
              <h1 class="h4 text-gray-900 mb-4">Create a new Customer</h1>
              
              <form class="user" id="registration_form" method="post">
                  <div class="alert alert-success" id="success_msg"
                      <?php
                        if("" != $success) {
                                echo ">". $success;
                        } else {
                            echo ' style="display:none;">';
                        }
                      ?>
                  </div>
                  <div class="alert alert-danger" id="error_msg"
                      <?php
                          if( $error_msg != "") {
                              echo "> ".$error_msg;
                          } else {
                              echo ' style="display:none;">';
                          }
                      ?>
                  </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="Customer Full Name"
                        <?php if(isset($_POST['name'])) echo $_POST['name']; ?>
                    >
                  </div>
                  <div class="col-sm-6">
                    <input type="email" class="form-control form-control-user" name="email_address" id="email_address"
                      <?php if(isset($_POST['email_address'])) echo $_POST['email_address']; ?>
                         placeholder="Customer Email address" title="tooltip">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" name="location" id="location" placeholder="Customer City">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" name="contact" id="contact" placeholder="Customer Contact number">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="number" class="form-control form-control-user" name="since" id="since" placeholder="Customer Since (Year)">
                  </div>
                  <div class="col-sm-6">
                    <input type="number" class="form-control form-control-user" name="gst_no" id="gst_no" placeholder="Customer GST number">
                  </div>
                </div>                
                <input type="submit" name="submit" class="btn btn-primary btn-user btn-block" value="Create Customer"/>
              </form>
          </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
     <?php require_once ('includes/footer_end.php'); ?>