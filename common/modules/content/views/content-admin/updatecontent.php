<?php
$blocks = \common\modules\content\models\CoBlocks::find()->all();
if(count($blocks)) {
    echo "<ul>";
    foreach($blocks as $block) {
        echo "<li>{{$block->name}} {$block->title}</li>";
    }

    echo "</ul><hr />";
}

echo $form;
