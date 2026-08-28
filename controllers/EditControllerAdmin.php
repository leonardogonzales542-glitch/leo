<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/rol.php';
require_once __DIR__ . '/../models/proveedor.php';


class UsuarioController
{
    private $usuarioModel;
    private $proveedorModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
        $this->proveedorModel = new Proveedor();
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/admin/gusuarios.php');
            exit;
        }

        $id_usuario = $_POST['id_usuario'] ?? null;
        if (!$id_usuario) {
            $this->setAlert('error', 'Error', 'ID de usuario no proporcionado');
            header('Location: ../views/admin/gusuarios.php');
            exit;
        }

        $datos = [
            'usuario' => trim($_POST['usuario'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'id_rol' => trim($_POST['rol'] ?? ''),
            'estado' => $_POST['estado'] ?? ''
        ];

        if (!empty($_POST['password'])) {
            $datos['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $resultado = $this->usuarioModel->actualizar($id_usuario, $datos);

        if ($resultado === true) {
            $this->setAlert('success', 'Éxito', 'Usuario actualizado correctamente');
        } else {
            $this->setAlert('error', 'Error', $resultado);
        }

        header('Location: ../views/admin/gusuarios.php');
        exit;
    }

    public function updateProveedor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/admin/proveedores.php');
            exit;
        }

        $id_proveedor = $_POST['id_proveedor'] ?? null;
        if (!$id_proveedor) {
            $this->setAlert('error', 'Error', 'ID de proveedor no proporcionado');
            header('Location: ../views/admin/proveedores.php');
            exit;
        }

        $datos = [
            'razon_social' => trim($_POST['razon_social'] ?? ''),
            'nit' => trim($_POST['nit'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'estado' => $_POST['estado'] ?? ''
        ];

        $resultado = $this->proveedorModel->actualizar($id_proveedor, $datos);

        if ($resultado === true) {
            $this->setAlert('success', 'Éxito', 'Proveedor actualizado correctamente');
        } else {
            $this->setAlert('error', 'Error', $resultado);
        }

        header('Location: ../views/admin/proveedores.php');
        exit;
    }

    public function deleteProveedor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/admin/proveedores.php');
            exit;
        }

        $id_proveedor = $_POST['id_proveedor'] ?? null;
        if (!$id_proveedor) {
            $this->setAlert('error', 'Error', 'ID de proveedor no proporcionado');
            header('Location: ../views/admin/proveedores.php');
            exit;
        }

        $resultado = $this->proveedorModel->eliminar($id_proveedor);

        if ($resultado === true) {
            $this->setAlert('success', 'Éxito', 'Proveedor eliminado correctamente');
        } else {
            $this->setAlert('error', 'Error', $resultado);
        }

        header('Location: ../views/admin/proveedores.php');
        exit;
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/admin/gusuarios.php');
            exit;
        }

        $id_usuario = $_POST['id_usuario'] ?? null;
        if (!$id_usuario) {
            $this->setAlert('error', 'Error', 'ID de usuario no proporcionado');
            header('Location: ../views/admin/gusuarios.php');
            exit;
        }

        $resultado = $this->usuarioModel->eliminar($id_usuario);

        if ($resultado === true) {
            $this->setAlert('success', 'Éxito', 'Usuario eliminado correctamente');
        } else {
            $this->setAlert('error', 'Error', $resultado);
        }

        header('Location: ../views/admin/gusuarios.php');
        exit;
    }

    private function setAlert($icon, $title, $text)
    {
        $_SESSION['alert'] = [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ];
    }
}

if (isset($_GET['accion'])) {
    $controller = new UsuarioController();
    if ($_GET['accion'] === 'update') {
        $controller->update();
    } elseif ($_GET['accion'] === 'delete') {
        $controller->delete();
    } elseif ($_GET['accion'] === 'updateProveedor') {
        $controller->updateProveedor();
    } elseif ($_GET['accion'] === 'deleteProveedor') {
        $controller->deleteProveedor();
    }
}
?>