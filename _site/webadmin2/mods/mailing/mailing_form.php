<input type="hidden" id="no_viviendas" value="<?=trad('error_no_viviendas')?>" />
<input type="hidden" id="no_emails" value="<?=trad('error_no_emails')?>" />
<input type="hidden" id="trad_grupo" value="<?=trad('grupo')?>" />
<input type="hidden" id="trad_email" value="<?=trad('email')?>" />
<input type="hidden" id="trad_borrar" value="<?=trad('borrar')?>" />
<div class="row">
<div class="col-md-7">

	<div class="form-group">
		<label for="lang" class="col-md-2 control-label"><?=trad('idioma')?></label>
		<div class="col-md-5">
			<?=selectA('lang', $aIdiomas, false, false);?>
		</div>
	</div>
	

	<div class="form-group">
		<label class="col-md-2 control-label"><?=trad('vivienda')?></label>
		<div class="col-md-5">
			<div class="input-group">
				<?=selectA('vivienda', $viviendas, false, false);?>&nbsp;
				<span class="input-group-btn">
					<button  id="addVivienda" class="btn btn-sm green" style="margin-top: -19px;line-height:17px;" type="button"><i class="fa fa-plus-circle"></i> <?=show_label('anadir')?></button>
				</span>
			</div>
		</div>
	</div>	
	
</div>
<div class="col-md-5">
	<div id="viviendas"></div>
</div>
</div>
<!--
<div class="col-md-12 col-sm-12">
	<div class="portlet box blue-hoki tipos">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-note"></i>
				<?=trad('busqueda')?>
			</div>
			<div class="tools">
				<a class="expand" href="javascript:;" data-original-title="" title=""></a>
			</div>
		</div>
		<div class="portlet-body display-hide">
			<div class="col-md-6">
				<form action="mods/mailing/mailing_search.php" method="get" id="searchForm">
					<?=selectA('tipoventa',getData('clases'));?>
					<?=selectA('tipovivienda',getData('tipos'));?>
					<?=selectA('localizacion',getData('localidades'));?>
					<?=selectA('dormitorios',getRange(1,10));?>
					<?=selectA('banos',getRange(1,10));?>
					<?=inputA('number','precio_desde');?>
					<?=inputA('number','precio_hasta');?>
					<?=selectA('piscina',getData('piscinas'));?>
					<?=selectA('aparcamiento',getData('parkings'));?>
					<?=selectA('vistas',getData('vistas'));?>
					<?=selectA('jardines',getData('jardines'));?>
					<?=selectA('orientaciones',getData('orientaciones'));?>
					<?=selectA('airco',getYesNo());?>
					<?=selectA('terraza',getYesNo());?>
					<?=inputA('text','text');?>
					<label class="col-md-6 control-label" for=""></label>
					<div class="col-md-6">
						<input type="submit" value="<?=trad('buscar');?>" name="submit" class=" form-control input-large btn green">
					</div>
				</form>
			</div>
			<div class="col-md-1"></div>
			<div class="col-md-5"  id="searchFormRight"></div>
			<br clear="all"/>
		</div>
	</div>	
</div>
-->


<br clear="all" />

<div class="row">
	<div class="col-md-6">

		<div class="form-group">
			<label class="col-md-2 control-label">Email</label>
			<div class="col-md-10">
				<div class="input-group">
					<div class="input-icon">
						<i class="fa fa-envelope-o fa-fw"></i>
						<?=inputA('text', 'email', false, false);?>
					</div>
					<span class="input-group-btn">
					<button id="addEmail" class="btn btn-success green" style="margin-top: -5px;" type="button"><i class="fa fa-plus-circle"></i> <?=show_label('anadir')?></button>
					</span>
				</div>
			</div>
		</div>
				
	</div>

	<div class="col-md-6">
		<div id="emails"></div>
		<div id="emailGroupList"></div>
	</div>
</div>


<br clear="all"/>
<h4 class="list-group-item bg-blue-hoki"><?=trad('email_content')?></h4>
<br clear="all"/>
<div class="col-md-12">
	
	<form action="" id="datos" method="post">
		<?=inputA('text','asunto')?>
		<?=textareaA('mensaje');?>
		<input type="hidden" name="lang" id="idioma" value="<?=$languages[0]?>" />
		<input type="hidden" name="viviendasInput" id="viviendasInput" value='viviendasInput' />
		<br clear="all" />
		<div class="form-group">
			<input type="submit" value="<?=trad('enviar');?>" name="submit" id="addVivienda" class="  btn-lg  btn-block btn red-thunderbird">
		</div>
	</form>
</div>

<br clear="all"/>
<!--
<div class="col-md-12 col-sm-12">
	<div class="portlet box red tipos">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-note"></i>
				<?=trad('pdf')?>
			</div>
			<div class="tools">
				<a class="collapse" href="javascript:;" data-original-title="" title=""></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="col-md-12">
				<form action="<?= $base_site.$language ?>/ajax/getpdf-multiple/" method="post" id="pdfForm" >
					<input type="hidden" name="idioma" id="pdfIdioma"  value="<?= $language ?>" />
					<button type="submit" class="btn btn-xlg red" id="send_pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;<?=trad('descargar_pdf')?></button>
				</form>
				
			</div>
			<br clear="all"/>
		</div>
	</div>	
</div>
-->