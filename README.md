Grid Tabular Form Widget
========================
Grid Tabular Form Widget

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pkpudev/yii2-gridtabularform "*"
```

or add

```
"pkpudev/yii2-gridtabularform": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \pkpudev\widget\grid\TabularInput::widget([
	'title'=>'Title',
	'idField'=>'id',
	'parentIdField'=>'parent_id',
	'controls'=>[
		new InputControl(['type'=>'textInput', 'name'=>'field_name1', 'title'=>'Input 1']),
		new InputControl(['type'=>'dateInput', 'name'=>'field_name2', 'title'=>'Date 1']),
		new InputControl(['type'=>'dropDownList', 'name'=>'field_name3', 'title'=>'Options 1', 'data'=>[
			'', 6=>'Val 1', 12=>'Val 2',
		]]),
	],
	'data'=>[
		['id'=>1, 'parent_id'=>0, 'field_name1'=>null, 'field_name2'=>null, 'field_name3'=>null],
		...
	]
]); ?>```