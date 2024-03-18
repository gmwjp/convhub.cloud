<span class="mr-1">
    <a href="/tickets/attach/output/<?=esc($section)?>/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" target="_blank">
        <div class="card float-left mr-1" style="width: 9rem;" href="/tickets/attach/output/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>"  data-toggle="tooltip" data-placement="top" title="<?=esc($file->file_name)?>">
            <img src="/tickets/attach/output/<?=esc($section)?>/<?=esc($this->library("Crypt2")->encode($id))?>/<?=esc($this->library("Crypt2")->encode($no))?>" class="card-img-top" style="max-height:80px;">
        </div>
    </a>
</span>