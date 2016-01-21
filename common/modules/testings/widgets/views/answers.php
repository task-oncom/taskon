<style>
	.edit-checkbox-field, .edit-text-field, .icon-ok, .icon-abort {
		display: none;
	}
</style>
<script>
    $(document).ready(function() {
        $('.edit-block').bind('click', function(event) {
			current = $(this).html();
				$(this).find('.edit-text-label, .edit-checkbox-label').css('display', 'none');
				$(this).find('.edit-text-field, .edit-checkbox-field').css('display', 'block');
				$(this).find('.icon-ok, .icon-abort').css('display', 'table-cell');
		});

        $('.add-text-field').bind('click', function(event) {

			if ($('.add-text-field').val() == 'Текст нового ответа') {
				$('.add-text-field').val('');
			}
		});

        $('.add-text-field').bind('blur', function(event) {
			
			if ($('.add-text-field').val() == '') {
				$('.add-text-field').val('Текст нового ответа');
			}
		});

		$('.icon-abort').bind('click', function(event) {
			$(this).closest('tr').find('.edit-text-field, .edit-checkbox-field, .icon-ok, .icon-abort').css('display', 'none');
			$(this).closest('tr').find('.edit-text-label, .edit-checkbox-label').css('display', 'block');
			return false;
		});

		$('.icon-ok').bind('click', function(event) {

			id = $(this).closest('tr').attr("id");
			text    = $(this).closest('tr').find('.edit-text-field').val();
			checkbox = $(this).closest('tr').find('.edit-checkbox-field').val();
			
			if (checkbox == 1) {
				checked = 'Да'
			} else {
				checked = 'Нет'; 
			}
			
			user = <?=json_encode(serialize(Yii::app()->user))?>;

			$.post('/testings/testingQuestionAdmin/updateanswer/id/'+id,  { t: text, c: checkbox, us: user});

			$(this).closest('tr').find('.edit-text-label').html(text);
			$(this).closest('tr').find('.edit-checkbox-label').html(checked);

			$(this).closest('tr').find('.edit-text-field, .edit-checkbox-field, .icon-ok, .icon-abort').css('display', 'none');
			$(this).closest('tr').find('.edit-text-label, .edit-checkbox-label').css('display', 'block');
			return false;
		});

		$('.icon-add').bind('click', function(event) {
			qid = 'new';
			id = $(this).closest('tr').attr("id");
			text    = $(this).closest('tr').find('.add-text-field').val();
			checkbox = $(this).closest('tr').find('.add-checkbox-field').val();

			if (text == '' || text == 'Текст нового ответа') {
				alert('Вы не ввели ответ');
				return;
			}

			if (checkbox == 1) {
				checked = 'Да'
			} else {
				checked = 'Нет'; 
			}

			user = <?=json_encode(serialize(Yii::app()->user))?>;


			$.post('/testings/testingQuestionAdmin/updateanswer/id/'+id,  { t: text, c: checkbox, us: user, q: qid}, function(data) {
				$('.edit-block:last').after('<tr id="'+data+'" class="edit-block"><td class="edit-text"><span class="edit-text-label" style="display: block; ">'+text+'</span><textarea class="edit-text-field" style="display: none; ">'+text+'</textarea></td><td class="edit-checkbox" style="text-align:center;"><span class="edit-checkbox-label" style="display: block; ">'+checked+'</span><select class="edit-checkbox-field" name="checkbox-13957" id="checkbox-13957" style="display: none; "><option value="0" selected="selected">Нет</option><option value="1">Да</option></select></td><td class="icon-ok"></td><td class="icon-abort"></td></tr>');
				$('.add-text-field').val('');
			});
			
		});
    });
</script>
<div id="testing-answer-grid" class="grid-view">
	<table class="grid-view items">
		<thead>
			<th>Ответ</th>
			<th>Правильный?</th>
		</thead>
		<tbody>
			<?php foreach ($model->answers as $answer) : ?>
				<tr id="<?=$answer->id;?>" class="edit-block">
					<td class="edit-text">
						<span class="edit-text-label"><?=$answer->text;?></span>
						<textarea class="edit-text-field"><?=$answer->text;?></textarea>
					</td>
					<td class="edit-checkbox" style="text-align:center;">
						<span class="edit-checkbox-label"><?=TestingAnswer::$type_list[$answer->is_right];?></span>
						<?=CHtml::dropDownList('checkbox-'.$answer->id, $answer->is_right, TestingAnswer::$type_list, array('class' => 'edit-checkbox-field'));?>
					</td>
					<td class="icon-ok">
						<img src='/images/icons/icon_ok.png'>
					</td>
					<td class="icon-abort">
						<img src='/images/icons/icon_abort.png'>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr id="<?=$model->id;?>">
				<td class="add-text">
					<textarea class="add-text-field" style="width:200px;height:60px;">Текст нового ответа</textarea>
				</td>
				<td class="add-checkbox" style="text-align:center;">
					<?=CHtml::dropDownList('checkbox-add', null, TestingAnswer::$type_list, array('class' => 'add-checkbox-field'));?>
				</td>
				<td class="icon-add">
					<img src='/images/icons/icon_ok.png'>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
