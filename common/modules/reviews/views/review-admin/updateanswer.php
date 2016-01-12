<?php

echo '<b>Пользователь</b>: ' . $model->user->name;
echo '<hr>';
echo $model->text;
echo '<hr>';
echo '
Для оформления текст в виде цитаты пользователя, текст должен быть размещен в следующем теге:
<b>&ltblockquote class=&quot;quote&quot;&gt;&ltp&gt;</b>Пример цитаты пользователя<b>&lt/p&gt;&lt/blockquote&gt;</b>
Для оформления текст в виде цитаты закона, текст должен быть размещен в следующем теге:
<b>&ltblockquote class=&quot;quote quote-law&quot;&gt;&ltp&gt;</b>Это решение принято на основании Федерального закона Российской Федерации от 21 декабря 2013 г. N 353-ФЗ<b>&lt/p&gt;&lt/blockquote&gt;
';
echo '<hr>';
echo $form;
