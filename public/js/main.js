function ajax_request(handler, url, params ,callback)
{
   var height = $(handler).height();
   var width = $(handler).width();
   $(handler).verboseLoad("<div style=\"width:"+width+"px;height:"+height+"px;display:block;background:url(<?= $base_img ?>popup/boxy/farmBoxy/content.png);\"><img src=<?= $base_img ?>ajax-loader.gif style=\"display:block;margin:0 auto;padding-top:"+((height/2)-5)+"px\" /></div>",url, params,callback);
}