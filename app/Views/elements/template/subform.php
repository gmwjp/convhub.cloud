<div id="template" class="none">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <div class="item-title">
                    項目名
                </div>
                <div>
                    <input type="text" name="names[]" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="item-title">
                    説明文
                </div>
                <div>
                    <input type="text" name="abouts[]" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="item-title">
                    入力形式
                </div>
                <div>
                    <select class="form-control custom-select select" name="select[]">
                        <option value="textbox">１行テキスト</option>
                        <option value="textarea">複数行テキスト</option>
                        <option value="radio">単一選択肢</option>
                        <option value="checkbox">複数選択肢</option>
                    </select>
                    <div class="mt-2 none bodies">
                        <textarea class="form-control" rows="4" placeholder="選択肢を改行区切りで入力" name="bodies[]"></textarea>
                    </div>
                    <div class="clearfix mt-2">
                        <div class="float-left">
                            <input type="checkbox" name="require[]" value="1">&nbsp;必須入力
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-light up_button">上へ</button>
                            <button type="button" class="btn btn-sm btn-light down_button">下へ</button>
                            <button type="button" class="btn btn-sm btn-light del_button">削除</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>