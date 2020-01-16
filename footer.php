		</div>

	</div>

	<script src="js/jquery-2.2.4.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/select2.full.min.js"></script>
	<script src="js/jquery.inputmask.js"></script>
	<script src="js/jquery.inputmask.date.extensions.js"></script>
	<script src="js/jquery.inputmask.extensions.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/icheck.min.js"></script>
	<script src="js/fastclick.js"></script>
	<script src="js/jquery.sparkline.min.js"></script>
	<script src="js/jquery.slimscroll.min.js"></script>
	<script src="js/jquery.fancybox.pack.js"></script>
	<script src="js/app.min.js"></script>
	<script src="js/jscolor.js"></script>
	<script src="js/on-off-switch.js"></script>
    <script src="js/on-off-switch-onload.js"></script>
    <script src="js/clipboard.min.js"></script>
    <script src="libs/chartjs/Chart.min.js"></script>
	<!--<script src="js/demo.js"></script>-->
	<!--<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>-->
	<script src="js/summernote.js"></script>
	<!-- JavaScript Alertify-->
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/alertify.min.js"></script>

	<!-- pdf tables-->
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	<!-- pdf tables -->


	<script>
		$(document).ready(function() {


			$('button[type="submit"]').click(function(){
				$('#cover_top').fadeIn(500);
				setTimeout(function(){
					$('#cover_top').fadeOut(500);
				}, 3000);
				//$(this).attr()
			});

			$('#cover_top').on('dblclick', function(){
			 	$('#cover_top').fadeOut(500);
			});

			//seteo el ancho y alto de la pantalla:
			//$('#cover_top').css('width', window.innerWidth).css('height', window.innerHeight+30);

	        $('#editor1').summernote({
	        	height: 300
	        });
	        $('#editor2').summernote({
	        	height: 300
	        });
	        $('#editor3').summernote({
	        	height: 300
	        });
	        $('#editor4').summernote({
	        	height: 300
	        });
	        $('#editor5').summernote({
	        	height: 300
	        });

	        //$('input[name=p_code]').val(generateCode());
	        $('.generateCode').val(generateCode());




	        $inp_p_iva = $('input[name="p_iva"');
	        $inp_p_cost_price = $('input[name="p_cost_price"');
	        $inp_p_utilidad = $('input[name="p_utilidad"');
	        $inp_p_extra_expensives = $('input[name="p_extra_expensives"');
	        $inp_p_list_price = $('input[name="p_list_price"');
	        $inp_p_current_price = $('input[name="p_current_price"');
	        
	        $inp_p_cost_price.on('change', function(){
	        	$precio_de_costo = parseFloat($inp_p_cost_price.val());
	        	$iva = parseFloat($inp_p_iva.val());
	        	$utilidad = (parseFloat($inp_p_utilidad.val()) * $precio_de_costo) / 100;
	        	$gastos_extras = parseFloat($inp_p_extra_expensives.val());
	        	$sum_operacion = ($precio_de_costo + $utilidad); 
	        	$total = $sum_operacion + ($iva * $sum_operacion / 100) + $gastos_extras;
	        	
	        	$inp_p_list_price.val($total);
	        	$inp_p_current_price.val($total);

	        	//console.log($total);
	        });

	        $inp_p_current_price.on('keyup', function(){
	        	var precio_actual = parseFloat($(this).val());
	        	var precio_sin_iva = precio_actual - (((parseFloat($inp_p_iva.val())) * precio_actual )/ 100); 
	        	//var sin_iva = (precio_actual - (parseFloat($inp_p_iva.val()) / 100));
	        	$precio_de_costo = (precio_sin_iva /2);
	        	$inp_p_cost_price.val($precio_de_costo);
	        	//console.log(precio_actual);
	        });

	        /*$('#btnGuardarStock').click(function(e){
	        	e.preventDefault();
	        	$(this).attr('name', "form1");
	        	alertify.confirm("Confirmación de Eliminación", "<h3 style='color: red;'>¿Está seguro de actualizar el stock?</h3>", function(){
	        		console.log("si");
		        	console.log("hacer submit");
		        	$('#formTraspaso').submit();
		        	//return true;
	        	}, function(){
	        		console.log("no");
	        	}).show();
	        });*/

	        

	    });
		$(".top-cat").on('change',function(){
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",
				url: "get-mid-category.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".mid-cat").html(html);
				}
			});			
		});
		$(".mid-cat").on('change',function(){
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",
				url: "get-end-category.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".end-cat").html(html);
				}
			});			
		});

		// $(".selProvincias").on('change',function(){
		// 	$('.selLocalidades').attr('disabled', 'true');
		// 	var id=$(this).val();
		// 	var dataString = 'id='+ id;
		// 	$.ajax
		// 	({
		// 		type: "POST",
		// 		url: "get-localidades.php",
		// 		data: dataString,
		// 		cache: false,
		// 		success: function(html)
		// 		{
		// 			$(".selLocalidades").html(html);
		// 			$('.selLocalidades').removeAttr('disabled');
		// 		}
		// 	});			
		// });

		// $('select[name="c_genero"]').on('change', function(){
		// 	var $este = $(this);
		// 	var $dni = $('input[name=c_nro_doc]').val();
		// 	$('input[name=c_cuit]').val(get_cuil_cuit($dni ,$este.val()));
		// });

		
		// $('input[name="c_email"]').on('change', function(){
		// 	var email = $(this).val();
		// 	$('#loader-email').fadeIn();
		// 	$.ajax({
		// 		url: 'checkEmail.php',
		// 		data: 'email='+email,
		// 		method: 'GET',
		// 		success: function(data){
		// 			console.log(data);
		// 			if(data.existe==0){
		// 				$('input[name="user_email"]').val(email);
		// 				$('input[name=account_exist]').val(0);
		// 				$('#account-e-shop').fadeIn();
		// 			}else{
						
		// 				$('input[name=account_exist]').val(1);
		// 				$('#account-e-shop').fadeOut();
		// 			}
		// 			$('#loader-email').fadeOut();
		// 		},
		// 		error: function(error){
		// 			console.log("error: " + error);
		// 			$('#loader-email').fadeOut();
		// 		}
		// 	});
		// });

		// $('input[name="c_nro_doc"]').on('change', function(){
		// 	$('input[name="user_pass"]').val($(this).val());
		// });

		$('#stockSucursales').on('change', function(){
			//console.log("change");
		});

		$('#btnAddStock').on('click', function(e){
			//e.preventDefault();
			var idSucursal = $('#stockSucursales').val();
			if(idSucursal!=''){
				var idProducto = $('#idProducto').val();
				var sucursalName = $('#stockSucursales option:selected').text();
				var linea = '<div class="form-group"><label for="" class="col-sm-3 control-label"><i class="fa fa-building-o"></i> '+sucursalName+' </label> <div class="col-sm-4"> <input type="hidden" name="id_productos[]" value="'+idProducto+'"> <input type="hidden" name="id_sucursales[]" value="'+idSucursal+'"> <input type="number" class="form-control" name="stocks[]" min="0" value="0" required> </div> </div>'; 
				$('#boxStock').append(linea);
				$('#stockSucursales option:selected').remove();
			}
		});

		$('#selTraspasoFrom').on('change', function(){
			$('#inpStockTrasp').attr('max', $('#selTraspasoFrom option:selected').data('max'));
			$('#inpStockTrasp').val($('#selTraspasoFrom option:selected').data('max'));
			$('#selTraspasoTo option').each(function(i, e){$(e).removeAttr('disabled')});
			$('#selTraspasoTo option').each(function(i, e){if($(e).val()==$('#selTraspasoFrom').val()){$(e).attr('disabled', 'disabled');}});
		});
	</script>

	<script>
	  $(function () {

	    //Initialize Select2 Elements
	    $(".select2").select2();

	    //Datemask dd/mm/yyyy
	    $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
	    //Datemask2 mm/dd/yyyy
	    $("#datemask2").inputmask("mm-dd-yyyy", {"placeholder": "mm-dd-yyyy"});
	    //Money Euro
	    $("[data-mask]").inputmask();

	    //Date picker
	    $('#datepicker').datepicker({
	      autoclose: true,
	      format: 'dd-mm-yyyy',
	      todayBtn: 'linked',
	    });

	    //Date picker
	    $('.datepicker_format').datepicker({
	      autoclose: true,
	      format: 'dd/mm/yyyy',
	      todayBtn: 'linked',
	    });


	    $('#datepicker1').datepicker({
	      autoclose: true,
	      format: 'dd-mm-yyyy',
	      todayBtn: 'linked',
	    });

	    $('#fechaLimite_sel').change(function(){
	    	var date_values = $(this).val().split('/');
	    	//06/12/1990
	    	//12-06-1990
	    	var fecha = new Date(date_values[1]+'-'+date_values[0]+'-'+date_values[2]);
	    	console.log(fecha);
	    	var unix_time = Math.round(fecha.getTime() / 1000);
	    	$('#fechaLimite').val(unix_time);
	    	console.log(unix_time);
	    });

	    //fechaLimite
	    $('#fechaLimite_sel').datepicker({
	      autoclose: true,
	      format: 'dd/mm/yyyy',
	      startDate: 'tomorrow'
	    });

	    //iCheck for checkbox and radio inputs
	    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	      checkboxClass: 'icheckbox_minimal-blue',
	      radioClass: 'iradio_minimal-blue'
	    });
	    //Red color scheme for iCheck
	    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	      checkboxClass: 'icheckbox_minimal-red',
	      radioClass: 'iradio_minimal-red'
	    });
	    //Flat red color scheme for iCheck
	    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	      checkboxClass: 'icheckbox_flat-green',
	      radioClass: 'iradio_flat-green'
	    });


	    var languageTable = {
	            "lengthMenu": "Mostrar _MENU_ por página.",
	            "zeroRecords": "No se encontró registros.",
	            "info": "Mostrando _PAGE_ de _PAGES_",
	            "infoEmpty": "Sin registros disponibles",
	            "search": "Buscar",
	            "paginate": {
			        "first": "Primero",
			        "last": "Último",
			        "previous": "Anterior",
	            	"next": "Siguiente"
			    },
	            "infoFiltered": "(filtrado de _MAX_ registros)",
	        };


	    

	    $("#example1").DataTable({
	    	language: languageTable,
	    });
	    
	    $("#tablaComodatos").DataTable({
	    	language: languageTable,
	    });

	    $("#tablaLogs").DataTable({
	    	language: languageTable,
	    });
	    $('#tablaMensajesCliente').DataTable({
	    	language: languageTable,
	    });
	    $('#tablaCustomers').DataTable({
	    	"language": {
	            "lengthMenu": "Mostrar _MENU_ por página.",
	            "zeroRecords": "No se encontró registros.",
	            "info": "Mostrando _PAGE_ de _PAGES_",
	            "infoEmpty": "Sin registros disponibles",
	            "search": "Buscar",
	            "paginate": {
			        "first": "Primero",
			        "last": "Último",
			        "previous": "Anterior",
	            	"next": "Siguiente"
			    },
	            "infoFiltered": "(filtrado de _MAX_ registros)",
	        }
	    });
	    $("#tablaHistoria").DataTable({
	    	language: languageTable
	    });
	    $('#tablaStockModal').DataTable();
	    $("#tablaUsuarios").DataTable();
	    $("#tablaSinStock").DataTable();
	    $('#tablaProductos').DataTable({
	    	/*columns: [
	    			{data: 'N°'},
	    			{data: 'Producto'},
	    			{data: 'Precio'},
	    			{data: 'Cantidad'},
	    			{data: 'Sucursal'}
	    	],*/
	    	language: languageTable,
	    	dom: 'Blfrtip',
	    	buttons : [
	    		{
	    			extend: 'pdf',
	    			text: '<i class="fa fa-file-pdf-o"></i> PDF',
	    			className: 'btn btn-danger',
	    			title: 'Productos',
	    			exportOptions: {
			    		columns: [0, 2, 3, 4, 5]
			    	},
	    		},
	    		{
	    			extend: 'excel',
	    			text: '<i class="fa fa-table"></i> Excel',
	    			className: 'btn btn-success',
	    			title: 'Productos',
	    			exportOptions: {
	    				columns: [0, 2, 3, 4, 5]
	    			}
	    		},
	    		{
	    			extend: 'print',
	    			text: '<i class="fa fa-print"></i> Imprimir',
	    			className: 'btn btn-secondary',
	    			title: 'Productos',
	    			exportOptions: {
	    				columns: [0, 2, 3, 4, 5]
	    			}
	    		}
	    	],
	    });
	    $('#tablaClientes').DataTable({
	    	//'copy', 'csv', 'excel', 'pdf', 'print'
	    	language: languageTable,
	    	dom: 'Blfrtip',
	    	lengthChange: true,
	    	buttons : [
	    		{
	    			extend: 'pdf',
	    			text: '<i class="fa fa-file-pdf-o"></i> PDF',
	    			className: 'btn btn-danger',
	    			exportOptions: {
			    		columns: [0, 1, 2, 3, 4, 5]
			    	},
	    		},
	    		{
	    			extend: 'excel',
	    			text: '<i class="fa fa-table"></i> Excel',
	    			className: 'btn btn-success',
	    			exportOptions: {
	    				columns: [0, 1, 2, 3, 4, 5]
	    			}
	    		},
	    		
	    		//{extend:'pageLength',},
	    	],
	    	lengthMenu: [
                [100, 200, 500, 1000,  -1],
                [100, 200, 500, 1000, "Todos"]
            ],
	    	/*lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 filas', '25 filas', '50 filas', 'Mostrar todos' ]
	        ],*/

	    });
	    $('#example2').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": false,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false
	    });

	    $('.btnAdd').click(function(){
	    	$este = $(this);
	    	$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('.inpTotal');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = valor_actual + 1;
	    	$label.val(nuevo_valor);
	    	$btnPrint = $('#btnPrintCodeBar');
	    	$btnPrint.attr('href', $padre.find('.url').val()+'&cant='+nuevo_valor);
	    });
	    $('.btnLess').click(function(){
	    	$este = $(this);
	    	$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('.inpTotal');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = (valor_actual <= 0)? 0 : valor_actual - 1; //limitamos a cero
	    	$label.val(nuevo_valor);
	    	$btnPrint = $('#btnPrintCodeBar');
	    	$btnPrint.attr('href', $padre.find('.url').val()+'&cant='+nuevo_valor);
	    });

	    $('.inpTotal').on('change', function(){
	    	var $este = $(this);
	    	var url = 'libs/codebar/index.php?id='+$('#prodId').val()+'&cant='+$este.val();
	    	$('#btnPrintCodeBar').attr('href', url);
	    });

	    $('#confirm-delete').on('show.bs.modal', function(e) {
	      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	      $('#verProductoModal').modal('hide');
	    });

	    $('#printCodeModal').on('show.bs.modal', function(e) {
	      $(this).find('.btn-print').attr('href', $(e.relatedTarget).data('href')+'&cant=1');
	      $(this).find('.url').val($(e.relatedTarget).data('href'));
	      $('#verProductoModal').modal('hide');
	    });
		
		$('#confirm-approve').on('show.bs.modal', function(e) {
	      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	    });



		$('#verProductoModal').on('show.bs.modal', function(e){
			var button = $(e.relatedTarget);
			var id = button.data('id');
			$('#prodId').val(id);
			var loader = $('#loaderModalProduct');

			var modal = $(this);
			modal.find('.row').hide();

			/*
				<a id="btnEditar" href="product-edit.php?id=" class="btn btn-primary "><i class="fa fa-pencil-square-o"></i> Editar </a>
				<a id="btnEliminar" href="#" class="btn btn-danger " data-href="product-delete.php?id=" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i> Eliminar </a>  
				<a id="btnCodeBar" href="#" class="btn btn-warning " data-href="libs/codebar/index.php?id=" data-toggle="modal" data-target="#printCodeModal"><i class="fa fa-barcode"></i> Código de barra </a>
			*/
			var btnEditar = modal.find('#btnEditar');
			var btnEliminar = modal.find('#btnEliminar');
			var btnCodeBar = modal.find('#btnCodeBar');
			var btnTraspaso = modal.find('#btnTraspaso');

			btnEditar.attr('href', 'product-edit.php?id='+id);
			btnEliminar.data('href', 'product-delete.php?id='+id);
			btnCodeBar.data('href', 'libs/codebar/index.php?id='+id);
			btnTraspaso.attr('href', 'traspaso.php?id='+id);

			btnEditar.hide();
			btnEliminar.hide();

			loader.show();
			$.ajax({
				url: 'apiGetProduct.php',
				data: 'id='+id,
				method: 'GET',
				success: function(datos){
					if(datos.length>0){
						btnEditar.show(400);
						btnEliminar.show(400);

						modal.find('#spanID').text(' ' + id);
						modal.find('#spanNombre').text(' ' + datos[0].p_name);
						modal.find('#spanMarca').text(' ' + datos[0].marca);
						modal.find('#spanCode').text(' ' + datos[0].p_code);
						modal.find('#spanSucursalName').text(' ' + datos[0].s_name);
						modal.find('#spanCantidad').text(' ' + datos[0].p_qty);
						modal.find('#spanTotalVisitas').text(' ' + datos[0].p_total_view);
						//modal.find('#p_descrip').html(datos[0].p_description);

						modal.find('#spanPrecio').text(' $' + (datos[0].p_current_price));
						modal.find('#spanPrecioLista').text(' $' + datos[0].p_list_price);
						modal.find('#spanPrecioCosto').text(' $' + datos[0].p_cost_price);
						modal.find('#spanUtilidad').text(' ' + datos[0].p_utilidad);
						modal.find('#spanGastosExtras').text(' $' + datos[0].p_extra_expensives);
						modal.find('#spanPublicado').text(' ' + (
							datos[0].p_is_featured=='1'? 'Sí' : 'No'));
						modal.find('#spanActivo').text(' ' + (datos[0].p_is_active=='1'? 'Sí' : 'No'));
						
						//{sk_stock: "8", s_name: "Muebles & Deco"}
						var stock = datos[1].stock;
						$listSucursal = $('#listSucursal');
						if(stock.length>0){
							$listSucursal.empty();
							for (var i = 0; i < stock.length; i++) {
								var stk = stock[i];
								$listSucursal.append('<li > <i class="fa fa-building-o"></i><span id="">'+stk.s_name+'</span>: '+stk.sk_stock+'</li>');
							}
						}else{
							$listSucursal.empty();
							$listSucursal.append('<li > <i class="fa fa-building-o"></i><span id="">Sin Stock</span></li>');
						}
					}
					loader.fadeOut();
					modal.find('.row').fadeIn();
				},
				error: function(error){
					console.log(error);
					loader.fadeOut();
				}

			});

		});

 
	  });

		function confirmDelete()
	    {
	        return confirm("Are you sure want to delete this data?");
	    }
	    function confirmActive()
	    {
	        return confirm("Are you sure want to Active?");
	    }
	    function confirmInactive()
	    {
	        return confirm("Are you sure want to Inactive?");
	    }

	</script>

	<script type="text/javascript">
		function showDiv(elem){
			if(elem.value == 0) {
		      	document.getElementById('photo_div').style.display = "none";
		      	document.getElementById('icon_div').style.display = "none";
		   	}
		   	if(elem.value == 1) {
		      	document.getElementById('photo_div').style.display = "block";
		      	document.getElementById('photo_div_existing').style.display = "block";
		      	document.getElementById('icon_div').style.display = "none";
		   	}
		   	if(elem.value == 2) {
		      	document.getElementById('photo_div').style.display = "none";
		      	document.getElementById('photo_div_existing').style.display = "none";
		      	document.getElementById('icon_div').style.display = "block";
		   	}
		}
		function showContentInputArea(elem){
		   if(elem.value == 'Full Width Page Layout') {
		      	document.getElementById('showPageContent').style.display = "block";
		   } else {
		   		document.getElementById('showPageContent').style.display = "none";
		   }
		}
	</script>

	<script type="text/javascript">

        $(document).ready(function () {

            $("#btnAddNew").click(function () {

		        var rowNumber = $("#ProductTable tbody tr").length;

		        var trNew = "";              

		        var addLink = "<div class=\"upload-btn" + rowNumber + "\"><input type=\"file\" name=\"photo[]\"  style=\"margin-bottom:5px;\"></div>";
		           
		        var deleteRow = "<a href=\"javascript:void()\" class=\"Delete btn btn-danger btn-xs\">X</a>";

		        trNew = trNew + "<tr> ";

		        trNew += "<td>" + addLink + "</td>";
		        trNew += "<td style=\"width:28px;\">" + deleteRow + "</td>";

		        trNew = trNew + " </tr>";

		        $("#ProductTable tbody").append(trNew);

		    });

		    $('#ProductTable').delegate('a.Delete', 'click', function () {
		        $(this).parent().parent().fadeOut('slow').remove();
		        return false;
		    });

        });

        // function generateCode(){
        // 	var chars = "0123456789";
        // 	var code = 'HDS-123-';
        // 	for (var i = 0; i < 4; i++) {
        // 		code += chars.charAt(Math.floor(Math.random() * chars.length));
        // 	}
        // 	return code;
        // }

        function generateCode(){
        	var ts = Math.round((new Date()).getTime() / 1000);
        	var code = 'HD'+ts;
        	
        	return code;
        }

  //       function get_cuil_cuit(document_number, gender){
		//     /**
		//      * Cuil format is: AB - document_number - C
		//      * Author: Nahuel Sanchez, Woile
		//      *
		//      * @param {str} document_number -> string solo digitos
		//      * @param {str} gender -> debe contener HOMBRE, MUJER o SOCIEDAD
		//      *
		//      * @return {str}
		//      **/
		//     'use strict';
		//     var HOMBRE = ['HOMBRE', 'M', 'MALE'],
		//         MUJER = ['MUJER', 'F', 'FEMALE'],
		//         SOCIEDAD = ['SOCIEDAD', 'S', 'SOCIETY', 'O'];
		//     var AB, C;

		//     /**
		//      * Verifico que el document_number tenga exactamente ocho numeros y que
		//      * la cadena no contenga letras.
		//      */
		//     if(document_number.length != 8 || isNaN(document_number)) {
		//         if (document_number.length == 7 && !isNaN(document_number)) {
		//             document_number = '0'.concat(document_number);
		//         } else {
		//             // Muestro un error en caso de no serlo.
		//             throw 'El numero de documento ingresado no es correcto.';
		//         }
		//     }

		//     /**
		//      * De esta manera permitimos que el gender venga en minusculas,
		//      * mayusculas y titulo.
		//      */
		//     gender = gender.toUpperCase();

		//     // Defino el valor del prefijo.
		//     if(HOMBRE.indexOf(gender) >= 0) {
		//         AB = '20';
		//     } else if(MUJER.indexOf(gender) >= 0) {
		//         AB = '27';
		//     } else {
		//         AB = '30';
		//     }

		//     /*
		//      * Los numeros (excepto los dos primeros) que le tengo que
		//      * multiplicar a la cadena formada por el prefijo y por el
		//      * numero de document_number los tengo almacenados en un arreglo.
		//      */
		//     var multiplicadores = [3, 2, 7, 6, 5, 4, 3, 2];

		//     // Realizo las dos primeras multiplicaciones por separado.
		//     var calculo = ((parseInt(AB.charAt(0)) * 5) + (parseInt(AB.charAt(1)) * 4));

		//     /*
		//      * Recorro el arreglo y el numero de document_number para
		//      * realizar las multiplicaciones.
		//      */
		//     for(var i=0;i<8;i++) {
		//         calculo += (parseInt(document_number.charAt(i)) * multiplicadores[i]);
		//     }

		//     // Calculo el resto.
		//     var resto = (parseInt(calculo)) % 11;

		//     /*
		//      * Llevo a cabo la evaluacion de las tres condiciones para
		//      * determinar el valor de C y conocer el valor definitivo de
		//      * AB.
		//      */
		//     if((SOCIEDAD.indexOf(gender) < 0)&&(resto==1)){
		//         if(HOMBRE.indexOf(gender) >= 0){
		//             C = '9';
		//         } else {
		//             C = '4';
		//         }
		//         AB = '23';
		//     } else if(resto === 0){
		//         C = '0';
		//     } else {
		//         C = 11 - resto;
		//     }

		//     // Show example
		//     console.log([AB, document_number, C].join('-'));

		//     // Generate cuit
		//     var cuil_cuit = [AB, document_number, C].join('');

		//     return cuil_cuit;

		// }



       /* var items = [];
        for( i=1; i<=24; i++ ) {
        	items[i] = document.getElementById("tabField"+i);
        }

		items[1].style.display = 'block';
		items[2].style.display = 'block';
		items[3].style.display = 'block';
		items[4].style.display = 'none';

		items[5].style.display = 'block';
		items[6].style.display = 'block';
		items[7].style.display = 'block';
		items[8].style.display = 'none';

		items[9].style.display = 'block';
		items[10].style.display = 'block';
		items[11].style.display = 'block';
		items[12].style.display = 'none';

		items[13].style.display = 'block';
		items[14].style.display = 'block';
		items[15].style.display = 'block';
		items[16].style.display = 'none';

		items[17].style.display = 'block';
		items[18].style.display = 'block';
		items[19].style.display = 'block';
		items[20].style.display = 'none';

		items[21].style.display = 'block';
		items[22].style.display = 'block';
		items[23].style.display = 'block';
		items[24].style.display = 'none';

		function funcTab1(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[1].style.display = 'block';
		       	items[2].style.display = 'block';
		       	items[3].style.display = 'block';
		       	items[4].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[1].style.display = 'none';
		       	items[2].style.display = 'none';
		       	items[3].style.display = 'none';
		       	items[4].style.display = 'block';
			}
		};

		function funcTab2(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[5].style.display = 'block';
		       	items[6].style.display = 'block';
		       	items[7].style.display = 'block';
		       	items[8].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[5].style.display = 'none';
		       	items[6].style.display = 'none';
		       	items[7].style.display = 'none';
		       	items[8].style.display = 'block';
			}
		};

		function funcTab3(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[9].style.display = 'block';
		       	items[10].style.display = 'block';
		       	items[11].style.display = 'block';
		       	items[12].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[9].style.display = 'none';
		       	items[10].style.display = 'none';
		       	items[11].style.display = 'none';
		       	items[12].style.display = 'block';
			}
		};

		function funcTab4(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[13].style.display = 'block';
		       	items[14].style.display = 'block';
		       	items[15].style.display = 'block';
		       	items[16].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[13].style.display = 'none';
		       	items[14].style.display = 'none';
		       	items[15].style.display = 'none';
		       	items[16].style.display = 'block';
			}
		};

		function funcTab5(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[17].style.display = 'block';
		       	items[18].style.display = 'block';
		       	items[19].style.display = 'block';
		       	items[20].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[17].style.display = 'none';
		       	items[18].style.display = 'none';
		       	items[19].style.display = 'none';
		       	items[20].style.display = 'block';
			}
		};

		function funcTab6(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[21].style.display = 'block';
		       	items[22].style.display = 'block';
		       	items[23].style.display = 'block';
		       	items[24].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[21].style.display = 'none';
		       	items[22].style.display = 'none';
		       	items[23].style.display = 'none';
		       	items[24].style.display = 'block';
			}
		};*/


		//añade clase col-lg-6 a tabla dinámicamente
		$('#tablaProductos_length').addClass('col-lg-6');
    </script>

</body>
</html>



