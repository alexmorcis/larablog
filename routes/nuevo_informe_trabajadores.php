<?php
	session_start(); //Se abre la sesión o se crea una nueva.
	$id_de_empresa = $_SESSION["id_de_empresa_activa"];
		header('Content-Type: text/html; charset=utf-8');
		ini_set('memory_limit', '1024M');
		ini_set('display_errors', 1);
		header("Pragma: no-cache");
		header("Expires: 0");
		include ("../../../includes/config.php");
		include_once ("../../../includes/funciones_auxiliares.php");
		include ('../../../includes/cab_excel.php');

		$matriz_de_empresas = $_POST['matriz_de_empresas'];
		$lista_de_empresas = implode(',', $matriz_de_empresas);

		$matrizDeTipologias = [];
		$consulta = "SELECT ";
		$consulta .= "id_de_tipo, ";
		$consulta .= "nombre_de_tipo ";
		$consulta .= "FROM ";
		$consulta .= "maestro_de_tipologias_de_empresas ";
		$consulta .= "WHERE titular_gestor = ?;";
		$hacerConsulta = $conexionPDO->prepare($consulta);
		$hacerConsulta->bindParam(1, $id_de_empresa);
		$hacerConsulta->execute();
		while ($item = $hacerConsulta->fetch(PDO::FETCH_ASSOC)) {
			if ($item['nombre_de_tipo'] == '') continue;
			$matrizDeTipologias[$item['id_de_tipo']] = $item['nombre_de_tipo'];
		}
		$hacerConsulta->closeCursor();

		$consulta = "SELECT ";
		$consulta .= "maestro_de_empresas.id_de_empresa, ";
		$consulta .= "maestro_de_empresas.nombre_de_empresa, ";
		$consulta .= "maestro_de_empresas.sigue_vigente, ";
		$consulta .= "maestro_de_trabajadores.id_de_trabajador, ";
		$consulta .= "maestro_de_trabajadores.nombre_completo_del_trabajador, ";
		$consulta .= "maestro_de_trabajadores.dni_nif_nie_del_trabajador ";
		$consulta .= "FROM ";
		$consulta .= "maestro_de_trabajadores, ";
		$consulta .= "maestro_de_empresas ";
		$consulta .= "WHERE ";
		$consulta .= "maestro_de_trabajadores.id_de_empresa IN (".$lista_de_empresas.") ";
		$consulta .= "AND maestro_de_trabajadores.sigue_vigente = ? ";
		$consulta .= "AND maestro_de_empresas.id_de_empresa = maestro_de_trabajadores.id_de_empresa;";
		$hacerConsulta = $conexionPDO->prepare($consulta);
		$hacerConsulta->bindValue(1, 'S');
		$hacerConsulta->execute();
		$trabajadoresObtenidos = $hacerConsulta->fetchAll(PDO::FETCH_ASSOC);
		$hacerConsulta->closeCursor();

		foreach ($trabajadoresObtenidos as $keyTrab => $trab) {
			$evaluacion = evaluarTrabajador($id_de_empresa, $trab['id_de_trabajador'], false, '0', true);
			$trabajadoresObtenidos[$keyTrab]['estado'] = ($evaluacion['trabajadorApto'] === true) ? "APTO": "INAPTO";
		}

		foreach ($trabajadoresObtenidos as $keyTrab => $trab) {
			$consulta = "SELECT ";
			$consulta .= "id_de_tipo ";
			$consulta .= "FROM ";
			$consulta .= "maestro_de_relaciones_empresas_tipologias ";
			$consulta .= "WHERE ";
			$consulta .= "id_de_empresa = ?;";
			$hacerConsulta = $conexionPDO->prepare($consulta);
			$hacerConsulta->bindParam(1, $trab['id_de_empresa']);
			$hacerConsulta->execute();
			if ($hacerConsulta->rowCount() == 0) {
				$id_de_tipo = '0';
				$nombre_de_tipo_de_empresa = "SIN TIPOLOGIA ESTABLECIDA";
			} else {
				$id_de_tipo = $hacerConsulta->fetch(PDO::FETCH_ASSOC)["id_de_tipo"];
				$nombre_de_tipo_de_empresa = $matrizDeTipologias[$id_de_tipo];
			}
			$trabajadoresObtenidos[$keyTrab]['id_de_tipo_de_empresa'] = $id_de_tipo;
			$trabajadoresObtenidos[$keyTrab]['nombre_de_tipo_de_empresa'] = $nombre_de_tipo_de_empresa;
			$hacerConsulta->closeCursor();
        }

        $tablaDeResultados = "";
		$tablaDeResultados .= "<table border='1'>";
		$tablaDeResultados .= "<tr>";
		$tablaDeResultados .= "<th>EMPRESA</th>";
		$tablaDeResultados .= "<th>TIPOLOGIA</th>";
		$tablaDeResultados .= "<th>TRABAJADOR</th>";
		$tablaDeResultados .= "<th>DNI</th>";
		$tablaDeResultados .= "<th>ESTADO</th>";
		$tablaDeResultados .= "<th>DOCUMENTO</th>";
		$tablaDeResultados .= "<th>SOLICITADO EL</th>";
		$tablaDeResultados .= "<th>LIMITE DE ENVIO</th>";
        $tablaDeResultados .= "<th>PERIODICIDAD (MESES)</th>";
        $tablaDeResultados .= "<th>ESTATUS</th>";
		$tablaDeResultados .= "<th>FECHA ESTATUS</th>";

        $tablaDeResultados .= "</tr>";
        foreach ($trabajadoresObtenidos as $keyTrab => $trab) {
            $consulta = "SELECT ";
    $consulta .= "nombre_de_categoria,maestro_de_categorias_de_docs_de_trabajadores.id_de_categoria, documento_eliminatorio,documento_eliminatorio_inicial,";
    $consulta .= "periodo_de_vigencia_en_meses, maestro_de_requerimientos_de_docs_de_trabajador.fecha_de_solicitud, ";
    $consulta .= "maestro_de_categorias_de_docs_de_trabajadores.periodo_de_vigencia_en_meses, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.documento_validado, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.documento_enviado, periodo_de_carencia_eliminatoria_inicial, ";
    $consulta .= "periodo_de_carencia_eliminatoria,maestro_de_requerimientos_de_docs_de_trabajador.documento_rechazado, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.id_de_empresa, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.fecha_de_validacion_o_rechazo, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.fecha_de_envio, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.fecha_limite_de_aportacion, ";
    $consulta .= "maestro_de_requerimientos_de_docs_de_trabajador.id_de_requerimiento ";
    $consulta .= "FROM egesdoc_es.maestro_de_categorias_de_docs_de_trabajadores ";
    $consulta .= "inner join maestro_de_requerimientos_de_docs_de_trabajador ";
    $consulta .= "on maestro_de_requerimientos_de_docs_de_trabajador.id_de_categoria = maestro_de_categorias_de_docs_de_trabajadores.id_de_categoria ";
    $consulta .= "WHERE ";
    $consulta .= " maestro_de_categorias_de_docs_de_trabajadores.sigue_vigente = 'S'";
    $consulta .= "AND maestro_de_requerimientos_de_docs_de_trabajador.id_de_trabajador = '" . $trab['id_de_trabajador'] . "' ";
    $consulta .= "AND esta_regularizado = 'N' ;";

    $hacerConsulta = $conexionPDO->prepare($consulta);

    $hacerConsulta->execute();

    $matrizDeRequerimientos = $hacerConsulta->fetchAll(PDO::FETCH_ASSOC);
	
    foreach ($matrizDeRequerimientos as $key => $req) {
		
    if ($req['documento_eliminatorio_inicial'] == 'N' && $req['documento_eliminatorio'] == 'N' && $req['periodo_de_carencia_eliminatoria_inicial'] == 0 && $req['periodo_de_carencia_eliminatoria'] == 0) {
        $matrizDeRequerimientos[$key]['aportacion'] = 'R';
    } else {
        $matrizDeRequerimientos[$key]['aportacion'] = 'O';
    }

    if ($req['documento_validado'] == 'S') {
        $matrizDeRequerimientos[$key]['estado']  = 'Validado';
    } elseif ($req['documento_rechazado'] == 'S') {
        $matrizDeRequerimientos[$key]['estado'] = 'Rechazado';
    } elseif ($req['documento_enviado'] == 'N') {
        $matrizDeRequerimientos[$key]['estado']  = 'Pendiente';
    } elseif($req['documento_enviado'] == 'S' &&(($req['documento_validado'] == 'N')||$req['documento_rechazado'] == 'N') ){
        $matrizDeRequerimientos[$key]['estado']  = 'En validacion';
    }
  
 

          // if (($req['fecha_de_validacion_o_rechazo']) == '0000-00-00') {
    //     $matrizDeRequerimientos[$key]['fecha_de_validacion_o_rechazo'] = '';
    // }
}


foreach ($matrizDeRequerimientos as $key => $req) {
    $consulta = "SELECT min(fecha_de_solicitud) as fecha_pendiente FROM egesdoc_es.maestro_de_requerimientos_de_docs_de_trabajador ";
    $consulta .="where id_de_categoria = '".$req['id_de_categoria']."' and id_de_empresa = '".$trab['id_de_empresa']."' ";
    $consulta.=" and documento_enviado ='N'";
    
      $hacerConsulta = $conexionPDO->prepare($consulta);

    $hacerConsulta->execute();
    $matrizDeFechas = array();
    while ($matrizDeFecha = $hacerConsulta->fetch(\PDO::FETCH_OBJ)) {
        $matrizDeRequerimientos[$key]['fecha_pendiente']  = $matrizDeFecha->{"fecha_pendiente"};
        
    }
    if ($req['documento_enviado'] == 'N'){
        $matrizDeRequerimientos[$key]['fecha_de_validacion_o_rechazo'] =  $matrizDeRequerimientos[$key]['fecha_pendiente'] ;
    }
    
}
if ($trab['estado'] == "INAPTO") {
foreach ($matrizDeRequerimientos as $key => $req) {

    $tablaDeResultados .= "<tr>";
	$tablaDeResultados .= "<td>".$trab["nombre_de_empresa"]."</td>";
						$tablaDeResultados .= "<td>".$trab["nombre_de_tipo_de_empresa"]."</td>";
						$tablaDeResultados .= "<td>".$trab["nombre_completo_del_trabajador"]."</td>";
						$tablaDeResultados .= "<td>".$trab["dni_nif_nie_del_trabajador"]."</td>";
                        $tablaDeResultados .= "<td>".$trab["estado"]."</td>";
                        $tablaDeResultados .= "<td>".$req["nombre_de_categoria"]."</td>";
    $tablaDeResultados .= "<td>" . $req["fecha_de_solicitud"] . "</td>";
    $tablaDeResultados .= "<td>" . $req["fecha_limite_de_aportacion"] . "</td>";
    $tablaDeResultados .= "<td align=\"center\">" . $req["periodo_de_vigencia_en_meses"] . "</td>";
    $tablaDeResultados .= "<td align=\"center\">" . $req["estado"] . "</td>";
    $tablaDeResultados .= "<td>" . $req["fecha_de_validacion_o_rechazo"] . "</td>";
  
}
} else {
    $tablaDeResultados .= "<tr>";
    $tablaDeResultados .= "<td>".$trab["nombre_de_empresa"]."</td>";
    $tablaDeResultados .= "<td>".$trab["nombre_de_tipo_de_empresa"]."</td>";
    $tablaDeResultados .= "<td>".$trab["nombre_completo_del_trabajador"]."</td>";
    $tablaDeResultados .= "<td>".$trab["dni_nif_nie_del_trabajador"]."</td>";
    $tablaDeResultados .= "<td>".$trab["estado"]."</td>";
    $tablaDeResultados .= "<td colspan = '4'>TRABAJADOR APTO</td>";
    $tablaDeResultados .= "</tr>";
}
$tablaDeResultados .= "</tr>";
}

$tablaDeResultados .= "</table>";
echo $tablaDeResultados;

        