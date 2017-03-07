<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>
<?php include('dialogs/buyer_list_dialog.php'); ?>
<?php include('dialogs/seller_list_dialog.php'); ?>
<?php include('dialogs/maker_selected_dialog.php'); ?>
<?php include('dialogs/buyer_file_choice.php'); ?>
<?php include('dialogs/seller_file_choice.php'); ?>
<?php include('dialogs/after_seller_file_choice.php'); ?>
<?php include('dialogs/final_after_seller_file_choice.php'); ?>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/homePage_tableHelper.css">

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

        //Home page background table scrolling
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

        //manufacture list
        $(document).on("click", function(event){
            if ($(event.target).parents("#buyer-list").length > 0 || $(event.target).is("#home_posting")) {
                return false;
            }
            $("#buyer-list").addClass("display_none");
        });

        $("#home_posting").on("click", function(){
            $("#buyer-list").removeClass("display_none");
        });

        //Buyer table selection
        var table = $("#table-buyer-list");
        var button;
        var td;
        var buyer_id;
        var buyer_name;

        table.find('td').click(function() {
            td = $(this);
            button = td.find('button').first();
            button.addClass("item_manufacturer");
            buyer_id     = button.attr('data-id');
            buyer_name   = button.attr('data-name');
        });
       

        //buyer file choice
        $(document).on("click", ".item_manufacturer", function(){
            $("#buyer_file_choice_dialog").removeClass("display_none");
        
            var html = "<div id='buyer_file_choice_item' style='margin-bottom: 10px;'>"+

                            "<span id='buyer_name'>"+buyer_name+"</span>"+

                            "<button style='margin-left: 5px; display: inline-block; background: #2471a3; border: 1px solid #2E86C1; font-size: 13px; padding: 10px; border-radius: 5px; color: white;' onclick="+

                            "browserClientFile();"+">ファイル選択</button>"+

                            //"<input id='input_excel_file_buyer' data-id='"+buyer_id+"' data-name='"+buyer_name+"' type='file' accept='application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' style='display: none; width:0; height: 0;' required='' onchange='readExcelFileMaker(this)'>"+

                            "<span style='font-size:14px; margin-left: 5px;' id='buyer_file_name'></span>"+

                        "</div>";

            $('#buyer_choiced_file').html(html);
        });

        var buyer_file;
        function readExcelFileSeller(input){
            buyer_file = $(input)[0].files[0];
            $('#buyer_file_name').text(buyer_file.name);
        }


        //buyer file choice back
        $("#maker_selection_back").on("click", function(){
            $("#buyer_file_choice_dialog").addClass("display_none");
            //$("#buyer_file_choice_item").remove();
            $("#buyer-list").removeClass("display_none");
            $("#buyer-list").addClass("display_block");
            $("#header-form")[0].reset();
        });

        //buyer file choice next
        $("#maker_selection_next").on("click", function(){
            if (buyer_file) {
                $("#buyer_file_choice_dialog, #buyer-list").addClass("display_none");
                $("#buyer_file_choice_dialog, #buyer-list").removeClass("display_block");
                $("#seller_file_choice_dialog").addClass("display_block");
                $("#seller_file_choice_dialog").removeClass("display_none");
                //$("#seller-list").removeClass("display_none");
            }else{
                alert("メーカーを選んでください");
            }
        });

        //Seller table selection
        var table = $("#seller-list");
        var seller_id;
        var seller_name;

        table.find('td').click(function() {
            td = $(this);
            button = td.find('button').first();
            button.addClass("item_seller");
            seller_id     = button.attr('data-id');
            seller_name   = button.attr('data-name');
        });

        //buyer file choice
        $(document).on("click", ".item_seller", function(){
            $("#seller_file_choice_dialog").removeClass("display_none");
            
                var html = "<div id='seller_file_choice_item' style='margin-bottom: 10px;'>"+

                                "<span id='seller_name'>"+seller_name+"</span>"+

                                "<button style='margin-left: 5px; display: inline-block; background: #2471a3; border: 1px solid #2E86C1; font-size: 13px; padding: 10px; border-radius: 5px; color: white;' onclick="+

                                "browserClientFile();"+">ファイル選択</button>"+

                                "<span style='font-size:14px; margin-left: 5px;' id='seller_file_name'></span>"+

                            "</div>";

            $('#seller_choiced_file').html(html);
        });

        var seller_file;
        function readExcelFileMaker(input){
            seller_file = $(input)[0].files[0];
            $('#seller_file_name').text(seller_file.name);
        }

        //Seller file choice back
        $("#seller_selection_back").on("click", function(){
            $("#seller_file_choice_dialog, #seller-list").addClass("display_none");
            //$("#seller_file_choice_item").remove();
            $("#buyer_file_choice_dialog, #buyer-list").removeClass("display_none");
            $("#buyer_file_choice_dialog, #buyer-list").addClass("display_block");
            $("#header-form")[0].reset();
        });

        //Seller file choice next
        $("#seller_selection_next").on("click", function(){
            if (seller_file) {
                $("#seller_file_choice_dialog, #seller-list").addClass("display_none");
                //$("#after_seller_file_choice_dialog").removeClass("display_none");
                startMatching();
            }else{
                alert("販売先を選んでください");
            }
        });


    </script>
    
</body>
</html>