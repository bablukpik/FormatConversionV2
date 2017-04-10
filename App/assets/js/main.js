var mapsData = {}, tmpMapsData = {}, timeOut, lineWeight = 0.5, drawOffsetLeft = 10;

// var sync = {
//     "JAN": ["ＪＡＮＣＤ", "JAN", "JANCD"],
//     "商品名": ["商品名", "品名"],
//     "規格": ["規格", "量目"],
//     "メーカー名": ["メーカー名", "ブランド/セグメント"],
//     "発売日": ["発売日", "発売予定"],
//     "賞味期限": ["賞味期限", "賞味期限"],
//     "売価\n（税抜": ["売価\n（税抜）", "税込価格"],
//     "原価\n（税抜）": ["原価\n（税抜）", "原価"],
//     "発注\n単位": ["発注\n単位", "入数"]
// };
    var sync = {};

$(document).ready(function(){
    // Init canvas
    initCanvas();

    $('.close-support').on('click', function(){
        $(this).closest('.support-div').hide();
    });

    handleRemoveLine();

    handleClickOutsideModal();

    var connectItem = $('.connect-item a');

    // Init draggable
    connectItem.draggable({ helper: "clone"});
    // Init drop
    connectItem.droppable({
        drop: function( event, ui ) {
            // Get drop item,
            $( this )
                .addClass( "ui-state-highlight" )
                .find( "p" )
                .html( "Dropped!" );

            var $left, $right;
            var $dropItem = $(ui.draggable);

            if ($dropItem.data('type') == 'server') {
                $left = $(event.target);
                $right = $dropItem;
            } else {
                $left = $dropItem;
                $right = $(event.target);
            }

            createMatchLine($left, $right);
        }
    });

    // Re-draw when change windows size
    window.onresize = function(event) {
        clearTimeout(timeOut);
        timeOut = setTimeout(function() { initCanvas(); drawAll();}, 200);
    };
});

/**
 * Start matching: submit header form
 */
function startMatching() {
    $("#header-form").submit();
}

/**
 * Browser client file
 */
function browserClientFile(fileType) {
    if (fileType == 'itoham'){
        $("input[name=client-data]").attr("accept","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    }else if(fileType == 'donki'){
        $("input[name=client-data]").attr("accept","application/vnd.ms-excel");
    }

    $("input[name=client-data]").trigger('click');
}

/**
 * Browser server file
 */
function browserServerFile() {
    $("input[name=server-data]").trigger('click');
}

var canvas, ctx, $canvas, canvasOffset, offsetX, offsetY;
var connectors = [];

function initCanvas() {
    var bodyElm = $('body');
    canvas = document.getElementById("canvas");
    if (canvas) {
        ctx = canvas.getContext("2d");
        canvas.width = bodyElm.outerWidth();

        canvas.height = $(document).height();
        canvas.style.height = $(document).height() + 'px';
        ctx.lineWidth = 2;

        $canvas = $("#canvas");
        canvasOffset = $canvas.offset();
        offsetX = canvasOffset.left;
        offsetY = canvasOffset.top;
    }
}

/**
 * Handle canvas click. Init in canvas tag in index.php
 * @param e
 */
function handleCanvasClick(e) {
    var c, eFrom, eTo, pos1, pos2, offsetTop, btnRemoveCanvas;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    offsetTop = $(document).scrollTop();

    btnRemoveCanvas = $('.remove-canvas-btn');

    btnRemoveCanvas.css('top', '-100px');

    // Hide all modal
    $('.modal-matching-info').remove();

    // Re draw for test
    for (var i = 0; i < connectors.length; i++) {
        c = connectors[i];
        if (c) {
            eFrom = c.from;
            eTo = c.to;
            pos1 = eFrom.offset();
            pos2 = eTo.offset();

            customDrawLine(pos1.left + eFrom.outerWidth() - drawOffsetLeft, pos1.top + eFrom.outerHeight() / 2,
                pos2.left + eTo.outerWidth() / 2 - drawOffsetLeft, pos2.top + eTo.outerHeight() / 2, lineWeight);

            if (IsInPath(e)) {
                ctx.strokeStyle = 'red';
                ctx.stroke();
                btnRemoveCanvas.css('top', (e.clientY - 15) + offsetTop + 'px');
                btnRemoveCanvas.css('left', (e.clientX - 10) + 'px');
                btnRemoveCanvas.attr('data-key', i);

                // Show match info
                showMatchInfo(eFrom, eTo, e);

            } else {
                ctx.strokeStyle = '#333';
                ctx.stroke();
            }
        }
    }
}

function IsInPath(event) {
    var bb, x, y, inPath;

    bb = canvas.getBoundingClientRect();

    x = (event.clientX - bb.left) * (canvas.width / bb.width);
    y = (event.clientY - bb.top) * (canvas.height / bb.height);

    if (ctx.isPointInPath(x + 1, y) || ctx.isPointInPath(x - 1, y) || ctx.isPointInPath(x, y + 1) || ctx.isPointInPath(x, y - 1) ||
        ctx.isPointInPath(x + 2, y) || ctx.isPointInPath(x - 2, y) || ctx.isPointInPath(x, y + 2) || ctx.isPointInPath(x, y - 2)) {
        inPath = true;
    } else {
        inPath = false;
    }

    return inPath;
}

/**
 * Create match line between two items: left and right
 *
 * @param leftItem
 * @param rightItem
 * @param removeOld
 */
function createMatchLine(leftItem, rightItem, removeOld)
{
    // Check map created
    if (!mapsData[leftItem.data('key')] && !tmpMapsData[rightItem.data('key')]) {
        // Draw line and send ajax map-data
        drawLine(leftItem, rightItem);
        // Push to list compare
        mapsData[leftItem.data('key')] = rightItem.data('key');
        tmpMapsData[rightItem.data('key')] = leftItem.data('key');
    } else {
        if (mapsData[leftItem.data('key')] == rightItem.data('key')) {
            // Link exist, do nothing
        } else {
            // If accept remove old matching: remove from connectors
            if (removeOld) {
                if (confirm('リンクが存在しています。置き換えますか？')) {
                    // Remove exist line
                    delete mapsData[leftItem.data('key')];
                    delete tmpMapsData[rightItem.data('key')];

                    // Remove connector
                    connectors = $.grep(connectors, function (item) {
                        return item.from.data('key') != leftItem.data('key');
                    });

                    // Create new line
                    createMatchLine(leftItem, rightItem);
                }
            } else {
                alert('リンクが存在してます');
            }
        }
    }
}

/**
 * Draw line between to element
 *
 * @param fromElm
 * @param to
 */
function drawLine (fromElm, to) {
    connectors.push({from: fromElm, to: to});

    drawAll();
}

/**
 * Draw all line
 */
function drawAll() {
    if (ctx) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (var i = 0; i < connectors.length; i++) {
            var c = connectors[i];
            if (c) {
                var eFrom = c.from;
                var eTo = c.to;
                var pos1 = eFrom.offset();
                var pos2 = eTo.offset();
                ctx.strokeStyle = 'black';
                customDrawLine(pos1.left + eFrom.outerWidth() / 2 - drawOffsetLeft, pos1.top + eFrom.outerHeight() / 2,
                    pos2.left + eTo.outerWidth() / 2 - drawOffsetLeft, pos2.top + eTo.outerHeight() / 2, lineWeight);
            }
        }
    }
}

/**
 * Function draw one line from two point
 *
 * @param x1
 * @param y1
 * @param x2
 * @param y2
 * @param lineWeight
 */
function customDrawLine(x1, y1, x2, y2, lineWeight)
{
    var tlX, tlY, blX, blY, trX, trY, brX, brY; // TopLeft, BottomLeft, TopRight, BottomRight

    // Calc 4 point for line
    tlX = x1;
    tlY = y1;

    blX = tlX;
    blY = tlY + lineWeight;

    trX = x2;
    trY = y2;

    brX = trX;
    brY = trY + lineWeight;

    // Draw shape line
    ctx.beginPath();
    ctx.moveTo(tlX, tlY);
    ctx.lineTo(trX, trY);
    ctx.lineTo(brX, brY);
    ctx.lineTo(blX, blY);
    ctx.fill();
    ctx.stroke();
}

/**
 * Remove draw line
 */
function handleRemoveLine() {
    $('.remove-canvas-btn').click(function() {
        var index = $(this).attr('data-key');
        if (index) {
            var connector = connectors[index];
            // Position remove button
            $('.remove-canvas-btn').css('top', '-100px');

            // Remove right-map data
            delete tmpMapsData[mapsData[connector.from.data('key')]];
            // Remove map-data
            delete mapsData[connector.from.data('key')];

            // remove line with index
            delete connectors[index];

            // Re draw
            drawAll();
        }
    });
}

/**
 * Do compare data
 */
function compareData() {

    var form = $('<form action="index.php?action=compareData" method="post">').appendTo('body');
    $.each(mapsData, function(key, value) {
        $('<input name="'+key+'" value="'+value+'">').appendTo(form);
    });
    form[0].submit();

    //end


    /*$("#fountainG").show();
    $.ajax({
        url: 'index.php?action=compareData',
        type: 'post',
        data: mapsData,
        async: false,
        success: function(res) {
            var compareWrap = $('.compare-wrapper');
            // Import html
            compareWrap.html(res);
            $('html,body').animate({scrollTop: compareWrap.offset().top}, 1000);

            $("#fountainG").hide();
        }
    });*/
}



/**
 * Do compare data
 */
function exportCompareData() {
    var form = $('<form>').attr('action', 'index.php?action=exportCompareData').attr('method','post').appendTo('body');
    $('<textarea>').attr('name','mapsData').html(JSON.stringify(mapsData)).appendTo(form);
    form[0].submit();
    form.remove();
}

// Auto compare, create link
function autoCompare() {
    var clientList, serverList, match = false, noMatching = true;
    console.log(mapsData);
    console.log(tmpMapsData);
    // Get client list
    clientList = $('.client-data-connect a');

    // Server list
    serverList = $('.server-data-connect a');

    // Auto match data
    serverList.each(function(){
        match = false;
        var $r = $(this);
        var rowMatched = false;
        clientList.each(function(){
            if ( rowMatched ) {
                
            } else {
                var $l = $(this);
                if ($r.data('key') === $l.data('key')) {
                    match = true;
                    // Check map created
                    if (!mapsData[$l.data('key')] && !tmpMapsData[$r.data('key')]) {
                        drawLine($l, $r);
                        mapsData[$l.data('key')] = $r.data('key');
                        tmpMapsData[$r.data('key')] = $l.data('key');
                        noMatching = false;
                        rowMatched = true;
                    }
                } else if ( typeof listIndex[$r.data('key')] != 'undefined' ) {
                    // Get list word similar
                    var arrSimilar = similarWords[listIndex[$r.data('key')]];
                    var item = false;

                    $.each(arrSimilar, function(k, v){
                        if (v != $l.data('key') && v != '') {
                            // Check key in server list
                            item = $('.client-data-connect a[data-key="' + v + '"]');
                            // Find similar word success: create line.
                            if (item.length > 0) {
                                // Check map created
                                if (!mapsData[item.data('key')] && !tmpMapsData[item.data('key')]) {
                                    drawLine(item, $r);
                                    mapsData[item.data('key')] = $r.data('key');
                                    tmpMapsData[$r.data('key')] = item.data('key');
                                    updateCounterSimilarWord(listIndex[$r.data('key')], v);
                                    noMatching = false;
                                    rowMatched = true;
                                }
                            }
                        }
                    });
                } else if ( typeof sync[$r.data('key')] != 'undefined' ) {
                    // Get list word similar
                    var arrSimilar = sync[$r.data('key')];
                    var item = false;

                    $.each(arrSimilar, function(k, v){
                        if (v != $l.data('key') && v != '') {
                            // Check key in server list
                            item = $('.client-data-connect a[data-key="' + v + '"]');
                            // Find similar word success: create line.
                            if (item.length > 0) {
                                // Check map created
                                if (!mapsData[item.data('key')] && !tmpMapsData[item.data('key')]) {
                                    drawLine(item, $r);
                                    mapsData[item.data('key')] = $r.data('key');
                                    tmpMapsData[$r.data('key')] = item.data('key');
                                    updateCounterSimilarWord(listIndex[$r.data('key')], v);
                                    noMatching = false;
                                    rowMatched = true;
                                }
                            }
                        }
                    });
                }
            }
        });

        if (!rowMatched) {
            //$r.closest('tr').remove();
        }

    });

    if (noMatching) {
        // Show modal
        $('.no-matching').show();
    } else {
        // Update counter for word match
        //compareData();
        //sortMatchServer();
    }
}
function autoCompareBackup() {
    var clientList, serverList, match = false, noMatching = true;

    // Get client list
    clientList = $('.client-data-connect a');

    // Server list
    serverList = $('.server-data-connect a');

    // Auto match data
    clientList.each(function(){
        match = false;
        var $l = $(this);
        serverList.each(function(){
            var $r = $(this);
            if ($r.data('key') === $l.data('key')) {
                match = true;
                // Check map created
                if (!mapsData[$l.data('key')] && !tmpMapsData[$r.data('key')]) {
                    // Draw line and send ajax map-data
                    drawLine($l, $r);
                    // Push to list compare
                    mapsData[$l.data('key')] = $r.data('key');
                    tmpMapsData[$r.data('key')] = $l.data('key');
                    noMatching = false;
                }
            }
        });

        if (match == false) {
            // Check similar words
            if (typeof listIndex[$l.data('key')] != 'undefined') {
                // Get list word similar
                var arrSimilar = similarWords[listIndex[$l.data('key')]];
                var item = false;
                $.each(arrSimilar, function(k, v){
                    if (v != $l.data('key') && v != '') {
                        // Check key in server list
                        item = $('.server-data-connect a[data-key="' + v + '"]');

                        // Find similar word success: create line.
                        if (item.length > 0) {
                            // Check map created
                            if (!mapsData[$l.data('key')] && !tmpMapsData[item.data('key')]) {
                                // Draw line and send ajax map-data
                                drawLine($l, item);
                                // Push to list compare
                                mapsData[$l.data('key')] = item.data('key');
                                tmpMapsData[item.data('key')] = $l.data('key');

                                // Add counter for similar word
                                updateCounterSimilarWord(listIndex[$l.data('key')], v);

                                noMatching = false;
                            }
                        }
                    }
                });
            }
        }
    });

    if (noMatching) {
        // Show modal
        $('.no-matching').show();
    } else {
        // Update counter for word match
        //compareData();
    }
}


/**
 * Init translate
 */
function initTranslate()
{
    Loading.show();
    // Init translate library
    kuroshiro.init(function () {
        Loading.hide();
        translateRomaji();
    });
}

/**
 * Update counter for similar word
 *
 * @param basicId
 * @param word
 */
function updateCounterSimilarWord(basicId, word)
{
    if (word && basicId) {
        $.ajax({
            url: 'index.php?action=updateSimilarCounter',
            dataType: 'json',
            type: 'post',
            data: { word: word, basic_id: basicId},
            async: true,
            success: function(res) {

            }
        });
    }
}

/**
 * Translate all word-key to Romaji
 */
function translateRomaji()
{
    var listHeaderKey = $('.head-col'), translate;

    if (listHeaderKey.length > 0) {
        $.each(listHeaderKey, function() {
            // Add translate to attribute
            translate = kuroshiro.convert($(this).html(), {to: 'romaji'});

            if (translate) {
                $(this).attr('data-romaji', translate);
            }
        });
    }

    compareRomaji();
}

var listMatch = {};

// Compare by romaji foreach row in server side
function compareRomaji()
{
    var listServerKey, listClientKey, percent, item1, item2;

    listServerKey = $('.tbl-server-data').find('.head-col');
    listClientKey = $('.csv-data-client').find('.head-col');

    // Compare each word from server-side with client-side
    $.each(listServerKey, function() {
        item1 = $(this);
        // Init list match
        listMatch[item1.html()] = {
            '-1': [],
            '-0.9' : [],
            '-0.8' : [],
            '-0.6' : [],
            '-0.4' : []
        };

        $.each(listClientKey, function() {
            item2 = $(this);
            percent = getMatchingPercent(item1.data('romaji'), item2.data('romaji'));
            if (percent >= 40) {
                // Group by percent
                if (percent == 100) {
                    // Push item to list
                    listMatch[item1.html()]['-1'].push(item2);
                } else  if (percent >= 90) {
                    // Push item to list
                    listMatch[item1.html()]['-0.9'].push(item2);
                } else  if (percent >= 80) {
                    // Push item to list
                    listMatch[item1.html()]['-0.8'].push(item2);
                } else if (percent >= 60) {
                    // Push item to list
                    listMatch[item1.html()]['-0.6'].push(item2);
                } else if (percent >= 40) {
                    // Push item to list
                    listMatch[item1.html()]['-0.4'].push(item2);
                }
            }
        });
    });

    handleClickServerKey();
}

/**
 * Handle click server-side item
 */
function handleClickServerKey()
{
    $('body').on('click', '.csv-data-server .head-col', function() {
        // Get list match for item
        showModalMatching($(this));
        console.log('Click catch!');
    });

    handleEventMatchingList();
}

/**
 * Get matching percent between two words
 * @param item1
 * @param item2
 * @returns {*}
 */
function getMatchingPercent(item1, item2)
{
    var maxLength, minLength, i, j, item1Length, item2Length, percent, oddNumber;
    item1Length = item1.length;
    item2Length = item2.length;

    // Get max length
    if (item1Length < item2Length) {
        maxLength = item2Length;
        minLength = item1Length;
    } else if (item1.length > item2Length) {
        maxLength = item1Length;
        minLength = item2Length;
    } else {
        // Check text similar
        minLength = maxLength = item1Length;
    }

    // Check text similar
    percent = 0;
    oddNumber = false;

    // Start from 0 to maxLength, j is start index of item1.
    j = 0;
    while (percent == 0 && j < maxLength) {
        for (i = 0; i < minLength; i++) {
            // Get position matching, two words can matching at index > 0
            if (item1[j] == item2[i] && oddNumber === false) {
                oddNumber = i - j;
            }

            // If matching found: count matching word
            if (oddNumber !== false) {
                if (item1[i - oddNumber] == item2[i]) {
                    percent++;
                } else {
                    percent = 0;
                }
            }
        }

        if (percent == 0) {
            j++;
        }
    }

    // Calc matching percent
    percent = parseInt((percent / maxLength) * 100);
    if (percent >= 40) {
        return percent;
    } else {
        return false;
    }
}

var lastDirection = '';

/**
 * Sort table by matchCount for server data
 */
function sortMatchServer()
{
    var $table;
    if (lastDirection == '') {
        lastDirection = 'desc';
    } else if (lastDirection == 'desc') {
        lastDirection = 'asc';
    } else if (lastDirection == 'asc') {
        lastDirection = 'desc';
    }

    // Get table
    $table = $('.tbl-server-data');

    if (lastDirection == 'desc') {
        orderDescMatchTable($table);
        // Show support modal
        $('.support-div.sort-support').hide();
    } else {
        orderAscMatchTable($table, 'index');
        // Show support modal
        $('.support-div.sort-support').show();
    }

    // Re-draw line
    drawAll();

    // Update position for modal
    updateModalMatchingPosition();
}

/**
 * Order server-side table by match-count desc
 * @param $table
 */
function orderDescMatchTable($table) {
    var sortData = 'count', listLength, i, j, tmp, maxIndex, maxValue;

    listLength = $table.find('tr').length;

    for (i = 0; i < listLength; i++) {
        tmp = $table.find('tr').eq(i);
        maxValue = tmp.data(sortData);
        maxIndex = false;
        for (j = i + 1; j < listLength; j++) {
            if (maxValue < $table.find('tr').eq(j).data(sortData)) {
                maxValue = $table.find('tr').eq(j).data(sortData);
                maxIndex = j;
            }
        }

        //console.log('Max index: ' + maxIndex + ': ' + maxValue);

        if (maxIndex) {
            // Swap tr
            $table.find('tr').eq(i).before($table.find('tr').eq(maxIndex));
        }
    }
}

/**
 * Order server-side table by match-count asc
 *
 * @param $table
 * @param sortData
 */
function orderAscMatchTable($table, sortData) {
    var listLength, i, j, tmp, minIndex, minValue;

    if (!sortData) {
        sortData = 'count'
    }

    listLength = $table.find('tr').length;

    for (i = 0; i < listLength; i++) {
        tmp = $table.find('tr').eq(i);
        minValue = tmp.data(sortData);
        minIndex = false;
        for (j = i + 1; j < listLength; j++) {
            if (minValue > $table.find('tr').eq(j).data(sortData)) {
                minValue = $table.find('tr').eq(j).attr(sortData);
                minIndex = j;
            }
        }

        if (minIndex) {
            // Swap tr
            $table.find('tr').eq(i).before($table.find('tr').eq(minIndex));
        }
    }
}

/**
 * Show modal matching for word
 */
function showModalMatching(item)
{
    if (item) {
        var htmlModal, listMatchPercent, tmp = '', tmp2, modal, matchingBorder, hasMatch = false, $body = $('body');

        modal = $body.find('.modal-matching[data-key="' + item.html() + '"]');
        if (modal.length <= 0) {
            htmlModal = $('<div class="modal-matching"><div class="list-matching-border"></div></div>');
            matchingBorder = htmlModal.find('.list-matching-border');
            listMatchPercent = listMatch[item.html()];

            if (listMatchPercent && Object.keys(listMatchPercent).length > 0) {
                $.each(listMatchPercent, function (percent, listMatches) {
                    if (listMatches.length > 0) {
                        tmp = $('<div class="march-item-wrapper"><div class="match-percent">' + (percent * -100) + '%</div><div class="list-match"></div></div>');
                        tmp2 = tmp.find('.list-match');
                        $.each(listMatches, function () {
                            tmp2.append('<a href="javascript:" class="match-item" data-row="' + $(this).html() + '">' + $(this).html() + '</a>');
                        });
                        matchingBorder.append(tmp);
                        hasMatch = true;
                    }
                });
            }

            if (hasMatch == false) {
                matchingBorder.html('候補 項目はありません。');
                matchingBorder.addClass('not-found');
            } else {
                matchingBorder.removeClass('not-found');
            }

            item.addClass('hasMatching');
            // Append item to page
            $body.append(htmlModal);
            // Add modal attribute
            htmlModal.attr('data-key', item.html());

            modal = $body.find('.modal-matching[data-key="' + item.html() + '"]');

            // Calc position for modal
            modal.css('top', item.offset().top + 30 + 'px');
            modal.css('left', (item.offset().left - 50) + 'px');
        }

        if (modal.length > 0) {
            // show modal for item
            modal.show();
        }
    }
}

/**
 * Handle event click matching word and create matching-line
 */
function handleEventMatchingList()
{
    var $body = $('body');

    $body.on('mouseenter', 'a.match-item', function () {
        var elm = $('.csv-data-client .head-col[data-value=' + $(this).data('row') + ']');
        // Highlight client-side
        elm.addClass('matching');
    }).on('mouseleave', 'a.match-item', function() {
        $('.csv-data-client .head-col[data-value=' + $(this).data('row') + ']').removeClass('matching');
    });

    $body.on('click', 'a.match-item', function() {
        // Create match link, if link exist?
        var left, right;
        left = $('.client-data-connect a[data-key=' + $(this).data('row') + ']');
        right = $('.server-data-connect a[data-key=' + $(this).parents('.modal-matching').data('key') + ']');

        if (left.length > 0 && right.length > 0) {
            // Create match line
            createMatchLine(left, right, true);
        }

        // Hide matching-modal
        $('.modal-maching').hide();
    });
}

function updateModalMatchingPosition()
{
    // Update position modal for each word on server-side
    var items = $('.csv-data-server .head-col'), modal;

    if (items.length > 0) {
        $.each(items, function() {
            modal = $('body').find('.modal-matching[data-key="' + $(this).html() + '"]');

            // Calc position for modal
            modal.css('top', $(this).offset().top + 30 + 'px');
            modal.css('left', ($(this).offset().left - 50) + 'px');
        });
    }
}

/**
 * Click outside modal: hide modal
 */
function handleClickOutsideModal()
{
    var modal;

    $('body').on('click', function(e) {
        // Hide all modal first
        $('.modal-matching').hide();
        if (!$(e.target).hasClass('modal-matching') && $(e.target).parents('.modal-matching').length <= 0) {
            // Check click head-col
            if ($(e.target).hasClass('head-col')) {
                // Show modal for current word
                modal = $('.modal-matching[data-key="' + $(e.target).html() + '"]');
                if (modal.length > 0) {
                    modal.show();
                }
            }
        } else {
            modal = $(e.target);
            if ($(e.target).parents('.modal-matching').length > 0) {
                modal = $(e.target).parents('.modal-matching');
            }
        }
    });

    // Click not found modal: hide modal
    $('.list-matching-border.not-found').click(function(){
        $(this).parents('.modal-matching').hide();
    });
}

/**
 * Loading item
 * @type {{element: string, show: Loading.show, hide: Loading.hide}}
 */
var Loading = {
    element: '#loading-page',
    show: function() {
        $(this.element).show();
    },
    hide: function() {
        $(this.element).hide();
    }
};

/**
 * Show match info
 */
function showMatchInfo(leftElm, rightElm, clickPosition)
{
    var modal;
    modal = $('<div class="modal-matching-info"><div class="modal-content"></div></div>');

    // Add modal content
    modal.find('.modal-content').html('<p>' + leftElm.data('key') + '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;' + rightElm.data('key') + '</p>');

    // Hide all modal
    $('.modal-matching-info').remove();

    // Append item to page
    $('body').append(modal);

    // Show modal
    modal.show();

    // Set modal position
    modal.css('top', (clickPosition.pageY + 10) + 'px');
    modal.css('left', (clickPosition.pageX - (modal.outerWidth() / 2)) + 'px');
}

/*Bablu*/




/*/Bablu*/