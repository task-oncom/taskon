$(function()
{
    var $answers_forms = $('#answers_forms');

    var $form = $('#testing-question-form');


    $('#add_answer').click(function()
    {
        var $inputs = $('#answers_forms input');

        var num = 0;

        if ($inputs.length)
        {
            var max_num = 0;

            $inputs.each(function()
            {
                var reg = new RegExp('([0-9]+)');
                var curr_num = reg.exec($(this).attr('name'))[0];
              
                if (curr_num > max_num)
                {
                    max_num = curr_num;
                }
            });

            num = ++max_num;
        }

        var params = {
            'sub_form' : 'add',
            'num'      : num
        };

        $.post('/testings/testingQuestionAdmin/GetAnswerForm',  params , function(form)
        {
            $answers_forms.append(form);
        });
       
        return false;
    });


    $form.submit(function()
    {
        if ($('#answers_forms input').length)
        {
            $.post('/testings/testingQuestionAdmin/ValidateAnswerForm', $(this).serialize(), function(res)
            {
                if (res.done)
                {
                    alert('valid');
                }
                else
                {
                    $answers_forms.html(res.html);
                }
            },
            'json'
            );
            //return true;
        }

        return false;
    });
});
