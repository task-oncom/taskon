<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

use yii\helpers\Html;
use common\models\Settings;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="format-detection" content="telephone=no" />

<style type="text/css">
body {
	margin:0;
	padding:0;
}
@media only screen and (max-width:600px) {
body[merkle] .hide {
	display:none !important;
	width:0px !important;
	height:0px !important;
}
body[merkle] .width270 {
	width:270px !important;
}
body[merkle] .width230{
  width: 230px;
}
.btn_resp {
  width: 190px;
  display: block;
  overflow: hidden;
  word-wrap: break-word;
}
body[merkle] .aligncenter {
	text-align:center !important;
}
body[merkle] .padding0 {
	padding:0px !important;
}
body[merkle] .paddingbottom6 {
	padding:6px !important;
}
body[merkle] .alignleft {
	text-align:left !important;
}
body[merkle] .bgwhite {
	background-color:#FFFFFF !important;
	background-image:none !important;
	width:inherit !important;
	height:inherit !important;
}
body[merkle] .bggray {
	background-color:#efefef !important;
}
body[merkle] .drop {
width:270px !important;
float:left !important;
}
body[merkle] .desktop{visibility:hidden; display:none !important;}
body[merkle] .mobile{max-height: none !important; font-family:Arial, Helvetica, sans-serif; display: block !important; }
body[merkle] .yellow{color:#ffc222 !important;}
body[merkle] .head_space_height{height:2px;}
body[merkle] .right_col_height{height:280px;}
}
</style>
<style type="text/css">
 .desktop{visibility:visible;}
 .mobile { max-height: 0px !important; font-size: 0 !important; display: none; }
 .ExternalClass {
	width:100%;
}
 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td,  .ExternalClass div {
	mso-line-height-rule:exactly;
	mso-line-height-rule:exactly;
	line-height: 100%;
}
body {
	-webkit-text-size-adjust:none;
	-ms-text-size-adjust:none;
}
table td {
	border-collapse:collapse;
}
p {
	margin:0;
	padding:0;
	margin-bottom:0;
}
h1, h2, h3, h4, h5, h6 {
	mso-line-height-rule:exactly;
	mso-line-height-rule:exactly;
	line-height: 100%;
}
a, a:link {
	text-decoration: underline;
}
span.yshortcuts {
	color:#000;
	background-color:none;
	border:none;
}
span.yshortcuts:hover, span.yshortcuts:active, span.yshortcuts:focus {
	background-color:none;
	border:none;
}
a:visited {
	text-decoration: none
}
a:focus {
	text-decoration: underline
}
a:hover {
	text-decoration: underline
}
.head_space_height{height:80px;}
.Img_Descrip{font-size:12px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;}
.right_col_height{height:328px;}
</style>
</head>
<body merkle="fix" style="margin:0; padding:0;  -webkit-text-size-adjust: none;" bgcolor="#f2f2f2" >
<div style="background-color:#f2f2f2;" align="center">
  <!--[if gte mso 9]>
  <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
    <v:fill type="tile" src="" color="#efefef"/>
  </v:background>
  <![endif]-->

  <table height="100%" width="100%"  cellpadding="0" cellspacing="0" border="0" bgcolor="#424242">
    <tr>
      <td valign="top" align="center" bgcolor="#424242">
          <!--Begin Header1 -->
        <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
          <tr>
            <td align="center" valign="top"  style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; mso-line-height-rule:exactly; line-height:16px; color:#ffffff; padding-top:8px; padding-bottom:8px">Если письмо отображается неккоректно, нажмите <a href="#" target="_blank" style="color:#ffffff">здесь</a></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td valign="top" align="center" background="">

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
	<tr>
	  <td align="left" valign="top" style="font-size:30px; line-height:30px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="30" border="0" /></td>
	</tr>
</table>
<!--end spacer -->     
    <!--Begin Header2 -->
<table width="600" border="0" cellspacing="0" cellpadding="0" style="-moz-border-radius:0px; -webkit-border-radius:0px; border:solid 1px #cccccc; border-radius:1px;" class="width270" >
  <tr>
    <td align="left" valign="top">
<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
	<tr>
	  <td align="left" valign="top" style="font-size:8px; line-height:8px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="8" border="0" /></td>
	</tr>
</table>
<!--end spacer -->     
        <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="top" bgcolor="#FFFFFF" class="aligncenter">
          <a href=""><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/logo.jpg" width="51" height="58" alt="Taskon" border="0"/></a>
        </td>
        <td align="right" valign="middle" bgcolor="#ffffff" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#808080; font-size:20px; mso-line-height-rule:exactly; line-height:28px;">Добро пожаловать</td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>
    <!--End Header2 --> 


    <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="font-size:16px; line-height:16px;" class="hide">
  <tbody><tr>
    <td align="left" width="20" valign="top" style="font-size:16px; line-height:16px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="8" border="0"></td>
        <td align="left" valign="top" style="font-size:16px; line-height:16px; border-bottom:#e3e3e3 solid 1px;" class="hideborder"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="8" border="0"></td>
        <td width="20" align="left" valign="top" style="font-size:16px; line-height:16px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="8" border="0"></td>
  </tr>
</tbody></table>


<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
	<tr>
	  <td align="left" valign="top" style="font-size:15px; line-height:15px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="15" border="0" /></td>
	</tr>
</table>
<!--end spacer --> 

    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#ffffff" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#424242; font-size:16px; mso-line-height-rule:exactly; line-height:30px; font-weight: bold;">Добрый день!</td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>

    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#ffffff" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#666666; font-size:14px; mso-line-height-rule:exactly; line-height:18px;">
          Уведомляем Вас о том, что Вы были зарегистрированы на сайте <?=Settings::getValue('setting-project-name')?>.<br> Для входа используйте следующие пароли доступа:
        </td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 

    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#f0f7fc" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#1397c1; font-size:14px; mso-line-height-rule:exactly; line-height:18px; padding: 10px 20px;">
          <span class="btn_resp" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; font-size:14px; line-height:18px; text-decoration: none; color:#1397c1;"><strong>Логин:</strong> <?=$user->email;?></span>
        </td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 

    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#f0f7fc" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#1397c1; font-size:14px; mso-line-height-rule:exactly; line-height:18px; padding: 10px 20px;">
          <span class="btn_resp" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; font-size:14px; line-height:18px; text-decoration: none; color:#1397c1;"><strong>Пароль:</strong> <?=$user->password;?></span>
        </td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:15px; line-height:15px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="15" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 


<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f7f7" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 
<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f7f7" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 
    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#f7f7f7" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#f7f7f7" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#666666; font-size:14px; mso-line-height-rule:exactly; line-height:18px;">
          В целях безопасности просим Вас не передавать пароль третьим лицам.
        </td>

        <td align="left" valign="top" bgcolor="#f7f7f7" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>  
    

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f7f7" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:20px; line-height:20px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="20" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:15px; line-height:15px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="15" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 


    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="left" valign="middle" bgcolor="#ffffff" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#666666; font-size:14px; mso-line-height-rule:exactly; line-height:18px;">
          С уважением, команда <?=Settings::getValue('setting-project-name')?>.
        </td>

        <td align="left" valign="top" bgcolor="#FFFFFF" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>

<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:15px; line-height:15px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="15" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 


    
<!--begin spacer -->  
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#0e3242" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 

<!--begin spacer -->  
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#0e3242" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:10px; line-height:10px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="10" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 

    <table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
      <tr>
        <td align="left" valign="top" bgcolor="#0e3242" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>

        <td align="center" valign="middle" bgcolor="#0e3242" style="font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; color:#ffffff; font-size:14px; mso-line-height-rule:exactly; line-height:24px;">

        Если у Вас есть вопросы обратитесь к администратору сервиса<br> на адрес <?=Html::a(Settings::getValue('content-support-email'), 'mailto:'.Settings::getValue('content-support-email'), ['style'=>"font-family: 'Trebuchet MS',  Arial, Helvetica, sans-serif; font-size:14px; line-height:24px; color:#1397c1; margin-right: 20px;"])?>

        </td>

        <td align="left" valign="top" bgcolor="#0e3242" width="20"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="20" height="1" border="0" style="display:block;"></td>
      </tr>
    </table>
<!--begin spacer -->  
<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#0e3242" class="width270">
  <tr>
    <td align="left" valign="top" style="font-size:20px; line-height:20px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="20" border="0" /></td>
  </tr>
</table>
<!--end spacer --> 






</td>
  </tr>


</tbody></table>

<!--END A-2 -->

</td>
  </tr>
</table>
<!--begin spacer -->    
<table width="600" border="0" cellspacing="0" cellpadding="0" class="width270">
	<tr>
		<td align="left" valign="top" style="font-size:20px; line-height:20px"><img src="<?=Yii::$app->params['frontUrl'];?>/images/letter/spacer.gif" width="1" height="20" border="0" /></td>
	</tr>
</table>
<!--end spacer --> 

</div> 
</body>
</html>

