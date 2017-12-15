<?php

namespace pkpudev\widget\grid;

use pkpudev\widget\assets\DatePickerAsset;
use pkpudev\widget\StandardPanel;
use yii\bootstrap\ButtonGroup;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Grid Tabular Form Input
 */
class TabularInput extends \yii\base\Widget
{
  /**
   * @var string title
   */
  public $title;
  /**
   * Control per Row
   * @var InputControl[] controls
   */
  public $controls = [];
  /**
   * @var array data
   */
  public $data = [];
  /**
   * @var string id
   */
  public $idField = 'id';
  /**
   * @var string parentIdField
   */
  public $parentIdField = 'parent_id';
  /**
   * @var string idListName
   */
  public $idListName = 'taskList';
  /**
   * @var string modelClassName
   */
  public $modelClassName = 'Task';
  /**
   * @var string requiredClassName
   */
  public $requiredClassName = 'required';
  /**
   * @var string rowClassName
   */
  public $rowClassName = 'taskRowClone';
  /**
   * @var string addRowClassName
   */
  public $addRowClassName = 'addTaskRow';
  /**
   * @var string delRowClassName
   */
  public $delRowClassName = 'delTaskRow';
  /**
   * @var string btnSaveClassName
   */
  public $btnSaveClassName = 'btnSave';
  /**
   * @var string formName
   */
  public $formName = 'frmForm';
  /**
   * @var string titleClassName
   */
  public $titleClassName = 'titleName';
  /**
   * @var string calendarClassName
   */
  public $calendarClassName = 'calendarTask';
  /**
   * @var string useCalendar
   */
  public $useCalendar = 'true';
  /**
   * @var string messageOnErrorSubmitForm
   */
  public $messageOnErrorSubmitForm = 'Silakan lengkapi isian Task/Activity!';
  /**
   * @var bool withHeader
   */
  public $withHeader = true;
  /**
   * @var string cloneWithEvent
   */
  public $cloneWithEvent = 'false';

  public function run()
  {
    $this->registerScript();
    $this->createWidget();
  }

  protected function createWidget()
  {
    echo StandardPanel::begin(['title'=>$this->title]); ?>
    <table class="table table-condensed table-bordered">
      <thead>
        <tr class="filters">
          <th style="width:75px;">No</th>
          <?php foreach ($this->controls as $control): ?>
          <?php if($control->title): ?><th><?=$control->title?></th><?php endif; ?>
          <?php endforeach; ?>
          <th class="col-sm-2">Actions</th>
        </tr>
      </thead>
      <tbody id="<?=$this->idListName?>">
        <?php $num = 1; $class = 'odd'; ?>
        <?php foreach ($this->data as $task): ?>
          <?php $this->buildRow($num++, $class, $task); ?>
        <?php endforeach ?>
        <?php $this->buildRow($num, $class, []); ?>
      </tbody>
    </table>
    <?= StandardPanel::end();
  }

  protected function buildRow($index, $classOddEven, $paramData)
  {
    $disabled = ($index==1) ? ['disabled'=>true] : []; ?>
    <tr class="<?=$this->rowClassName?> <?=($classOddEven=='odd')?'even':'odd'?>">
      <td>
        <div class="num"><?=$index?></div>
        <?= Html::hiddenInput(
          "{$this->modelClassName}[{$this->idField}][]",
          $paramData[$this->idField]); ?>
        <?= Html::hiddenInput(
          "{$this->modelClassName}[{$this->parentIdField}][]",
          $paramData[$this->parentIdField]); ?>
        <?php foreach ($this->controls as $control): ?>
          <?php if ($control->type == InputControl::HIDDEN_INPUT): ?>
          <?php $this->buildControl($control, $paramData); ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </td>
      <?php foreach ($this->controls as $control): ?>
        <?php if ($control->type != InputControl::HIDDEN_INPUT): ?>
        <td><?php $this->buildControl($control, $paramData); ?></td>
        <?php endif; ?>
      <?php endforeach; ?>
      <!-- Action Buttons -->
      <td>
        <?= ButtonGroup::widget(['buttons'=>[
          [
            'label'=>'<i class="fa fa-plus"></i>',
            'encodeLabel'=>false,
            'options'=>['class'=>$this->addRowClassName]
          ],
          [
            'label'=>'<i class="fa fa-remove"></i>',
            'encodeLabel'=>false,
            'options'=>['class'=>$this->delRowClassName]
          ],
        ]]); ?>
      </td>
    </tr>
    <?php
  }

  protected function buildControl(InputControl $control, $paramData)
  {
    $name = "{$this->modelClassName}[{$control->name}][]";
    $value = $paramData[$control->name];
    $class = 'form-control ' . ($control->type == InputControl::DATE_INPUT) ? $this->calendarClassName : null;
    $class = ($control->required) ? "{$class} {$this->requiredClassName} " : $class;
    $class = ($control->htmlOptions && ($cls = $control->htmlOptions['class'])) ? "$class $cls" : $class;
    $htmlOptions = ArrayHelper::merge($control->htmlOptions, [
      'class'=>$class,
      'placeholder'=>$control->title
    ]);

    $options = [$name, $value];
    if ($control->type == InputControl::DROPDOWN_INPUT) {
      array_push($options, $control->data);
    }
    array_push($options, $htmlOptions); ?>

    <?= $control->preContent ?>
    <?php if ($control->type == InputControl::DATE_INPUT): ?>
      <div class="input-group">
        <?= Html::textInput($name, $value, $htmlOptions); ?>
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
    <?php elseif ($control->type == InputControl::NUMBER_INPUT): ?>
      <?= Html::textInput($name, $value, ArrayHelper::merge($htmlOptions, [
        'class'=>'text-right txt_number',
      ])); ?>
    <?php else: ?>
      <?= call_user_func_array(['Html', $control->type], $options)?>
    <?php endif; ?>
    <?= $control->postContent;
  }

  protected function registerScript()
  {
    DatePickerAsset::register($this->view);

    $script = "
    var addTaskRow = addTaskRow || '.{$this->addRowClassName}'
    var btnSave = btnSave || '#{$this->btnSaveClassName}'
    var calendarClassName = calendarClassName || '.{$this->calendarClassName}'
    var calendarTask = calendarTask || '.{$this->calendarClassName}'
    var delTaskRow = delTaskRow || '.{$this->delRowClassName}'
    var formName = formName || '#{$this->formName}'
    var idListName = idListName || '#{$this->idListName}'
    var messageSubmit = messageSubmit || '{$this->messageOnErrorSubmitForm}'
    var taskRowClone = taskRowClone || '.{$this->rowClassName}'
    var titleName = titleName || '.{$this->titleClassName}'
    var useCalendar = useCalendar || {$this->useCalendar}
    var withDataAndEvents = withDataAndEvents || {$this->cloneWithEvent}";
    $script .= file_get_contents(__DIR__.'/../assets/script.js');

    $rand = date('YmdHis');
    $view = $this->view;
    $view->registerJs($script, $view::POS_READY, "js-func-{$rand}");
  }
}