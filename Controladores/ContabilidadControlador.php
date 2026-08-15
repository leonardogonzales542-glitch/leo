<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../Modelos/ContabilidadCompleta.php';

$cont   = new ContabilidadCompleta();
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$input  = json_decode(file_get_contents('php://input'), true) ?: [];
$data   = array_merge($_POST, $input);

switch ($action) {
    case 'resumen':
        echo json_encode(['ok'=>true,'data'=>$cont->resumen()]);
        break;

    case 'movimientos':
        $tipo   = $data['tipo']   ?? '';
        $desde  = $data['desde']  ?? '';
        $hasta  = $data['hasta']  ?? '';
        $buscar = $data['buscar'] ?? '';
        echo json_encode(['ok'=>true,'data'=>$cont->movimientos($tipo,$desde,$hasta,$buscar)]);
        break;

    case 'cxc':
        echo json_encode(['ok'=>true,'data'=>$cont->cxc($data['estado']??'')]);
        break;

    case 'cxp':
        echo json_encode(['ok'=>true,'data'=>$cont->cxp($data['estado']??'')]);
        break;

    case 'gastos':
        echo json_encode(['ok'=>true,'data'=>$cont->gastos($data['categoria']??'',$data['desde']??'',$data['hasta']??'')]);
        break;

    case 'balance':
        echo json_encode(['ok'=>true,'data'=>$cont->balance()]);
        break;

    case 'estadisticas':
        echo json_encode(['ok'=>true,'data'=>$cont->estadisticas()]);
        break;

    case 'flujo':
        echo json_encode(['ok'=>true,'data'=>$cont->flujoMensual()]);
        break;

    case 'registrar_movimiento':
        if (empty($data['fecha'])||empty($data['tipo'])||empty($data['concepto'])||empty($data['monto'])) {
            echo json_encode(['ok'=>false,'error'=>'Campos requeridos faltantes']); break;
        }
        $ok = $cont->registrarMovimiento($data);
        echo json_encode(['ok'=>$ok]);
        break;

    case 'registrar_gasto':
        if (empty($data['fecha'])||empty($data['categoria'])||empty($data['descripcion'])||empty($data['monto'])) {
            echo json_encode(['ok'=>false,'error'=>'Campos requeridos faltantes']); break;
        }
        $ok = $cont->registrarGasto($data);
        echo json_encode(['ok'=>$ok]);
        break;

    case 'registrar_cxc':
        if (empty($data['cliente_id'])||empty($data['concepto'])||empty($data['monto_total'])||empty($data['fecha_emision'])) {
            echo json_encode(['ok'=>false,'error'=>'Campos requeridos faltantes']); break;
        }
        $ok = $cont->registrarCxC($data);
        echo json_encode(['ok'=>$ok]);
        break;

    case 'registrar_cxp':
        if (empty($data['concepto'])||empty($data['monto_total'])||empty($data['fecha_emision'])) {
            echo json_encode(['ok'=>false,'error'=>'Campos requeridos faltantes']); break;
        }
        $ok = $cont->registrarCxP($data);
        echo json_encode(['ok'=>$ok]);
        break;

    case 'pagar_cxc':
        $result = $cont->pagarCxC((int)($data['id']??0),(float)($data['monto']??0));
        echo json_encode($result);
        break;

    case 'pagar_cxp':
        $result = $cont->pagarCxP((int)($data['id']??0),(float)($data['monto']??0));
        echo json_encode($result);
        break;

    case 'anular':
        $tabla = $data['tabla'] ?? '';
        $id    = (int)($data['id'] ?? 0);
        $ok    = $cont->anular($tabla, $id);
        echo json_encode(['ok'=>$ok]);
        break;

    default:
        echo json_encode(['ok'=>false,'error'=>'Acción no válida']);
}
