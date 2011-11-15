Formidable.Classes.Link = Formidable.Classes.RdtBaseClass.extend({
	getLabel: function() {
		return false;
	},
	replaceLabel: function(sLabel) {
		this.domNode().innerHTML = sLabel;
	},
	follow: function(bDisplayLoader) {
		if(bDisplayLoader) this.oForm.displayLoader();
		var href = this.domNode().getAttribute("href");
		//javascript wird nun direkt ausgeführt
		if( href.substr(0,11) == 'javascript:'){
			eval(href.substr(11,href.length));
			return;
		}
		if(href.substr(0,window.location.protocol.length) != window.location.protocol)
			href =  this.getBaseUrl() + href;
		window.location.href = href;
	},
	getBaseUrl: function(){
		var baseAddr = '';
		if( document.getElementsByTagName ) {
			var elems = document.getElementsByTagName( 'base' );
			if( elems.length )
				baseAddr = elems[0].href;
		}
		if(baseAddr == '')
			baseAddr = window.location.protocol + '//' + window.location.host
		return baseAddr;
	}
});