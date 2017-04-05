//Global variable
var client_file;
var readExcelFileClient;

jQuery(function ($) {

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

//Seller file popup if Buyer file selected



//Buyer List Back
    $(document).on("click", '#buyerListBack', function(event){
        $("#buyer-list").addClass("display_none");
    });

//Default display none
    //$("#buyer-list").addClass("display_none");

//Buyer/Manufacture/Maker list
    $(document).on("click", function(event){
        if ($(event.target).parents("#buyer-list").length > 0 || $(event.target).is("#maker_selection_back")) {
            return false;
        }
        $("#buyer-list").addClass("display_none");
    });

    $("#buyer_posting").on("click", function(){
        console.log('Clicked');
        $("#buyer-list").removeClass("display_none");
    });

    var button;
    var td;
    var buyer_id;
    var buyer_name;

//Buyer table selection
    var table = $("#table-buyer-list");

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
        $("#buyer-list").addClass("display_none");

        var html = "<div id='buyer_file_choice_item' style='margin-bottom: 10px;'>"+

           /* "<span id='buyer_name'>"+buyer_name+"</span>"+*/

            "<button style='margin-left: 45px; display: inline-block; background: #2471a3; border: 1px solid #2E86C1; font-size: 13px; padding: 10px; border-radius: 5px; color: white;' onclick="+

            "browserClientFile();"+">ファイル選択</button>"+

            //"<input id='input_excel_file_buyer' data-id='"+buyer_id+"' data-name='"+buyer_name+"' type='file' accept='application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' style='display: none; width:0; height: 0;' required='' onchange='readExcelFileMaker(this)'>"+

            "<span style='font-size:14px; margin-left: 5px;' class='client_file_name'></span>"+

            "</div>";

        $('#buyer_file_choiced').html(html);
    });

//Client file declaration for buyer and seller
    readExcelFileClient = function(input){
        client_file = $(input)[0].files[0];
        $('.client_file_name').text(client_file.name);
        console.log(client_file.name);
    };


//buyer file choice back
    $("#maker_selection_back").on("click", function(){
        $("#header-form")[0].reset();
        client_file='';
        $('.client_file_name').text('');
        $("#buyer_file_choice_dialog").addClass("display_none");
        $("#buyer-list").removeClass("display_none");
    });

//Data Formation by company type
    $('.itoham').click(function () {
        $('#inputCompanyType').val('Itoham');
        console.log('itoham');
    });


//buyer file choice next
    $("#maker_selection_next").on("click", function(){

        console.log('clicked');
        if (client_file) {
            $("#buyer_file_choice_dialog, #buyer-list").addClass("display_none");
            //$("#standard_file_choice_dialog_forBuyer").removeClass("display_none");
            $('#inputReportType').val('maker');
            sessionStorage.removeItem('sellerPopup');
            startMatching();
        }else{
            alert("メーカーを選んでください");
        }
    });


//In view Overwrite Result sellerPopup will false
    $('#viewOverwriteResult').on('click', function () {
        console.log('clicked');
        //sessionStorage.removeItem("sellerPopup");
    });

//Standard File Declaration for Buyer and seller
    var standard_file;
    function readExcelFileStadard(input){
        standard_file = $(input)[0].files[0];
        $('.standard_file_name').text(standard_file.name);
        console.log(standard_file.name);
    }

//Standard file choice for Buyer Back
    $("#standard_selection_back_forBuyer").on("click", function(){
        $("#header-form")[0].reset();
        standard_file = '';
        client_file='';
        $('.client_file_name').text('');
        $('.standard_file_name').text('');
        $("#standard_file_choice_dialog_forBuyer").addClass("display_none");
        $("#buyer_file_choice_dialog").removeClass("display_none");
    });

//Standard file choice for Buyer next
    $("#standard_selection_next_forBuyer").on("click", function(){
        if (standard_file) {
            $("#standard_file_choice_dialog_forBuyer").addClass("display_none");
        }else{
            alert("販売先を選んでください");
        }
    });

}); //End jQuery Block

/// Seller and Buyer List showing according to URL parameter
function getURLParam( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}

/// Buyer or Seller list display on main screen
jQuery(function($) {
    var select = getURLParam('select');

    if (select == 'seller') {
        $("#seller-list").removeClass("display_none");
        $("#buyer-list").addClass("display_none");
    } else if (select == 'manufacturer') {
        $("#buyer-list").removeClass("display_none");
        $("#seller-list").addClass("display_none");
    }

    /// Final report showing according to URL parameter
    $("#onlyOverwrittingDialogButton").click(function(){
        console.log('clicked');
        window.location = 'index.php?action=finalCompareData';
    });

});