<!-- ================== BEGIN BASE JS ================== -->
	<?php //$this->registerJsFile('/plugins/jquery/jquery-1.9.1.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/jquery/jquery-migrate-1.1.0.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/jquery-ui/ui/minified/jquery-ui.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/bootstrap/js/bootstrap.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>

	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
    <?php $this->registerJsFile('/plugins/slimscroll/jquery.slimscroll.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/jquery-cookie/jquery.cookie.js', ['position' => \yii\web\View::POS_HEAD ]);?>

	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <?php $this->registerJsFile('/plugins/gritter/js/jquery.gritter.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/flot/jquery.flot.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/flot/jquery.flot.time.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/flot/jquery.flot.resize.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/flot/jquery.flot.pie.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/sparkline/jquery.sparkline.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/js/dashboard.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
    <?php $this->registerJsFile('/js/apps.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>

	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
//			Dashboard.init();
		});
	</script>