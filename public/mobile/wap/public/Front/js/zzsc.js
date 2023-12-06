// JavaScript Document
function b(){	
	t = parseInt(x.css('top'));
	y.css('top','19px');	
	x.animate({top: t - 19 + 'px'},'slow');	//19为每个li的高度
	if(Math.abs(t) == h-19){ //19为每个li的高度
		y.animate({top:'0px'},'slow');
		z=x;
		x=y;
		y=z;
	}
	setTimeout(b,3000);//滚动间隔时间 现在是3秒
}
$(document).ready(function(){
	$('.swap1,.swap2,.swap3,.swap4').html($('.news_li1,.news_li2,.news_li3,.news_li4').html());
	x = $('.news_li1,.news_li2,.news_li3,.news_li4');
	y = $('.swap1,.swap2,.swap3,.swap4');
	h = $('.news_li1 li,.news_li2 li,.news_li3 li,.news_li4 li').length * 19; //19为每个li的高度
	setTimeout(b,3000);//滚动间隔时间 现在是3秒
	
})