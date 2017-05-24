<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if($_GET['echostr'])
{
    $wechatObj->valid();
}else
{
    $wechatObj->responseMsg_2();
}



class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $Event = $postObj->Event;
                $EventKey = $postObj->EventKey;
                $MsgType = $postObj->MsgType;
                $time = time();
                $textTpl = "<xml>
    			<ToUserName><![CDATA[%s]]></ToUserName>
    			<FromUserName><![CDATA[%s]]></FromUserName>
    			<CreateTime>%s</CreateTime>
    			<MsgType><![CDATA[%s]]></MsgType>
    			<Content><![CDATA[%s]]></Content>
    			<FuncFlag>0</FuncFlag>
    			</xml>";
                if($MsgType == "image")
                {
                    $MsgType = "text";
                    $Content = "您发送了一个图片信息";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                if($Event == "CLICK" and $EventKey == "V1001_TODAY_MUSIC")
                {
                    $MsgType = "text";
                    $Content = "您点击了今日歌曲";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                $EventKeystr=substr($EventKey,0,8);

                if($Event == "subscribe" && $EventKeystr=="qrscene_")
                {
                    /*
                        数据库插入
                        插入  value值=$EventKey=qrscene_生成参数
                    */

                    $MsgType = "text";
                    $Content = "您之前未关注平台并且扫描了带参数的二维码";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                if($Event == "subscribe")
                {
                    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[欢迎参加极客学院]]></Title> 
                    <Description><![CDATA[极客学院微信公众平台开发视频教程]]></Description>
                    <PicUrl><![CDATA[http://www.sinaimg.cn/dy/slidenews/4_img/2015_11/704_1575962_849639.jpg]]></PicUrl>
                    <Url><![CDATA[http://www.jikexueyuan.com]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }

                if($Event == "SCAN")
                {
                    $MsgType = "text";
                    $Content = "您之前已关注平台并且扫描了带参数的二维码";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

        if(!empty( $keyword ))
                {
                if($keyword == "你好")
                {
                    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[您发送了你好]]></Title> 
                    <Description><![CDATA[极客学院微信公众平台关键字回复]]></Description>
                    <PicUrl><![CDATA[http://www.sinaimg.cn/dy/slidenews/4_img/2015_11/704_1575962_849639.jpg]]></PicUrl>
                    <Url><![CDATA[http://www.jikexueyuan.com]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }
                if($keyword == "人工" || $keyword == "问题")
                {
                    $textTpl = " <xml>
                     <ToUserName><![CDATA[%s]]></ToUserName>
                     <FromUserName><![CDATA[%s]]></FromUserName>
                     <CreateTime>%s</CreateTime>
                     <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                     </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }
                }else{
                	echo "Input something...";
                }

       
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

public function responseMsg_2()
    {
		$detailHttp="http://fh.netbarmon.com ";
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $Event = $postObj->Event;
                $EventKey = $postObj->EventKey;
                $MsgType = $postObj->MsgType;
                $time = time();
                $textTpl = "<xml>
    			<ToUserName><![CDATA[%s]]></ToUserName>
    			<FromUserName><![CDATA[%s]]></FromUserName>
    			<CreateTime>%s</CreateTime>
    			<MsgType><![CDATA[%s]]></MsgType>
    			<Content><![CDATA[%s]]></Content>
    			<FuncFlag>0</FuncFlag>
    			</xml>";
    			$fp = fopen("garyTest.json", "w");
          fwrite($fp, $postStr);
          fclose($fp);
         if($Event == "subscribe")
                {
                    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[欢迎添加大少测试公众号]]></Title> 
                    <Description><![CDATA[进入大少公众号平台]]></Description>
                    <PicUrl><![CDATA[http://fh.netbarmon.com/pic/Gary.bmp]]></PicUrl>
                    <Url><![CDATA[http://fh.netbarmon.com]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }
                 else if($Event =="scancode_waitmsg") 
                 { 
                 	    $MsgType = "text";
		                    $Content = "Scan code:".$postObj->ScanCodeInfo->ScanResult."\nDetail addr:\n".$detailHttp;
		                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
		                    echo $resultStr;
                 	 
                 	 } 
                 	  if(!empty( $keyword )) 
                 	   {
                 	    	 $MsgType = "text";
		                    $Content = "Received:".$keyword."\nDetail addr:\n".$detailHttp;
		                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
		                    echo $resultStr;
                 	    }
                 else
                  {
		    			     			$MsgType = "text";
		                    $Content = $postStr."\nDetail addr:\n".$detailHttp;
		                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
		                    echo $resultStr;
		                     
		                  }
                }
        }
}
?>