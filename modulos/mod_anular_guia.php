					<?php require_once('common.php'); checkUser(); 
					
					?>
					<form id=form_anular action="javascript:anular_guia_definitivo(<?php echo $_POST['f']==0? '0': '1'; ?>);" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title">
						<span class="fa fa-fa-exclamation-triangle" style="margin-right:10px"></span>
						Anular Guia
					</h2>
				</div>
				<div class="modal-body"  >
						
				
					<div class="row" >
						<div class="col-xs-12">
							<div class="callout callout-danger">
								<h4>Información Importante!</h4>
								<p>Recuerde que anular la Guía es solo de uso exclusivo y autorizado por personal que tiene nivel de aprobación dentro de la organización, ya que esto afecta la numeración y el inventario.</p>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top:20px">
							
							<div class="col-xs-3">
								<label>N° de Guia:</label>
								<?php
									if ($_POST['f']==0){
										$guia="0000000";
										$sql="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 ORDER BY id_movimiento DESC LIMIT 1";
										$res=abredatabase(g_BaseDatos,$sql);
										$row=dregistro($res);
										$guia= $row['guia']."-".substr($guia,1,strlen($guia)-strlen($row['siguiente'])).$row['siguiente'];
										cierradatabase();
									}else{
										$guia=$_POST['guia'];
									}
								?>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="guia_anular_modal" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $guia; ?>"   <?php echo $_SESSION['rol']<4? 'disabled="disabled"' : ''; ?> >
								</div>
							</div>
							
							<div class="col-xs-3">
								<label>Fecha de Anulación:</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input id="fe_anular" type="text" class="form-control pull-right" placeholder="Fecha de Anulación" required="required" value="<?php echo date('d/m/Y'); ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>>
									</div>
							</div>
							
							<div class="col-xs-3">
								<label>Usuario:</label>
								
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="usuario_aprovacion_anular" type="text" class="form-control pull-right" placeholder="Usuario Autorizado" required="required" value="" >
								</div>
							</div>
							
							<div class="col-xs-3">
								<label>Clave de Aprobación:</label>
								
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-arrow-circle-right"></i>
									</div>
									<input id="clave_aprovacion_anular" type="password" class="form-control pull-right" placeholder="Password Autorizado" required="required" value="" >
								</div>
							</div>
					
					</div>
					
					<div class="row" style="margin-top:20px">
						<div class="col-xs-12">
							<label>Observaciones</label>
							<textarea id="observaciones_anular" placeholder="Observaciones" class="form-control" rows="4"></textarea>
						</div>
					</div>
					</div>
					<div class="modal-footer"  style="text-align:center">
					
						<button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancelar</button>
						<button type="submit" class="btn btn-success" >Aceptar</button>
					</div>
				</form>