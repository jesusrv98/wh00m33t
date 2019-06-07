<?php
ob_start();

//GRÁFICO CANTIDAD USUARIOS POR COMUNIDAD
$valoresNombreComunidad = array();
$valoresCantidadComunidad = array();
while ($ver = mysqli_fetch_row($params['resultadoPorComunidad'])) {
    $valoresNombreComunidad[] = $ver[0];
    $valoresCantidadComunidad[] = $ver[1];
}
$datosNombreComunidad = json_encode($valoresNombreComunidad);
$datosCantidadComunidad = json_encode($valoresCantidadComunidad);

//GRÁFICO CANTIDAD USUARIOS POR PROVINCIA
$valoresNombreProvincia = array();
$valoresCantidadProvincia = array();
while ($ver = mysqli_fetch_row($params['resultadoPorProvincia'])) {
    $valoresNombreProvincia[] = $ver[0];
    $valoresCantidadProvincia[] = $ver[1];
}
$datosNombreProvincia = json_encode($valoresNombreProvincia);
$datosCantidadProvincia = json_encode($valoresCantidadProvincia);

//GRÁFICO CANTIDAD ESTADOS POR USUARIO
$valoresNombreUsuario = array();
$valoresCantidadEstados = array();
while ($ver = mysqli_fetch_row($params['cantidadEstadosPorUsuario'])) {
    $valoresNombreUsuario[] = $ver[0];
    $valoresCantidadEstados[] = $ver[1];
}
$datosNombreUsuarios = json_encode($valoresNombreUsuario);
$datosCantidadEstados = json_encode($valoresCantidadEstados);
?>
<script src="js/jqueryGoogle.js"></script>
<script>
    function crearCadenaBarras(json){
        var parsed = JSON.parse(json);
        var arr = [];
        for(var x in parsed){
            arr.push(parsed[x]);
        }
        return arr;
    }
</script>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 d-block mt-5 mb-5">
                <h3 class="text-center" style="color: #33cbad;">Cantidad de usuarios por comunidad autónoma</h3>
                <div id="usuariosPorComunidad"></div>
            </div>
            <div class="col-12 d-block mt-5 mb-5">
                <h3 class="text-center" style="color: #33cbad;">Cantidad de usuarios por provincia</h3>
                <div id="usuariosPorProvincia"></div>
            </div>
            <div class="col-12 d-block mt-5 mb-5">
                <h3 class="text-center" style="color: #33cbad;">Cantidad de publicaciones por usuario</h3>
                <div id="estadosPorUsuarios"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        //GRÁFICO CANTIDAD USUARIOS POR COMUNIDAD
        var datosNombreComunidad=crearCadenaBarras('<?php echo $datosNombreComunidad ?>');
        var datosCantidadComunidad=crearCadenaBarras('<?php echo $datosCantidadComunidad ?>');
        var trace1 = { type: 'bar', x: datosNombreComunidad, y: datosCantidadComunidad, marker: { color: '#33cbad', line: { width: 2.5 } } };
        var data = [trace1];
        var layout = { font: { size: 15 }};
        Plotly.newPlot('usuariosPorComunidad', data, layout, { responsive: true });

        //GRÁFICO CANTIDAD USUARIOS POR PROVINCIA
        var datosNombreProvincia=crearCadenaBarras('<?php echo $datosNombreProvincia ?>');
        var datosCantidadProvincia=crearCadenaBarras('<?php echo $datosCantidadProvincia ?>');
        var trace2 = { type: 'bar', x: datosNombreProvincia, y: datosCantidadProvincia, marker: { color: '#33cbad', line: { width: 2.5 } } };
        var data = [trace2];
        var layout = { font: { size: 15 }};
        Plotly.newPlot('usuariosPorProvincia', data, layout, { responsive: true });

        //GRÁFICO CANTIDAD ESTADOS POR USUARIO
        var datosNombreUsuario=crearCadenaBarras('<?php echo $datosNombreUsuarios ?>');
        var datosCantidadEstados=crearCadenaBarras('<?php echo $datosCantidadEstados ?>');
        var trace3 = { type: 'bar', x: datosNombreUsuario, y: datosCantidadEstados, marker: { color: '#33cbad', line: { width: 2.5 } } };
        var data = [trace3];
        var layout = { font: { size: 15 }};
        Plotly.newPlot('estadosPorUsuarios', data, layout, { responsive: true });
</script>
</body>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Estadísticas </title>
</head>
<?php include 'layout.php' ?>