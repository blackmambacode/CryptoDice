<?php require_once 'config.php'; ?>
<?php require_once 'constants.php'; ?>
<?php require_once 'calculation_utils.php'; ?>
<?php require_once 'audit.php'; ?>
<?php require_once 'check_db_ver.php';?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($config['name'] . ' - ' . $config['title']) ?></title>
	<meta name="keywords" content="<?php echo($config['name'] . ',' . $config['val']) ?>,ponzi,pyramid scheme,pyramid,cryptocoin,bitcoin">
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/bootstrap.min.css">
	<link rel="stylesheet" href="assets/style.css">
	<script src="assets/jquery-1.11.0.min.js"></script>
	<meta name="viewport" content="width=none, initial-scale=1">
</head>
<body>
	<div class="container" style="background: white; margin-bottom: 40px;">
		<div id="header">
			<?php echo($config['name'] . ' - ' . $config['title']) ?>
		</div>
		<ul class="nav nav-tabs" style="margin-left: 4px; width: 99%;">
			<li id="header-play"><a href="index.php">Play!</a></li>
			<li id="header-audit"><a href="index_audit.php">Audit</a></li>
			<li id="header-table" class="active"><a href="audit_table.php">Raw Audit data</a></li>
			<li><a href="<?php echo($config['blockchain-addr'] . $config['address']) ?>">Transactions</a></li>
		</ul>
<div class="jumbotron" style="text-align: center;">
  <h1><?php echo $config['full-name'] ?></h1>
  <p>Export all past transactions as table.</p>

  <?php if($show_version_msg): ?>
  <span class="label label-warning" id="wrongversion"><strong>Attention:</strong> The database version does not match the code version. Please run "php update_db.php".</span>
  <?php endif; ?>
	
</div>

<div class="jumbotron" style="text-align: center; padding: 20px; overflow: auto !important;">	
	<h2>Last transactions</h2>
	<h6 style="text-align: center; color: rgb(200,200,200)">Last 20 transactions, for a full table, please <a href="audit_csv.php">export the CSV file</a>.</h6>
	<div class="table-responsive">
		<table class="table table-hover table-striped">
			<thead>
				<tr>
<?php 
//$query = mysql_query('SELECT * FROM `transactions` LIMIT 1;');
//$row = mysql_fetch_assoc($query);
$keys = array('tx','block','out','address','amount','topay','actually_paid','date','out_date','state','secret','pot_fee','fee','version');
//$keys = array_keys($row);
echo "<td ></td>";
foreach ($keys as $key) {
	echo "<td >" . $key . "</td>";
}
?>
				</tr>
			</thead>
			<tbody id="trans">
<?php
$query = mysql_query('SELECT * FROM `transactions` ORDER BY id DESC, state DESC LIMIT 20;');
while($row = mysql_fetch_assoc($query))
{
	$audit = audit_single_row($row);
	echo "<tr>";
	echo '<td><span class="label label-' . (($audit == 'SUCCESS')?'success':'warning') . 
			'" id="collecting">' . $audit . '</span></td>';
	foreach ($keys as $key) {
		echo "<td >" . $row[$key] . "</td>";
	}
	echo "</tr>";
}
?>
			</tbody>
		</table>
	</div>
</div>

<script>
// sets this tab as selected
	$('#header-table').addClass("active");
</script>

<?php include('footer.php'); ?>
