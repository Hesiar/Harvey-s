<?php
    $host = 'localhost';
    $dbname = 'harveys_DB';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);

            $categorias_urls = [
                "Bebidas" => "/Harvey-s/secciones/bebidas.php",
                "Carnes y Embutidos" => "/Harvey-s/secciones/carnes.php",
                "Conservas" => "/Harvey-s/secciones/conservas.php",
                "Dulces" => "/Harvey-s/secciones/desayunos.php",
                "Frutas y Verduras" => "/Harvey-s/secciones/frutas_verduras.php",
                "Lácteos" => "/Harvey-s/secciones/lacteos.php",
                "Panadería" => "/Harvey-s/secciones/panaderia.php"
            ];

            $categorias_nombres = [
                "Dulces" => "Desayunos, dulces, frutos secos"
            ];

            $stmt = $pdo->prepare("
                SELECT DISTINCT categoria 
                FROM productos 
                WHERE categoria LIKE :search 
                OR categoria IN (
                    SELECT categoria FROM productos WHERE nombre LIKE :search
                )
                ORDER BY categoria ASC
            ");
            $stmt->execute([':search' => "%$search%"]);

            $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $resultados = [];
            foreach ($categorias as $categoria) {
                if (isset($categorias_urls[$categoria])) {
                    $nombre_mostrar = $categorias_nombres[$categoria] ?? $categoria; 
                    $resultados[] = [
                        "nombre" => $nombre_mostrar,
                        "url" => $categorias_urls[$categoria]
                    ];
                }
            }

            echo json_encode($resultados);
        }
    } catch (PDOException $e) {
        echo json_encode([]);
    }
?>
