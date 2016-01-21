<?php

class AnswerSubForm extends Portlet
{
    public $model;


    public function init()
    {
        parent::init();


    }

    
    public function renderContent()
    {
        
        $this->render('AnswerSubForm');
    }


    public static function formHtml($num, $text = '', $is_correct = 0, $errors = array())
    {
        $checked = (bool) $is_correct ? "checked" : "";

        return "
        <table>
            <tr>
                <td>
                    <input type='text' class='text' value='{$text}' name='Answers[{$num}][text]' class='ans_txt'>
                </td>
                <td>
                     <input type='checkbox' name='Answers[{$num}][is_correct]' {$checked} value='1'>
                     верный
                </td>
                <td>
                     <input type='checkbox' name='Answers[{$num}][delete]' value='1'>
                     удалить
                </td>
            </tr>
        </table>
        ";
    }


    public static function validate($answers)
    {
        $errors = array();

        $texts = array();

        foreach ($answers as $num => $answer)
        {
            if (isset($answer['delete']))
            {
                continue;
            }

            $text = trim($answer['text']);
            if (empty($text) && !isset($answer['delete']))
            {
                $errors[] = 'Заполните все текста ответов либо отметьте удалить!';
                continue;
            }

            if (in_array($text, $texts))
            {
                $errors[] = 'Ответы не могут совпадать!';
                continue;
            }

            $texts[] = $text;
        }

        return array_unique($errors);
    }
}
