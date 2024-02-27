<form method="post" action="/widgets/edit/<?=esc($section)?>/<?=esc($widget->id)?>">
    <?=$this->element("forms/widget",["section"=>$section])?>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">保存する</button>
        </div>
        <div class="mt-2">
            <a href="/widgets/index" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
</form>