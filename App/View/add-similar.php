<div id="mapDataModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body align-center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="modal-title">地図データを追加します。</h3>
                            <form class="form-horizontal frm-mapdata" method="post" action="index.php?action=addSimilarPost">
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