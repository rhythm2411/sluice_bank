<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="transferCSS.css">
</head>
<body>
	<form action="action_page.php">
	  <div class="container">
	    <h1>Transfer Amount</h1>
	    <p>Please fill in these details</p>
	    <hr>

	    <label for="sen-name"><b>Sender's Name:</b></label>
	    <input type="text" placeholder="Enter your Full Name" name="sen-name" required>

	    <label for="rec-name"><b>Receiver's Name:</b></label>
	    <input type="text" placeholder="Enter receiver's name" name="rec-name" required>

	    <label for="amt"><b>Amount</b></label>
	    <input type="number" placeholder="Enter Amount" name="amt" required>

	    <!-- <label for="password-repeat"><b>Account Number</b></label>
	    <input type="password" placeholder="Repeat Password" name="password-repeat" required> -->
	    <hr>

	    
	    <button type="submit" class="RegisterButton">Transfer</button>
	  </div>
	</form> 
</body>
</html>