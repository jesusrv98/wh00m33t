<?php if(!isset($_SESSION['usuarioconectado'])){ header("Location:index.php"); } ob_start() ?>

 <table border="1">
     <tr>
         <th>alimento (por 100g)</th>
         <th>energía (Kcal)</th>
         <th>Proteina (g)</th>
         <th>Hidratos de Carbono (g)</th>
         <th>Fibra (g)</th>
         <th>grasa (g)</th>
     </tr>
     <?php foreach ($params['alimentos'] as $alimento) :?>
     <form method="post" name="formulario" action="index.php?ctl=editar">
     <tr>
         <td>
            <input type='text' style="text-align: center;" name="alimento" value="<?php echo $alimento['nombre']?>">
         </td>
         <td>
            <input type='text' style="text-align: center;" name="energia" value="<?php echo $alimento['energia']?>">
        </td>
        <td>
            <input type='text' style="text-align: center;" name="proteina" value="<?php echo $alimento['proteina']?>">
        </td>
        <td>
            <input type='text' style="text-align: center;" name="hidratocarbono" value="<?php echo $alimento['hidratocarbono']?>">
        </td>
        <td>
            <input type='text' style="text-align: center;" name="fibra" value="<?php echo $alimento['fibra']?>">
        </td>
        <td>
            <input type='text' style="text-align: center;" name="grasa" value="<?php echo $alimento['grasatotal']?>"> 
        </td>
         <td>
                <input type="hidden" name="oculto" value="<?php echo $alimento['id']?>">
                <input type="submit" value="Confirmar edición" style="color: red;font-weight: bold;margin-top: 1em">
        </td>
     </tr>
    </form>
     <?php endforeach; ?>

 </table>


 <?php $contenido = ob_get_clean() ?>

 <?php include 'layout.php' ?>
