
//Seller list
$(document).on("click", function(event){
    if ($(event.target).parents("#seller-list").length > 0 || $(event.target).is("#seller_posting") || $(event.target).is("#seller_selection_back")) {
        return false;
    }
    $("#seller-list").addClass("display_none");
});

$("#seller_posting").on("click", function(){
    $("#seller-list").removeClass("display_none");
});

//Seller table selection
var table = $("#table-seller-list");
var button;
var td;
var seller_id;
var seller_name;

table.find('td').click(function() {
    td = $(this);
    button = td.find('button').first();
    button.addClass("item_seller");
    seller_id     = button.attr('data-id');
    seller_name   = button.attr('data-name');
});


//seller file choice
$(document).on("click", ".item_seller", function(){
    $("#seller_file_choice_dialog").removeClass("display_none");

    var html = "<div id='seller_file_choice_item' style='margin-bottom: 10px;'>"+

        "<span id='seller_name'>"+seller_name+"</span>"+

        "<button style='margin-left: 5px; display: inline-block; background: #2471a3; border: 1px solid #2E86C1; font-size: 13px; padding: 10px; border-radius: 5px; color: white;' onclick="+

        "browserClientFile();"+">ファイル選択</button>"+

        //"<input id='input_excel_file_seller' data-id='"+seller_id+"' data-name='"+seller_name+"' type='file' accept='application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' style='display: none; width:0; height: 0;' required='' onchange='readExcelFileMaker(this)'>"+

        "<span style='font-size:14px; margin-left: 5px;' id='client_file_name'></span>"+

        "</div>";

    $('#seller_file_choiced').html(html);
});

var client_file;
function readExcelFileClient(input){
    client_file = $(input)[0].files[0];
    $('#client_file_name').text(client_file.name);
}


//seller file choice back
$("#seller_selection_back").on("click", function(){
    $("#header-form")[0].reset();
    client_file='';
    $('#seller_file_name').text('');

    $("#seller_file_choice_dialog").addClass("display_none");
    $("#seller_file_choice_dialog").removeClass("display_block");
    $("#seller-list").removeClass("display_none");
});

//seller file choice next
$("#seller_selection_next").on("click", function(){
    if (client_file) {
        $("#seller_file_choice_dialog, #seller-list").addClass("display_none");
        $("#seller_file_choice_dialog, #seller-list").removeClass("display_block");
        $("#standard_file_choice_dialog").addClass("display_block");
        $("#standard_file_choice_dialog").removeClass("display_none");
        $('#inputReportType').val('seller');

    }else{
        alert("メーカーを選んでください");
    }
});

var standard_file;
function readExcelFileStadard(input){
    standard_file = $(input)[0].files[0];
    $('#standard_file_name').text(standard_file.name);
}

//Standard file choice for Seller and Buyer Back
$("#standard_selection_back").on("click", function(){
    $("#header-form")[0].reset();
    standard_file = '';
    client_file='';
    $('#seller_file_name').text('');
    $('#standard_file_name').text('');
    $("#standard_file_choice_dialog").addClass("display_none");
    $("#standard_file_choice_dialog").removeClass("display_block");
    $("#seller_file_choice_dialog").removeClass("display_none");
    $("#seller_file_choice_dialog").addClass("display_block");

});

//Standard file choice for Seller and Buyer next
$("#standard_selection_next").on("click", function(){
    if (standard_file) {
        $("#standard_file_choice_dialog").addClass("display_none");
        startMatching();
    }else{
        alert("販売先を選んでください");
    }
});