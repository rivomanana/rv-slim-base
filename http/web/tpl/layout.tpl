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
    {Assets::css()}
</head>

<body id="{block name="body-id"}{/block}">

<div class="global-container">
    {block name="wrapper"}{/block}
</div>

<!-- *************************************** --\\
    Load all scripts
//-- *************************************** --->
{Assets::js()}
</body>
</html>