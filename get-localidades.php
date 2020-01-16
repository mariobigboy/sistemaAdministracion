<?php
include 'inc/config.php';
if($_POST['id'])
{
	$id = $_POST['id'];
	
	$statement = $pdo->prepare("SELECT * FROM tbl_localidad WHERE l_p_id=?");
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	?><option value="">Seleccione Localidad</option><?php						
	foreach ($result as $row) {
		?>
        <option value="<?php echo $row['l_id']; ?>"><?php echo $row['l_name']; ?></option>
        <?php
	}
}