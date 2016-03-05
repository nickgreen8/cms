function animate(type, element, speed) {
	if (type === 'slideUp') {
		//Get the element height
		var height  = element.clientHeight;
		var padding = window.getComputedStyle(element, null).getPropertyValue('padding').replace('px', '');
		var margin  = window.getComputedStyle(element, null).getPropertyValue('margin').replace('px', '');
		margin = -10;
		console.log('Height: ' + height);
		console.log('Padding: ' + padding);

		//Set the speed
		var interval  = 5;
		if (typeof speed !== 'undefined') {
			if (speed === 'fast') {
				interval = 2;
			} else if (speed === 'slow') {
				interval = 8;
			}
		}
		console.log('Interval: ' + interval);

		//Set css attributes
		element.style.overflowY = 'hidden';

		//Begin animation
		var animation = setInterval(frame, interval);

		//Set frame animation
		function frame() {
			if (height < 0) {
				clearInterval(animation);
				element.className += 'hidden';
				element.style.margin = '0px';
			} else {
				if (padding == height) {
					element.style.padding = padding-- + 'px';
					console.log('New padding: ' + element.style.padding);
				}
				element.style.marginTop = margin-- + 'px';
				element.style.height = height-- + 'px';
				console.log('New height: ' + element.style.height);
		
			}
		}
	}
}