<?=$this->element("page_title")?>
<div class="alert">
    ログインまたは会員登録を行ってください。
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <form method="post" action="/users/signup">
            <div class="card">
                <div class="card-header">
                    <h4>新規会員登録</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>メールアドレス</label>
                        <input type="text" name="mail" class="form-control" value="<?=request()->getPost("mail")?>">
                        <?=err($errors->getError("mail"))?>
                        <div class="alert">
                            「<?=esc(env("smtp.from"))?>」からのメールを受信できるアドレスを使うか、設定で着信を許可してください。
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center"><button type="submit" class="btn btn-dark">認証メール送信する</button></div>
                        <input type="hidden" name="execute" value="on">
                    </div>
                </div>
            </div>
            <?=csrf()?>
        </form>
    </div>
    <div class="col-md-6 col-sm-12">
        <form method="post" action="/users/login?from=<?=esc(request()->getGet("fromurl"))?>">
            <div class="card">
                <div class="card-header">
                    <h4>ログイン（既に会員の方）</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>メールアドレス</label>
                        <input type="text" name="mail2" class="form-control" value="<?=request()->getPost("mail2")?>">
                        <?=err($errors->getError("mail2"))?>
                        <?if(!empty($login_error)){?><?=err("メールアドレスまたはパスワードが違います")?><?}?>
                    </div>
                    <div class="form-group">
                        <label>パスワード</label>
                        <input type="password" name="password" class="form-control" value="<?=request()->getPost("password")?>">
                        <?=err($errors->getError("password"))?>
                    </div>
                    <div class="form-group">
                        <div class="text-center"><button type="submit" class="btn btn-dark ">ログイン</button></div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-lin href" data-action="/users/resend">パスワードを忘れた方</button>
                        <input type="hidden" name="execute" value="on">
                    </div>
                </div>
            </div>
            <?=csrf()?>
        </form>
    </div>
</div>
