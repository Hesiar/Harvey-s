<?php
    if (!isset($_SESSION['empleado'])) {
        header("Location: ../layout/home.php");
        exit;
    }

    $host = "localhost";
    $dbname = "harveys_DB";
    $dbuser = "root";
    $dbpass = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(total) AS ingresos FROM ventas GROUP BY mes ORDER BY mes ASC");
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }

    $meses = [
        "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril",
        "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto",
        "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
    ];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="/Harvey-s/elementos/css/css_graficos.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Historial de Ventas</h1>
            <div class="button-container">
                <button onclick="cambiarGrafico('bar')">Gráfico de Barras</button>
                <button onclick="cambiarGrafico('line')">Gráfico Lineal</button>
            </div>
        </div>

        <div class="chart">
            <canvas id="graficoVentas"></canvas>
        </div>
    </div>

    <script>
        const meses = <?= json_encode($meses); ?>;
        const datos = <?= json_encode($datos); ?>;
        
        const labels = datos.map(d => {
            const [year, month] = d.mes.split("-");
            return `${meses[month]} ${year}`;
        });

        const ingresos = datos.map(d => d.ingresos);

        let tipoGrafico = 'bar';
        let chartInstance;

        function renderizarGrafico() {
            const ctx = document.getElementById('graficoVentas').getContext('2d');

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: tipoGrafico,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ingresos Mensuales',
                        data: ingresos,
                        backgroundColor: tipoGrafico === 'line' || tipoGrafico === 'bar' ? 'rgba(75, 192, 192, 0.2)' : 'transparent',
                        borderColor: 'rgb(12, 190, 68)',
                        borderWidth: 2,
                        fill: tipoGrafico === 'line'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value + " €"; 
                                }
                            }
                        }
                    }
                }
            });
        }

        window.cambiarGrafico = function(tipo) {
            tipoGrafico = tipo;
            renderizarGrafico();
        };

        renderizarGrafico();
    </script>
</body>
</html>
