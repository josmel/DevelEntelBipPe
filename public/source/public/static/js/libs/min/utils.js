var getBrowser,uaMatch;getBrowser=function(){var d,c;d=uaMatch(navigator.userAgent);c={};if(d.browser){c[d.browser]=true;c.version=d.version}if(c.chrome){c.webkit=true}else{if(c.webkit){c.safari=true}}return c};uaMatch=function(c){var d;c=c.toLowerCase();d=/(chrome)[ \/]([\w.]+)/.exec(c)||/(webkit)[ \/]([\w.]+)/.exec(c)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(c)||/(msie) ([\w.]+)/.exec(c)||c.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(c)||[];return{browser:d[1]||"",version:d[2]||"0"}};yOSON.browser=getBrowser();var Cookie={create:function(c,d,e){if(e){var b=new Date();b.setTime(b.getTime()+(e*24*60*60*1000));var a="; expires="+b.toGMTString()}else{var a=""}document.cookie=c+"="+d+a+"; path=/";return this},read:function(b){var e=b+"=";var a=document.cookie.split(";");for(var d=0;d<a.length;d++){var f=a[d];while(f.charAt(0)==" "){f=f.substring(1,f.length)}if(f.indexOf(e)==0){return f.substring(e.length,f.length)}}return null},del:function(a){return this.create(a,"",-1)}};var utils={loader:function(d,a,c){if(typeof c=="undefined"){if(a){d.css("position","relative");d.append("<div class='loader'><span></span></div>")}else{d.find(".loader").remove()}}else{if(a){var e=utils.unique(),f=d.offset(),b="height:"+d.outerHeight(true)+"px;width:"+d.outerWidth(true)+"px;top:"+f.top+"px;left:"+f.left+"px";ele=$("<div />",{id:e,"class":"loader",style:"position:absolute;"+b});ele.append("<span></span>");$("body").append(ele);return e}else{d.remove()}}},vLength:function(b,a){$(b).bind("keyup",function(d){var f=$(this),c=f.val();if(c.length>a){f.val(c.substr(0,a))}});$(b).bind("paste",function(){var c=$(this);setTimeout(function(){var d=c.val();if(d.length>a){c.val(d.substr(0,a))}},300)})},block:function(b,a){if(a){if(b.find(".ublock").length==0){b.css("position","relative");b.append("<div class='ublock'></div>")}}else{b.find(".ublock").remove()}},serializeJson:function(d){var e=d.serializeArray(),c={},b;for(var a=0;a<e.length;a++){b=e[a];c[b.name]=b.value}return c},unique:function(){return Math.random().toString(36).substr(2)}};