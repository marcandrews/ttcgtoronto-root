<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<script type="text/javascript" src="/n/templates/ttcg/js/jquery.js"></script>
<script type="text/javascript">
$(function(){
	$('#photo_visibility').css('white-space', 'nowrap').toggle(function(){
		$(this).html('[ hide photo ]');
		$('#home .column').fadeTo('slow', 0.1);
	},function(){
		$(this).html('[ see photo ]');
		$('#home .column').fadeTo('slow', 0.9);
	});
});
</script>
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/ttcg/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/ttcg/css/ttcg.css" type="text/css" />
</head>
<body>
<div id="top"></div>
<div id="wrapper">
	<div id="coat_of_arms"><img src="/n/templates/ttcg/images/coat_of_arms.jpg" width="55" height="55" alt="Coat of Arms" /></div>
	<div id="title"><?php echo $mainframe->getCfg('sitename'); ?>
		<ul>
			<li><abbr title="Telephone">T</abbr>: 1 416 495 9442</li>
			<li><abbr title="Email">E</abbr>: congen@ttconsulatetoronto.com</li>
			<li><abbr title="Website">W</abbr>: ttconsulatetoronto.com</li>
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

<?php if ($this->countModules( 'top' )) : ?>
<jdoc:include type="modules" name="top" />
<?php endif; ?>

<?php if ($this->countModules( 'home_column_middle' )) : ?>
<div class="column">
<jdoc:include type="component" style="xhtml" />
</div>

<div class="column">
<jdoc:include type="modules" name="home_column_middle" />
</div>
<?php endif; ?>

<?php if ($this->countModules( 'home_column_left' )) : ?>
<div class="home_column">
<jdoc:include type="modules" name="home_column_left" />
</div>
<?php endif; ?>

	</div>
	<div id="photo_description">
		<strong>Ministry of Foreign Affairs</strong>.
		This building, Knowsley, occupies the entire block from Queen's Park West to Albion Street, between Chancery Lane and Dundonald Street. It was designed and constructed in 1904 by Taylor Gillies, at a cost of $100,000 for William Gordon Gordon. Gordon resided at Knowsley for many years. It has been recorded that the building might have been so named after the residence of Gordon's friend in Cheshire, Lord Derby. Today, it houses the Ministry of Foreign Affairs.
		&copy; The Toronto Consulate General of The Repulic of Trinidad & Tobago.
<?php if ($this->countModules( 'home_column_middle' )) : ?>
		<a href="#" id="photo_visibility">[ see photo ]</a>
<?php endif; ?>
	</div>



<?php if ($this->countModules( 'home_column_middle' ) == 0) : ?>

<?php if ($this->countModules( 'breadcrumbs' )) : ?>
<jdoc:include type="modules" name="breadcrumbs" />
<?php endif; ?>

<div id="component">
<jdoc:include type="component" style="xhtml" />
</div>

<?php endif; ?>



	<div align="center"><a href="#top">&circ; Top</a></div>
</div>
<div id="footer">
	Copyright &copy; 2008 The Toronto Consulate General of The Repulic of Trinidad & Tobago.
</div>
</body>
</html>