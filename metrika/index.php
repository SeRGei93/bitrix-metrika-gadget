<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$params = [
    'ids' => $arGadgetParams["IDS"],
    'metrics' => 'ym:s:visits,ym:s:pageviews,ym:s:users',
    'date1' => $arGadgetParams["PERIOD"] . 'daysAgo',
    'date2' => 'today',
    'group' => 'day'
];

$cache = new CPHPCache;
if($cache->StartDataCache($arGadgetParams["CACHE_TIME"], 'metrika', "gadget_metrika")) {

    $http = new \Bitrix\Main\Web\HttpClient();
    $http->setTimeout(10);
    $http->setHeader('Authorization:', 'OAuth ' . $arGadgetParams["TOKEN"]);
    $res = $http->get("https://api-metrika.yandex.net/stat/v1/data/bytime?". urldecode(http_build_query($params)));

    #$res = \Bitrix\Main\Text\Encoding::convertEncoding($res, 'UTF-8', SITE_CHARSET);

    $res = \Bitrix\Main\Web\Json::decode($res);

    $visits = implode(',', $res['data'][0]['metrics'][0]);
    $pageviews = implode(',', $res['data'][0]['metrics'][1]);
    $users = implode(',', $res['data'][0]['metrics'][2]);
    $date1 = new DateTime($res['query']['date1']);
    $date2 = new DateTime($res['query']['date2']);
    $period = $date1->format('d.m.Y') . ' - ' . $date2->format('d.m.Y');


    $all_visits = $res['totals'][0][0];
    $all_pageviews = $res['totals'][0][1];
    $all_users = $res['totals'][0][2];
    $time_intervals = $res['time_intervals'];
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.19.0/apexcharts.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.19.0/apexcharts.min.js"></script>

    <style>
        #chart{
            max-width: 750px
        }
        .metrika_legend_header{
            text-align: center;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .metrika_legend_body{
            display: flex;
            flex-direction: row;
            justify-content: center;
            font-size: 13px;
        }
        .metrika_legend_body span{
            padding: 8px 15px;
        }
    </style>

    <div id="chart"></div>

    <div class="metrika_legend">
        <div class="metrika_legend_header">
            <span><b>Интервал:</b> <?=$period;?></span>
        </div>
        <div class="metrika_legend_body">
            <span><b>Посетителей:</b> <?echo $res['totals'][0][2];?></span>
            <span><b>Визитов:</b> <?echo $res['totals'][0][0];?></span>
            <span><b>Просмотров:</b> <?echo $res['totals'][0][1];?></span>
        </div>
    </div>

    <script>
        var options = {
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
                locales: [{
                    "name": "ru",
                    "options": {
                        "months": ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                        "shortMonths": ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
                        "days": ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
                        "shortDays": ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                        "toolbar": {
                            "exportToSVG": "Скачать SVG",
                            "exportToPNG": "Скачать PNG",
                            "menu": "Меню",
                            "selection": "Выделение",
                            "selectionZoom": "Выделение с увеличением",
                            "zoomIn": "Увеличить",
                            "zoomOut": "Уменьшить",
                            "pan": "Управлять",
                            "reset": "Сбросить Zoom"
                        }
                    }
                }],
                defaultLocale: "ru"
            },
            colors: ['#008FFB', '#00E396', '#feb019'],
            dataLabels: {
                enabled: true,
                enabledOnSeries: [2],
            },
            stroke: {
                curve: 'smooth',
                width: [2, 2, 3],
            },
            fill: {
                opacity: [0.5, 0.5, 0.5],
            },
            series: [
                {
                    name: 'Посетители',
                    data: [<?=$users;?>],
                    type: 'area'
                },
                {
                    name: 'Визиты',
                    data: [<?=$visits;?>],
                    type: 'area'
                },
                {
                    name: 'Просмотры',
                    data: [<?=$pageviews;?>],
                    type: 'line'
                },
            ],
            xaxis: {
                type: 'datetime',
                categories: [
                    <?
                    foreach ($time_intervals as $item) {
                        echo "'" . $item[0] . "',";
                    }
                    ?>
                ],
            },
            legend: {
                show: true,
                position: 'top',
            },

        };

        var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );

        chart.render();

    </script>

<?}
$cache->EndDataCache();
?>




