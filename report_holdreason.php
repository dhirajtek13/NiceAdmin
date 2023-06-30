<?php
 include('layout/head.php');
 
       //Middleware: check user type and restrict access 
       if(isset($_SESSION) && $_SESSION['user_type'] != 1) {
         header("location: pages-ticket.php");
         exit;
       }
?>

<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


<main id="main" class="main">

<div class="pagetitle">
  <div><h1>Report - Hold tickets with Reason </h1></div>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <table  id="dataList" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket Id</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                    <th scope="col">Reason</th>
                </tr>
            </thead>
            <tfoot style="display:table-header-group">
                <tr>
                    <th>S.N</th>
                    <th>Ticket Id</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Reason</th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</section>

</main><!-- End #main -->

<?php include('layout/footer.php'); ?>
<script src="assets/js/report_holdreason-table.js"></script>
<script>
 
</script>

</body>

</html>