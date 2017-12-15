<?php

namespace pkpudev\widget\assets;

class DatePickerAsset extends \yii\web\AssetBundle
{
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

	/**
   * @inheritdoc
   */
  public function init()
  {
    $this->sourcePath = __DIR__ . '/bootstrap-datepicker';
    parent::init();
  }
}