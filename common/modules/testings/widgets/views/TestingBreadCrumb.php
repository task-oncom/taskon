<?php if($this->crumbs && is_array($this->crumbs)) { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="way">
                <?php $i = 0; foreach ($this->crumbs as $name => $url) 
                {
                    $i++;
                    
                    if($url) 
                    {
                        echo CHtml::link($name, $url);
                    } 
                    else 
                    {
                        echo $name;
                    }

                    if(count($this->crumbs) > $i) 
                    {
                        echo $this->delimiter;
                    }
                } ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
<?php } ?>