	// "Cache"
var classColors = {};

function rgb2hex(rgb) {
		if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hexToRgbA(hex,alphaCanal){
		var c;
		if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
			c= hex.substring(1).split('');
			if(c.length== 3){
				c= [c[0], c[0], c[1], c[1], c[2], c[2]];
			}
			c= '0x'+c.join('');
			return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alphaCanal+')';
		}
		throw new Error('Bad Hex');
		
}
	
	


function getColor(className) {
    // Check for the color
    if (!classColors[className]) {

        // Create an element with the class name and add it to the dom
        $c = $('<div class="' + className + '"></div>').css('display', 'none');
        $(document.body).append($c);

        // Get color from dom and put it in the color cache
        classColors[className] = $c.css('color');

        // Remove the element from the dom again
        $c.remove();
    }

    // Return color
    return classColors[className];
}

function blendColors(c0, c1, p) {
	var f=parseInt(c0.slice(1),16),t=parseInt(c1.slice(1),16),R1=f>>16,G1=f>>8&0x00FF,B1=f&0x0000FF,R2=t>>16,G2=t>>8&0x00FF,B2=t&0x0000FF;
	return "#"+(0x1000000+(Math.round((R2-R1)*p)+R1)*0x10000+(Math.round((G2-G1)*p)+G1)*0x100+(Math.round((B2-B1)*p)+B1)).toString(16).slice(1);
}