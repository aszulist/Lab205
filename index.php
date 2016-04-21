<?php
	include_once('script.php');
?>
<html>
<head>
<title>Lab 205</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="js/jquery-2.2.3.min.js"></script>
</head>
<body>
	<div class="content">
		<form method="POST">
			<label name="p_value">P</label>
			<input type="text" name="p_value" />
			<label name="q_value">Q</label>
			<input type="text" name="q_value" />
			<label name="message">Message</label>
			<input type="text" name="message" />
			<input type="submit" name="load_values" value="Load">
		</form>
		<?php 
			renderData();
		?>
		<p>Which P/Q to use for my agent number? Use the form below :) </p>
		<label name="agent_value">Agent number</label>
		<input class="agent_value" type="text" name="agent_value" />
		<button onClick="getAgentColumnNumber()">Get number</button>
	</div>		
	<script type="text/javascript">
		function getAgentColumnNumber(){
			
			var p_array = [11, 13, 17, 19, 11, 13, 17, 19, 11, 13, 17, 19, 11, 13, 17, 19];
			var q_array = [109, 97, 97, 97, 101, 101, 101, 101, 103, 103, 103, 103, 107, 107, 107, 107];
			
			var agent_number = $('.agent_value').val();
			
			if(agent_number.length > 0 && $.isNumeric(agent_number)){
				var column_number = agent_number % 16;
				insertDataInfoForm(p_array[column_number], q_array[column_number]);
			} else {
				alert('You must put number in field!');
			}
		}
		
		function insertDataIntoForm(p, q){
			$('input[name=p_value]').val(p);
			$('input[name=q_value]').val(q);
		}
	</script>
</body>
</html>