<?php if(!isset($_SESSION['usuarioconectado'])){ header("Location:index.php"); } ob_start() ?>

 <table border="1">
     <tr>
         <th>alimento (por 100g)</th>
         <th>energía (Kcal)</th>
         <th>grasa (g)</th>
     </tr>
     <?php foreach ($params['alimentos'] as $alimento) :?>
     <tr>
         <td><a href="index.php?ctl=ver&id=<?php echo $alimento['id']?>">
                 <?php echo $alimento['nombre'] ?></a></td>
         <td><?php echo $alimento['energia']?></td>
         <td><?php echo $alimento['grasatotal']?></td>
         <td>
            <form method="post" action="index.php?ctl=eliminar">
                <input type="hidden" name="oculto" value="<?php echo $alimento['id']?>">
                <input type="submit" value="X" style="color: red;font-weight: bold;margin-top: 1em">
            </form>
        </td>
     </tr>
     <?php endforeach; ?>

 </table>


 <?php $contenido = ob_get_clean() ?>

 <?php include 'layout.php' ?>
