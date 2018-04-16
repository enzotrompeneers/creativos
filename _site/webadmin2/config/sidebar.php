<li class="start">
	<a href="javascript:;">
	<i class="icon-book-open"></i>
	<span class="title"><?=show_label('contenido')?></span>
	<span class="selected"></span>
	<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<li>
			<a href="traducciones.php?idioma=<?=$language?>">
			<i class="icon-flag"></i>
			<?=show_label('traducciones')?></a>
		</li>
		<li>
			<a href="articulos.php?idioma=<?=$language?>">
			<i class="icon-docs"></i>
			<?=show_label('paginas')?></a>
		</li>
		<?=getLink('noticias',show_label('noticias'),'icon-book-open')?>
		<?=getLink('emails',show_label('emails'),'icon-envelope-open')?>
		<?=getLink('panoramicas',show_label('panoramicas'),'icon-camera',0)?>	
		<?=getLink('config',show_label('config'),'icon-info')?>
	</ul>
</li>
<li>
	<a href="javascript:;">
	<i class="icon-home"></i>
	<span class="title"><?=show_label('proyectos')?></span>
	<span class="selected"></span>
	<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?=getLink('proyectos_slider',show_label('proyectos_slider'))?>
		<?=getLink('proyectos',show_label('proyectos'))?>
		<?=getLink('familias',show_label('familias'))?>
		<?=getLink('categorias',show_label('categorias'))?>
		<?=getLink('colores',show_label('colores'))?>
	</ul>
</li>
<li>
	<a href="javascript:;">
	<i class="icon-home"></i>
	<span class="title"><?=show_label('hospedaje')?></span>
	<span class="selected"></span>
	<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?=getLink('hosting',show_label('cuentas'))?>
		<?=getLink('statuses',show_label('statuses'))?>
	</ul>
</li>
<li>
	<a href="javascript:;">
	<i class="icon-settings"></i>
	<span class="title"><?=show_label('admin')?></span>
	<span class="selected"></span>
	<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?=getLink('admins',show_label('admins'),'icon-user')?>
		<?=getLink('contactos',show_label('contactos'),'icon-speech')?>
	</ul>
</li>
