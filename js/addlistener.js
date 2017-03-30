function addListener(element, type, expression, bubbling) {
	if(window.addEventListener) { // Voor alle !IE browsers
	element.addEventListener(type, expression, bubbling);
	return true;
	} else if(window.attachEvent) { // Voor oude IE versies
	element.attachEvent('on' + type, expression);
	return true;
	} else {
	return false; }
}
//addListener(window, 'load', mijnFunctie, false); //ID - event - result - false (structuur aflopen)
