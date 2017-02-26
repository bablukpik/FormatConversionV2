<?php include('elements/head.php'); ?>
<div class="posting fl">転記</div>
<?php include('elements/header.php'); ?>
<?php include('dialogs/buyer_dialog.php'); ?>

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
                </table>

            </div>
        </div>

    <script>

        var lastPos = 0;
        $('#content-goods').scroll(function() {
            var currPos = $('#content-goods').scrollTop();

            if (lastPos < currPos) {}
            if (lastPos > currPos) {}

            $('.table-header').css({
                position: 'absolute',
                top: currPos+"px"
            });
            lastPos = currPos;
        });

        $("#click-overlay").on("click", function(){
            $("#buyer-list").toggleClass("display_none");
        });

    </script>
    
</body>
</html>