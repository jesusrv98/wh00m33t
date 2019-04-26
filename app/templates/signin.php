 <?php ob_start() ?>

      <form name="signin" action="index.php?ctl=signin" method="POST">
          <table>
              <tr>
                  <td>Usuario:</td>
                  <td><input type="text" name="nombre" value="<?php echo $params['nombre']?>"></td>
							</tr>
							<tr>
									<td>Password:</td>
                  <td><input type="password" name="pass" value="<?php echo $params['pass']?>"></td>
							</tr>
							<tr>
                  <td><input type="submit" value="Registrarse"></td>
              </tr>
          </table>
      </form>

      <?php if (count($params['resultado'])==0): ?>
        
        <span><?php echo $params['mensaje']?></span>
			 
			<?php else:?>
 				<b><span style="color: red;"><?php echo $params['mensaje'] ?></span></b>
 			
      <?php endif; ?>
			

      <?php $contenido = ob_get_clean() ?>

      <?php include 'layout.php' ?>
