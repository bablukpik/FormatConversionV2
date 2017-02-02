<div class="csv-data-wrapper csv-data-client">
    <div class="data-wrapper">
        <table class="row-content shortTable">
            <tr>
                <td class="head-col" data-value="量販店名" title="量販店名">量販店名</td>
                <td data-value="ﾂｷｼﾞｽｰﾊﾟｰ" title="ﾂｷｼﾞｽｰﾊﾟｰ">ﾂｷｼﾞｽｰﾊﾟｰ</td>
                <td class="connect-item client-data-connect">
                    5
                    <a id="row-1" data-type="client" data-key="量販店名"></a>
                </td>
            </tr>
            <tr>
                <td class="head-col" data-value="店舗コード" title="店舗コード">店舗コード</td>
                <td data-value="1" title="1">1</td>
                <td class="connect-item client-data-connect">
                    5
                    <a id="row-2" data-type="client" data-key="店舗コード"></a>
                </td>
            </tr>
            <tr>
                <td class="head-col" data-value="量販店舗名" title="量販店舗名">量販店舗名</td>
                <td data-value="ｼﾝﾊﾞｼﾃﾝ" title="ｼﾝﾊﾞｼﾃﾝ">ｼﾝﾊﾞｼﾃﾝ</td>
                <td class="connect-item client-data-connect">
                    5
                    <a id="row-3" data-type="client" data-key="量販店舗名"></a>
                </td>
            </tr>
            <tr>
                <td class="head-col" data-value="取引先名" title="取引先名">取引先名</td>
                <td data-value="1" title="1">1</td>
                <td class="connect-item client-data-connect">
                    5
                    <a id="row-4" data-type="client" data-key="取引先名"></a>
                </td>
            </tr>
            <tr>
                <td class="head-col" data-value="先コード" title="先コード">先コード</td>
                <td data-value="99999" title="99999">99999</td>
                <td class="connect-item client-data-connect">
                    5
                    <a id="row-5" data-type="client" data-key="先コード"></a>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="csv-data-wrapper csv-data-server">
    <div class="data-wrapper">
        <table class="row-content tbl-server-data shortTable">
            <tr data-count="8" data-index="1">
                <td class="connect-item server-data-connect">
                    <a id="row-1" data-type="server" data-key="量販店名"></a>
                    8
                </td>
                <td class="head-col" data-value="量販店名" title="量販店名">量販店名</td>
                <td data-value="ﾂｷｼﾞｽｰﾊﾟｰ" title="ﾂｷｼﾞｽｰﾊﾟｰ">ﾂｷｼﾞｽｰﾊﾟｰ</td>
            </tr>
            <tr data-count="9" data-index="2">
                <td class="connect-item server-data-connect">
                    <a id="row-2" data-type="server" data-key="店舗コード"></a>
                    9
                </td>
                <td class="head-col" data-value="店舗コード" title="店舗コード">店舗コード</td>
                <td data-value="1" title="1">1</td>
            </tr>
            <tr data-count="5" data-index="3">
                <td class="connect-item server-data-connect">
                    <a id="row-3" data-type="server" data-key="量販店舗名"></a>
                    5
                </td>
                <td class="head-col" data-value="量販店舗名" title="量販店舗名">量販店舗名</td>
                <td data-value="ｼﾝﾊﾞｼﾃﾝ" title="ｼﾝﾊﾞｼﾃﾝ">ｼﾝﾊﾞｼﾃﾝ</td>
            </tr>
            <tr data-count="1" data-index="4">
                <td class="connect-item server-data-connect">
                    <a id="row-4" data-type="server" data-key="取引先名"></a>
                    1
                </td>
                <td class="head-col" data-value="取引先名" title="取引先名">取引先名</td>
                <td data-value="1" title="1">1</td>
            </tr>
            <tr data-count="3" data-index="5">
                <td class="connect-item server-data-connect">
                    <a id="row-5" data-type="server" data-key="先コード"></a>
                    3
                </td>
                <td class="head-col" data-value="先コード" title="先コード">先コード</td>
                <td data-value="99999" title="99999">99999</td>
            </tr>
        </table>
    </div>
</div>
<canvas id="canvas" width=300 height=300 onclick="handleCanvasClick(event)"></canvas>
<button class="btn btn-danger btn-xs remove-canvas-btn"><i class="glyphicon glyphicon-remove"></i></button>
<div class="cl"></div>
<div id="fountainG">
    <div id="fountainG_1" class="fountainG"></div>
    <div id="fountainG_2" class="fountainG"></div>
    <div id="fountainG_3" class="fountainG"></div>
    <div id="fountainG_4" class="fountainG"></div>
    <div id="fountainG_5" class="fountainG"></div>
    <div id="fountainG_6" class="fountainG"></div>
    <div id="fountainG_7" class="fountainG"></div>
    <div id="fountainG_8" class="fountainG"></div>
</div>
<div class="compare-wrapper">

</div>

<div class="support-div">
    <p>
        1. 自社のデータを取り込んで下さい。
    </p>
    <p>
        2. 販売先のデータを取り込んで下さい。
    </p>
    <p>
        3. 類似語を登録すれば、リンク率は向上します。
    </p>
    <p>
        4. 終わりましたら、ボタンを押して下さい。
    </p>
    <p>開始</p>
    <button class="btn btn-primary close-support">認確</button>
</div>

<script type="text/javascript">
    hasData = true;
    $(document).ready(function() {
        initTranslate();
        sortMatchServer();
        autoCompare();
    });
</script>