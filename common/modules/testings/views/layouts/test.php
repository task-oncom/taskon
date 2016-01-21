<?php $this->beginContent('//layouts/testing'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="green_h">
            <?php
            $style = '';

            if (mb_strlen($this->page_title, 'utf-8') > 55)
            {
                $style = 'font-size: 28px !important';
            }
            elseif (mb_strlen($this->page_title, 'utf-8') > 50)
            {
                $style = 'font-size: 32px !important';
            }
            elseif (mb_strlen($this->page_title, 'utf-8') > 23)
            {
                $style = 'font-size: 35px !important';
            }
            ?>
            <h3 style="<?php echo $style; ?>">
                <?php echo $this->page_title; ?><?php if ($this->page_subtitle) { echo ' \ '; } ?>
                <span ><?php echo $this->page_subtitle; ?></span>
            </h3>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php $this->widget('application.modules.testings.widgets.TestingBreadCrumb', array(
    'crumbs' => $this->crumbs
)); ?>

<?php $this->widget('application.modules.testings.widgets.BrowsersCheckWidget'); ?>

<?php echo $content; ?>

<?php $this->endContent(); ?>