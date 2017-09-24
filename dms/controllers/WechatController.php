<?php

namespace dms\controllers;

use Yii;
use yii\helpers\Url;
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
                        'type' => 'view',
                        'name' => '公司简介',
                        'url' => 'http://u.eqxiu.com/s/JAmGUHqa?from=singlemessage&isappinstalled=0&share_level=1&from_user=2565dab7-84e0-4b65-b272-cc5797cc0fe9&from_id=857b5346-a37c-4828-8bde-1e7628b1c8b7&share_time=1506127583421'
                    ],
                    [
                        'type' => 'view',
                        'name' => '东吴文化',
                        'url' => 'http://mp.weixin.qq.com/s/tuNCLdiaB1eMrRkrb4GUeA'
                    ],
                    [
                        'type' => 'view',
                        'name' => '南农新闻',
                        'url' => 'http://news.njau.edu.cn'
                    ]
                ]
            ],
            [
                'name' => '报修投诉',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '在线报修',
                        'url' => Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['business/repair-business'], true)], true)
                    ],
                    [
                        'type' => 'view',
                        'name' => '在线投诉',
                        'url' => Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['business/suggest-business'], true)], true)
                    ],
                    [
                        'type' => 'view',
                        'name' => '失物招领',
                        'url' => Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['business/pickup-business'], true)], true)
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
            $this->redirect($wechat->getOauth2AuthorizeUrl(Url::toRoute(['wechat/auth', 'url' => $url], true)));
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
            return $this->redirect(Url::toRoute(['/site/complete']));
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
                    $this->msg_text('您好，欢迎关注东吴物业。');
                } elseif (strtolower($this->postObj->Event) == 'unsubscribe') {
                    //取消关注事件处理
                } elseif (strtolower($this->postObj->Event) == 'scan') {
                    //扫描事件处理
                    //事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
                } elseif (strtolower($this->postObj->Event) == 'click') {
                    if ($this->postObj->EventKey == 'ABOUT_NEWS') {
                        $this->msg_text('南农新闻');
                    } elseif ($this->postObj->EventKey == 'CONTACT_US') {
                        $this->msg_text("电话：025-84395271\n微信：苏州市东吴物业南农项目部\n地址：南京农业大学物业管理部（南苑学工处旁）\n官网：http://szdwwy.suda.edu.cn\nQQ群：5276000372\n邮箱：dwwynn@126.com");
                    } elseif ($this->postObj->EventKey == 'CONTACT_HELP') {
                        $this->msg_text('帮助');
                    } else {
                        echo "无内容";
                    }
                }
            } else {
                if (!empty($this->keyword)) {
                    switch ($this->keyword) {
//                        case '测试账号':
//                            $this->msg_text('顶级测试账号：admin/1234 <a href="http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com">点击这里</a> 进入');
//                            break;
//                        case '报修':
//                            $this->msg_picture('报修', 'https://mmbiz.qlogo.cn/mmbiz_png/2iczHvak98e22xmQGYp5ia5olQw7rodDDyGE8RAuaDS6tfCGeBz5JZPqNlhW8ssUoy67goiaC4r2VmDRloKlOmQzA/0?wx_fmt=png', '', 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business');
//                            break;
//                        case '维修':
//                            $this->msg_picture('报修', 'https://mmbiz.qlogo.cn/mmbiz_png/2iczHvak98e22xmQGYp5ia5olQw7rodDDyGE8RAuaDS6tfCGeBz5JZPqNlhW8ssUoy67goiaC4r2VmDRloKlOmQzA/0?wx_fmt=png', '', 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business');
//                            break;
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
