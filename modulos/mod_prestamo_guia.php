					<?php require_once('common.php'); checkUser();	?>
					<div class="row" >
						<div class="col-xs-12">
							<div class="callout callout-warning">
								<h4>Información Importante!</h4>
								<p>Recuerde que el prestamos de Guías es solo de uso exclusivo y autorizado por personal que tiene nivel de aprobación dentro de la organización, ya que esto afecta la numeración.</p>
							</div>
						</div>
					</div>
					
					<div class="row" style="margin-top:20px">
							
							<div class="col-xs-6">
								<label>N° de Guia:</label>
								<?php
									$guia="0000000";
									$sql="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 ORDER BY id_movimiento DESC LIMIT 1";
									$res=abredatabase(g_BaseDatos,$sql);
									$row=dregistro($res);
									$guia= $row['guia']."-".substr($guia,1,strlen($guia)-strlen($row['siguiente'])).$row['siguiente'];
									cierradatabase();
								?>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="guia_prestamo" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $guia; ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>>
								</div>
							</div>
							
							<div class="col-xs-3">
								<label>Usuario:</label>
								
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="usuario_aprovacion" type="text" class="form-control pull-right" placeholder="Usuario Autorizado" required="required" value="" >
								</div>
							</div>
							
							<div class="col-xs-3">
								<label>Clave de Aprobación:</label>
								
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="clave_aprovacion" type="password" class="form-control pull-right" placeholder="Password Autorizado" required="required" value="" >
								</div>
							</div>
						
					</div>
					
					<div class="row" style="margin-top:20px">
						<div class="col-xs-12">
							<label>Observaciones</label>
							<textarea id="observaciones_prestamo" placeholder="Observaciones" class="form-control" rows="4"></textarea>
						</div>
					</div>
			