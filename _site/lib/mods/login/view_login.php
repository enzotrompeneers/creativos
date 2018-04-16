	<div id="login">
		<form action="" id="login" method="post" class="form">
		<div class="row">
			<div class="small-3 columns">
				<label for="user"><?=trad('usuario');?>:</label>
			</div>
			<div class="small-9 columns">
				<input type="text" name="user" id="user" class="form-control" />
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				<label for="user"><?=trad('clave');?>:</label>
			</div>
			<div class="small-9 columns">
				<input type="password" name="pass" id="pass" class="form-control" />
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				
			</div>
			<div class="small-9 columns">
				<input type="submit" value="<?=trad('enviar');?>" class="button expand"  />
			</div>
		</div>			
		</form>
		<br clear="left" />
	</div>
	<script language="javascript" type="text/javascript">
			$(document).ready(function(){
				$('#user').focus();
			});
	</script>
	