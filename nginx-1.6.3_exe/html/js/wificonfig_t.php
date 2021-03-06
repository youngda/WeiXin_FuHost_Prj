<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx99b29fcacfb9410c", "a52d110b8fd16f3c0266bc0121ccdcda");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;"/>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!--<link rel="stylesheet" href="./weixin_dist/style/weui.css"/>
  <link rel="stylesheet" href="./weixin_dist/example.css"/>-->
  <title>WifiConfigure</title>
</head>
<body>
    <!--<button onclick="wifi_configure()" class="weui_btn weui_btn_primary">Wifi Configure</button>-->
     <button onclick="wifi_configure()" >Wifi Configure</button>
     <div id="message">hello</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中// 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
       'configWXDeviceWiFi'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
      wx.checkJsApi({
            jsApiList: ['configWXDeviceWiFi'],
            success: function(res) {	
             	document.getElementById("message").innerHTML= "success";
                WeixinJSBridge.invoke('configWXDeviceWiFi', {}, function(res){
                    var err_msg = res.err_msg;
                     document.getElementById("message").innerHTML= "配置 WIFI ...";
                    if(err_msg == 'configWXDeviceWiFi:ok') {
                        document.getElementById("message").innerHTML="配置 WIFI成功，<span id='second'>5</span>秒后跳转到首页。";
                        //setInterval(count,1000);
                        //return;
                    } else {
                        document.getElementById("message").innerHTML="配置 WIFI失败，是否<a href=\"/wechat/scan/airkiss" + window.location.search +  "\">再次扫描</a>。<br>不配置WIFI,<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf1867e87a4eeeb16&redirect_uri=http://letux.xyz/wechat/page/main&response_type=code&scope=snsapi_base&state=1#wechat_redirect\">直接进入首页</a>。";
                    }
                });
            }
        });
  });
  function wifi_configure()
	{
	   WeixinJSBridge.invoke('configWXDeviceWiFi', {"key":"MTIzNDU2Nzg5MDEyAAAAzQ1Ng=="}, function(res){
				console.log('configWXDeviceWiFi', res);
			});
	}
</script>
</html>