//jQuery Cookie
jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options.expires=-1;}
var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;}
expires='; expires='+date.toUTCString();}
var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('');}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}}
return cookieValue;}};

//tip tip
(function($){$.fn.tipTip=function(options){var defaults={activation:"hover",keepAlive:false,maxWidth:"200px",edgeOffset:3,defaultPosition:"bottom",delay:400,fadeIn:200,fadeOut:200,attribute:"title",content:false,enter:function(){},exit:function(){}};var opts=$.extend(defaults,options);if($("#tiptip_holder").length<=0){var tiptip_holder=$('<div id="tiptip_holder" style="max-width:'+opts.maxWidth+';"></div>');var tiptip_content=$('<div id="tiptip_content"></div>');var tiptip_arrow=$('<div id="tiptip_arrow"></div>');$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))}else{var tiptip_holder=$("#tiptip_holder");var tiptip_content=$("#tiptip_content");var tiptip_arrow=$("#tiptip_arrow")}
return this.each(function(){var org_elem=$(this);if(opts.content){var org_title=opts.content}else{var org_title=org_elem.attr(opts.attribute)}
if(org_title!=""){if(!opts.content){org_elem.removeAttr(opts.attribute)}
var timeout=false;if(opts.activation=="hover"){org_elem.hover(function(){active_tiptip()},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}else if(opts.activation=="focus"){org_elem.focus(function(){active_tiptip()}).blur(function(){deactive_tiptip()})}else if(opts.activation=="click"){org_elem.click(function(){active_tiptip();return false}).hover(function(){},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}
function active_tiptip(){opts.enter.call(this);tiptip_content.html(org_title);tiptip_holder.hide().removeAttr("class").css("margin","0");tiptip_arrow.removeAttr("style");var top=parseInt(org_elem.offset()['top']);var left=parseInt(org_elem.offset()['left']);var org_width=parseInt(org_elem.outerWidth());var org_height=parseInt(org_elem.outerHeight());var tip_w=tiptip_holder.outerWidth();var tip_h=tiptip_holder.outerHeight();var w_compare=Math.round((org_width-tip_w)/2);var h_compare=Math.round((org_height-tip_h)/2);var marg_left=Math.round(left+w_compare);var marg_top=Math.round(top+org_height+opts.edgeOffset);var t_class="";var arrow_top="";var arrow_left=Math.round(tip_w-12)/2;if(opts.defaultPosition=="bottom"){t_class="_bottom"}else if(opts.defaultPosition=="top"){t_class="_top"}else if(opts.defaultPosition=="left"){t_class="_left"}else if(opts.defaultPosition=="right"){t_class="_right"}
var right_compare=(w_compare+left)<parseInt($(window).scrollLeft());var left_compare=(tip_w+left)>parseInt($(window).width());if((right_compare&&w_compare<0)||(t_class=="_right"&&!left_compare)||(t_class=="_left"&&left<(tip_w+opts.edgeOffset+5))){t_class="_right";arrow_top=Math.round(tip_h-13)/2;arrow_left=-12;marg_left=Math.round(left+org_width+opts.edgeOffset);marg_top=Math.round(top+h_compare)}else if((left_compare&&w_compare<0)||(t_class=="_left"&&!right_compare)){t_class="_left";arrow_top=Math.round(tip_h-13)/2;arrow_left=Math.round(tip_w);marg_left=Math.round(left-(tip_w+opts.edgeOffset+5));marg_top=Math.round(top+h_compare)}
var top_compare=(top+org_height+opts.edgeOffset+tip_h+8)>parseInt($(window).height()+$(window).scrollTop());var bottom_compare=((top+org_height)-(opts.edgeOffset+tip_h+8))<0;if(top_compare||(t_class=="_bottom"&&top_compare)||(t_class=="_top"&&!bottom_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_top"}else{t_class=t_class+"_top"}
arrow_top=tip_h;marg_top=Math.round(top-(tip_h+5+opts.edgeOffset))}else if(bottom_compare|(t_class=="_top"&&bottom_compare)||(t_class=="_bottom"&&!top_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_bottom"}else{t_class=t_class+"_bottom"}
arrow_top=-12;marg_top=Math.round(top+org_height+opts.edgeOffset)}
if(t_class=="_right_top"||t_class=="_left_top"){marg_top=marg_top+5}else if(t_class=="_right_bottom"||t_class=="_left_bottom"){marg_top=marg_top-5}
if(t_class=="_left_top"||t_class=="_left_bottom"){marg_left=marg_left+5}
tiptip_arrow.css({"margin-left":arrow_left+"px","margin-top":arrow_top+"px"});tiptip_holder.css({"margin-left":marg_left+"px","margin-top":marg_top+"px"}).attr("class","tip"+t_class);if(timeout){clearTimeout(timeout)}
timeout=setTimeout(function(){tiptip_holder.stop(true,true).fadeIn(opts.fadeIn)},opts.delay)}
function deactive_tiptip(){opts.exit.call(this);if(timeout){clearTimeout(timeout)}
tiptip_holder.fadeOut(opts.fadeOut)}}})}})(jQuery);

//zeroclipboard
var ZeroClipboard={version:"1.0.7",clients:{},moviePath:'ZeroClipboard.swf',nextId:1,$:function(thingy){if(typeof(thingy)=='string')thingy=document.getElementById(thingy);if(!thingy.addClass){thingy.hide=function(){this.style.display='none';};thingy.show=function(){this.style.display='';};thingy.addClass=function(name){this.removeClass(name);this.className+=' '+name;};thingy.removeClass=function(name){var classes=this.className.split(/\s+/);var idx=-1;for(var k=0;k<classes.length;k++){if(classes[k]==name){idx=k;k=classes.length;}}
if(idx>-1){classes.splice(idx,1);this.className=classes.join(' ');}
return this;};thingy.hasClass=function(name){return!!this.className.match(new RegExp("\\s*"+name+"\\s*"));};}
return thingy;},setMoviePath:function(path){this.moviePath=path;},dispatch:function(id,eventName,args){var client=this.clients[id];if(client){client.receiveEvent(eventName,args);}},register:function(id,client){this.clients[id]=client;},getDOMObjectPosition:function(obj,stopObj){var info={left:0,top:0,width:obj.width?obj.width:obj.offsetWidth,height:obj.height?obj.height:obj.offsetHeight};while(obj&&(obj!=stopObj)){info.left+=obj.offsetLeft;info.top+=obj.offsetTop;obj=obj.offsetParent;}
return info;},Client:function(elem){this.handlers={};this.id=ZeroClipboard.nextId++;this.movieId='ZeroClipboardMovie_'+this.id;ZeroClipboard.register(this.id,this);if(elem)this.glue(elem);}};ZeroClipboard.Client.prototype={id:0,ready:false,movie:null,clipText:'',handCursorEnabled:true,cssEffects:true,handlers:null,glue:function(elem,appendElem,stylesToAdd){this.domElement=ZeroClipboard.$(elem);var zIndex=99;if(this.domElement.style.zIndex){zIndex=parseInt(this.domElement.style.zIndex,10)+1;}
if(typeof(appendElem)=='string'){appendElem=ZeroClipboard.$(appendElem);}
else if(typeof(appendElem)=='undefined'){appendElem=document.getElementsByTagName('body')[0];}
var box=ZeroClipboard.getDOMObjectPosition(this.domElement,appendElem);this.div=document.createElement('div');var style=this.div.style;style.position='absolute';style.left=''+box.left+'px';style.top=''+box.top+'px';style.width=''+box.width+'px';style.height=''+box.height+'px';style.zIndex=zIndex;if(typeof(stylesToAdd)=='object'){for(addedStyle in stylesToAdd){style[addedStyle]=stylesToAdd[addedStyle];}}
appendElem.appendChild(this.div);this.div.innerHTML=this.getHTML(box.width,box.height);},getHTML:function(width,height){var html='';var flashvars='id='+this.id+'&width='+width+'&height='+height;if(navigator.userAgent.match(/MSIE/)){var protocol=location.href.match(/^https/i)?'https://':'http://';html+='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="'+protocol+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'+width+'" height="'+height+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ZeroClipboard.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+flashvars+'"/><param name="wmode" value="transparent"/></object>';}
else{html+='<embed id="'+this.movieId+'" src="'+ZeroClipboard.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+width+'" height="'+height+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+flashvars+'" wmode="transparent" />';}
return html;},hide:function(){if(this.div){this.div.style.left='-2000px';}},show:function(){this.reposition();},destroy:function(){if(this.domElement&&this.div){this.hide();this.div.innerHTML='';var body=document.getElementsByTagName('body')[0];try{body.removeChild(this.div);}catch(e){;}
this.domElement=null;this.div=null;}},reposition:function(elem){if(elem){this.domElement=ZeroClipboard.$(elem);if(!this.domElement)this.hide();}
if(this.domElement&&this.div){var box=ZeroClipboard.getDOMObjectPosition(this.domElement);var style=this.div.style;style.left=''+box.left+'px';style.top=''+box.top+'px';}},setText:function(newText){this.clipText=newText;if(this.ready)this.movie.setText(newText);},addEventListener:function(eventName,func){eventName=eventName.toString().toLowerCase().replace(/^on/,'');if(!this.handlers[eventName])this.handlers[eventName]=[];this.handlers[eventName].push(func);},setHandCursor:function(enabled){this.handCursorEnabled=enabled;if(this.ready)this.movie.setHandCursor(enabled);},setCSSEffects:function(enabled){this.cssEffects=!!enabled;},receiveEvent:function(eventName,args){eventName=eventName.toString().toLowerCase().replace(/^on/,'');switch(eventName){case'load':this.movie=document.getElementById(this.movieId);if(!this.movie){var self=this;setTimeout(function(){self.receiveEvent('load',null);},1);return;}
if(!this.ready&&navigator.userAgent.match(/Firefox/)&&navigator.userAgent.match(/Windows/)){var self=this;setTimeout(function(){self.receiveEvent('load',null);},100);this.ready=true;return;}
this.ready=true;this.movie.setText(this.clipText);this.movie.setHandCursor(this.handCursorEnabled);break;case'mouseover':if(this.domElement&&this.cssEffects){this.domElement.addClass('hover');if(this.recoverActive)this.domElement.addClass('active');}
break;case'mouseout':if(this.domElement&&this.cssEffects){this.recoverActive=false;if(this.domElement.hasClass('active')){this.domElement.removeClass('active');this.recoverActive=true;}
this.domElement.removeClass('hover');}
break;case'mousedown':if(this.domElement&&this.cssEffects){this.domElement.addClass('active');}
break;case'mouseup':if(this.domElement&&this.cssEffects){this.domElement.removeClass('active');this.recoverActive=false;}
break;}
if(this.handlers[eventName]){for(var idx=0,len=this.handlers[eventName].length;idx<len;idx++){var func=this.handlers[eventName][idx];if(typeof(func)=='function'){func(this,args);}
else if((typeof(func)=='object')&&(func.length==2)){func[0][func[1]](this,args);}
else if(typeof(func)=='string'){window[func](this,args);}}}}};

//copy udids
function enableClipboard(){var clip;clip=new ZeroClipboard.Client();clip.setHandCursor(true);clip.addEventListener('complete',function onCopy(event){$(event.domElement).css('background','#ffff99');setTimeout(function(){$(event.domElement).css('background','transparent');},250);});$('.copy-device-udid').mouseover(function(){clip.setHandCursor(true);clip.setText($(this).attr('rel'));if(clip.div){clip.receiveEvent('mouseout',null);clip.reposition(this);}
else clip.glue(this);clip.receiveEvent('mouseover',null);});}
$(function(){enableClipboard();});