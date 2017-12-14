<?php

namespace pkpudev\widget;

/**
 * Bootstrap Panel Standard Widget
 */
class StandardPanel extends \yii\base\Widget
{
	public $panelType = 'panel-primary';
	public $title = 'Default Panel';
	public $withHeader = true;

	public function init()
	{
		if ($this->withHeader): ?>
		<div class="panel <?=$this->panelType?> filterable">
			<div class="panel-heading">
				<h3 class="panel-title"><?=$this->title?></h3>
			</div>
		<?php endif;
	}

	public function run()
	{
		if ($this->withHeader): ?>
		</div>
		<?php endif;
	}
}