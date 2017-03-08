<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>
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
            <div class="data-wrapper w-100 ml-0">
                <table class="row-content">
                    <thead>
                    <tr class="bg-header">
                        <?php foreach ($titles as $title) : ?>
                            <th><?php echo $title; ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($clientMapData as $k => $row) : ?>
                        <tr>
                            <?php foreach ($titles as $title) : ?>
                                <td contenteditable="true"><?php echo isset($row[$title]) ? $row[$title] : '';?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($titles as $title) : ?>
                                <td contenteditable="true"></td>
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
