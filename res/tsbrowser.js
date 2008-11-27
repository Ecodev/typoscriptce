var TSbrowser = Class.create({
    initialize: function() {
		Event.observe(window, 'load', function(){
			this.registerEventListeners();
		}.bindAsEventListener(this));
	},

    /**
	 * registers the event listeners, can be used to re-register them after refreshing the menu
	 */
	registerEventListeners: function() {
    	$$('#ts-tree ul li').invoke('observe', 'click', this.previewTSobject.bind(this));
    	$('checkAllObjectsTS').observe('click', this.renderTStree.bind(this));
	},


    previewTSobject: function(event) {
		var clickedElement = Event.element(event);
		var pid = $('TSthePid').value;
     	var objName = '';
		var old = $('selected');
        var tagName;

        if (old) old.id = '';

        if (clickedElement.tagName == 'LI') {
			clickedElement.up('li').id = 'selected';
			this.toggleState(clickedElement);
		}
        if (clickedElement.tagName == 'STRONG')  {
            objName = clickedElement.up('li').readAttribute('name');
		}
			
		/*while (clickedElement = clickedElement.up()) {
            if (clickedElement.tagName == 'UL' && clickedElement.hasClassName('tree')) break;
		} */

		if (objName && !objName.blank() && !objName.endsWith('.')) {
	        new Ajax.Updater('ts-preview', 'ajax.php', {
				parameters   : 'ajaxID=TSbrowser::TSobject&obj=' + objName + '&pid=' + pid,
				evalScripts  : true
			});
		}

		Event.stop(event);

	},

	renderTStree: function(event) {
        var flag = $('checkAllObjectsTS').checked;
        var pid = $('TSthePid').value;
        
        $('checkAllObjectsTS').stopObserving('click', this.renderTStree);
        $('ts-tree').update('<img src="gfx/spinner.gif" />');
        $('ts-preview').update('');
        
        new Ajax.Updater('ts-tree', 'ajax.php', {
			parameters   : 'ajaxID=TSbrowser::TSshowFlag&objflag=' + flag + '&pid=' + pid,
			asynchronous : false
		});
        this.registerEventListeners();

        //Event.stop(event);
	},

	toggleState: function(el) {
	    if (el.hasClassName('pm')) {
	        el.toggleClassName('plus').toggleClassName('minus');
		}
	}
});

var TYPO3BackendTSbrowser = new TSbrowser();