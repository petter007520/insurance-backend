var c,_=Function;
with(o=document.getElementById("div1")){ innerHTML+=innerHTML; onmouseover=_("c=1"); onmouseout=_("c=0");}
(F=_("if(#%32||!c)#++,#%=o.scrollHeight>>1;setTimeout(F,#%32?10:1500);".replace(/#/g,"o.scrollTop")))();


$(function(){ 
var $this = $(".renav"); 
var scrollTimer; 
$this.hover(function(){ 
clearInterval(scrollTimer); 
},function(){ 
scrollTimer = setInterval(function(){ 
scrollNews( $this ); 
}, 2000 ); 
}).trigger("mouseout"); 
}); 
function scrollNews(obj){ 
var $self = obj.find("ul:first"); 
var lineHeight = $self.find("li:first").height(); 
$self.animate({ "margin-top" : -lineHeight +"px" },300 , function(){ 
$self.css({"margin-top":"0px"}).find("li:first").appendTo($self); 
}) 
} 
