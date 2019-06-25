  
   var _uifmvar = _uifmvar || {};
    _uifmvar.fm_ids = _uifmvar.fm_ids || [];
    _uifmvar.fm_ids.push(rockfm_vars._uifmvar.id);
    
    for( var i in _uifmvar.fm_ids ) {
iFrameResize({
				log                     : false,
                                autoResize: true,
                                sizeWidth: true,
                                warningTimeout:0,
                                onScroll: function (coords) {
                                    /*console.log("[OVERRIDE] overrode scrollCallback x: " + coords.x + " y: " + coords.y);*/
                                }
			},'#zgfm-iframe-'+_uifmvar.fm_ids[i]);
                        
    }

