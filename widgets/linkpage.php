<?php namespace app\widgets;

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/11/28
 * Time: 15:46
 */
class linkpage extends widgets
{
    public $nextPageLabel = '下一页';

    public $prevPageLabel = '上一页';

    public $firstPageLabel = '首页';

    public $lastPageLabel = '末页';

    public $showSummary = true;

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        $summary = $this->renderSummary();
        // $perPage = $this->renderPerPage();
        $perPage = '';
        $button = $this->renderPageButtons();

        $output = $perPage . $button;
        $output = $this->showSummary ? $summary . $output : $output;

        echo $output;
    }

    /**
     * @return string
     */
    protected function renderSummary()
    {
        $totalCount = $this->pagination->totalCount;

        if ($totalCount == 0) {
            return '';
        }

        $pageCount = $this->pagination->getPageCount();


        $begin = $this->pagination->getPage() * $this->pagination->pageSize + 1;
        $end =  $begin + $this->pagination->pageSize - 1;
        if ($totalCount < $this->pagination->pageSize) {
            $end = $totalCount;
        }

        $output = "<div class='summary'>第<b>$begin - $end</b>条，共<b>$pageCount</b>页，共<b>$totalCount</b>条数据</div>";

        return $output;
    }

    /**
     * @return string
     */
    protected function renderPerPage()
    {

        $totalCount = $this->pagination->totalCount;

        if ($totalCount == 0) {
            return '';
        }

        $request = \yii::$app->getRequest();
        $params = $request->getQueryParams();
        $params[0] = \yii::$app->controller->getRoute();
        $urlManager = \yii::$app->getUrlManager();

        $params['pageSize'] = 20;
        $page20 = ($request->get('pageSize') == 20) ? '<b>20</b>' : '<a href="' . $urlManager->createUrl($params) . '">20</a>';

        $params['pageSize'] = 50;
        $page50 = ($request->get('pageSize') == 50) ? '<b>50</b>' : '<a href="' . $urlManager->createUrl($params) . '">50</a>';

        $params['pageSize'] = 100;
        $page100 = ($request->get('pageSize') == 100) ? '<b>100</b>' : '<a href="' . $urlManager->createUrl($params) . '">100</a>';


        $output =  '<div class="page-size">每页: ' . $page20  .$page50 . $page100 . '</div>';

        return $output;
    }

}