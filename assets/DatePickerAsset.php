<?php

namespace pkpudev\widget\assets;

\Yii::setAlias('@grid-assets', __DIR__.'/bootstrap-datepicker');

class DatePickerAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@grid-assets';
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'dist/css/bootstrap-datepicker.css',
	];
	public $js = [
		'js/bootstrap-datepicker.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}