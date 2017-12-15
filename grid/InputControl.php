<?php

namespace pkpudev\widget\grid;

class InputControl extends \yii\base\BaseObject
{
  const DATE_INPUT = 'dateInput';
  const DROPDOWN_INPUT = 'dropDownList';
  const HIDDEN_INPUT = 'hiddenInput';
  const TEXT_INPUT = 'textInput';
  const NUMBER_INPUT = 'numberInput';

  /**
   * @var array data for dropdownList
   */
  public $data;
  /**
   * @var string name
   */
  public $name;
  /**
   * @var array options
   */
  public $htmlOptions;
  /**
   * @var string postContent
   */
  public $postContent;
  /**
   * @var string preContent
   */
  public $preContent;
  /**
   * @var bool required
   */
  public $required = false;
  /**
   * @var string title
   */
  public $title;
  /**
   * @var string type
   */
  public $type;

  public function __construct($config = [])
  {
    parent::__construct($config);
  }
}