    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<base href= "<?=$base_site?>" />
	<meta name="language" content="<?=$language?>" />
	<meta name="domain" content="<?=$_SERVER['HTTP_HOST']?>" />
	<?php include('inc/controlador_metas.php');?>
    
	<?= getHreflangLinks() ?>
	
	<title><?=$meta['titulo']?></title>
    <meta name="author" content="Brunel-Encantado | www.brunel-encantado.com" />
	<meta name="ROBOTS" content="INDEX,FOLLOW" />
	<meta name="revisit-after" content="7 days" />
	<meta name="Keywords" content="<?=$meta['key']?>" />
	<meta name="Description" content="<?=$meta['descr']?>" />
    
	<meta property='og:url' content="<?= curPageURL() ?>" />
	<meta property="og:type" content="website" />
	<meta property='og:description' content='<?=$meta['descr']?>' />
	<meta property="og:title" content="<?=$meta['titulo']?>" /> 
	<meta property="og:image" content="<?=$meta['img']?>"/>
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="<?=$meta['imgWidth']?>" />
	<meta property="og:image:height" content="<?=$meta['imgHeight']?>" />
	<meta property="og:image:alt" content="<?=$meta['titulo']?>" />
	<meta property="og:site_name" content="<?=webConfig('nombre')?>" />
    
	<link rel="shortcut icon" href="favicon.ico" />

    <link rel="stylesheet" href="css/foundation-sites.css" />
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="css/jquery.bxslider.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/layout.min.css" />
    
    <script async>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-78063503-1', 'auto');
	  ga('send', 'pageview');
	</script>
