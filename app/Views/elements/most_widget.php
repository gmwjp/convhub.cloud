<?=$this->element("page_title",["title"=>"よく読まれているヘルプ記事"])?>
<?foreach($widgets as $widget){?>
    <div class="mt-2"><a  target="_blank" href="<?=esc($widget->notion_public_url)?>" style="font-weight:normal;"><?=esc($widget->name)?><i class="ml-1 fal fa-external-link"></i></a></div>
<?}?>
<hr>
<div class="text-center">
    <a href="<?=esc($form->notion_url)?>" target="_blank" class="btn btn-light">ヘルプセンターへ<i class="ml-1 fal fa-external-link"></i></a>
</div>
