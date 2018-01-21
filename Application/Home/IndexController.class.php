<?php
    namespace Ylp\Controller;
    use think\Controller;
    define('TOKEN','YLP');
    class IndexController extends Controller{
    	public $appid="wx194d62041f149141";
        public $appsecret="1cf7b14724f443e3388d458d750bd9e4"; 
        public function index(){ //读取数据
        	$code=I('get.code');//获取code值
        	$userinfourl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
        	$objuser=file_get_contents($userinfourl);
	    	$objtoken=json_decode($objuser);
	    	$access_token=$objtoken->access_token;
	    	$openid=$objtoken->openid;//获取用户的openid
	    	$getinfourl="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
	    	$info=file_get_contents($getinfourl);
	    	$users=json_decode($info);
	    	$user1=get_object_vars($users);//对象转数组
	    	$infos=m('wx_getuser')->where("openid='".$openid."'")->find();
        if($infos){
        	
        	$this->assign('openid',$infos);
        }else{
            $inserts=m('wx_getuser')->add($users);
            $infoss=m('wx_getuser')->where("openid='".$openid."'")->find();
            $this->assign('openid',$infoss);
        }


        	$catid=I('get.catid');
        	$list=M('wxnews')->where('catid='.$catid)->select();//查询表内容
        	$this->assign('list',$list);//映射到页面的值
            //echo "123123";
            $this->display();

        }
        public function content()//内容页面
        {	
        	$id=i('id');//获取id值
        	$oid=i('get.oid');
        	
        	
        	$list=M('wxnews')->where("id=".$id)->find();//根据id值进行单条数据查询
        	$this->assign('list',$list);//映射到内容页的值
        	$info = M('wxcomment')->where("nid=".$id)->select();
        	$info1 = M('wxlike')->where("nid=".$id)->count();
        	$info2 = M('wxcomment')->where("nid=".$id)->count();
        	$this->assign('love',$info1);//映射到内容页的值
        	$this->assign('pl',$info2);//映射到内容页的值
        	$this->assign('info',$info);//映射到内容页的值
        	$this->assign('openid',$oid);
        	$this->display();
        }
        public function signup()//报名表
        {
        	 $this->display('signup');
        }
        public function do_signup()//处理报名表
        {
            $data=I('post.');//接收数据库所有的字段
            $info=M('wxusers')->add($data);//添加数据
            if($info){//判断添加是否成功
                echo"succ";
            }else{
                echo"fail";
            }
        }
        public function getuser()//微信页面登录授权
        {	
        	$id=i('get.id');
        	$redirect_uri=urlencode("http://www.opjpg.com/TP3/index.php/Ylp/Index/index/catid/".$id.".html");
        	$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        		header('Location:'.$url);
        }
        public function pinglun()
        {	
        $id=i('get.id');
        $oid=i('get.oid');
        $info=m('wx_getuser')->where("Id='".$oid."'")->find();
        // echo "<pre>";print_r($info);exit;
        $this->assign('info',$info);
        $this->assign('id',$id);
        $this->display();
        }
        public function do_pinglun()
        {
        	$data=i('post.');
        	$data['inputtime']=date('Y-m-d',time());
        	$info1=m('wxcomment')->add($data);
        	if($info1)
        	{
        		$url="http://www.opjpg.com/TP3/index.php/ylp/index/content/id/".$data['nid'].".html";
            header('Location:'.$url);
        	}else{
        		echo "评论失败";
        	}

        	        }
        	public function like()
        	{
        		 $id=i('get.id');
                 $oid=i('get.oid');
                 $info=m('wx_getuser')->where("Id='".$oid."'")->find();
                 $openid=$info['openid'];
                 $info1=m('wxlike')->where("openid='".$openid."' && nid='".$id."'")->find();
                 if($info1)
                 {
                 	echo "请勿重复提交";
                 	$url="http://www.opjpg.com/TP3/index.php/ylp/index/content/id/".$data['nid'].".html";
                 	 header('Location:'.$url);
                 }else{
                 	$data=[
                 		'nid'=>$id,
                 		'love'=>1,
                 		'openid'=>$openid
                 	];
                 	$inserts=m('wxlike')->add($data);
                 	$url="http://www.opjpg.com/TP3/index.php/ylp/index/content/id/".$data['nid'].".html";
                 	 header('Location:'.$url);
                 }
        	}



//砍价
//        public function bargains()
//        {
//            $redirect_uri=urlencode("http://www.opjpg.com/TP3/index.php/ylp/index/firstbargain");
//            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
//            header('Location:'.$url);
//        }
//        public function firstbargain()
//        {
//            $code=I('get.code');
//            $userinfourl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL,$userinfourl);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $objtoken = json_decode(curl_exec($ch),true) ;
//            curl_close($ch);
//            $access_token=$objtoken['access_token'];
//            $openid=$objtoken['openid'];
//            $getinfourl="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
//            $chh = curl_init();
//            curl_setopt($chh, CURLOPT_URL,$getinfourl);
//            curl_setopt($chh, CURLOPT_RETURNTRANSFER, 1);
//            $users = json_decode(curl_exec($chh),true) ;
//            curl_close($chh);
//            session('openid',$openid);
//            $infos=m('user')->where("openid='".$openid."'")->find();
//            if($infos){
//                $this->assign('openid',$infos);
//            }else{
//                $inserts=m('user')->add($users);
//                $infoss=m('user')->where("openid='".$openid."'")->find();
//                $this->assign('openid',$infoss);
//            }
//            $url="http://www.opjpg.com/TP3/index.php/ylp/index/bargain/openid/".$openid.".html";
//            header('Location:'.$url);
//        }
//        public function helpbargains()
//        {
//            $session=I('openid');
//            $code=I('get.code');
//            $userinfourl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL,$userinfourl);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $objtoken = json_decode(curl_exec($ch),true) ;
//            curl_close($ch);
//            $access_token=$objtoken['access_token'];
//            $openid=$objtoken['openid'];
//            $getinfourl="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
//            $chh = curl_init();
//            curl_setopt($chh, CURLOPT_URL,$getinfourl);
//            curl_setopt($chh, CURLOPT_RETURNTRANSFER, 1);
//            $users = json_decode(curl_exec($chh),true) ;
//            curl_close($chh);
//            session('openid',$openid);
//            $infos=m('user')->where("openid='".$openid."'")->find();
//            if($infos){
//                $this->assign('openid',$infos);
//            }else{
//                $inserts=m('user')->add($users);
//                $infoss=m('user')->where("openid='".$openid."'")->find();
//                $this->assign('openid',$infoss);
//            }
//            $url="http://www.opjpg.com/TP3/index.php/ylp/index/bargain/openid/".$session.".html";
//            header('Location:'.$url);
//        }
//        public function bargain(){
//            if(session('openid')){
//                $session=I('openid');
//                if($session==session('openid')){
//                    $infos=m('user')->where("openid='".session('openid')."'")->find();
//                    if($infos){
//                        $this->assign('nickname',$infos['nickname']);
//                    }else{
//                        echo "未找到该用户";exit;
//                    }
//                    $inf=M('bargain')->where("openid='".session('openid')."'")->find();
//                    $this->assign('kanjiamoney',$inf['money']-$inf['newmoney']);
//                    $this->assign('bargain',$inf);
//                    $this->assign('openid',$session);
//                    $this->display('bargain');
//                }else{
//                    $infos=m('user')->where("openid='".session('openid')."'")->find();
//                    if($infos){
//                        $this->assign('openid',$infos);
//                    }
//                    $inf=M('bargain')->where("openid='".$session."'")->find();
//                    $this->assign('bargain',$inf);
//                    $usinfo=M('bargainfor')->where("helpopenid='".session('openid')."'")->find();
//                    $this->assign('money',$usinfo['money']);
//                    $this->assign('openid',$session);
//                    $this->display('exbargain');
//                }
//            }else{
//                $session=I('openid');
//                $redirect_uri=urlencode("http://www.opjpg.com/TP3/index.php/ylp/index/helpbargains/openid/".$session."");
//                $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
//                header('Location:'.$url);
//            }
//
//
//        }
//        public function do_bargain(){
//            if(session('openid')){
//                $session=I('openid');
//                if(session('openid')==$session){
//                    $data['openid']=session('openid');
//                    $info=m('user')->where("openid='".session('openid')."'")->find();
//                    $data['username']=$info['nickname'];
//                    $data['money']=800;
//                    $infos=m('bargain')->where("username='". $data['username']."'")->find();
//                    if($infos){
//                        echo "不能重复砍价";
//                    }else{
//                        $data['newmoney']=$data['money']-100;
//                        $inserts=M('bargain')->add($data);
//                        echo "成功砍掉100元";
//                    }
//                }else{
//                    $infos=M('bargainfor')->where("helpopenid='".session('openid')."' and useropenid='".$session."'")->find();
//                    if($infos){
//                        echo "只能帮砍一次";
//                    }else{
//                        $data['useropenid']=$session;
//                        $data['helpopenid']=session('openid');
//                        $datas=array();
//                        while(count($datas)<100){
//                            $val=rand(1,800);
//                            $datas[$val]=$val;
//                        }
//                        $data['money']=$datas[$val];
//                        $insert=m('bargainfor')->add($data);
//                        echo "成功帮忙砍价";
//                    }
//
//                }
//            }
//        }




    public function newindex()
    {
        $this->response();         //消息推送
    }
        //服务器配置签名
        public function checkcorresp(){
            $timestamp=$_GET['timestamp'];
            $nonce=$_GET['nonce'];

            //1）将token、timestamp、nonce三个参数进行字典序排序
            $arr=array(TOKEN,$timestamp,$nonce);
            sort($arr,SORT_STRING);
            //2）将三个参数字符串拼接成一个字符串进行sha1加密
            $str=implode('',$arr);
            $str=sha1($str);

            //3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
            if($str==$_GET['signature'])
            {
                echo $_GET['echostr'];
            }
        }
        //消息推送
        public function response(){
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $ToUserName=$postObj->ToUserName;
            $FromUserName=$postObj->FromUserName;
            $content=$postObj->Content;
            $nowtime=time();
            $xmltpl="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
            $xmltpl1="<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
                        <item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
						</item>
					</Articles>
					</xml>";
            switch ($postObj->MsgType) {
                case 'text':

                    break;

                case 'location':

                    break;
                case 'event':
                    switch ($postObj->Event) {
                        case 'subscribe':       //用户关注后的操作
                            $testurl="<a href='http://www.opjpg.com/TP3/index.php/ylp/index/getinfo'>砍价活动</a>";   //用户关注后向用户推送一个进入活动页面的链接
                            $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'text',$testurl);   //发送text类型的文本消息
                            echo $resultStr;
                            break;

                    }break;
                default:break;
            }
        }

        //活动页面
        public function huodong()
        {
            $zopenid=$_GET['zopenid'];  //接收被砍价人的openid

            $openid=session('openid');  //获取当前登录用户的openid，在getopenid操作中存储
            $symoney=M('users')->where("openid='".$zopenid."'")->getField('money'); //获取被砍价人剩余被砍钱数
            if($zopenid==$openid)   //判断是不是本人操作
            {
                $info=M('users')->where("openid='".$openid."'")->find();  //查询此人是否参与此活动
                if(empty($info))
                {
                    $this->redirect('index/baoming');  //没参与活动的话跳转到报名页面
                }

                $this->assign('zopenid',$openid);  //将被砍价人的openid在页面显示
                $this->assign('openid',$openid);  //如果是本人操作的话，zopenid和openid为同一个值
                $this->assign('flag','本人');
            }
            else{
                $this->assign('zopenid',$zopenid);
                $this->assign('openid',$openid);
                $this->assign('flag','非本人');
            }

            $this->assign('symoney',$symoney); //将剩余被砍钱数在页面显示
            $signpack=$this->getSignPackage(); //获取js的签名，用于实现分享到朋友圈和发送给朋友功能
            $this->assign('signpack',$signpack); //将获取的签名发送到对应页面的wx.config中
            $this->display('index');




        }

        //活动报名页面
        public function baoming()
        {
            $this->display();
        }
        //实现砍价功能
        public function kanjia(){


            $data['openid']=I('post.openid');
            $data['zopenid']=I('post.zopenid')?I('post.zopenid'):I('post.openid');
            $data['kmoney']=100;
            $data['pubtime']=date('Y-m-d H:i:s',time());
            $map['openid']=$data['openid'];
            $map['zopenid']=$data['zopenid'];
            $moneys=M('users')->where("openid='".$data['zopenid']."'")->getField('money'); //查看被砍人是否还需要砍价
            if($moneys<=0)
            {
                $result=[
                    'status'=>2,
                    'msg'=>'已经到底，无需再砍！！'
                ];
            }else{
                $info=M('kanjia')->where($map)->find(); //查询当前openid此人是否帮zopenid这人砍过价
                if(!empty($info))
                {
                    $result=[
                        'status'=>0,
                        'msg'=>'每人仅限砍一刀！'
                    ];
                }else{

                    $kyd=M('kanjia')->add($data);  //如果没砍过，则实现砍价，向kanjia表中插入一条记录
                    if($kyd!==false)
                    {
                        M('users')->where("openid='".$data['zopenid']."'")->setDec('money',100);//同时被砍价人的被砍钱数相应减少100元，此一百元可以自定随机数
                        $result=[
                            'status'=>1,
                            'msg'=>'成功砍掉100元！'
                        ];
                    }
                }
            }

            $this->ajaxReturn($result);
        }

        //报名处理页面
        public function do_baoming(){
            $data['username']=I('post.username');
            $data['telphone']=I('post.telphone');
            $data['openid']=session('openid');
            $data['money']=1000;
            $data['pubtime']=date('Y-m-d H:i:s',time());
            $al=M('users')->where("openid='".session('openid')."'")->find();
            if(!$al)
            {
                $info=M('users')->add($data);
                if($info!==false)
                {
                    $result=[
                        'status'=>1,
                        'openid'=>session('openid'),
                        'msg'=>'报名成功！'
                    ];
                }
            }
            else{
                $result=[
                    'status'=>0,
                    'msg'=>'您已经报完名，不能重复报名'
                ];
            }
            $this->ajaxReturn($result);

        }

        //获取openid页面，此页面是微信授权页面的回调地址
        public function getopenid()
        {
            $zopenid=$_GET['zopenid'];

            $code = $_GET['code'];
            $get_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
            $josn_token = file_get_contents($get_token);
            $obj_token = json_decode($josn_token);
            $token = $obj_token->access_token;
            S('access_token',$token,3600);
            $open_id = $obj_token->openid;
            //下面可以获取微信用户的详细信息包括 头像，用户名，性别等
//		$get_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$open_id."&lang=zh_CN";
//		$josn_userinfo = file_get_contents($get_userinfo);
//		$obj_userinfo = json_decode($josn_userinfo);
            session('openid',$open_id);
            if($zopenid!='')
            {
                redirect('http://www.opjpg.com/TP3/index.php/ylp/index/huodong/zopenid/'.$zopenid);
            }
            else{
                if($open_id !='')
                {
                    redirect('http://www.opjpg.com/TP3/index.php/ylp/index/huodong/zopenid/'.$open_id);
                    //$this->redirect('index/huodong',array('zopenid' => 0));
                }
                else{

                }
            }


        }

        //微信授权引导页面
        public function getinfo()
        {
            $zopenid=isset($_GET['zopenid'])?$_GET['zopenid']:'';
            $returnurl = urlEncode("http://www.opjpg.com/TP3/index.php/ylp/index/getopenid?zopenid=".$zopenid);
            header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$returnurl."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
        }


        //jssdk
        public function getSignPackage() {
            $jsapiTicket = $this->getJsApiTicket();

            // 注意 URL 一定要动态获取，不能 hardcode.
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $timestamp = time();
            $nonceStr = $this->createNonceStr();

            // 这里参数的顺序要按照 key 值 ASCII 码升序排序
            $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

            $signature = sha1($string);

            $signPackage = array(
                "appId"     => $this->appId,
                "nonceStr"  => $nonceStr,
                "timestamp" => $timestamp,
                "url"       => $url,
                "signature" => $signature,
                "rawString" => $string
            );
            return $signPackage;
        }

        private function createNonceStr($length = 16) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $str = "";
            for ($i = 0; $i < $length; $i++) {
                $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            }
            return $str;
        }

        private function getJsApiTicket() {
            // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例

            $data = S('jsapi_ticket');
            if(!$data)
            {
                $accessToken = $this->getAccessToken();
                // 如果是企业号用以下 URL 获取 ticket
                // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $res = json_decode($this->httpGet($url));
                $ticket = $res->ticket;
                if ($ticket) {

                    S('jsapi_ticket',$ticket,7200);
                }
            } else {
                $ticket = $data;
            }

            return $ticket;
        }

        private function getAccessToken() {
            // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
            $data = S('access_token');
            if (!$data) {
                // 如果是企业号用以下URL获取access_token
                // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appsecret";
                $res = json_decode($this->httpGet($url));
                $access_token = $res->access_token;
                if ($access_token) {
                    S('access_token',$access_token,7200);
                }
            } else {
                $access_token =S('access_token');
            }
            return $access_token;
        }

        private function httpGet($url) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 500);
            // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
            // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($curl, CURLOPT_URL, $url);

            $res = curl_exec($curl);
            curl_close($curl);

            return $res;
        }

    }
?>