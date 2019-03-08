<?php

namespace pkpudev\widget\grid;

use pkpudev\widget\assets\DatePickerAsset;
use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

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
     * @var bool is bootstrap form horizontal
     */
    public $isHorizontal;
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
     * @var bool withActions
     */
    public $withActions = true;
    /**
     * @var bool withNewRow
     */
    public $withNewRow = true;
    /**
     * @var bool withCheckbox
     */
    public $withCheckbox = false;
    /**
     * @var string checkboxInputId
     */
    public $checkboxInputId = 'tabular_grid';
    /**
     * @var string checkboxInputClass
     */
    public $checkboxInputClass = 'check_task';
    /**
     * @var callable checkboxJsCallback
     */
    public $checkboxJsCallback;
    /**
     * @var string cloneWithEvent
     */
    public $cloneWithEvent = 'false';

    public function init()
    {
        parent::init();

        // Check controls TODO change to column
        if (empty($this->controls)) {
            throw new InvalidConfigException("Property controls is not defined");
        }

        // Sanitize controls
        $validControls = [];
        foreach ($this->controls as $row) {
            if (is_array($row)) {
                $control = new InputControl($row);
            } elseif ($row instanceof InputControl) {
                $control = $row;
            } else {
                throw new InvalidConfigException("One of controls property is not valid");
            }
            $validControls[] = $control;
        }
        $this->controls = $validControls;
    }

    public function run()
    {
        $this->registerScript();
        $this->createWidget();
    }

    protected function createWidget()
    {
        ?>
        <div class="form-group">
            <?= Html::label($this->title, null, ['class'=>'control-label']) ?>
            <?php $this->createGrid() ?>
            <!-- Error Block -->
        </div>
        <?php
    }

    protected function createGrid()
    {
        ?>
        <table class="table table-condensed table-bordered">
            <thead>
                <tr class="filters">
                    <?php if ($this->withCheckbox ) : ?>
                    <th>
                        <?= Html::checkbox("{$this->checkboxInputId}_all", false, [
                            'id'=>"{$this->checkboxInputId}_all",
                        ]) ?>
                    </th>
                    <?php endif; ?>
                    <th style="width:75px;">No</th>
                    <?php foreach ($this->controls as $control): ?>
                        <?php if($control->title ) : ?>
                        <th class="<?= $control->class ?>"><?=$control->title?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($this->withActions ) : ?>
                    <th class="col-sm-2">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="<?=$this->idListName?>">
                <?php $num = 1; $class = 'odd'; ?>
                <?php foreach ($this->data as $task): ?>
                    <?php $this->buildRow($num++, $class, $task); ?>
                <?php endforeach ?>
                <?php if ($this->withNewRow ) : ?>
                    <?php $this->buildRow($num, $class, []); ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    protected function buildRow($index, $classOddEven, $paramData)
    {
        // $disabled = ($index==1) ? ['disabled'=>true] : [];
        $value = $paramData[$this->idField]; ?>
        <tr class="<?=$this->rowClassName?> <?=($classOddEven=='odd')?'even':'odd'?>">
            <?php if ($this->withCheckbox ) : ?>
            <td>
                <?= Html::checkbox("{$this->checkboxInputId}[]", false, [
                    'id'=>"{$this->checkboxInputId}_{$index}",
                    'class'=>"{$this->checkboxInputClass}",
                    'value'=>$value,
                ]) ?>
            </td>
            <?php endif; ?>
            <td>
                <div class="num"><?=$index?></div>
                <?= Html::hiddenInput(
                    "{$this->modelClassName}[{$this->idField}][]",
                    $paramData[$this->idField]
                ); ?>
                <?= Html::hiddenInput(
                    "{$this->modelClassName}[{$this->parentIdField}][]",
                    $paramData[$this->parentIdField]
                ); ?>
                <?php foreach ($this->controls as $control): ?>
                    <?php if ($control->type == InputControl::HIDDEN_INPUT ) : ?>
                        <?php $this->buildControl($control, $paramData); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </td>
            <?php foreach ($this->controls as $control): ?>
                <?php if ($control->type != InputControl::HIDDEN_INPUT ) : ?>
                    <td><?php $this->buildControl($control, $paramData); ?></td>
                <?php endif; ?>
            <?php endforeach; ?>
            <!-- Action Buttons -->
            <?php if ($this->withActions ) : ?>
            <td>
                <?= ButtonGroup::widget(['buttons'=>[
                    [
                        'label'=>'<i class="fa fa-plus"></i>',
                        'encodeLabel'=>false,
                        'options'=>[
                            'class'=>"{$this->addRowClassName} btn-sm",
                            'type'=>'button',
                        ]
                    ],
                    [
                        'label'=>'<i class="fa fa-remove"></i>',
                        'encodeLabel'=>false,
                        'options'=>[
                            'class'=>"{$this->delRowClassName} btn-sm",
                            'type'=>'button',
                            'disabled'=>true,
                        ]
                    ],
                ]]); ?>
            </td>
            <?php endif; ?>
        </tr>
        <?php
    }

    protected function buildControl(InputControl $control, $paramData)
    {
        $name = "{$this->modelClassName}[{$control->name}][]";
        $value = $paramData[$control->name];

        $class = 'form-control input-sm ' .
            ($control->type == InputControl::DATE_INPUT ? $this->calendarClassName : null);
        $class = ($control->required) ? "{$class} {$this->requiredClassName} " : $class;
        $class = ($control->htmlOptions && ($cls = $control->htmlOptions['class'])) ? "$class $cls" : $class;

        $htmlOptions = [
            'class' => $class,
            'placeholder' => $control->title,
        ];
        if (is_array($control->htmlOptions)) {
            foreach ($control->htmlOptions as $dataKey=>$dataValue) {
                $htmlOptions[$dataKey] = is_callable($dataValue) ? $dataValue($paramData) : $dataValue;
            }
        }

        $options = [$name, $value];
        if ($control->type == InputControl::DROPDOWN_INPUT) {
            array_push($options, $control->data);
        }
        array_push($options, $htmlOptions); ?>

        <?= $control->preContent ?>
        <?php if ($control->type == InputControl::DATE_INPUT): ?>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= Html::textInput($name, $value, $htmlOptions); ?>
            </div>
        <?php elseif ($control->type == InputControl::NUMBER_INPUT): ?>
            <?php $value = number_format($value, 0, ',', '.'); ?>
            <?= Html::textInput($name, $value, ArrayHelper::merge($htmlOptions, [
                'class'=>"{$class} text-right txt_number",
            ])); ?>
        <?php elseif ($control->type == InputControl::LABEL_INPUT): ?>
            <p class="<?=$control->class?>">
            <?= $value ?>
            </p>
        <?php else: ?>
            <?= call_user_func_array(['yii\bootstrap\Html', $control->type], $options)?>
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
        var calendarTask = calendarTask || '{$this->calendarClassName}'
        var delTaskRow = delTaskRow || '.{$this->delRowClassName}'
        var formName = formName || '#{$this->formName}'
        var idListName = idListName || '#{$this->idListName}'
        var messageSubmit = messageSubmit || '{$this->messageOnErrorSubmitForm}'
        var taskRowClone = taskRowClone || '.{$this->rowClassName}'
        var titleName = titleName || '.{$this->titleClassName}'
        var useCalendar = useCalendar || {$this->useCalendar}
        var withDataAndEvents = withDataAndEvents || {$this->cloneWithEvent}
        var withCheckbox = withCheckbox || ".($this->withCheckbox?'true':'false')."
        var allCheckboxId = allCheckboxId || '#{$this->checkboxInputId}_all'
        var checkboxesClass = checkboxesClass || '.{$this->checkboxInputClass}'";
        $script .= file_get_contents(__DIR__.'/../assets/grid-tabular-input.js');

        $rand = date('YmdHis');
        $view = $this->view;
        $view->registerJs($script, $view::POS_READY, "js-func-{$rand}");
    }
}