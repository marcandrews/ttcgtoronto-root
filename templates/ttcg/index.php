<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



global $database;

$sql = 'SELECT filename, title, description FROM #__phocagallery WHERE catid = 1 ORDER BY RAND() LIMIT 1';

$database->setQuery($sql);

if ($result = $database->query()) {

	$rows = $database->loadObjectList();

	$photo['location']		= 'images/phocagallery/'.$rows[0]->filename;

	$photo['title']				= $rows[0]->title;

	$photo['description']	= $rows[0]->description ; 

} else {

	echo '<!-- Could not retrieve header photos: '.$database->stderr().' -->';

	$photo['location']		= 'templates/ttcg/images/frontpage/knowsley.JPG';

	$photo['title']				= 'Ministry of Foreign Affairs';

	$photo['description']	= 'This building, Knowsley, occupies the entire block from Queen\'s Park West to Albion Street, between Chancery Lane and Dundonald Street. It was designed and constructed in 1904 by Taylor Gillies, at a cost of $100,000 for William Gordon Gordon. Gordon resided at Knowsley for many years. It has been recorded that the building might have been so named after the residence of Gordon\'s friend in Cheshire, Lord Derby. Today, it houses the Ministry of Foreign Affairs.'; 

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<head>

<jdoc:include type="head" />

<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/ttcg/javascript/jquery.js"></script>

<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/ttcg/javascript/jquery.innerfade.js"></script>

<script type="text/javascript">

$(function(){

	/* Hide email address */

	var spt = $('span.email');

	var at = / at /;

	var dot = / dot /g;

	var addr = $(spt).text().replace(at,"@").replace(dot,".");

	$(spt).after(addr);

	$(spt).remove();

<?php if ($this->countModules( 'home_column_middle or home_column_left' )) : ?>



/* Cycle news feeds */

	$('.newsfeed').innerfade({

		animationtype: 'fade', speed: 2000, timeout: 20000, type: 'sequence', containerheight: '90px'

	});



	/* Toggle header photo visibility */

	$('#photo_visibility').show().css('white-space', 'nowrap').toggle(function(){

		$(this).html('[ hide photo ]');

		$('#home .header_columns').fadeTo('slow', 0.1);

	},function(){

		$(this).html('[ see photo ]');

		$('#home .header_columns').fadeTo('slow', 0.85);

	});

<?php endif; ?>

});

</script>

<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/ttcg/css/reset.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/ttcg/css/screen.css" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/ttcg/css/print.css" type="text/css" media="print" />

<?php if ($result) { ?><style> #home { background-image: url(<?php print $photo['location'] ?>); } </style><?php } ?>

</head>



<body>

<div id="top"></div>

<div id="wrapper">

	<div id="coat_of_arms"><img src="<?php echo $this->baseurl; ?>/templates/ttcg/images/coat_of_arms.jpg" width="55" height="55" alt="Coat of Arms" /></div>

	<div id="title">

		<h1><?php echo $mainframe->getCfg('sitename'); ?></h1>

		<ul>

			<li><abbr title="Telephone">T</abbr>: <?php print $this->params->get( 'tel' ); ?></li>

			<li><abbr title="Email">E</abbr>: <span class="email"><?php print str_replace(array('@', 'dot'), array(' at ', ' dot '), $this->params->get( 'email' )) ?></span></li>

			<li><abbr title="Website">W</abbr>: <?php print $_SERVER['SERVER_NAME'] ?></li>

<?php if ($this->countModules( 'search' )) : ?>

			<li class="right">

<jdoc:include type="modules" name="search" />

			</li>

<?php endif; ?>

<?php if ($this->countModules( 'sitemap' )) : ?>

			<li class="right">

<jdoc:include type="modules" name="sitemap" />

			</li>

<?php endif; ?>

		</ul>

	</div>

	<div id="home">

<?php if ($this->countModules( 'mainmenu' )) : ?>

		<jdoc:include type="modules" name="mainmenu" />

<?php endif; ?>

<?php if ($this->countModules( 'home_column_left' )) : ?>

		<div class="header_columns">

<jdoc:include type="component" style="xhtml" />

<jdoc:include type="modules" name="home_column_left" style="xhtml" />

		</div>





<?php endif; ?>



<?php if ($this->countModules( 'home_column_middle' )) : ?>

		<div class="header_columns">

<jdoc:include type="modules" name="home_column_middle" />

		</div>

<?php endif; ?>

	</div>

	<div id="photo_description">

		<?php if (!empty($photo['title'])) print '<strong>'.$photo['title'].'</strong>. ' ?><?php if (!empty($photo['description'])) print $photo['description'].' ' ?><?php if ($this->countModules( 'home_column_middle' )) : ?> <a href="#" id="photo_visibility">[ see photo ]</a><?php endif; ?>

	</div>

<?php if ($this->countModules( 'home_column_middle or home_column_left' ) == 0) : ?>

<?php if ($this->countModules( 'breadcrumbs' )) : ?>

<jdoc:include type="modules" name="breadcrumbs" />

<?php endif; ?>

	<div id="component">

<jdoc:include type="component" style="xhtml" />

	</div>

<?php endif; ?>

	<div id="top_link" align="center"><a href="#top">&circ; Top</a></div>

</div>

<div id="footer">

	Copyright &copy; <?php print date('Y') ?> The Consulate General of The Republic of Trinidad &amp; Tobago.

</div>

</body>

</html>