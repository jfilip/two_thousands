/**
 * Javascript functions used for this site.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 *  findChildNode (start, elementName, elementClass, elementID)
 *  
 *  Travels down the DOM hierarchy to find all child elements with the
 *  specified tag name, class, and id. All conditions must be met,
 *  but any can be ommitted.
 *  
 *  Doesn't examine children of matches.
 */
function findChildNodes(start, tagName, elementClass, elementID, elementName) {
    var children = new Array();
    for (var i = 0; i < start.childNodes.length; i++) {
        var classfound = false;
        var child = start.childNodes[i];
        if((child.nodeType == 1) &&//element node type
                  (elementClass && (typeof(child.className)=='string'))) {
            var childClasses = child.className.split(/\s+/);
            for (var childClassIndex in childClasses) {
                if (childClasses[childClassIndex]==elementClass) {
                    classfound = true;
                    break;
                }
            }
        }
        if(child.nodeType == 1) { //element node type
            if  ( (!tagName || child.nodeName == tagName) &&
                (!elementClass || classfound)&&
                (!elementID || child.id == elementID) &&
                (!elementName || child.name == elementName))
            {
                children = children.concat(child);
            } else {
                children = children.concat(findChildNodes(child, tagName, elementClass, elementID, elementName));
            }
        }
    }
    return children;
}

function rowFocus(element, seconds) {
	var seconds_waited = seconds;
	document.getElementById(element).focus(); 
	seconds_waited += 100;

	if (document.getElementById(element) != document.activeElement && seconds_waited < 2000) {
		setTimeout('rowFocus()', 100, element, seconds_waited);
  	}
	
	return true;
}

/**
 * Hide and un-hide various elements of the page when a row item has been clicked on.
 *
 * @param int  id      The specific table item to modify.
 * @param bool reverse Are we switching to a big or small row (default: big)?
 * @return bool true
 */
function bigitup(id, reverse) {
	var rowSmall = document.getElementById('small' + id);
	var rowLarge = document.getElementById('large' + id);
	
	rowSmall.style.display    = reverse ? '' : 'none';
	rowSmall.style.visibility = reverse ? '' : 'hidden';

    rowLarge.className		  = reverse ? 'hidden' : '';
    rowLarge.style.display    = reverse ? 'none' : '';
    rowLarge.style.visibility = reverse ? 'hidden' : '';

    if (!reverse) {
		var cellsLarge = findChildNodes(rowLarge);
		
		for (var i = 0; i < cellsLarge.length; i++) {
		    cellsLarge[i].style.display    = '';
		    cellsLarge[i].style.visibility = '';
		}
    }

    if (reverse) {
    	rowFocus('small' + id, 0);
    } else {
    	rowFocus('large' + id, 0);
    }
    
	return true;
}
