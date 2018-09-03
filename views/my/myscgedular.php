<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;

$this->title = 'scgedular';
$this->params['breadcrumbs'][] = $this->title;
?>
<?//=Html::jsFile('@web/js/template7.min.js')?>



<script id="template" type="text/template7">
    <div>
        学年:
        <select id="schoolyear">
            {{#each stugrade[0]}}
            <option value="{{this}}">{{this}}</option>
            {{/each}}
        </select>
        学期:
        <select id="semester">
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
        <input value="提交" type="submit" id="removeattr" />
        <h1 align="center">课表</h1>
    </div>

    <p>学号：{{stuNumber}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 姓名：{{stuName}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年级：{{stugrade[0][3]}} 学年：</p>
    <table width="100%" align="center" border="1">
        <tr>
            <th width="5%">课时</th>
            <th width="15%">星期一</th>
            <th width="15%">星期二</th>
            <th width="15%">星期三</th>
            <th width="15%">星期四</th>
            <th width="15%">星期五</th>
            <th width="10%">星期六</th>
            <th>星期日</th>
        </tr>
        {{#each time }}
        <tr>
            <td style="text-align: center;">{{@index+1}}</td>
            {{#each this}}
            <td style="text-align: center;"> {{class}} <br> {{classname}} <br> {{teacher}} <br> {{classroom}} </td>
            {{/each}}
        </tr>
        {{/each}}
    </table>
</script>

<script type="text/javascript">
    $(function () {

        function templateMethod(jso) {
            var template = $('#template').html(); //获取模板
            var compiled = Template7.compile(template); //编译模板
            var htmlStr = compiled(jso); //使用模板加载数据
            $('#content').html(htmlStr); //将得到的结果输出到指定区域
        }

        var jso = new Array();
        $.post('?r=my/acgedular', {
                schoolyear: "2018",
                semester: "1"
            },
            function(data, status) {
                jso = data;
                templateMethod(jso);
            }, "json"
        );

        /************方法二************/
        $('div').on('click', '#removeattr', function() {
            var jso = new Array();
            $.post('?r=my/acgedular', {
                    schoolyear: $('#schoolyear').find('option:selected').attr('value'),
                    semester: $('#semester').find('option:selected').attr('value')
                },
                function(data, status) {
                    jso = data;
                    templateMethod(jso);
                }, "json"
            );

        })
        

        // $('#removeattr').click(function() {
        //     var jso = new Array();
        //     $.post('?r=my/acgedular', {
        //             schoolyear: $('#schoolyear').find('option:selected').attr('value'),
        //             semester: $('#semester').find('option:selected').attr('value')
        //         },
        //         function(data, status) {
        //             jso = data;
        //             templateMethod(jso);
        //         }, "json"
        //     );
        //
        // })


    });
</script>

<div id="content">

</div>
