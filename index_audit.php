<?php include('header.php'); ?>
<?php include('constants.php'); ?>
<?php require_once 'check_db_ver.php';?>

<div class="jumbotron" style="text-align: center;">
  <h1><?php echo $config['full-name'] ?></h1>
  <p>Audit all past transactions.</p>

  <?php if($show_version_msg): ?>
  <span class="label label-warning" id="wrongversion"><strong>Attention:</strong> The database version does not match the code version. Please run "php update_db.php".</span>
  <?php endif; ?>
	
</div>

<div class="jumbotron" style="text-align: center; padding: 20px;">	
	<h2>Last transactions</h2>
	<h6 style="text-align: center; color: rgb(200,200,200)">See (<a href="audit_table.php">html</a>) or export (<a href="audit_csv.php">csv</a>) the raw data for external audit.</h6>
	<div class="table-responsive">
		<table class="table table-hover table-striped">
			<thead>
				<tr>
					<td style="width: 6%;"></td>
					<td style="width: 34%">Transaction</td>
					<td style="width: 20%">Amount</td>
					<td style="width: 20%">Prize</td>
					<td style="width: 15%">Date</td>
				</tr>
			</thead>
			<tbody id="trans"></tbody>
		</table>
	</div>
</div>

<script>

// sets this tab as selected
	$('#header-audit').addClass("active");

	var what = "all";
	
	function search()
	{
		what = $('#tid').val();
		update();
	}
	
	function showall()
	{
		what = "all";
		update();
	}
	
	function update()
	{
		$.get(
				"json_audit.php",
				{
					what: what
				},
				function(data){
					data = JSON.parse(data);

					$('#trans').html('');
					for(var i in data['transactions'])
					{
						var state = "";
						if (data['transactions'][i]['audit'] == "SUCCESS")
							state = '<span class="label label-success" id="collecting">Ok!</span>';
						else 
							state = '<span class="label label-warning" id="collecting">' + data['transactions'][i]['audit']  + '</span>';
					
						$tr = $('<tr></tr>');
						$('#trans').append($tr);
					
						$td = $('<td>' + state + '</td>');
						$($tr).append($td);
						
						var tx = "";
						if (data['transactions'][i]['tx'])
							tx = 'IN: <a href="<?php echo $config['blockchain-tx'] ?>' + data['transactions'][i]['tx'] + '">' + data['transactions'][i]['tx'].substring(0,18) + '...</a>';
													
						var out = "";
						if (data['transactions'][i]['out']) {
							if (data['transactions'][i]['tx']) 
								out = "<br>";
							out = out + 'OUT: <a href="<?php echo $config['blockchain-tx'] ?>' + data['transactions'][i]['out'] + '">' + data['transactions'][i]['out'].substring(0,18) + '...</a>';
						}

						$td = $('<td style="text-align: left;">' + tx + out + '</td>');
						$($tr).append($td);
						
						$td = $('<td>' + parseFloat(data['transactions'][i]['amount']).toFixed(<?php echo $config['precision'] ?>) + ' <?php echo $config['val'] ?></td>');
						$($tr).append($td);
						
						$td = $('<td>' + parseFloat(data['transactions'][i]['topay']).toFixed(<?php echo $config['precision'] ?>) + ' <?php echo $config['val'] ?></td>');
						$($tr).append($td);
					
						$td = $('<td>' + data['transactions'][i]['date'] + '</td>');
						$($tr).append($td);
						
						if (data['transactions'].length == 1)
						{
							$tr = $('<tr></tr>');
							$('#trans').append($tr);
							
							$td = $('<td colspan="4">In queue, actual position: <span class="label label-info">' + data['transactions'][i]['queue'] + '</span></td>');
							$($tr).append($td);
						}
					}
					
					if (data['transactions'].length == 0)
					{
						$tr = $('<tr></tr>');
						$('#trans').append($tr);
						
						$td = $('<td colspan="4">No transactions found.</td>');
						$($tr).append($td);
					}
					
					setTimeout(update, 15 * 1000);
				}
		);
	}
	update();
</script>

<?php include('footer.php'); ?>
