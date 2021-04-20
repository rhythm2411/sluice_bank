<?php 
    session_start();
    require_once("connection.php");


    if(isset($_SESSION['transaction_successfull'])){
        header('Location: transactionHistory.php');
        exit;
    }

    //variable declaration
    $control = 0;
    $recipient_account_num_error = '';
    $name_error = '';
    $amount_error = '';
    $name = '';
    $recipient_account_num = '';

    function filterText($str){
        $str = strip_tags($str);
        $str = trim($str);
        $str = addslashes($str);
        return $str;
    }
    //Getting the customer_id from the home page, the customer's data is taken out to be displayed
    if(isset($_GET['c_id'])){
        $customer_id = $_GET['c_id'];
        $sql = "SELECT name, account_num, current_balance FROM customers WHERE customer_id='$customer_id'";
        $result = $conn->query($sql);
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $sender_name = $row['name'];
            $sender_acc_num = $row['account_num'];
            $sender_balance = $row['current_balance'];
        }
        else {
            header('Location: index.php');
            $_SESSION['message'] = 'Invalid customer ID';
            $_SESSION['message-css'] = 'error-msg';
            exit;
        }
    }
    //if the customer_id is wrong, the client is redirected back to the home page
    else{
        $_SESSION['message'] = 'Invalid customer ID';
        $_SESSION['message-css'] = 'error-msg';
        header('Location: index.php');
        exit;
    }

    if(isset($_POST["name"]) && isset($_POST["account_num"]) && isset($_POST["amount"])){
        $control = 1;
        $name = filterText($_POST["name"]);
        $recipient_account_num = filterText($_POST["account_num"]);
        $amount = filterText($_POST["amount"]);
        if($name==''){
            $name_error = 'Name required';
            $control = 0;
        }
        if($recipient_account_num==''){
            $recipient_account_num_error = 'Account number required';
            $control = 0;
        }
        //if recipient's account number doesn't exist
        else{
            $sql = "SELECT * FROM customers WHERE name='$name' AND account_num='$recipient_account_num'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if($result->num_rows == 0){
                $control = 0;
                $recipient_account_num_error = 'Invalid credentials';
            }
            else $recipient_balance = $row['current_balance'];
        }
        
        if($sender_acc_num==$recipient_account_num){
            $recipient_account_num_error = 'Cannot transfer money to own account';
            $control = 0;
        }
        
        if($amount==''){
            $amount_error = 'Amount required';
            $control = 0;
        }
        else if($amount<0){
            $control = 0;
            $amount_error = 'Invalid amount';
        }
        else if($amount>$sender_balance){
            $control = 0;
            $amount_error = 'Insufficient balance';
        }

        if($control){

            //updating sender and recipient balance
            $new_sender_bal = $sender_balance - $amount;
            $new_recipient_bal = $recipient_balance + $amount;
            $sql = "UPDATE `customers` SET current_balance='$new_sender_bal' WHERE account_num='$sender_acc_num'";
            $conn->query($sql);

            $new_sender_bal = $sender_balance-$amount;
            $sql = "UPDATE `customers` SET current_balance='$new_recipient_bal' WHERE account_num='$recipient_account_num'";
            $conn->query($sql);

            //sending transaction details to the transaction table
            $sql = "INSERT INTO `transactions` (`transaction_id`, `sender_acc_num`, `recipient_acc_num`, `amount`,  `timestamp`) VALUES (NULL, '$sender_acc_num', '$recipient_account_num', '$amount', current_timestamp())";
            $conn->query($sql);

			 //after the transaction is complete, the details for the reciept are sent to the reciept page by session
			 $sql = "SELECT * FROM transactions ORDER BY transaction_id DESC LIMIT 0, 1";
			 $result = $conn->query($sql);
			 $row = $result->fetch_assoc();
			 $_SESSION['transaction_successfull'] = true;
			 $_SESSION['success_msg'] = 'Transaction successful';
			 $_SESSION['sender_account_num'] = $sender_acc_num;
			 $_SESSION['recipient_account_num'] = $recipient_account_num;
			 $_SESSION['amount'] = $amount;
			 $_SESSION['transaction_id'] = $row['transaction_id'];
			 $_SESSION['transaction_time'] = $row['timestamp'];
			 $_SESSION['reciept-working'] = 1;

			$_SESSION['transaction_successfull'] = true;
			header('Location: transactionHistory.php');
            exit;
            
        }


    }


?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="transferCSS.css">
</head>
<body>
	
	  <div class="container">
	    <h1>Transfer Amount</h1>
	    <p>Please fill in these details</p>
	    <hr>

	    <label for="sen-name"><b>Sender's Name:</b></label>
		<div><?php echo $sender_name; ?></div>

		<label for="sen-acc"><b>Sender's Account Number:</b></label>
	    <div><?php echo $sender_acc_num; ?></div>


	<form action="" method="POST">
	    <label for="name"><b>Receiver's Name:</b></label>
	    <input type="text" placeholder="Enter receiver's name" name="name" >
		<div class="error">
			<?php echo $name_error; ?>
		</div>

		<label for="account_num"><b>Receiver's Account Number:</b></label>
	    <input type="text" placeholder="Enter receiver's account number" name="account_num" >
		<div class="error">
			<?php echo $recipient_account_num_error; ?>	
		</div>

	    <label for="amount"><b>Amount</b></label>
	    <input type="number" placeholder="Enter Amount" name="amount">
		<div class="error">
			<?php echo $amount_error; ?>
		</div>

	    <!-- <label for="password-repeat"><b>Account Number</b></label>
	    <input type="password" placeholder="Repeat Password" name="password-repeat" required> -->
	    <hr>

	    
	    <button type="submit" class="RegisterButton">Transfer</button>
	  </div>
	</form> 
</body>
</html>