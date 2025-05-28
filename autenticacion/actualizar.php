<?php
    session_start();

    $host = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if (!isset($_SESSION['usuario_id'])) {
        echo "No hay sesión activa.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_id = $_SESSION['usuario_id'];

        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $ciudad = trim($_POST['ciudad']);
        $provincia = trim($_POST['provincia']);
        $codigo_postal = trim($_POST['codigo_postal']);
        $contrasenia = !empty($_POST['contrasenia']) ? password_hash(trim($_POST['contrasenia']), PASSWORD_DEFAULT) : null;

        try {
            $query = "UPDATE clientes SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono,
                    direccion = :direccion, ciudad = :ciudad, provincia = :provincia, codigo_postal = :codigo_postal";
            
            $params = [':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':telefono' => $telefono,
                    ':direccion' => $direccion, ':ciudad' => $ciudad, ':provincia' => $provincia, ':codigo_postal' => $codigo_postal,
                    ':usuario_id' => $usuario_id];

            if ($contrasenia) {
                $query .= ", contrasenia = :contrasenia";
                $params[':contrasenia'] = $contrasenia;
            }

            $query .= " WHERE id = :usuario_id";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            echo "success";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>
