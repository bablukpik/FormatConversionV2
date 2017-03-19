<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>
<?php include('dialogs/buyer_list_dialog.php'); ?>
<?php include('dialogs/seller_list_dialog.php'); ?>
<?php include('dialogs/maker_selected_dialog.php'); ?>
<?php include('dialogs/buyer_file_choice.php'); ?>
<?php include('dialogs/standard_file_choice.php'); ?>
<?php include('dialogs/seller_file_choice.php'); ?>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/homePage_tableHelper.css">
<script src="assets/js/buyer_helper.js" type="application/javascript"></script>
<script src="assets/js/seller_helper.js" type="application/javascript"></script>

<div class="wraper">
    <div id="data-print">

        <div class="body" id="content-goods">
            <div class="table-custom">
                <div class="table-header">
                    <div class="table-row">
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                        <div class="table-item" style=""></div>
                    </div>
                    <br>
                </div>
                <div class="table-body">
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                    <div class="table-row">
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div style="" class="table-item"></div>
                        <div class="clear"></div>
                    </div>
                    <br>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function getURLParam( name, url ) {
        if (!url) url = location.href;
        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+name+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( url );
        return results == null ? null : results[1];
    }

    jQuery(function($) {
        var select = getURLParam('select');

        if (select == 'seller') {
            $("#seller-list").removeClass("display_none");
        } else if (select == 'manufacturer') {
            $("#buyer-list").removeClass("display_none");
        }

    });
</script>

</body>
</html>