window.onload = function() {
	if(typeof oldIE === 'undefined' && Object.keys)
	hljs.initHighlighting();
	baguetteBox.run('.baguetteBoxOne');
	baguetteBox.run('.baguetteBoxTwo');
	baguetteBox.run('.baguetteBoxThree', {
		animation: 'fadeIn',
	});
	baguetteBox.run('.baguetteBoxFour', {
		buttons: false
	});
	baguetteBox.run('.baguetteBoxFive');
};