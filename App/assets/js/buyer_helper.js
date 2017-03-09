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

//Buyer/Manufacture/Maker list
$(document).on("click", function(event){
    if ($(event.target).parents("#buyer-list").length > 0 || $(event.target).is("#buyer_posting") || $(event.target).is("#maker_selection_back")) {
        return false;
    }
    $("#buyer-list").addClass("display_none");
});

$("#buyer_posting").on("click", function(){
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

        "<span style='font-size:14px; margin-left: 5px;' id='client_file_name'></span>"+

        "</div>";

    $('#buyer_file_choiced').html(html);
});

var client_file;
function readExcelFileClient(input){
    client_file = $(input)[0].files[0];
    $('#client_file_name').text(client_file.name);
    console.log("hi");
}


//buyer file choice back
$("#maker_selection_back").on("click", function(){
    $("#header-form")[0].reset();
    client_file='';
    $('#buyer_file_name').text('');

    $("#buyer_file_choice_dialog").addClass("display_none");
    $("#buyer_file_choice_dialog").removeClass("display_block");
    $("#buyer-list").removeClass("display_none");
});

//buyer file choice next
$("#maker_selection_next").on("click", function(){
    if (client_file) {
        $("#buyer_file_choice_dialog, #buyer-list").addClass("display_none");
        $("#buyer_file_choice_dialog, #buyer-list").removeClass("display_block");
        $("#standard_file_choice_dialog").addClass("display_block");
        $("#standard_file_choice_dialog").removeClass("display_none");

    }else{
        alert("メーカーを選んでください");
    }
});

var standard_file;
function readExcelFileStadard(input){
    standard_file = $(input)[0].files[0];
    $('#standard_file_name').text(standard_file.name);
}

//Seller file choice back
$("#standard_selection_back").on("click", function(){
    $("#header-form")[0].reset();
    standard_file = '';
    client_file='';
    $('#buyer_file_name').text('');
    $('#standard_file_name').text('');
    $("#standard_file_choice_dialog").addClass("display_none");
    $("#standard_file_choice_dialog").removeClass("display_block");
    $("#buyer_file_choice_dialog").removeClass("display_none");
    $("#buyer_file_choice_dialog").addClass("display_none");
    $("#seller_file_choice_dialog").addClass("display_none");

});

//Seller file choice next
$("#standard_selection_next").on("click", function(){
    if (standard_file) {
        $("#standard_file_choice_dialog").addClass("display_none");
        startMatching();
    }else{
        alert("販売先を選んでください");
    }
});