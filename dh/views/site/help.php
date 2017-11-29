<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
?>
<div class="container">

    <div class="row content">
        <section class="col-lg-3 hidden-xs hidden-md hidden-sm">
            <div class="help help-title" id="affix">
                <ul class="nav">
                    <li class="active"><a href="#quit">一键收藏</a></li>
                    <li><a href="#login">登录、注册</a></li>
                    <li><a href="#cat">分类管理</a></li>
                    <li><a href="#url">自定义网址</a></li>
                    <li><a href="#url_adds">网址批量添加</a></li>
                    <li><a href="#tuijian">热门推荐</a></li>
                    <li><a href="#bianqian">便签添加</a></li>
                    <li><a href="#pifu">更换皮肤</a></li>
                    <li><a href="#banshi">切换板式</a></li>
                    <li><a href="#user">个人中心</a></li>
                    <li><a href="#tool">工具栏</a></li>
                </ul>
            </div>
        </section>
        <section class="col-lg-9">


            <div class="help">
                <div class="section" id="quit">
                    <h1>一键收藏</h1>
                    <div>
                        <p>网址共享平台 加入了 <b>一键收藏</b> 功能，该功能能方便用户把需要保存的网址快速收藏到 网址共享平台 ，而不需要填写太多繁杂的内容。使用该功能，需要先把该按钮加入到浏览器收藏夹，操作方法有两种。</p>
                        <p>1.拖动方式</p>
                        <p>快速保存工具<a class="btn btn-primary" href='javascript:(function(){var m = window.open("https://www.wzgxpt.com/site/user?url=" + encodeURIComponent(document.URL) + "&title=" + encodeURIComponent(document.title), "_blank"); m.focus()}())'>一键收藏</a>，移动鼠标到该按钮上，按住鼠标左键不放，将其拖动到<b>浏览器收藏夹工具栏</b>中。
                        </p>
                        <?= Html::img('@web/image/help/collect1.png', ['class' => 'img-responsive']) ?>
                        <p>2.手动方式</p>
                        <p>1）在浏览器中打开 网址共享平台 网站；</p>
                        <p>2）点击浏览器收藏按钮，将 网址共享平台 保存到收藏栏中；</p>
                        <?= Html::img('@web/image/help/collect2.png', ['class' => 'img-responsive']) ?>
                        <p>3）右击”拖动方式”步骤里面的快速保存工具”一键收藏”,在所弹出的弹框中，选择”复制链接地址”;</p>
                        <img src="/Public/help/33.png" />
                        <p>4）将鼠标移动到步骤二所添加的网址上右击，选中地址栏里面原来的地址（或直接删除原来的地址），右键粘贴步骤三中复制的地址链接，接着更改属性里面的名称为“一键收藏”，最后点击保存，“一键收藏”的功能就可以使用了；</p>
                        <img src="/Public/help/34.png" />
                        <p>3.使用“一键收藏”添加网址</p>
                        <p>1）在所想收藏的网站上，直接点击上面步骤中添加在收藏栏的“一键收藏”工具，自动获取该网站的名称和网址，并跳转到 网址共享平台，进一步对该网址进行操作,最后点击”确定”，该网址就收录到 网址共享平台 了。提醒：没有登录的用户，会先跳转到登录页面登录。</p>
                        <img src="/Public/help/35.png" />

                    </div>
                </div>
                <div class="section" id="login">
                    <h1>登录、注册</h1>
                    <div>
                        <p>只有拥有网址共享平台账号并且登录的用户才可以自定义自己的网址。</p>
                        <p>有账号的用户可以直接登录，没有账号的用户需要先注册。</p>
                        <img src="/Public/help/1.jpg" />
                    </div>
                </div>

                <div class="section" id="cat">
                    <h1>分类管理</h1>
                    <div>
                        <p>1、分类增删改</p>
                        <img src="/Public/help/3.jpg" />
                        <p>2、分类排序</p>
                        <p>将鼠标放在分类标签处，鼠标会变成十字光标的形状，点击左键并拖动，便可将该分类拖动到自己所需的位置，以此改变分类的排序。</p>
                        <img src="/Public/help/4.jpg" />
                    </div>
                </div>
                <div class="section" id="url">
                    <h1>自定义网址</h1>
                    <div>
                        <p>登录或注册成功后，自动跳转到用户自定义页面，用户可以根据需求自定义自己的网址。</p>
                        <img src="/Public/help/2.jpg" />
                        <p>1、网址添加</p>
                        <img src="/Public/help/5.jpg" />
                        <p>2、网址编辑删除</p>
                        <img src="/Public/help/6.jpg" />
                        <p>3、网址排序</p>
                        <p>将鼠标放在所需移动的网址标签上，鼠标会变成十字光标的形状，点击左键并拖动，便可将该网址拖动到自己所需的位置，以此改变网址的排序。注：网址标签不受分类的影响，可以随意拖动到自己所需的位置。</p>
                        <img src="/Public/help/7.jpg" />
                    </div>
                </div>
                <div class="section" id="url_adds">
                    <h1>网址批量添加</h1>
                    <div>
                        <p>点击页面右上角的“网址大全”按钮，进入网址大全页面。网址大全中有39个类别，共390个网址，您可以根据自己的需求直接选择并添加到自己的导航网址里面。</p>
                        <img src="/Public/help/8.jpg" />
                    </div>
                </div>
                <div class="section" id="tuijian">
                    <h1>热门推荐</h1>
                    <div>
                        <p>在首页，右边栏，可以直接查看热门推荐，或者点击顶部“热门推荐”按钮，查看推荐网站。</p>
                        <img src="/Public/help/12.jpg" />
                        <p>点击“热门推荐”按钮，进入热门推荐页面，点击“发布推荐”按钮，发布推荐的网站。</p>
                        <img src="/Public/help/13.jpg" />
                    </div>
                </div>
                <div class="section" id="bianqian">
                    <h1>便签添加</h1>
                    <div>
                        <img src="/Public/help/10.png" />
                    </div>
                </div>
                <div class="section" id="pifu">
                    <h1>更换皮肤</h1>
                    <div>
                        <img src="/Public/help/11.jpg" />
                    </div>
                </div>
                <div class="section" id="banshi">
                    <h1>切换板式</h1>
                    <div>
                        <p>本站有“横板”和“竖版”两种形式，直接点击“切换横/竖版”按钮进行切换。</p>
                        <img src="/Public/help/14.jpg" />
                    </div>
                </div>
                <div class="section" id="user">
                    <h1>个人中心</h1>
                    <div>
                        <p>用户登录后，页面右上角会显示“个人中心”的按钮。其中，包含“签到”、“站内信”、“修改密码”、“回收站”、“退出登录”5个功能，用户可根据自己的需求点击相应功能。</p>
                        <img src="/Public/help/15.jpg" />
                        <p>1、签到</p>
                        <p>点击“签到”按钮，进入签到页面，进行签到。注：每日只能签到一次，且只能签当天的。</p>
                        <img src="/Public/help/16.jpg" />
                        <p>2、站内信</p>
                        <p>点击“站内信”按钮，进入站内信页面，查看网址共享平台发给您的想过信息。</p>
                        <img src="/Public/help/17.jpg" />
                        <p>3、修改密码</p>
                        <p>点击“修改密码”按钮，进入修改密码页面，根据提示修改用户密码。</p>
                        <img src="/Public/help/18.jpg" />
                        <p>4、回收站</p>
                        <p>点击“回收站”按钮，进入回收站页面，对被删除的网址进行永久删除的操作。</p>
                        <img src="/Public/help/19.jpg" />
                    </div>
                    <div class="section" id="tool">
                        <h1>工具栏</h1>
                        <div>
                            <p>1、工具栏设置</p>
                            <p>“工具栏设置”可“显示/隐藏”首页左侧的应用中心和文章中心这两个模块。注：只有登录后，才会显示该按钮。</p>
                            <img src="/Public/help/20.jpg" />
                            <p>2、应用中心</p>
                            <p>网址共享平台精心挑选出生活中常用到的应用，用户可以根据需求直接点击相关应用，进行操作。</p>
                            <img src="/Public/help/21.jpg" />
                            <p>3、文章中心</p>
                            <p>网址共享平台会在文章中心中，发布网站的最新公告、导航日志，以及推送各类网站的排行榜。</p>
                            <img src="/Public/help/22.jpg" />

                        </div>
                    </div>

                </div>



        </section>
    </div>
</div>
<script>
<?php $this->beginBlock('help') ?>
    $(document).ready(function () {
        setTimeout(function () {
            $('#affix').affix({
                offset: {
                    top: 230,
                    bottom: function () {
                        return this.bottom = $('.footer').outerHeight(true) + 60
                    }
                }
            });
        }, 100);

        $('body').scrollspy({target: '#affix'});
    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['help'], \yii\web\View::POS_END); ?>