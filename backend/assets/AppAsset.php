<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/style.css',
        'css/font-awesome.css',
        'css/font-awesome.min.css',
        'css/simple-line-icons.css',
        'css/form_ajax.css',
    ];
    public $js = [
        'js/app.js',
        'js/views/charts.js',
        'js/views/main.js',
        'js/views/widgets.js',
        'js/bower_components/jquery/dist/jquery.min.js',
        'js/bower_components/tether/dist/js/tether.min.js',
        'js/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'js/bower_components/pace/pace.min.js',
        'js/bower_components/chart.js/dist/Chart.min.js',
        'js/form_ajax.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
