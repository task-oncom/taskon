<!-- ================== BEGIN BASE CSS STYLE ================== -->
    <?php $this->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/fonts.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/bootstrap/css/bootstrap.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/font-awesome/css/font-awesome.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/animate.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/style.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/style-responsive.min.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/theme/default.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/simple-line-icons/simple-line-icons.css', ['position' => \yii\web\View::POS_HEAD]);?>
    <?php $this->registerCssFile('/css/custom.css', ['position' => \yii\web\View::POS_HEAD ]);?>
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <?php $this->registerCssFile('/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/bootstrap-datepicker/css/datepicker.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/bootstrap-datepicker/css/datepicker3.css', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/plugins/gritter/css/jquery.gritter.css', ['position' => \yii\web\View::POS_HEAD ]);?>
	<!-- ================== END PAGE LEVEL STYLE ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <?php $this->registerCssFile('/plugins/DataTables/css/data-table.css', ['position' => \yii\web\View::POS_HEAD ]);?>
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
    <?php $this->registerJsFile('/plugins/pace/pace.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
	<!-- ================== END BASE JS ================== -->
    
    
    
    <!-- ================== PagerSelector ================ -->
    <?php
    // moved in AdminGrid::run()
	/*$publish = Yii::$app->assetManager->publish(
        Yii::getAlias('@app/../common/js/plugins')
    );
    $this->registerJsFile($publish[1].'/gridview/gridBase.js', ['position'=>$this::POS_END]);
    $this->registerJsFile($publish[1].'/gridview/grid.js', ['position'=>$this::POS_END]);
	$this->registerJsFile($publish[1].'/gridview/pocket.js', ['position'=>$this::POS_END]);*/
	?>
    <!-- ================== PagerSelector ================ -->