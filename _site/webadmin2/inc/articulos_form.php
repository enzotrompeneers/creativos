<?php 
// Latest update 26/12/2014



?>
<?php /*** NUEVA PÁGINA ***/ ?>
<div id="articulos">
<div class="portlet box green tipos">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-star"></i>
			<?=show_label('nueva_pagina');?>
		</div>
		<div class="tools">
			<a class="expand" href="javascript:;" data-original-title="" title=""></a>
		</div>
	</div>
	<div class="portlet-body display-hide">
		<form action="" method="post" id="nueva_clave" class="form-horizontal" role="form">
			<fieldset>
				<input type="hidden" style="dispay:none;" name="act" value="add" />
				<?=inputA('text','nueva_pagina');?>
				<?=selectA('parent_new',$parents);?>	
				<input type="submit" value="<?=show_label('crear')?>" class="btn green input btn-lg" />
				
			</fieldset>
		</form>	
	</div>
</div>

<?php /*** EDITAR PÁGINA ***/ ?>
<div class="portlet box blue tipos">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note"></i>
			<?=show_label('editar');?>: <strong><?=$clave?></strong>
		</div>
		<div class="tools">
			<a class="collapse" href="javascript:;" data-original-title="" title=""></a>
		</div>
	</div>
	<div class="portlet-body">
	<form action="" method="post"  class="form-horizontal">
		<div class="btn-group">
			<button class="btn blue-hoki" type="button"><?=show_label('cambiar_a');?></button>
			<button class="btn blue-hoki dropdown-toggle" data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" type="button">
			<i class="fa fa-angle-down"></i>
			</button>
			<ul class="dropdown-menu" role="menu">
				<?php foreach($cambiar as $k=>$v) : ?>
				<li><a href="articulos.php?clave=<?=$v['id']?>"><strong><?=$v['nombre']?></strong> (<?=$v['titulo']?>)</a></li>
				<?php endforeach ?>
			</ul>
		</div>
		<a href="inc/articulos_orden.php" id="cambiar_orden" class="btn yellow" data-toggle="modal" data-target="#ajax"><?=show_label('cambiar_orden');?></a>
		<input type="submit" value="<?=trad('guardar')?>" class="btn blue" />
		<br />
		<br />
		<?php if ($clave) : ?>
			<div class="tabbable-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tabs-1" data-toggle="tab" aria-expanded="true"><?=show_label('general');?></a></li>
					<li><a href="#tabs-2" data-toggle="tab" aria-expanded="true"><?=show_label('seo');?></a></li>
					<li><a href="#tabs-3" data-toggle="tab" aria-expanded="true"><?=show_label('textos');?></a></li>
					<li><a href="#tabs-4" data-toggle="tab" aria-expanded="true"><?=show_label('imagenes');?></a></li>
					<?php if (table_has_images('articulos','files')) : ?>
					<li><a href="#tabs-5" data-toggle="tab" aria-expanded="true"><?=show_label('documentos');?></a></li>
					<?php endif ?>
				</ul>
				<div class="tab-content">
					<div id="tabs-1" class="tab-pane active">
						<input type="hidden" name="clave" value="<?=$clave?>" />
						<input type="hidden" name="formId" id="formId" value="<?=$id?>" />
						<input type="hidden" name="table" id="table" value="<?=$xname?>_articulos" />
						<input type="hidden" name="act" value="edit" />
						
						<?=selectA('parent_id',$parents);?>
						<?=inputA('checkbox','header_menu');?>
						<?=inputA('checkbox','footer_menu');?>
						<?=inputA('checkbox','privado');?>
						<?php foreach ($languages as $l) : ?>
							<?=inputA('text','titulo_'.$l);?>
						<?php endforeach ?>						
						<?php foreach ($languages as $l) : ?>
							<?=inputA('text','link_'.$l);?>
						<?php endforeach ?>	
						
					</div>
					<div id="tabs-2" class="tab-pane">
						<?php foreach ($languages as $l) : ?>
							<?=inputA('text','slug_'.$l);?>
						<?php endforeach ?>
						<?php foreach ($languages as $l) : ?>
							<?=textareaA('meta_descr_'.$l,TRUE);?>
						<?php endforeach ?>
						<?php foreach ($languages as $l) : ?>
							<?=textareaA('meta_key_'.$l,TRUE);?>
						<?php endforeach ?>
					</div>
					<div id="tabs-3" class="tab-pane">
						<div id="flags_art">
						<?php foreach ($languages as $k => $v) { ?>
							<img src="img/flags/<?=$v?>.png" class="click_<?=$v?>" />&nbsp;
						<?php } ?>
						</div>
						<?php foreach ($languages as $l) : ?>
							<?=textareaA('art_'.$l,FALSE,$l);?>
						<?php endforeach ?>
					</div>
					<div id="tabs-4" class="tab-pane">
						<div id="images_int">
							<input type="file" name="Imagedata" id="Imagedata" />
							<div id="gal" class="sort">
								<ul id="album"></ul>
								<div id="response"></div>
								<div id="queue"></div>
							</div>
						</div>
					</div>
					<?php if (table_has_images('articulos','files')) : ?>
					<div id="tabs-5" class="tab-pane">
						<div id="images_int">
							<input type="file" name="Filedata" id="Filedata" class="Filedata" />
							<div id="files" class="sort" role="files">
								<ul id="fileList" class="list-group"></ul>
								<div id="fileRespone"></div>
								<div id="fileQueue"></div>
							</div>
						</div>
					</div><br clear="all" />
					<?php endif ?>
					<br clear="all"/>
				</div>
			</div>
		<?php endif ?>
			</form>
	</div>
</div>
<div class="portlet box red tipos">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-close"></i>
			<?=show_label('borrar');?>
		</div>
		<div class="tools">
			<a class="expand" href="javascript:;" data-original-title="" title=""></a>
		</div>
	</div>
	<div class="portlet-body display-hide">
		<a href="articulos.php?act=delete&id=<?=$id?>" class="borrar btn red">
		<i class="glyphicon glyphicon-remove" style="color:#fff;"> </i>
		<?=show_label('borrar');?>
		</a>
	</div>
</div>
        </div><!--/articulos--><br clear="all" />