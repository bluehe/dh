<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;
use common\models\UserAuth;

class WechatController extends Controller {

    public $enableCsrfValidation = false;
    public $postObj;
    public $fromUsername;
    public $toUsername;
    public $keyword;
    public $time;
    public $token;

    public function actions() {
        $weixin = Yii::$app->wechat;
        $this->token = $weixin->token;
    }

    public function actionIndex() {
        $wechat = Yii::$app->wechat;

//        $ticket = $wechat->createQrCode(['expire_seconds' => 600, 'action_name' => 'QR_SCENE', 'action_info' => ['scene' => ['scene_id' => 1]]]);
//        $qrcode = $wechat->getQrCode($ticket['ticket']);
//        return $this->redirect($qrcode);


        if ($wechat->checkSignature()) {
            //分发处理的code
            //为了安全,建议接收信息的时候验证一下signature
            if (Yii::$app->request->get('echostr')) {
                echo Yii::$app->request->get('echostr');
                exit;
            } else {
                $this->responseMsg();
            }
        } else {
            //signature不通过的提示信息
            echo "";
        }
    }

    public function actionCreateMenu() {
        $wechat = Yii::$app->wechat;
        $res = $wechat->createMenu([
            [
                'name' => '关于东吴',
                'sub_button' => [
                    [
                        'type' => 'click',
                        'name' => '公司简介',
                        'key' => 'ABOUT_COMPANY'
                    ],
                    [
                        'type' => 'click',
                        'name' => '东吴文化',
                        'key' => 'ABOUT_US'
                    ],
                    [
                        'type' => 'click',
                        'name' => '南农新闻',
                        'key' => 'ABOUT_NEWS'
                    ]
                ]
            ],
            [
                'name' => '报修投诉',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '在线报修',
                        'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business'
                    ],
                    [
                        'type' => 'view',
                        'name' => '在线投诉',
                        'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/suggest-business'
                    ],
                    [
                        'type' => 'view',
                        'name' => '失物招领',
                        'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/pickup-business'
                    ]
                ]
            ],
            [
                'name' => '联系我们',
                'sub_button' => [
                    [
                        'type' => 'click',
                        'name' => '服务电话',
                        'key' => 'CONTACT_US'
                    ],
                    [
                        'type' => 'click',
                        'name' => '帮助',
                        'key' => 'CONTACT_HELP'
                    ]
                ]
            ]
//            [
//                'type' => 'view',
//                'name' => '网上报修',
//                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business'
//            ]
        ]);
        return $res ? '成功' : '失败';
    }

    public function actionGetMenu() {
        $wechat = Yii::$app->wechat;
        return var_dump($wechat->getMenu());
    }

    //网页跳转
    public function actionRedirect($url) {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect($url);
        } else {
            $wechat = Yii::$app->wechat;
            $this->redirect($wechat->getOauth2AuthorizeUrl('http://ny.gxgygl.com/wechat/auth?url=' . $url));
        }
    }

    //网页授权
    public function actionAuth($url, $code) {
        $wechat = Yii::$app->wechat;
        $access_token = $wechat->getOauth2AccessToken($code);
        $auth = UserAuth::find()->where(['type' => 'weixin', 'open_id' => $access_token['openid']])->one();
        if ($auth) {
            //存在
            if (Yii::$app->user->login($auth->user)) {
                return $this->redirect($url);
            }
        } else {
            //不存在，注册
            Yii::$app->session->set('auth_type', 'weixin');
            Yii::$app->session->set('auth_openid', $access_token['openid']);
            return $this->redirect('/site/complete');
        }
    }

    public function responseMsg() {
        $postStr = file_get_contents('php://input');
        if (!empty($postStr)) {
            libxml_disable_entity_loader(true);
            $this->postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->fromUsername = $this->postObj->FromUserName;
            $this->toUsername = $this->postObj->ToUserName;
            $this->keyword = trim($this->postObj->Content);
            $this->time = time();
            if (strtolower($this->postObj->MsgType) == 'event') {
                if (strtolower($this->postObj->Event) == 'subscribe') {
                    //关注事件处理
                    if ($this->postObj->EventKey) {
                        //带参数的关注,事件KEY值，qrscene_为前缀，后面为二维码的参数值
                    }
                    $this->msg_text('您好，欢迎关注高校公寓管理，目前功能持续开发中，报修系统体验请<a href="http://ny.gxgygl.com">点击这里</a>，或者回复【报修】。如果需要系统测试账号，请回复【测试账号】。');
                } elseif (strtolower($this->postObj->Event) == 'unsubscribe') {
                    //取消关注事件处理
                } elseif (strtolower($this->postObj->Event) == 'scan') {
                    //扫描事件处理
                    //事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
                } elseif (strtolower($this->postObj->Event) == 'click') {
                    if ($this->postObj->EventKey == 'ABOUT_COMPANY') {
                        $this->msg_text('公司简介');
                    } elseif ($this->postObj->EventKey == 'ABOUT_US') {
                        $this->msg_text('东吴文化');
                    } elseif ($this->postObj->EventKey == 'ABOUT_NEWS') {
                        $this->msg_text('南农新闻');
                    } elseif ($this->postObj->EventKey == 'CONTACT_US') {
                        $this->msg_text('服务电话');
                    } elseif ($this->postObj->EventKey == 'CONTACT_HELP') {
                        $this->msg_text('帮助');
                    } else {
                        echo "";
                    }
                }
            } else {
                if (!empty($this->keyword)) {
                    switch ($this->keyword) {
                        case '测试账号':
                            $this->msg_text('顶级测试账号：admin/1234 <a href="http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com">点击这里</a> 进入');
                            break;
                        case '报修':
                            $this->msg_picture('报修', 'https://mmbiz.qlogo.cn/mmbiz_png/2iczHvak98e22xmQGYp5ia5olQw7rodDDyGE8RAuaDS6tfCGeBz5JZPqNlhW8ssUoy67goiaC4r2VmDRloKlOmQzA/0?wx_fmt=png', '', 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business');
                            break;
                        case '维修':
                            $this->msg_picture('报修', 'https://mmbiz.qlogo.cn/mmbiz_png/2iczHvak98e22xmQGYp5ia5olQw7rodDDyGE8RAuaDS6tfCGeBz5JZPqNlhW8ssUoy67goiaC4r2VmDRloKlOmQzA/0?wx_fmt=png', '', 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business');
                            break;
                        default:
                            echo "";
                            break;
                    }
                } else {
                    echo "";
                }
            }
        } else {
            echo "";
            exit;
        }
    }

    //文本回复
    public function msg_text($text) {
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";
        $msgType = "text";
        $contentStr = $text;
        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
        echo $resultStr;
        exit;
    }

    //单图文回复
    public function msg_picture($title, $pic, $desc, $url) {
        $pictureTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>1</ArticleCount>
						<Articles>
								<item>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								<PicUrl><![CDATA[%s]]></PicUrl>
								<Url><![CDATA[%s]]></Url>
								</item>
						</Articles>
						</xml> ";
        $msgType = 'news';
        $resultStr = sprintf($pictureTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $title, $desc, $pic, $url);
        echo $resultStr;
        exit;
    }

}
