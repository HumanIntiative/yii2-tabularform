
var taskList = jQuery(idListName)

jQuery(addTaskRow).on('click', addTaskOnClick)
jQuery(delTaskRow).on('click', delTaskOnClick)
jQuery(btnSave).on('click', btnSaveOnClick)
jQuery('.txt_number').on('keydown', validateNumberOnKeydown)

if (withCheckbox) {
    jQuery(allCheckboxId).on('change', allCheckboxOnChange)
}

function addTaskOnKeyup(e) {
	e.preventDefault()
	if (e.keyCode==13) addTaskOnClick(e)
}
function addTaskOnClick(e) {
	var clone  = jQuery('tr:last-child', taskList).clone(withDataAndEvents),
		num    = clone.children('td:eq(0)').find('.num').html(),
		odd    = clone.hasClass('odd'),
		length = clone.children().length,
		input  = null

	clone.removeClass(odd ? 'odd' : 'even')
	clone.addClass(odd ? 'even' : 'odd')
	clone.children('td:eq(0)').find('.num').html(parseInt(num) + 1)

	for (var i = 1; i < length; i++) {
		if (i == (length-1)) continue
		input = clone.children('td:eq('+i+')').find('.form-control')
		input.val('')
		if (input.is('div')) {
			input.html('')
		}
		if (input.hasClass(calendarTask)) {
			input.datepicker({
				'language': 'id',
				'format': 'yyyy-mm-dd',
				'viewformat': 'yyyy-mm-dd',
				'placement': 'right',
				'autoclose': 'true'
			})
		}
		if (input.hasClass('txt_number')) {
			input.on('keydown', validateNumberOnKeydown)
		}
	}

	clone.children('td:last-child').find('button'+delTaskRow).removeClass('disabled').removeAttr('disabled')
	clone.children('td:last-child').find('button'+addTaskRow).unbind('click')
	clone.children('td:last-child').find('button'+addTaskRow).on('click', addTaskOnClick)
	clone.children('td:last-child').find('button'+delTaskRow).unbind('click')
	clone.children('td:last-child').find('button'+delTaskRow).on('click', delTaskOnClick)
	clone.appendTo(taskList)
}
function delTaskOnClick(e) {
	jQuery(this).parents('tr'+taskRowClone).remove()

	taskList.children('tr').each(function(index){
		jQuery(this).children('td:eq(0)').find('.num').html(index + 1)
	})
}
function btnSaveOnClick(e) {
	var errCount = 0
	e.preventDefault()

	jQuery(titleName).each(function(index){
		if (jQuery(this).val().length<=0) {
			errCount++
			jQuery(this).focus()
		}
	});

	if (errCount==0) {
		jQuery(formName).submit()
	} else {
		bootbox.alert(messageSubmit)
	}
}
function validateNumberOnKeydown(e) {
	let newVal = this.value + e.key
  
  return e.keyCode == 36 || // e.key=Home
    e.keyCode == 8  || // e.key=Backspace
    e.keyCode == 37 || // e.key=ArrowLeft
    e.keyCode == 39 || // e.key=ArrowRight
    jQuery.isNumeric(newVal)
}

// Checkboxes
function allCheckboxOnChange(e) {
    jQuery(checkboxesClass).attr('checked', this.checked)
}

// Intiate datePicker
if (useCalendar) {
	jQuery(calendarClassName).datepicker({
		'language': 'id',
		'format': 'yyyy-mm-dd',
		'viewformat': 'yyyy-mm-dd',
		'placement': 'right',
		'autoclose': 'true'
	})
}