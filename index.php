<?php 
$conex = mysqli_connect("localhost", "root", "galito.97", "crudphp");

if (!$conex) {
	echo "<p> Error: No se pudo conectar a MySQL." . PHP_EOL;
	echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
	echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
	echo "</p>";
	exit;
}
//echo "<p>Conectado con éxito</p>" . PHP_EOL;

$cedula= "";
$nombres= "";
$apellidos="";
$salario= "";
$telefono= "";
$fechaNacimiento= "";
$codEmpleado= "";
$accion = "Agregar";

	if(isset($_POST['accion']) && $_POST['accion'] == "Agregar" ){
		$stmt = $conex->prepare("INSERT INTO empleados (cedula, nombres,apellidos,salario,telefono,fecha_nacimiento) VALUES (?,?,?,?,?,?)");
		$stmt->bind_param("sssdss",$cedula,$nombres,$apellidos,$salario,$telefono,$fechaNacimiento);
		$cedula= $_POST["cedula"];
		$nombres= $_POST["nombres"];
		$apellidos= $_POST["apellidos"];
		$salario= $_POST["salario"];
		$telefono= $_POST["telefono"];
		$fechaNacimiento= $_POST["fechaNacimiento"];
		
		/*$cedula= $_POST["1719"];
		$nombres= $_POST["liz"];
		$apellidos= $_POST["pic"];
		$salario= $_POST[84];
		$telefono= $_POST["0656"];
		$fechaNacimiento= $_POST["1976-09-12"];
		print_r($cedula);*/
		$stmt->execute();
		$stmt->close();

		$cedula= "";
		$nombres= "";
		$apellidos="";
		$salario= "";
		$telefono= "";
		$fechaNacimiento= "";

		
	}else if(isset($_POST['accion']) && $_POST['accion'] == "Modificar"){
		$stmt = $conex->prepare("UPDATE empleados SET cedula=?, nombres=?, apellidos=?, salario=?, telefono=?,fecha_nacimiento=? WHERE cod_empleado=?");
		$stmt->bind_param("sssdssi",$cedula,$nombres,$apellidos,$salario,$telefono,$fechaNacimiento, $codEmpleado);
		$cedula= $_POST["cedula"];
		$nombres= $_POST["nombres"];
		$apellidos= $_POST["apellidos"];
		$salario= $_POST["salario"];
		$telefono= $_POST["telefono"];
		$fechaNacimiento= $_POST["fechaNacimiento"];
		$codEmpleado = $_POST["codEmpleado"];
		print_r($codEmpleado);

		$stmt->execute();
		$stmt->close();

		$cedula= "";
		$nombres= "";
		$apellidos="";
		$salario= "";
		$telefono= "";
		$fechaNacimiento= "";
	}else if(isset($_GET["update"])){
		$result = $conex->query("SELECT * FROM empleados where cod_empleado=".$_GET["update"]);
		if ($result->num_rows > 0) {
		$row1 = $result->fetch_assoc();
		$codEmpleado= $row1["cod_empleado"];
		$cedula=$row1["cedula"];
		$nombres=$row1["nombres"];
		$apellidos=$row1["apellidos"];
		$salario= $row1["salario"];
		$telefono= $row1["telefono"];
		$fechaNacimiento= $row1["fecha_nacimiento"];
		$accion="Modificar";
		//print_r($codEmpleado);
		}					
	}else if($_GET["delete"]){
		$stmt = $conex->prepare("DELETE FROM empleados WHERE cod_empleado=?");
		$stmt->bind_param("i", $codEmpleado);
		$codEmpleado= $_GET["delete"];
		$stmt->execute();
		$stmt->close();
		$codEmpleado= "";
	}
	
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CRUD PHP</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container">
         
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="./">INICIO <span class="sr-only">(current)</span></a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <div class="container">
        <h1 class="page-header text-center">CRUD PHP GALO PICHUCHO</h1>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                

                <form name="forma" method="POST" action="index.php">

                    <table class="table table-bordered table-striped" style="margin-top:20px;">
                        <thead>
                            <th>CODIGO EMPLEADO</th>
                            <th>CÉDULA</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>SALARIO</th>
                            <th>TELEFONO</th>
                            <th>FECHA NACIMIENTO</th>
                            <th>ACCIONES</th>

                        </thead>
                        <tbody>
                            <?php
						$result = $conex->query("SELECT * FROM empleados");
						if ($result->num_rows > 0) {
							// output data of each row
							while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['cod_empleado']; ?></td>
                                <td><?php echo $row['cedula']; ?></td>
                                <td><?php echo $row['nombres']; ?></td>
                                <td><?php echo $row['apellidos']; ?></td>
                                <td><?php echo $row['salario']; ?></td>
                                <td><?php echo $row['telefono']; ?></td>
                                <td><?php echo $row['fecha_nacimiento']; ?></td>
                                <td>
                                    <a href="index.php?update=<?php echo $row['cod_empleado']; ?>"
                                        class="btn btn-success btn-sm" data-toggle="modal"><span
                                            class="glyphicon glyphicon-edit"></span> Editar</a>
                                    <a href="index.php?delete=<?php echo $row['cod_empleado']; ?>"
                                        class="btn btn-danger btn-sm" data-toggle="modal"><span
                                            class="glyphicon glyphicon-trash"></span> Borrar</a>
                                </td>

                            </tr>
                            <?php
                	}
				} else { ?>
                            <tr>
                                <td colspan="4">NO HAY DATOS</td>;
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                    <div class="modal-body">
						
                        <div class="container-fluid">
						<div class="row form-group ">
                                <div class="col-sm-12 text-center">
									<h3><?php echo $accion?> Empleado</h4>
								</div>
							</div>
                            <div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" id="lblcedula" for="cedula">Cédula </label>	
                                </div>
                                <div class="col-sm-3">
								<input class="form-control" type="text" name="cedula" value="<?php echo $cedula ?>" maxlength="10" size="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required />
                                </div>
							</div>
							<div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" id="lblNombres" for="nombres">Nombres</label>	
                                </div>
                                <div class="col-sm-5">
								<input class="form-control" type="text" type="text" name="nombres" value="<?php echo $nombres ?>" maxlength="100"
                                            size="25" required />
                                </div>
							</div>
							<div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" id="lblApellidos" for="apellidos">Apellidos </label>	
                                </div>
                                <div class="col-sm-5">
								<input class="form-control" type="text" type="text" name="apellidos" value="<?php echo $apellidos ?>"
                                            maxlength="100" size="11" >
                                </div>
							</div>
							<div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" label id="lblSalario" for="salario">Salario </label>	
                                </div>
                                <div class="col-sm-3">
								<input class="form-control" input type="number" name="salario" value="<?php echo $salario ?>" min="0" max ="9999"step="any" />
                                </div>
							</div>
							<div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" id="lblTelefono" for="telefono">Teléfono </label>	
                                </div>
                                <div class="col-sm-5">
								<input class="form-control" type="text" name="telefono" value="<?php echo $telefono ?>"
                                            maxlength="10" size="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
                                </div>
							</div>
							<div class="row form-group">
                                <div class="col-sm-4 text-center">
								<label class="control-label" style="position:relative; top:7px;" id="lblFechaNAcimiento" for="fechaNacimiento">Fecha Nacimiento  </label>	
                                </div>
                                <div class="col-sm-3">
								<input class="form-control" type="date" name="fechaNacimiento" min="1900-01-01" max="<?php echo date("Y-m-d");?>" value="<?php echo $fechaNacimiento ?>"
                                            maxlength="100" size="11"  />
                                </div>
                            </div>

                            <input type="hidden" name="codEmpleado" value="<?php echo $codEmpleado ?>">
							
							
                        </div>
					</div>
					<div class="modal-footer">
					
					<button type="submit" name="accion" value="<?php echo $accion?>" class="btn btn-primary"><span class="glyphicon glyphicon-check"></span><?php echo " ".$accion?></button>
				</form>
					
			</div>
                
            </div>

        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>