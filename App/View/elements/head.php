<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>変換ツール</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css.map" rel="stylesheet" type="text/css"/>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href='assets/css/convert.css?v=<?php echo time();?>' rel='stylesheet' type='text/css'>

    <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/jquery-ui/jquery-ui.min.js"></script>
    <script src="bower_components/kuroshiro/dist/browser/kuroshiro.js"></script>
    <script type="text/javascript" src="assets/js/json_parse.js"></script>
    <script type="text/javascript" src="assets/js/main.js?v=<?php echo time();?>"></script>


    <script type="text/javascript">
        var hasData = false,
            similarWords = json_parse('<?php echo json_encode(isset($listSimilar)?$listSimilar:''); ?>'),
            listIndex = json_parse('<?php echo json_encode(isset($listIndex)?$listIndex:''); ?>');
    </script>

    <link href="assets/css/main.css" rel="stylesheet" type="text/css">
    <script src="assets/js/buyer_helper.js" type="application/javascript"></script>
    <script src="assets/js/seller_helper.js" type="application/javascript"></script>

    <?php include(dirname(__FILE__) . '/../dialogs/yesOrNo.php'); ?>
    <?php include(dirname(__FILE__).'/../dialogs/onlyFinishButton.php'); ?>
    <?php include(dirname(__FILE__).'/../dialogs/onlyOverwrittingDialog.php'); ?>
    <?php include(dirname(__FILE__).'/../dialogs/onlyFinishDialogDialogUpperR.php'); ?>
    <?php include(dirname(__FILE__).'/../dialogs/viewMapDataOverwriteDialog.php'); ?>

    <style>
        .btn-wrapper a {
            box-sizing: border-box;
            padding: 5px 20px;
            font-size: 25px;
            margin-left: 2px;
            border: 2px solid #8c8c8c;
            background: white;
            color: #000;
        }
        .btn-wrapper button:hover {
            background: #ccc;
        }
    </style>

</head>
<body>