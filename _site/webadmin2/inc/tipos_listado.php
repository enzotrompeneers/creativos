<div id="listado">
	<p class="titulo"><?=show_label("resultados")?>: <?=$tableName ?></p> 
	<div class="table-responsive">
	<table id="myTable" class="table table-striped table-hover table-bordered"> 
	<thead> 
		<tr>
			<th>Id</th> 
			<?php if ($hasImages) :  ?>
			<th><?=show_label('image')?></th>
			<?php endif ?>
            <?php
			foreach($arrPieces as $th){ 
				$labelname = str_replace("_id","",$th);
				echo("<th>".show_label($labelname)."</th>"); 
			}
			?>       
        <th><?=show_label("editar")?></th> 
        <th><?=show_label("borrar")?></th> 
    </tr> 
    </thead> 
    <tbody> 
    <?php
        if(!empty($records)){ 
        foreach($records as $k => $v){
    ?>
        <tr> 
            <td><?= $v['id']; ?></td> 
			<?php if ($hasImages) :  ?>
			<td class="thumbnailImage"><?= getImagePath($v['image'], $folder, $v['id'], 's') ?></td>
			<?php endif ?>
			
            <?php
	   foreach($arrPieces as $mf){  	
		   $val = $v[$mf];
		   			// Check if table ends in _id, if so, get its value from the parent table
		   if(preg_match("/_id$/",$mf)) {
					   $parent = pluralize(substr($mf,0,-3));
					   	foreach ($exceptionsArray as $x=>$y) {
							$parent = ($parent==$xname.'_'.$x)?$xname.'_'.$y:$parent;
						}
						$field		= $optionsArray['default'];
					   foreach ($optionsArray as $x=>$y) {
							if ($x==str_replace($xname.'_','',$parent)) {
								$field		= $y;
							}
					   }
					
					   $idQuery = "SELECT {$field} FROM ".$parent." WHERE id = ".$val;
					   // echo $idQuery.'<br />';
					   $res = record($idQuery);
					   $val = $res[$field];
					   $val = ($parent==$xname.'_fichas')?$res['titulo_es']:$val;
					   $val = ($parent==$xname.'_promociones')?$res['nombre']:$val;
					   //echo $val;
		   }

		   // Check if value is a 1 or 0, to be replaced by an image 
		   if (in_array($mf,$symbolArray)){
			   switch($val){
				   case "1":
							  echo('<td><center><i class="glyphicon glyphicon-ok"></i> </center></td>'); 
							  break;
				   default:

							  echo('<td><center><i class="glyphicon glyphicon-remove" ></i> </center></td>'); 
							  break;
				}
		    } 
		   else {
			echo("<td>".$val."</td>"); 
		   }
	   }
		?>
            <td style="width:80px;">
				<i class="icon-note azul" ></i>&nbsp;<a class="edit" href="?action=editar&id=<?=$v['id'];?>&table=<?=$table;?>&titulo=<?=$tableName?>"><?=$editButton?></a>
			</td> 
            <td style="width:80px;">
				<i class="glyphicon glyphicon-remove rojo" ></i><a class="remove borrar  rojo" href="?action=borrar&id=<?=$v['id'];?>&table=<?=$table;?>&titulo=<?=$tableName?>">&nbsp;<?=$deleteButton?></a>
			</td> 
        </tr> 
    <?php } }?>
    </tbody> 
    </table>
</div>
</div>