<span class="mr-1">
    <?if(substr($file->mime,0,6) == "image/"){?>
        <a href="/tickets/attach/output/<?=esc($section)?>/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" target="_blank">
            <div class="card float-left mr-1" style="width: 9rem;" href="/tickets/attach/output/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" target="_blank">
                <img src="/tickets/attach/output/<?=esc($section)?>/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" class="card-img-top" style="max-height:80px;">
                <div class="card-img-overlay">
                    <p class="card-text"><?=esc($file->name)?></p>
                </div>
            </div>
        </a>
    <?} else {?>
        <a href="/tickets/attach/output/<?=esc($section)?>/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" target="_blank" class="btn btn-light btn-sm">
            <?=esc($file->name)?>
        </a>
    <?}?>
    </a>
</span>