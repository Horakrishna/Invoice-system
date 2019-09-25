<?php
    include('database_connection.php');

    $statement =$connect->prepare("
      SELECT * FROM tbl_order ORDER BY order_id DESC
    ");
    $statement->execute();
    $all_result =$statement->fetchAll();
    $total_rows =$statement->rowCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice System in PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/glyphicon.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js"></script>
 
</head>
<body>

    <script src="js/bootstrap-datepicker.min.js"></script>
    <script>
      $(document).ready(function(){
        $('#order_date').datepicker({
          format: "yyyy-mm-dd",
          autoclose: true
        });
      });
    </script>

<!--Header Area Start-->
    <div class="container-fluid">
        <form action="" id="invoice_form">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td colspan="2" align=center><h2>Create Invoice</h2></td>
              </tr>
              <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-8">
                      To,</br>
                        <b>Receiver(Bill to)</b></br>
                        <input type="text" name="order_receiver_name" class="form control input-sm" placeholder="Enter Reciever name"></br></br>
                        <textarea name="order_receiver_address" id="order_receiver_address" class="form-control"placeholder="Enter Billing Address"></textarea>
                    </div>
                    <div class="col-md-4">
                      <h2>Reverse Charge </h2>
                      <input type="text" name="order_no" id="order_no" class="form-control input-sm" placeholder="Enter Invoice No." /></br>
                      <input type="text" name="order_date" id="order_date" class="form-control input-sm" readonly placeholder="Select Invoice Date" /> 

                    </div>
                  </div></br>
                  <table id="invoice-item-table" class="table table-bordered">
                      <tr>
                        <th width="7%">Sr No</th>
                        <th width="20%">Item Name</th>
                        <th width="5%">Quantity</th>
                        <th width="5%">Price</th>
                        <th width="10%">Actual Amt.</th>
                        <th width="12.5%" colspan="2">Tax1(%)</th>
                        <th width="12.5%" colspan="2">Tax2(%)</th>
                        <th width="12.5%" colspan="2">Tax3(%)</th>
                        <th width="12.5%" rowspan="2">Total</th>
                       
                      </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>Rate</th>
                          <th>Amt.</th>
                          <th>Rate</th>
                          <th>Amt.</th>
                          <th>Rate</th>
                          <th>Amt.</th>
                        </tr>

                        <tr>
                          <td><span id="sr_no">1</span></td>
                          <td><input type="text" name="item_name" id="item_name1" class="form-control input-sm/"></td>
                          <td><input type="text" name="order_item_quantity[]" id="order_item_quantity1" data-srno="1" class="form-control input-sm order_item_quantity"></td>

                          <td><input type="text" name="order_item_price[]" id="order_item_price1" data-srno="1" class="form-control input-sm number-only order_item_price"></td>
                          
                          <td><input type="text" name="order_item_actual_amount[]" id="order_item_actual_amount1" data-srno="1" class="form-control input-sm  order_item_actual_amount"></td>
                          <td><input type="text" name="order_item_tax1_rate[]" id="order_item_tax1_rate1" data-srno="1" class="form-control input-sm number-only order_item_tax1_rate"></td>
                          <td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount1" data-srno="1" readonly class="form-control input-sm  order_item_tax1_amount"></td>
                          <td><input type="text" name="order_item_tax2_rate[]" id="order_item_tax1_rate1" data-srno="1" class="form-control input-sm number-only order_item_tax2_rate"></td>
                          <td><input type="text" name="order_item_tax2_amount[]" id="order_item_tax2_amount1" data-srno="1" readonly class="form-control input-sm  order_item_tax1_amount"></td>
                          <td><input type="text" name="order_item_tax3_rate1[]" id="order_item_tax3_rate1" data-srno="1" class="form-control input-sm number-only order_item_tax3_rate1"></td>
                          <td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount1" data-srno="1" readonly class="form-control input-sm  order_item_tax1_amount"></td>
                          <td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount1" data-srno="1" readonly class="form-control input-sm  order_item_final_amount"></td>
                        </tr>
                  </table>
                  <div align="right">
                    <button type="button" name="add_row" id="add_row" class="btn btn-success btn-xs">+</button>
                  </div>
                </td>
              </tr>
          
          <tr>
               <td align="right"><b>Total</td>
                 <td align="right"><b><span id="final_total_amt"></span></b></td>
                    </tr>
                    <tr>
                      <td colspan="2"></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center">
                        <input type="hidden" name="total_item" id="total_item" value="1" />
                        <input type="submit" name="create_invoice" id="create_invoice" class="btn btn-info" value="Create" />
                      </td>
                    </tr>
          </table>
          </div>       
        </form>
    </div>


<!--Invoice list -->
<div class="container-fluid">
    <h3 align="center">Invoice List</h3>
    <table id="data-table" class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>Invoice No.</th>
              <th>Invoice Date</th>
              <th>Reciver Name</th>
              <th>Total Invoice</th>
              <th>PDF</th>
              <th>Edit</th>
              <th>Delete</th>
          </tr>
      </thead>  
          <tr>
            <td>12</td>
            <td>29/018/2019</td>
            <td>Hora KRishna</td>
            <td>12345</td>
            <td><a href="">PDF</a></td>
            <td><a href=""><span class="glyphicon glyphicon-edit danger"></span></a></td>
            <td><a href=""><span class="glyphicon glyphicon-remove"></span></a></td>
          </tr>
    </table>
</div>
<!--Header Area End-->


<!--Footer Area Start-->
<footer class="container-fluid text-center">
      <p>Footer Text</p>
</footer>
<!--Footer Area End-->
 </body>   
</html>
