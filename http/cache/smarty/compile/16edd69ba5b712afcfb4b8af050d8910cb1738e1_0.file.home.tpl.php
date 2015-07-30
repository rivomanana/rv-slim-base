<?php /* Smarty version 3.1.27, created on 2015-07-30 12:54:41
         compiled from "D:\wamp\www\slim-concept-base\http\web\tpl\pages\home.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:76255ba027100b710_09438928%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '16edd69ba5b712afcfb4b8af050d8910cb1738e1' => 
    array (
      0 => 'D:\\wamp\\www\\slim-concept-base\\http\\web\\tpl\\pages\\home.tpl',
      1 => 1438243696,
      2 => 'file',
    ),
    '3537258e2787cdb61c8166b0b35a12dd27287ad1' => 
    array (
      0 => 'D:\\wamp\\www\\slim-concept-base\\http\\web\\tpl\\layout.tpl',
      1 => 1438243680,
      2 => 'file',
    ),
    'cac96c8bbc2e4bd20503835bce95d928d35df511' => 
    array (
      0 => 'cac96c8bbc2e4bd20503835bce95d928d35df511',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '76255ba027100b710_09438928',
  'variables' => 
  array (
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55ba027138be51_46253567',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55ba027138be51_46253567')) {
function content_55ba027138be51_46253567 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '76255ba027100b710_09438928';
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
$_smarty_tpl->properties['nocache_hash'] = '76255ba027100b710_09438928';
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