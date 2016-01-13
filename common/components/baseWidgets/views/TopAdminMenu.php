<ul id="nav" class="ul">
    <?php foreach ($modules as $module): ?>
        <?php
        if (!isset($module['admin_menu']) || !$module['admin_menu'])
        {
        	continue;	
        }
        ?>
        <li>
            <a href="<?php echo array_shift(array_values($module['admin_menu'])); ?>">
                <?php echo $module['name']; ?>
            </a>
            <ul >
                <?php foreach ($module['admin_menu'] as $title => $url): ?>
                    <li>
                        <?php echo CHtml::link($title, $url);?>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
    <?php endforeach ?>
    <li>
        <a href="/users/user/logout">Выйти (<?=Yii::app()->user->name;?>)</a></p>
    </li>
</ul>









