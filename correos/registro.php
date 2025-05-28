<?php

    $host   = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre         = trim($_POST['nombre']);
        $apellido       = trim($_POST['apellido']);
        $email          = trim($_POST['email']);
        $contrasenia    = trim($_POST['contrasenia']);
        $telefono       = trim($_POST['telefono']);
        $direccion      = trim($_POST['direccion']);
        $ciudad         = trim($_POST['ciudad']);
        $provincia      = trim($_POST['provincia']);
        $codigo_postal  = trim($_POST['codigo_postal']);


        if (empty($nombre) || empty($apellido) || empty($email) || empty($contrasenia) || empty($telefono) || empty($direccion) || empty($ciudad) || empty($provincia) || empty($codigo_postal)) {
            echo "Por favor, completa todos los campos.";
            exit;
        }
        
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE email = :email");
        $stmtCheck->execute([':email' => $email]);
        $emailExists = $stmtCheck->fetchColumn();
    
        if ($emailExists) {
            echo "Este correo ya está registrado. Por favor, usa otro o inicia sesión.";
            exit;
        }

        $contraseniaHash = password_hash($contrasenia, PASSWORD_DEFAULT);
        try {
            
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("INSERT INTO clientes (nombre, apellido, email, contrasenia, telefono, direccion, ciudad, provincia, codigo_postal, verificacion_token) VALUES (:nombre, :apellido, :email, :contrasenia, :telefono, :direccion, :ciudad, :provincia, :codigo_postal, :token)");
                    
            $stmt->execute([
                ':nombre'       => $nombre,
                ':apellido'     => $apellido,
                ':email'        => $email,
                ':contrasenia'  => $contraseniaHash,
                ':telefono'     => $telefono,
                ':direccion'    => $direccion,
                ':ciudad'       => $ciudad,
                ':provincia'    => $provincia,
                ':codigo_postal'=> $codigo_postal,
                ':token'         => $token
            ]);

            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
            $enlace_verificacion = $baseUrl . '/Harvey-s/autenticacion/verificar.php?token=' . $token;
            
        } catch(PDOException $e) {
            echo "Error al registrar el usuario: " . $e->getMessage();
            exit;
        }
        
        require_once 'correo_bienvenida.php';
        
        if (!enviarCorreoBienvenida($email, $nombre, $enlace_verificacion)) {
            error_log("Error al enviar el correo de bienvenida.");
        }
        
    }
?>
