<?php
require_once('includes/constants.php');
require_once ('includes/functions.php');

// If user not authenticated, logout
if(!isAuthenticated()) {
    header("Location: index.php");
}


$error_msg = "";

if(isset($_POST["customer_id"])) {
    $keyword = $_POST['customer_id'];

    if(0 < strpos($keyword, ' ')) {
      $error_msg = "Please a valid Customer ID";
    } else {
      $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
      if($connection->connect_error) {
          die("Connection failed:" . $connection->connect_error);
      } else {
        $query = "SELECT id, name, email_address, location, contact_number, since, GST_NO from customer where id='" . $keyword ."'";
        $result = mysqli_query($connection, $query) or die(mysqli_error($con) . "<br />The query is<br />" . $query);  
      }
      
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

  <title>Search Customers by ID<?php echo $TITLE; ?></title>

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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Customers</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <form class="search" method="post">
                              <div class="alert alert-danger" id="error_msg"
                                  <?php
                                  if(isset($_POST["customer_id"]) && $error_msg != "") {
                                      echo "> ".$error_msg;
                                  } else {
                                      echo ' style="display:none;">';
                                  }
                                  ?>
                              </div>          
                              <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="customer_id"  placeholder="Search by Customer ID">
                              </div>
                              <button type="submit" class="btn btn-primary btn-user btn-block">
                                Search
                              </button>
                            </form>
                        </div>
                        <br />
                        <?php 
                        if ( isset($result) && 0 < $result->num_rows) { ?>
                          <div class="card-body">
                            <div class="table-responsive">
                                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <table class="table table-bordered dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                        <thead>
                                        <tr role="row">
                                          <th>ID</th>
                                          <th>Name</th>
                                          <th>Email Address</th>
                                          <th>Location</th>
                                          <th>Contact Number</th>
                                          <th>GST NO</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                      <?php while($row = $result->fetch_assoc()) { ?>
                                      <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email_address']; ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td><?php echo $row['contact_number']; ?></td>
                                        <td><?php echo $row['GST_NO']; ?></td>
                                      </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          </div>
                        </div>
                        <?php
                        } else if (isset($result) && 0 == $result->num_rows) {
                          echo "<div class=\"col-sm-12\">Your search found no result. Please try again with the full name of a customer. E.g.: Hyderabad Tech Engineering Services Limited.</div><br/>";
                        }                          
                   ?>                        
                    </div>
                </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
     <?php require_once ('includes/footer_end.php'); ?>