<?php $review = $model?>
    <div class="reviews-item">
        <div class="reviews-item-head">
            <strong class="reviews-author pull-left"><?php echo $review->user->fullName?><time>Москва, <?php echo date('d.m.Y', strtotime($review->date))?></time></strong><!-- /reviews-author -->
            <div class="reviews-info">

                <ul class="reviews-rating">
                    <li>
                        <mark>Удобство</mark>
						<?php $rate = $review->getRate('rate_usability')?>
                        <span class="rating js-tooltip-dark" data-tooltip="<?php echo $rate['tooltip']?>">
                            <span style="width: <?php echo $rate['width']?>%;"></span>
                        </span><!-- /rating -->
                    </li>
                    <li>
                        <mark>Лояльность</mark>
                        <?php $rate = $review->getRate('rate_loyality')?>
                        <span class="rating js-tooltip-dark" data-tooltip="<?php echo $rate['tooltip']?>">
                            <span style="width: <?php echo $rate['width']?>%;"></span>
                        </span><!-- /rating -->
                    </li>
                    <li>
                        <mark>Выгода</mark>
                        <?php $rate = $review->getRate('rate_profit')?>
                        <span class="rating js-tooltip-dark" data-tooltip="<?php echo $rate['tooltip']?>">
                            <span style="width: <?php echo $rate['width']?>%;"></span>
                        </span><!-- /rating -->
                    </li>
                </ul><!-- /reviews-rating -->
                <?php if($review->hasComment()):?>
                <span class="reviews-has"><span class="icon-comment"></span>Есть комментарий сотрудника</span><!-- /reviews-has -->
                <?php endif?>
            </div><!-- /reviews-info -->

        </div><!-- /reviews-item-head -->
        <div class="reviews-item-body">

            <p><?php echo $review->text?></p>

            <?php if(!empty($review->good)): ?>
            <p><span class="text-success">Понравилось:</span> <?php echo $review->good?></p>
            <?php endif?>
            <?php if(!empty($review->bad)): ?>
            <p><span class="text-warning">Хотелось бы исправить:</span> <?php echo $review->bad?></p>
            <?php endif?>
            <?php if($review->hasComment()):?>
                <div class="reviews-reply" >
                    <div class="reviews-reply-in">
                    <figure class="reviews-reply-image">
                        <img src="<?php echo \common\components\AppManager::getSettingsParam('photo_operator')?>">
                    </figure><!-- /reviews-reply-image -->
                    <div class="reviews-reply-desc">
                        <strong class="reviews-author">Служба поддержки</strong><!-- /reviews-author -->
                        <div class="ans"><?php echo $review->truncateAnswer(500);?></div>
                        <div class="anshid"><?php echo $review->answer;?></div>
                    </div><!-- /reviews-reply-desc -->
                    </div>

                </div><!-- /reviews-reply -->
                <a href="javascript:void(0)" class="reviews-reply-toggle">
                    <span>Развернуть полный ответ</span>
                    <span>Свернуть полный ответ</span>
                </a>
            <?php endif?>
        </div><!-- /reviews-item-body -->
    </div><!-- /reviews-item -->
