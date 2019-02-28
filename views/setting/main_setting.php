<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 13:55
 */
use yii\helpers\Url;

$this->title =  "服务端-小程序设置";
$this->params['breadcrumbs'][] = '系统配置';
$tab = (isset($tab) && ($tab)) ? $tab : 'wechat';

$tabArr = [
    [
        'label' => 'mina_curl',
        'name' => '本地爬虫url设置',
        'url' => Url::toRoute(["/setting/curlsetting"])
    ],
    [
        'label' => 'mina_kuaidi',
        'name' => '快递api设置',
        'url' => Url::toRoute(["/setting/kuaidisetting"])
    ],
    [
        'label' => 'mina_baidu',
        'name' => '百度云api设置',
        'url' => Url::toRoute(["/setting/baidusetting"])
    ],
    [
        'label' => 'mina_kuaidilist',
        'name' => '快递公司列表',
        'url' => Url::toRoute(["/setting/kuaidilist"])
    ],
    [
        'label' => 'mina_comment',
        'name' => '文章列表',
        'url' => Url::toRoute(["/setting/commentlist"])
    ],
]
?>

<ul class="nav nav-tabs">
    <?php foreach($tabArr as $value) : ?>
        <li
            <?php
            if($value['label'] == $tab) {
                ?>
                class="active"
                <?php
            }
            ?>
        ><a href="<?=$value['url']?>"><?=$value['name']?></a></li>
    <?php endforeach;?>
</ul>

<div class="col-md-12" style="padding-top: 20px;"></div>

