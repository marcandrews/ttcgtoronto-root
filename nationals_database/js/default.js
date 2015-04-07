$(function(){
	$.SetImpromptuDefaults({
		opacity: 0.9
	});

	$.datepicker.setDefaults( {
		prevText		: '&larr;', prevStatus: 'Previous month',
		nextText		: '&rarr;', nextStatus: 'Next month',
		dateFormat	: 'yy-mm-dd',
		changeMonth	: true,
		changeYear	: true
	} );

	$('form').submit( function(){
		$(':submit').attr('disabled','disabled').attr('value','Please wait \u2026');
	});
	$('form :reset').click(function() {
		$.prompt('Are you sure you want to reset this form?', { buttons: { Yes: true, No: false }, callback: function(v,m) { if (v) { document.forms[0].reset(); } } });
		return false;
	});
	$('a#navigation_sign_out').click( function() {
		$.prompt('Are you sure you want to sign out?', { buttons: { Yes: true, No: false }, callback: function(v,m) { if (v) { location.href = 'sign.php?out' } } });
		return false;
	});
});
