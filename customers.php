<?php
    //connecting to db
    session_start();
    require_once('connection.php');

    //PHP script to take data out of db
    $sql = "SELECT * FROM `sluice bank`.`customers`";
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

<h2>CUSTOMERS</h2>

<table>
  <tr>
    <th>S. No.</th>
    <th>Name</th>
    <th>Email</th>
    <th>Address</th>
    <th>Contact</th>
    <th>Account number</th>
    <th>Balance</th>
    <th>Transaction</th>
  </tr>
  <?php 
        $i = 1;
        while($row = $result->fetch_assoc()){
    ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['contact_num']; ?></td>
            <td><?php echo $row['account_num']; ?></td>
            <td><?php echo $row['current_balance']; ?></td>
            <!-- Link to send customer_id to the transaction page -->
            <td><a href="transfer.php?c_id=<?php echo $row['customer_id']; ?>" target="_blank">Tranfer money</a></td>
        </tr>
    <?php $i++; } ?>
</table>

</body>
</html>