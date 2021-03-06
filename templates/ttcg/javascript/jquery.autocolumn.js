// version 1.1.0
// http://welcome.totheinter.net/columnizer-jquery-plugin/
// created by: Adam Wulf adam.wulf@gmail.com

(function($){
 $.fn.columnize = function(options) {


	var defaults = {
		width: 400,
		columns : false,
		buildOnce : false,
		overflow : false
	};
	var options = $.extend(defaults, options);

    return this.each(function() {
	    var $inBox = $(this);
		var maxHeight = $inBox.height();
		var $cache = $('<div></div>'); // this is where we'll put the real content
		var lastWidth = 0;
		var columnizing = false;
		var kids = $inBox[0].childNodes;
		$inBox.append($(kids));
		$cache.append($inBox.children().clone());
		
		columnizeIt();
		
		if(!options.buildOnce){
			$(window).resize(function() {
				if(!options.buildOnce && $.browser.msie){
					if($inBox.data("timeout")){
						clearTimeout($inBox.data("timeout"));
					}
					$inBox.data("timeout", setTimeout(columnizeIt, 200));
				}else if(!options.buildOnce){
					columnizeIt();
				}else{
					// don't rebuild
				}
			});
		}
		
		/**
		 * return a node that has a height
		 * less than or equal to height
		 *
		 * @param putInHere, a dom element
		 * @$pullOutHere, a jQuery element
		 */
		function columnize($putInHere, $pullOutHere, $parentColumn, height){
			while($parentColumn.height() < height &&
				  $pullOutHere[0].childNodes.length){
				$putInHere.append($pullOutHere[0].childNodes[0]);
			}
			if($putInHere[0].childNodes.length == 0) return;
			
			// now we're too tall, undo the last one
			var kids = $putInHere[0].childNodes;
			var lastKid = kids[kids.length-1];
			$putInHere[0].removeChild(lastKid);
			var $item = $(lastKid);
			
			
			if($item[0].nodeType == 3){
				// it's a text node, split it up
				var oText = $item[0].nodeValue;
				var counter2 = options.width / 18;
				if(options.accuracy)
				counter2 = options.accuracy;
				var columnText;
				var latestTextNode = null;
				while($parentColumn.height() < height && oText.length){
					if (oText.indexOf(' ', counter2) != '-1') {
						columnText = oText.substring(0, oText.indexOf(' ', counter2));
					} else {
						columnText = oText;
					}
					latestTextNode = document.createTextNode(columnText);
					$putInHere.append(latestTextNode);
					
					if(oText.length > counter2){
						oText = oText.substring(oText.indexOf(' ', counter2));
					}else{
						oText = "";
					}
				}
				if($parentColumn.height() >= height && latestTextNode != null){
					// too tall :(
					$putInHere[0].removeChild(latestTextNode);
					oText = latestTextNode.nodeValue + oText;
				}
				if(oText.length){
					$item[0].nodeValue = oText;
				}else{
					return false; // we ate the whole text node, move on to the next node
				}
			}
			
			if($pullOutHere.children().length){
				$pullOutHere.prepend($item);
			}else{
				$pullOutHere.append($item);
			}
			
			return $item[0].nodeType == 3;
		}
		
		function split($putInHere, $pullOutHere, $parentColumn, height){
			if($pullOutHere.children().length){
				$cloneMe = $pullOutHere.children(":first");
				$clone = $cloneMe.clone();
				if($clone.attr("nodeType") == 1 && !$clone.hasClass("dontend")){ 
					$putInHere.append($clone);
					if($clone.is("img") && $parentColumn.height() < height + 20){
						$cloneMe.remove();
					}else if(!$cloneMe.hasClass("dontsplit") && $parentColumn.height() < height + 20){
						$cloneMe.remove();
					}else if($clone.is("img") || $cloneMe.hasClass("dontsplit")){
						$clone.remove();
					}else{
						$clone.empty();
						if(!columnize($clone, $cloneMe, $parentColumn, height)){
							if($cloneMe.children().length){
								split($clone, $cloneMe, $parentColumn, height);
							}
						}
					}
				}
			}
		}
		
		
		function singleColumnizeIt() {
			if ($inBox.data("columnized") && $inBox.children().length == 1) {
				return;
			}
			$inBox.data("columnized", true);
			$inBox.data("columnizing", true);
			
			$inBox.empty();
			$inBox.append($("<div class='first last column' style='width:98%; padding: 3px; float: left;'></div>")); //"
			$col = $inBox.children().eq($inBox.children().length-1);
			$destroyable = $cache.clone();
			if(options.overflow){
				targetHeight = options.overflow.height;
				columnize($col, $destroyable, $col, targetHeight);
				// make sure that the last item in the column isn't a "dontend"
				if(!$destroyable.children().find(":first-child").hasClass("dontend")){
					split($col, $destroyable, $col, targetHeight);
				}
				
				while(checkDontEndColumn($col.children(":last").get(0))){
					var $lastKid = $col.children(":last");
					$lastKid.remove();
					$destroyable.prepend($lastKid);
				}

				var html = "";
				var div = document.createElement('DIV');
				while($destroyable[0].childNodes.length > 0){
					var kid = $destroyable[0].childNodes[0];
					for(var i=0;i<kid.attributes.length;i++){
						if(kid.attributes[i].nodeName.indexOf("jQuery") == 0){
							kid.removeAttribute(kid.attributes[i].nodeName);
						}
					}
					div.innerHTML = "";
					div.appendChild($destroyable[0].childNodes[0]);
					html += div.innerHTML;
				}
				var overflow = $(options.overflow.id)[0];
				overflow.innerHTML = html;

			}else{
				$col.append($destroyable);
			}
			$inBox.data("columnizing", false);
			
			if(options.overflow){
				options.overflow.doneFunc();
			}
			
		}
		
		function checkDontEndColumn(dom){
			if(dom.nodeType != 1) return false;
			if($(dom).hasClass("dontend")) return true;
			if(dom.childNodes.length == 0) return false;
			return checkDontEndColumn(dom.childNodes[dom.childNodes.length-1]);
		}
		
		function columnizeIt() {
			if(lastWidth == $inBox.width()) return;
			lastWidth = $inBox.width();
			
			var numCols = Math.round($inBox.width() / options.width);
			if(options.columns) numCols = options.columns;
//			if ($inBox.data("columnized") && numCols == $inBox.children().length) {
//				return;
//			}
			if(numCols <= 1){
				return singleColumnizeIt();
			}
			if($inBox.data("columnizing")) return;
			$inBox.data("columnized", true);
			$inBox.data("columnizing", true);
			
			$inBox.empty();
			$inBox.append($("<div style='width:" + (Math.round(100 / numCols) - 2)+ "%; padding: 3px; float: left;'></div>")); //"
			$col = $inBox.children(":last");
			$col.append($cache.clone());
			maxHeight = $col.height();
			$inBox.empty();
			
			var targetHeight = maxHeight / numCols;
			var firstTime = true;
			var maxLoops = 3;
			var scrollHorizontally = false;
			if(options.overflow){
				maxLoops = 1;
				targetHeight = options.overflow.height;
			}else if(options.height && options.width){
				maxLoops = 1;
				targetHeight = options.height;
				scrollHorizontally = true;
			}
			
			for(var loopCount=0;loopCount<maxLoops;loopCount++){
				$inBox.empty();
				var $destroyable = $cache.clone();
				$destroyable.css("visibility", "hidden");
				// create the columns
				for (var i = 0; i < numCols; i++) {
					/* create column */
					var className = (i == 0) ? "first column" : "column";
					var className = (i == numCols - 1) ? ("last " + className) : className;
					$inBox.append($("<div class='" + className + "' style='width:" + (Math.round(100 / numCols) - 2)+ "%; float: left;'></div>")); //"
				}
				
				// fill all but the last column (unless overflowing)
				var i = 0;
				while(i < numCols - (options.overflow ? 0 : 1) || scrollHorizontally && $destroyable.children().length){
					if($inBox.children().length <= i){
						// we ran out of columns, make another
						$inBox.append($("<div class='" + className + "' style='width:" + (Math.round(100 / numCols) - 2)+ "%; float: left;'></div>")); //"
					}
					var $col = $inBox.children().eq(i);
					columnize($col, $destroyable, $col, targetHeight);
					// make sure that the last item in the column isn't a "dontend"
					if(!$destroyable.children().find(":first-child").hasClass("dontend")){
						split($col, $destroyable, $col, targetHeight);
					}else{
//						alert("not splitting a dontend");
					}
					
					while(checkDontEndColumn($col.children(":last").get(0))){
						var $lastKid = $col.children(":last");
						$lastKid.remove();
						$destroyable.prepend($lastKid);
					}
					i++;
				}
				if(options.overflow && !scrollHorizontally){
					var html = "";
					var div = document.createElement('DIV');
					while($destroyable[0].childNodes.length > 0){
						var kid = $destroyable[0].childNodes[0];
						for(var i=0;i<kid.attributes.length;i++){
							if(kid.attributes[i].nodeName.indexOf("jQuery") == 0){
								kid.removeAttribute(kid.attributes[i].nodeName);
							}
						}
						div.innerHTML = "";
						div.appendChild($destroyable[0].childNodes[0]);
						html += div.innerHTML;
					}
					var overflow = $(options.overflow.id)[0];
					overflow.innerHTML = html;
//					$(options.overflow.id).empty().append($destroyable.children());
				}else if(!scrollHorizontally){
					// the last column in the series
					$col = $inBox.children().eq($inBox.children().length-1);
					while($destroyable.children().length) $col.append($destroyable.children(":first"));
					var afterH = $col.height();
					var diff = afterH - targetHeight;
					var totalH = 0;
					var min = 10000000;
					var max = 0;
					$inBox.children().each(function($inBox){ return function($item){
						var h = $inBox.children().eq($item).height();
						totalH += h;
						if(h > max) max = h;
						if(h < min) min = h;
					}}($inBox));
					var avgH = totalH / numCols;
					if(max - min > 30){
						targetHeight = avgH + 30;
					}else if(Math.abs(avgH-targetHeight) > 20){
						targetHeight = avgH;
					}else{
						loopCount = maxLoops;
					}
				}else{
					// it's scrolling horizontally, fix the width/classes of the columns
					$inBox.children().each(function(i){
						$col = $inBox.children().eq(i);
						$col.width(options.width + "px");
						if(i==0){
							$col.addClass("first");
						}else if(i==$inBox.children().length-1){
							$col.addClass("last");
						}else{
							$col.removeClass("first");
							$col.removeClass("last");
						}
					});
					$inBox.width($inBox.children().length * options.width + "px");
				}
				$inBox.append($("<br style='clear:both;'>"));
			}
			$inBox.find('.column').find(':first.removeiffirst').remove();
			$inBox.find('.column').find(':last.removeiflast').remove();
			$inBox.data("columnizing", false);

			if(options.overflow){
				options.overflow.doneFunc();
			}
		}
    });
 };
})(jQuery);
