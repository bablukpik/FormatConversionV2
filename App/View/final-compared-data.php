<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>

<style>
    .border_separator{
        border-right: 3px solid black !important;
        padding-right: 5px !important;
    }
    .border_separator2{
        border-left: 3px solid black !important;
        padding-left: 5px !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <?php if ($clientMapData) : ?>

            <?php
            // Get server data
            $serverData = $_SESSION['serverData'];
            $titles = reset($serverData);

            // Get array key
            $keys = $serverData[0];
            ?>

            <div class="col-md-12" style="margin-top: 30px;">
                <?php
                // Get title
                $titles = reset($serverData);
                ?>
                <div class="data-wrapper w-100 ml-0" contenteditable="true">
                    <table class="row-content">
                        <thead>
                        <tr class="bg-header">
                            <?php foreach ($titles as $title) : ?>
                                <th class="<?php echo $title == '賞味期限'?'border_separator':''?><?php echo $title == '包装形態'?'border_separator2':''?><?php echo $title == '包装形態' || $title == '保存温度' || $title == '税込価格' || $title == '縦' || $title == '横' || $title == '奥行' || $title == '発売予定' || $title == '新・リ'?' non_editable':''?>"><?php echo $title; ?></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($clientMapData as $k => $row) : ?>
                            <tr>
                                <?php foreach ($titles as $title) : ?>
                                    <td class="<?php echo $title == '賞味期限'?'border_separator':''?><?php echo $title == '包装形態'?'border_separator2':''?><?php echo $title == '包装形態' || $title == '保存温度' || $title == '税込価格' || $title == '縦' || $title == '横' || $title == '奥行' || $title == '発売予定' || $title == '新・リ'?' non_editable':''?>">
                                        <?php
                                        if($title == 'NO') {
                                            echo '今回';
                                        } else {
                                            echo (isset($row[$title]) ? $row[$title] : '');
                                        }

                                        ?>
                                        <br></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($titles as $title) : ?>
                                    <td class="<?php echo $title == '賞味期限'?'border_separator':''?><?php echo $title == '包装形態'?'border_separator2':''?><?php echo $title == '包装形態' || $title == '保存温度' || $title == '税込価格' || $title == '縦' || $title == '横' || $title == '奥行' || $title == '発売予定' || $title == '新・リ'?' non_editable':''?>"><?php if($title == 'NO') echo '前回';?><br></td>
                                <?php endforeach; ?>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    $('<td style="padding: 1px;">').insertAfter('.border_separator');
    var non_editable =$('.non_editable');
    non_editable.attr('contenteditable',false);
    non_editable.attr('disabled');
</script>