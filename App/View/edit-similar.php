<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3 class="modal-title">同様の言葉を編集します</h3>
            <form class="form-horizontal frm-mapdata" method="post" action="index.php?action=editSimilar&id=<?php echo $basic['id'];?>">
                <div class="row mb-10">
                    <div class="form-group col-md-1">
                        <label class="control-label">基本項目名</label>
                        <input type="text" name="basic_word" required class="form-control" value="<?php echo $basic['basic_word'];?>"/>
                    </div>
                    <?php for ($i = 0; $i < 10; $i++) : ?>
                        <div class="form-group col-md-1">
                            <label class="control-label">類似語<?php echo $i + 1;?></label>
                            <?php if (isset($arrSimilar[$i])) :?>
                                <input type="text" name="old_similar[<?php echo $arrSimilar[$i]['id'];?>]" class="form-control" value="<?php echo $arrSimilar[$i]['similar_word']; ?>"/>
                            <?php else : ?>
                                <input type="text" name="new_similar[]" class="form-control" value=""/>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="form-group">
                    <input type="hidden" name="type" value="edit"/>
                    <input type="hidden" name="id" value="<?php echo $data['basic']['id']; ?>"/>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </div>
</div>