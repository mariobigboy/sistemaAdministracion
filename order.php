<?php require_once('header.php'); ?>

<?php

require '../assets/mail/PHPMailer.php';
require '../assets/mail/Exception.php';
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = "smtp.hostinger.com.ar";
$mail->Port = 587;
$mail->SMPTAuth = true; // There was a syntax error here (SMPTAuth)
$mail->Username = "info@email.com";
$mail->Password = "pass2017?";
$mail->setFrom('info@email.com', 'HomeDesign');


$error_message = '';
if(isset($_POST['form1'])) {
    $valid = 1;
    if(empty($_POST['subject_text'])) {
        $valid = 0;
        $error_message .= 'El Asunto no puede estar vacío\n';
    }
    if(empty($_POST['message_text'])) {
        $valid = 0;
        $error_message .= 'El Asunto no puede estar vacío\n';
    }
    if($valid == 1) {

        $subject_text = strip_tags($_POST['subject_text']);
        $message_text = strip_tags($_POST['message_text']);

        // Getting Customer Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=?");
        $statement->execute(array($_POST['cust_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $cust_email = $row['cust_email'];
            $cust_name = $row['cust_name'];
        }

        // Getting Admin Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $admin_email = $row['contact_email'];
        }

        $order_detail = '';
        $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
        	
        	if($row['payment_method'] == 'PayPal'):
        		$payment_details = '
Transacción Id: '.$row['txnid'].'<br>
        		';
        	elseif($row['payment_method'] == 'Stripe'):
				$payment_details = '
Transacción Id: '.$row['txnid'].'<br>
Tarjeta de Crédito Nº: '.$row['card_number'].'<br>
Tarjeta de Crédito CVV: '.$row['card_cvv'].'<br>
Mes de Vencimiento: '.$row['card_month'].'<br>
Año de Vencimiento: '.$row['card_year'].'<br>
        		';
        	elseif($row['payment_method'] == 'Bank Deposit'):
				$payment_details = '
Detalles de la Transacción: <br>'.$row['bank_transaction_info'];
        	endif;

            $order_detail .= '
Nombre del Cliente: '.$row['customer_name'].'<br>
Email Cliente: '.$row['customer_email'].'<br>
Método de Pago: '.$row['payment_method'].'<br>
Fecha del Pago: '.$row['payment_date'].'<br>
Detalles del Pago: <br>'.$payment_details.'<br>
Total: '.$row['paid_amount'].'<br>
Estado del Pago: '.$row['payment_status'].'<br>
Estado del Envío: '.$row['shipping_status'].'<br>
Id de Pago: '.$row['payment_id'].'<br>
            ';
        }

        $i=0;
        $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $i++;
            $order_detail .= '
<br><b><u>Producto '.$i.'</u></b><br>
Nombre del Producto: '.$row['product_name'].'<br>
Tamaño: '.$row['size'].'<br>
Color: '.$row['color'].'<br>
Cantidad: '.$row['quantity'].'<br>
Precio Unitario: '.$row['unit_price'].'<br>
            ';
        }

        $statement = $pdo->prepare("INSERT INTO tbl_customer_message (subject,message,order_detail,cust_id) VALUES (?,?,?,?)");
        $statement->execute(array($subject_text,$message_text,$order_detail,$_POST['cust_id']));

        // sending email
        $to_customer = $cust_email;
        $message = '
<html><body>
<h3>Mensaje: </h3>
'.$message_text.'
<h3>Detalles de la Órden: </h3>
'.$order_detail.'
</body></html>
';
        

        try {
            //aqui
            $mail->setFrom($admin_email, 'Admin');
            $mail->addAddress($to_customer, $cust_name);
            $mail->addReplyTo($admin_email, 'Admin');
            
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $mail->Body = $message;
            $mail->send();

            $success_message = 'El email fué enviado correctamente al cliente.';   
        } catch (Exception $e) {
            echo 'El mail no pudo ser enviado.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        
        

    }
}
?>
<?php
if($error_message != '') {
    echo "<script>alert('".$error_message."')</script>";
}
if($success_message != '') {
    echo "<script>alert('".$success_message."')</script>";
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Vista de Pedidos OnLine</h1>
	</div>
</section>


<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>#</th>
                    <th>Detalles del Cliente</th>
			        <th>Detalles del Producto</th>
                    <th>
                    	Información de Pago
                    </th>
                    <th>Total del Pedido</th>
                    <th>Estado del Pago</th>
                    <th>Estado del Envío</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_payment ORDER by id DESC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr class="<?php if($row['payment_status']=='Pending'){echo 'bg-r';}else{echo 'bg-g';} ?>">
	                    <td><?php echo $i; ?></td>
	                    <td>
                            <b>Id:</b> <?php echo $row['customer_id']; ?><br>
                            <b>Nombre:</b><br> <?php echo $row['customer_name']; ?><br>
                            <b>Email:</b><br> <?php echo $row['customer_email']; ?><br><br>
                            <a href="#" data-toggle="modal" data-target="#model-<?php echo $i; ?>"class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Enviar Mensaje</a>
                            <div id="model-<?php echo $i; ?>" class="modal fade" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title" style="font-weight: bold;">Enviar Mensaje</h4>
										</div>
										<div class="modal-body" style="font-size: 14px">
											<form action="" method="post">
                                                <input type="hidden" name="cust_id" value="<?php echo $row['customer_id']; ?>">
                                                <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
												<table class="table table-bordered">
													<tr>
														<td>Asunto</td>
														<td>
                                                            <input type="text" name="subject_text" class="form-control" style="width: 100%;">
														</td>
													</tr>
                                                    <tr>
                                                        <td>Mensaje</td>
                                                        <td>
                                                            <textarea name="message_text" class="form-control" cols="30" rows="10" style="width:100%;height: 200px;"></textarea>
                                                        </td>
                                                    </tr>
													<tr>
														<td></td>
														<td><input type="submit" value="Send Message" name="form1"></td>
													</tr>
												</table>
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>
                        </td>
                        <td>
                           <?php
                           $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                           $statement1->execute(array($row['payment_id']));
                           $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result1 as $row1) {
                                echo '<b>Nombre del Producto:</b> '.$row1['product_name'];
                                echo '<br>(<b>Tamaño:</b> '.$row1['size'];
                                echo ', <b>Color:</b> '.$row1['color'].')';
                                echo '<br>(<b>Cantidad:</b> '.$row1['quantity'];
                                echo ', <b>Precio Unitario:</b> '.$row1['unit_price'].')';
                                echo '<br><br>';
                           }
                           ?>
                        </td>
                        <td>
                            <?php 
                                $fecha = date_create($row['payment_date']);
                                $fecha_format = date_format($fecha, 'd/m/Y H:i:s');
                            ?>
                        	<?php if($row['payment_method'] == 'PayPal'): ?>
                        		<b>Método de Pago:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Id Pago:</b> <?php echo $row['payment_id']; ?><br>
                        		<b>Fecha:</b> <?php echo $fecha_format; //echo $row['payment_date']; ?><br>
                        		<b>Id Transacción:</b> <?php echo $row['txnid']; ?><br>
                        	<?php elseif($row['payment_method'] == 'Stripe'): ?>
                        		<b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Id Pago:</b> <?php echo $row['payment_id']; ?><br>
								<b>Fecha:</b> <?php echo $fecha_format;//echo $row['payment_date']; ?><br>
                        		<b>Id Transacción:</b> <?php echo $row['txnid']; ?><br>
                        		<b>Tarjeta de Crédito Nº:</b> <?php echo $row['card_number']; ?><br>
                        		<b>CVV:</b> <?php echo $row['card_cvv']; ?><br>
                        		<b>Mes Vencimiento:</b> <?php echo $row['card_month']; ?><br>
                        		<b>Año Vencimiento:</b> <?php echo $row['card_year']; ?><br>
                        	<?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                        		<b>Método de Pago:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Id Pago:</b> <?php echo $row['payment_id']; ?><br>
								<b>Fecha:</b> <?php echo $fecha_format;//echo $row['payment_date']; ?><br>
                        		<b>Información de Transacción:</b> <br><?php echo $row['bank_transaction_info']; ?><br>
                            <?php elseif($row['payment_method'] == 'MercadoPago'): ?>
                                <b>Método de Pago:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                <b>Id Pago:</b> <?php echo $row['payment_id']; ?><br>
                                <b>Fecha:</b> <?php echo $fecha_format;//echo $row['payment_date']; ?><br>
                                <b>Id Transacción:</b> <?php echo $row['txnid']; ?><br>
                                <b>de Sucursales:</b><br>
                                <?php 
                                    $cons = $pdo->prepare("SELECT * FROM `tbl_pedidos` as tp INNER JOIN tbl_sucursales as ts ON tp.id_sucursal = ts.s_id where id_payment = ?;");
                                    $cons->execute(array($row['payment_id']));
                                    $resultCons = $cons->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($resultCons as $fila){
                                        echo '<b>'.$fila['s_name'].':</b> '.$fila['stock'].' <br>';
                                    }
                                 ?>
                        	<?php endif; ?>
                        </td>
                        <td><?php echo "$".number_format(floatval($row['paid_amount']), 2); ?></td>
                        <td>
                            <?php echo ($row['payment_status']=='Pending')? 'Pendiente' : 'Completado'; ?>
                            <br><br>
                            <?php
                                if($row['payment_status']=='Pending'){
                                    ?>
                                    <a href="order-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Completar venta</a>
                                    <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php echo ($row['shipping_status']=='Pending')? 'Pendiente' : 'Completado'; ?>
                            <br><br>
                            <?php
                            if($row['payment_status']=='Completed') {
                                if($row['shipping_status']=='Pending'){
                                    ?>
                                    <a href="shipping-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Completar Envío</a>
                                    <?php
                                }
                            }
                            ?>
                        </td>
	                    <td>
                            <a href="#" class="btn btn-danger btn-xs" data-href="order-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" style="width:100%;">Eliminar</a>
	                    </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmar Cancelación</h4>
            </div>
            <div class="modal-body">
                Está seguro que desea elimar este item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Borrar</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>