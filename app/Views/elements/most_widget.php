<?=$this->element("page_title",["title"=>"よく読まれているヘルプ記事"])?>
<?foreach($widgets as $widget){?>
    <div class="mt-2"><a  target="_blank" href="<?=esc($widget->notion_url)?>" style="font-weight:normal;"><?=esc($widget->name)?><i class="ml-1 fal fa-external-link"></i></a></div>
<?}?>
<hr>
<a href="<?=esc($form->notion_url)?>" target="_blank">ヘルプセンターへ<i class="ml-1 fal fa-external-link"></i></a>