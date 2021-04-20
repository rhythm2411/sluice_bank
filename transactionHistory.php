<?php 
    require_once('connection.php');
    session_start();
    $sql = "SELECT * FROM `transactions`";
    $result = $conn->query($sql);
    ?>


<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<?php if($result->num_rows > 0){ ?>
        <h1>Transaction History</h1>
        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Transaction ID</th>
                    <th>Sender's Account Number</th>
                    <th>Recipient's Account Number</th>
                    <th>Amount</th>
                    <th>Timestamp</th>  
                </tr>
            </thead>
            <tbody>
                <?php 
                    $serial_no = 1;
                    while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?php echo $serial_no; ?></td>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['sender_acc_num']; ?></td>
                    <td><?php echo $row['recipient_acc_num']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
                <?php $serial_no++; 
                 }
                 } ?>

</body>
</html>

<?php session_destroy(); ?>