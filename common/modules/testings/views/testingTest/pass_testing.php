<?php
	$this->page_title = $model->test->session->name;

	$this->crumbs = array(
		'Тестирование' => false,
		$model->test->name => false,
	);
?>

<?php $this->widget('application.modules.testings.widgets.NotCloseTabsWidget'); ?>

<?php if (Yii::app()->user->hasFlash('flash')) : ?>
	<div class="message"><span>
		<?php echo Yii::app()->user->getFlash('flash'); ?>
	</span></div>
<?php endif; ?>

<?php
	$questions = array();
	$count = 0;
	foreach ($model->questions as $question) {
		$count++;

		$pictures = array();
		foreach ($question->question->files as $file) {
			$pictures[] = $file->url;
		}

		$answers = array();
		// если ответ вводит сам пользователь, нельзя отправлять ответ - поскольку в нём указан верный ответ.

		foreach ($question->question->answers as $answer) {
			if ($question->question->type <> TestingQuestion::USER_ANSWER) {
				$answers[] = $answer->text;
			} else {
				$answers[] = 'тут должен быть ответ';
			}
		}

		$questions[$count] = array(
			'id' => $question->question->id,
			'text' => $question->question->text,
			'type' => $question->question->type,
			'pictures' => $pictures,
			'answers' => $answers,
			'userAnswer' => '',
			'time' => 0,
			'gamma' => $question->question->gamma->name,
		);

	}
?>

<style type="text/css">
	body {
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
	}

	#sendInfoBox p {
		text-align: center;
		padding-top: 10px;
	}

	span.fakeLink {
		color: #008800;
		text-decoration: underline;
		cursor: pointer;
	}

	.user-warning-message {
		padding: 10px 15px 10px 40px;
		margin: 10px 0;
		font-weight: bold;
		overflow: hidden;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		border: 1px solid #bbdbe0;
		background-color: #ecf9ff;
		color: #0888c3;
	}

	[data-toggle=buttons] .btn input {
		position: absolute;
	    clip: rect(0,0,0,0);
	    pointer-events: none;
	}

</style>

<script type="text/javascript">
	function updatePicture(link) {
		var img = $(link).prev('img');
		var imgCopy = img.clone();
		img.remove();
		imgCopy.insertBefore(link);
		return false;
	}
</script>

<script type="text/javascript">
	$(function(){

		countDown = {

			init : function() {
				<?php if(!$model->pass_date_start) { ?>
					this.current_time = new Date('<?=date("d F Y H:i:s", strtotime($model->pass_date_start))?>');
					this.end_time = new Date(this.current_time);
					this.end_time.setMinutes( this.current_time.getMinutes() + <?=$model->test->minutes;?> );
				<? } else { 

					$d1 = time();
					$d2 = strtotime($model->pass_date_start);
					$interval = $d1 - $d2; ?>

					this.current_time = new Date();
					this.end_time = new Date(this.current_time);
					this.end_time.setSeconds( this.current_time.getSeconds() + <?=($model->test->minutes * 60) - $interval;?> );
				<? } ?>

				this.tick();
			},
			convert : function(x) {
				if (x > 0) 
				{
					if (x < 60) 
					{
						h = 0;
						m = 0;
						s = x;
					}

					if (x >= 60) 
					{
						sec = x / 60;
						a = (sec.toString()).split(".");
						s = x - (a[0] * 60);

						if (a[0] < 60) 
						{
							h = 0;
							m = a[0];
						}

						if (a[0] >= 60) 
						{
							b = ((a[0] / 60).toString()).split(".");
							h = b[0];
							m = a[0] - (b[0] * 60);
						}
					}

					if (h < 10) { h = h.toString(); h = "0" + h; }
					if (m < 10) { m = m.toString(); m = "0" + m; }
					if (s < 10) { s = s.toString(); s = "0" + s; }

					return h + ":" + m + ":" + s;
				}
				
				return '00:00:00';
			},
			showOnThePage : function(millisec) {
				$('#countdown_timer').html(this.convert(parseInt(millisec/1000)));
			},

			tick : function() {
				this.current_time.setSeconds(this.current_time.getSeconds() + 1);
				var diff = this.end_time.getTime() - this.current_time.getTime();
				this.showOnThePage(diff);
				if (diff >= 1) {
					setTimeout('countDown.tick()',1000);
				} else {
					tester.finishTesting();
				}
			}
		}

		countDown.init();

	});
</script>

<script type="text/javascript">
	$(function(){

		tester = {

			init : function() {
				this.global_id = <?=$current_answer?>;
				this.one_option = <?php echo TestingQuestion::ONE_OPTION; ?>;
				this.few_options = <?php echo TestingQuestion::FEW_OPTIONS; ?>;
				this.user_answer = <?php echo TestingQuestion::USER_ANSWER; ?>;
				this.delimiter = '<?php echo TestingQuestion::DELIMITER; ?>';
				this.questions_count = <?php echo count($questions); ?>;
				this.questions = <?php echo json_encode($questions); ?>;

				this.questionNumberBox = $('.questionNumber');
				this.answerBox = $('#answerBox');
				this.questionBox = $('#questionTextBox');
				this.pictureBox = $('#questionPictureBox');
				this.progressbarText = $('#progressbarText');
				this.progressbar = $('#progressbar');

				this.timeOfStartOfAnswering = new Date();
				this.setQuestion(this.global_id);
			},
			updateNumber : function() {
				this.questionNumberBox.text(this.global_id);

				this.updateProgressbar();
			},
			updateProgressbar : function() {
				var progress = Math.round(((this.global_id - 1) / this.questions_count) * 100);

				this.progressbarText.text(progress);
				this.progressbar.css('width', progress + '%');
			},
			resetQuestion : function() {
				this.questionBox.html('');
				this.answerBox.html('');
				var sendInfoBox = $('#sendInfoBox');
				sendInfoBox.append('<p>Результат Вашего тестирования необходимо отправить на сервер для обработки.</p>');
				sendInfoBox.append('<p>Этот процесс может занять некоторое время. Не производите никаких действий и не закрывайте окно браузера до тех пор, пока не будет получен результат тестирования.</p>');
				this.pictureBox.html('');

				/* delete */
				$('#questionGammaBox').html('');
			},
			setQuestion : function(id) {
				var q = this.questions[id];
				this.questionBox.text(q['text']);
				this.answerBox.html('');
				$.each(q['answers'],function(a,b) {
					if (q['type'] == tester.one_option) {
						var html = '<input id="answer'+a+'" type="radio" name="radio" autocomplete="off">' + b;
						var html = '<label class="btn btn-primary" for="answer'+a+'">' + html + '</label>';
						var html = '<div class="checkbox_button_wr_1">' + html + '</div>';
						var html = '<div class="gray_wr">' + html + '</div>';
						tester.answerBox.append(html);
					}
					if (q['type'] == tester.few_options) {
						var html = '<input id="answer'+a+'" type="checkbox" name="radio" autocomplete="off">' + b;
						var html = '<label class="btn btn-primary" for="answer'+a+'">' + html + '</label>';
						var html = '<div class="checkbox_button_wr_2">' + html + '</div>';
						var html = '<div class="gray_wr">' + html + '</div>';
						tester.answerBox.append(html);
					}
					if (q['type'] == tester.user_answer) {
						tester.answerBox.html('');
						tester.answerBox.append('<div class="gray_wr"><input type="text" value="" placeholder="Ваш ответ" /></div>');
					}
					tester.answerBox.append('<div class="clear"></div>');
				});
				this.answersChangeStatus();
				this.pictureBox.html('');
				$.each(q['pictures'],function(a,b) {
					tester.pictureBox.append('<img src="'+b+'" /><span class="fakeLink" onclick="updatePicture(this);">Обновить изображение</span><br /><br />');
				});

				/* delete */
				$('#questionGammaBox').text(q['gamma']);

				this.updateNumber();

			},

			saveAnswer : function(id) 
			{
				var send = false;
				var clickNext = $('#clickNext');
				var q = this.questions[id];
				var answer = [];

				if (q['type'] == this.one_option) 
				{
					tester.answerBox.find('input:radio:checked').each(function(){
						answer.push($(this).parent().text());
					});
				}

				if (q['type'] == this.few_options) 
				{
					tester.answerBox.find('input:checkbox:checked').each(function(){
						answer.push($(this).parent().text());
					});
				}

				if (q['type'] == this.user_answer) 
				{
					tester.answerBox.find('textarea').each(function(){
						answer.push($(this).val());
					});
				}

				// сохранение времени ответа
				var current_time = new Date();
				var diff = current_time.getTime() - this.timeOfStartOfAnswering.getTime();
				var time = diff/1000;
				this.timeOfStartOfAnswering = new Date();

				clickNext.attr('disabled','disabled');

				// сохранение ответа
				$.ajax({
					url : '<?php echo Yii::app()->createAbsoluteUrl('/testings/testingTest/setAnswer/id/'.$model->id); ?>',
					data : {
						'question_id' 	: q['id'],
						'userAnswer' 	: answer.join(this.delimiter),
						'time'			: time
					},
					dataType : 'json',
					type : 'POST',
					timeout : 20000,
					success : function(data) {
						if(data.success)
						{
							$('#button'+tester.global_id).removeClass('selected');

							if (tester.global_id == tester.questions_count) 
							{
								tester.finishTesting();
							}
							else 
							{
								tester.global_id++;
								$('#button'+tester.global_id).addClass('selected');
								tester.setQuestion(tester.global_id);
								tester.restoreUserAnswer(tester.global_id);
							}

							if($('#messageStep50').data('timer') != 'true' && data.percentStep == 50)
							{
								$('#messageStep50').data('timer', 'true');
								$('#messageStep50').text(data.messageStep);
								setTimeout(function(){
									$('#messageStep50').css('display', 'none');
								}, 10000);
							}
							else if($('#messageStep75').data('timer') != 'true' && data.percentStep == 75)
							{
								$('#messageStep75').data('timer', 'true');
								$('#messageStep75').text(data.messageStep);
								setTimeout(function(){
									$('#messageStep75').css('display', 'none');
								}, 10000);
							}
							else if(data.percentStep == 100)
							{
								$('#messageStep100').text(data.messageStep);
							}
						}
						else
						{
							$('p.uploading_info').remove();
							clickFinish.removeAttr('disabled');
							sendInfoBox.append('<p class="uploading_info" style="color: red;">Произошла ошибка при отправке данных!</p>');
							sendInfoBox.append('<p class="uploading_info" style="color: red;">Подождите пару минут и нажмите на кнопку ниже.</p>');
						}

						if (($('#answerBox input:radio:checked').length > 0)
							|| ($('#answerBox input:checkbox:checked').length > 0)
							|| ($('#answerBox input[type="text"]').length > 0)
						) {
							clickNext.removeAttr('disabled');
						} else {
							clickNext.attr('disabled','disabled');
						}				
					},
					error : function() {
						$('p.uploading_info').remove();
						clickFinish.removeAttr('disabled');
						sendInfoBox.append('<p class="uploading_info" style="color: red;">Произошла ошибка при отправке данных!</p>');
						sendInfoBox.append('<p class="uploading_info" style="color: red;">Подождите пару минут и нажмите на кнопку ниже.</p>');
					}
				});

				this.updateNumber();
				
			},

			restoreUserAnswer : function(id) {
				var q = this.questions[id];
				var userAnswer = q['userAnswer'];
				var answers = userAnswer.split(this.delimiter);

				if (q['type'] == this.one_option) {
					$.each(answers,function(a,b){
						$('#answerBox label').each(function(){
							if ($(this).text() == b) {
								$(this).prev('input:radio').attr('checked','checked');
							}
						});
					});
				}
				if (q['type'] == this.few_options) {
					$.each(answers,function(a,b){
						$('#answerBox label').each(function(){
							if ($(this).text() == b) {
								$(this).prev('input:checkbox').attr('checked','checked');
							}
						});
					});
				}
				if (q['type'] == this.user_answer) {
					$.each(answers,function(a,b){
						$('#answerBox textarea').text(b);
					});
				}
			},

			finishTesting : function() {
				var clickNext = $('#clickNext');
				var clickFinish = $('#clickFinish');
				var sendInfoBox = $('#sendInfoBox');
				clickNext.css('display','none');
				this.resetQuestion();
				$('p.uploading_info').remove();
				clickFinish.css('display','inline-block');
				clickFinish.attr('disabled','disabled');
				sendInfoBox.append('<p class="uploading_info">Подождите, происходит отправка данных…</p>');
				sendInfoBox.append('<p class="uploading_info"><img src="/images/site/preloader.gif"></img></p>');
				var request = $.ajax({
					url : '<?php echo Yii::app()->createAbsoluteUrl('/testings/testingTest/finishTest/id/'.$model->id); ?>',
					data : {
						'questions': tester.questions
					},
					dataType : 'json',
					type : 'POST',
					timeout : 20000
				});
				request.done(function(){
					//window.history.go();
					window.location.href = '<?php echo Yii::app()->createAbsoluteUrl('/testings/testingTest/pass/id/'.$model->id); ?>';
				});
				request.fail(function(){
					$('p.uploading_info').remove();
					clickFinish.removeAttr('disabled');
					sendInfoBox.append('<p class="uploading_info" style="color: red;">Произошла ошибка при отправке данных!</p>');
					sendInfoBox.append('<p class="uploading_info" style="color: red;">Подождите пару минут и нажмите на кнопку ниже.</p>');
				});
			},

			nextQuestion : function() 
			{
				this.saveAnswer(this.global_id);
			},

			answersChangeStatus : function() {
				var clickNext = $('#clickNext');
				if (($('#answerBox input:radio:checked').length > 0)
					|| ($('#answerBox input:checkbox:checked').length > 0)
					|| ($('#answerBox input[type="text"]').length > 0)
				) {
					clickNext.removeAttr('disabled');
				} else {
					clickNext.attr('disabled','disabled');
				}
			}

		}

		tester.init();

		$('#answerBox').on('change','*',tester.answersChangeStatus);

	});
</script>

<br>

<div class="row">
	<div class="col-sm-12">
		<div class="questions_box">
			<div class="number_questions questionNumber">1</div>

			<p class="gray_text" id="questionGammaBox"></p>
			<h2 id="questionTextBox"></h2>
			<div class="number_questions_img_box" id="questionPictureBox"></div>

			<div class="questions" id="answerBox" data-toggle="buttons" class="btn-group"></div>

			<div class="answer" id="sendInfoBox"></div>

			<br>

			<button type="submit" class="green_button" id="clickNext" onClick="tester.nextQuestion();">Подтвердить ответ</button>

			<br>
			<br>
			<div class="congratulations_text">
				<h4 id="messageStep50"></h4>
				<h4 id="messageStep75"></h4>
				<h4 id="messageStep100"></h4>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="green_wr">
			<div class="green_box">
				<div class="progress_ins">
					<span class="col_text">Вопрос <strong class="green_text questionNumber">1</strong> из <strong class="green_text"><?=count($questions)?></strong></span>

					<div class="progress_wr">
						<div class="progress">
							<div class="progress-bar progress-bar-success" id="progressbar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0">
								<span class="sr-only">40% Complete (success)</span>
							</div>
						</div>
					</div>
					<div class="progress_done">
						Выполнено <span id="progressbarText">0</span>%
					</div>
				</div>
				<div class="time_progress">
					<p>До конца осталось:</p>
					<span class="timer" id="countdown_timer"></span>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>

<div class="clear"></div>












