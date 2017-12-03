<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>
<div class="container">

    <div class="row content">
        <section class="col-lg-3 hidden-xs hidden-md hidden-sm">
            <div class="help help-title" id="affix">
                <ul class="nav">
                    <li class="active"><a href="#quit">一键收藏</a></li>
                    <li><a href="#login">登录、注册</a></li>
                    <li><a href="#cat">分类管理</a></li>
                    <li><a href="#url">网址管理</a></li>
                    <li><a href="#url_adds">网址收藏、举报</a></li>
                    <li><a href="#suggests">建议反馈</a></li>
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
                        <p>3）右击“拖动方式”步骤里面的快速保存工具“一键收藏”,在所弹出的弹框中，选择“复制链接地址”;</p>
                        <?= Html::img('@web/image/help/collect3.png', ['class' => 'img-responsive']) ?>
                        <p>4）将鼠标移动到步骤二所添加的网址上右击，选中地址栏里面原来的地址（或直接删除原来的地址），右键粘贴步骤三中复制的地址链接，接着更改属性里面的名称为“一键收藏”，最后点击保存，“一键收藏”的功能就可以使用了；</p>
                        <?= Html::img('@web/image/help/collect4.png', ['class' => 'img-responsive']) ?>
                        <p>3.使用“一键收藏”添加网址</p>
                        <p>1）在所想收藏的网站上，直接点击上面步骤中添加在书签里的“一键收藏”工具，自动获取该网站的名称和网址，并跳转到 网址共享平台，进一步对该网址进行操作,最后点击”确定”，该网址就收录到 网址共享平台 了。提醒：没有登录的用户，会先跳转到登录页面登录。</p>
                        <?= Html::img('@web/image/help/collect5.png', ['class' => 'img-responsive']) ?>

                    </div>
                </div>
                <div class="section" id="login">
                    <h1>登录、注册</h1>
                    <div>
                        <p>只有拥有网址共享平台账号并且登录的用户才可以自定义自己的网址。</p>
                        <p>有账号的用户可以直接登录，没有账号的用户需要先注册。</p>
                        <p>您也可以通过第三方账号进行登录或者注册绑定。</p>
                        <?= Html::img('@web/image/help/login.png', ['class' => 'img-responsive']) ?>
                    </div>
                </div>
                <div class="section" id="cat">
                    <h1>分类管理</h1>
                    <div>
                        <p>1、分类增删改</p>
                        <p>将鼠标移动到分类标题栏，就会出现分类操作按钮。</p>
                        <?= Html::img('@web/image/help/category1.png', ['class' => 'img-responsive']) ?>
                        <p>2、分类排序</p>
                        <p>将鼠标移动到分类标题栏，鼠标会变成十字光标的形状，按住左键并拖动，将该分类拖动到自己所需的位置，就能实现分类的排序。</p>
                        <?= Html::img('@web/image/help/category2.png', ['class' => 'img-responsive']) ?>
                    </div>
                </div>
                <div class="section" id="url">
                    <h1>网址管理</h1>
                    <div>
                        <p>登录或注册成功后，自动跳转到我的网址页面，用户可以根据需求自定义自己的网址。</p>
                        <?= Html::img('@web/image/help/website1.png', ['class' => 'img-responsive']) ?>
                        <p>1、网址添加</p>
                        <?= Html::img('@web/image/help/website2.png', ['class' => 'img-responsive']) ?>
                        <p>2、网址分享、编辑、隐藏、私有/公开</p>
                        <?= Html::img('@web/image/help/website3.png', ['class' => 'img-responsive']) ?>
                        <p>3、网址排序</p>
                        <p>将鼠标放在所需移动的网址上，鼠标会变成十字光标的形状，点击左键并拖动，便可将该网址拖动到自己所需的位置，以此改变网址的排序。注：网址不受分类的影响，可以随意拖动到自己所需的位置。</p>
                        <?= Html::img('@web/image/help/website4.png', ['class' => 'img-responsive']) ?>
                    </div>
                </div>
                <div class="section" id="url_adds">
                    <h1>网址收藏、举报</h1>
                    <div>
                        <p>在“共享网址”或者他人网址页面中，您可以根据自己的需求把网址直接选择并添加到自己的页面中。</p>
                        <?= Html::img('@web/image/help/website5.png', ['class' => 'img-responsive']) ?>
                    </div>
                </div>
                <div class="section" id="suggests">
                    <h1>建议反馈</h1>
                    <div>
                        <p>点击页面右下角的“建议反馈”按钮，就能进行留言操作，有益的留言将会有相应的奖励。</p>
                        <?= Html::img('@web/image/help/suggest.png', ['class' => 'img-responsive']) ?>
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