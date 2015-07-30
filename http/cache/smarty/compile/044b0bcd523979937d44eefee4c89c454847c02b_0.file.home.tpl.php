<?php /* Smarty version 3.1.27, created on 2015-07-30 10:10:39
         compiled from "D:\wamp\www\jeux-sms\http\web\tpl\pages\home.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1257555b9dbff293f12_48542909%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '044b0bcd523979937d44eefee4c89c454847c02b' => 
    array (
      0 => 'D:\\wamp\\www\\jeux-sms\\http\\web\\tpl\\pages\\home.tpl',
      1 => 1438243696,
      2 => 'file',
    ),
    '1c2bc2e53dc7eccd472d8c2608a0d8b04afb4fa3' => 
    array (
      0 => 'D:\\wamp\\www\\jeux-sms\\http\\web\\tpl\\layout.tpl',
      1 => 1438243680,
      2 => 'file',
    ),
    '02e1ccbc2bab0f29c572672ef3dfb75f12ce5cc9' => 
    array (
      0 => '02e1ccbc2bab0f29c572672ef3dfb75f12ce5cc9',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1257555b9dbff293f12_48542909',
  'variables' => 
  array (
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55b9dbff37ca39_04614046',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55b9dbff37ca39_04614046')) {
function content_55b9dbff37ca39_04614046 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1257555b9dbff293f12_48542909';
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=""/>
    <meta name="keyword" content=""/>

    <!-- *************************************** --\\
        Load all styles
    //-- *************************************** --->
    <?php echo App\Concept\Assets::css();?>

</head>

<body id="">

<div class="global-container">
    <?php
$_smarty_tpl->properties['nocache_hash'] = '1257555b9dbff293f12_48542909';
?>
ddddddddddddd
</div>

<!-- *************************************** --\\
    Load all scripts
//-- *************************************** --->
<?php echo App\Concept\Assets::js();?>

</body>
</html><?php }
}
?>