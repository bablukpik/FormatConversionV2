<?php include('elements/head.php'); ?>
<div class="config-page">
    <a href="index.php?action=home" class="btn btn-primary">変換ページに戻る</a>
    <div class="container-fluid">
        <h1>類似語一覧</h1>
        <a href="javascript:" class="btn btn-primary add-btn mb-10" data-toggle="modal" data-target="#mapDataModal">追加</a>
        <table class="table table-bordered list-map-data">
            <thead>
            <tr>
                <th>基本項目名</th>
                <th>類似語１</th>
                <th>類似語２</th>
                <th>類似語３</th>
                <th>類似語４</th>
                <th>類似語５</th>
                <th>類似語６</th>
                <th>類似語７</th>
                <th>類似語8</th>
                <th>類似語9</th>
                <th>類似語10</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($listMapData) && !empty($listMapData)) : ?>
                <?php foreach($listMapData as $mapData) : ?>
                    <tr class="similar-word" data-key="<?php echo $mapData['id'];?>">
                        <td class="basic_word"><?php echo $mapData['basic_word'];?></td>
                        <?php for ($i = 0; $i < 10; $i++) : ?>
                            <?php if (isset($arrSimilar[$mapData['id']])) : ?>
                                <td class="similar_word"><?php echo isset($arrSimilar[$mapData['id']][$i]) ? $arrSimilar[$mapData['id']][$i]['similar_word'] : '';?></td>
                            <?php else : ?>
                                <td class="similar_word"></td>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <td>
                            <a href="?action=editSimilar&id=<?php echo $mapData['id']; ?>" class="btn btn-warning edit-btn btn-xs" data-id="<?php echo $mapData['id']; ?>">編集</a>
                            <a href="javascript:" class="btn btn-danger delete-btn btn-xs" data-id="<?php echo $mapData['id']; ?>">削除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php if (isset($totalPage)) : ?>
            <ul class="pagination">
                <?php for($i = 1; $i <= $totalPage; $i++) : ?>
                    <li <?php echo ($i == $page) ? 'class="active"' : '';?>><a href="index.php?action=configSimilarWord&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div id="ajaxModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body align-center"></div>
            </div>
        </div>
    </div>

    <div id="mapDataModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body align-center">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="modal-title">地図データを追加します。</h3>
                                <form class="form-horizontal frm-mapdata" method="post" action="index.php?action=addSimilar">
                                    <div class="row mb-10">
                                        <div class="form-group col-md-1">
                                            <label class="control-label">基本項目名</label>
                                            <input type="text" name="basic_word" required class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語１</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label"> 類似語２</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語３</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語４</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語５</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語６</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語７</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語8</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語9</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label class="control-label">類似語10</label>
                                            <input type="text" name="similar_words[]" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="type" value="add"/>
                                        <input type="hidden" name="id" value=""/>
                                        <button type="submit" class="btn btn-primary">加えます</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#ajaxModal').on('hidden.bs.modal', function () {
                $(this).find("input,textarea,select").val('').end();

            });

            $('body').on('submit', '.frm-mapdata', function(){
                var frm = $(this);

                $.ajax({
                    url: frm.attr('action'),
                    dataType: 'json',
                    type: 'post',
                    data: frm.serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Add to list field or reload page
                            window.location.reload();
                        } else {

                        }
                    }
                });

                // Close modal
                $(this).closest('.modal').modal('hide');

                return false;
            });

            $('.list-map-data .edit-btn').on('click', function(e){
                $.get($(this).attr('href'), function(res) {
                    $('#ajaxModal').find('.modal-body').html(res);
                    $('#ajaxModal').modal('show');
                });

                e.preventDefault();
            });

            $('.list-map-data .delete-btn').on('click', function(){
                if (confirm('本気ですか？')) {
                    $.ajax({
                        url: 'index.php?action=removeMapData',
                        dataType: 'json',
                        type: 'post',
                        data: {type: 'delete', id: $(this).data('id')},
                        success: function(res) {
                            if (res.success) {
                                // Remove item from list field or reload page
                                window.location.reload();
                            } else {

                            }
                        }
                    });
                }
            });
        });
    </script>
</div>
<?php include('elements/footer.php'); ?>