<div class="card">
	<div class="card-header">Cambiar estado</div>
	<div class="card-body">

		<?php 
		$chk = "";
		if (!empty($data_estado->result())) {
			foreach ($data_estado->result() as $key) {
				if ($key->estado == "Activo") {
					$chk = "checked";
				}
				?>
				<div class="form-check form-switch">
					<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
						<input type="checkbox" class="custom-control-input" name="chk_estado" id="chk_estado" onchange="cambiar_estado();" <?=$chk?>>
						<label class="custom-control-label" for="chk_estado">Estado <?=$key->estado?><div id="estado"></div></label>
					</div>
				</div>
			<?php }
		}else{
			?>
			<div class="form-check form-switch">
				<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					<input type="checkbox" class="custom-control-input" name="chk_estado" id="chk_estado" onchange="cambiar_estado();">
					<label class="custom-control-label" for="chk_estado">Estado Inactivo<div id="estado"></div></label>
				</div>
			</div>
		<?php } ?>
	</div>
</div>