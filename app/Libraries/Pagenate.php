<?
namespace App\Libraries;
/**
 * Pagination Class
 *
 * @package     MyNETS2
 * @subpackage  Libraries
 * @category    Pagination
 * @author      KUNIHARU Tsujioka 2009-07-10
 */
class Pagenate {
	var $language = "japanese";

    private $base_url           = ''; // リンクのベースになるURL
    public $total_rows         = ''; // トータルのレコード数
    private $per_page           = 10; // 1ページに表示する最大レコード数
    private $num_links          =  2; // リンクに表示させるページ番号数
    private $cur_page           =  1; // 初期ページ番号
    private $first_link         = '&lsaquo; 最初';
    private $next_link          = '&gt;';
    private $prev_link          = '&lt;';
    private $last_link          = '最後 &rsaquo;';
    private $uri_segment        = 3;  // URLセグメント内のページ番号の場所
    private $full_tag_open      = '';
    private $full_tag_close     = '';
    private $first_tag_open     = '';
    private $first_tag_close    = '&nbsp;';
    private $last_tag_open      = '&nbsp;';
    private $last_tag_close     = '';
    private $cur_tag_open       = '&nbsp;<span class="current">';  // 現在のページ番号での表示コントロール
    private $cur_tag_close      = '</span>';
    private $next_tag_open      = '&nbsp;';
    private $next_tag_close     = '&nbsp;';
    private $prev_tag_open      = '&nbsp;';
    private $prev_tag_close     = '';
    private $num_tag_open       = '&nbsp;';
    private $num_tag_close      = '';
    private $page_query_string  = FALSE; // クエリーパラメータでページ番号を渡すかどうか
    private $query_string_segment = 'per_page'; // クエリーパラメータでページ番号を渡す際のパラメータ名

    private $jumpping           = FALSE; // リンク表示でのページをジャンプさせるかスライドさせるか
    private $num_pages          = 1;

	function setLang($lang){
		$this->language = $lang;
	}


    /**
     * Constructor
     *
     * @access  public
     * @param   array   initialization parameters
     */
    function __construct($params = array())
    {
        if (count($params) > 0)
        {
            $this->initialize($params);
        }

        //log_message('debug', "Pagination Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Preferences
     *
     * @access  public
     * @param   array   initialization parameters
     * @return  void
     */
    function initialize($params = array())
    {
        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
    }
	function getPageData($data,$num,$page){
		$count = 0;
		$temp = array();
		//○件から
		$from = ($page-1) * $num;
		//○件まで
		$to = $page * $num-1;
		foreach($data as $val){
			if( $from <= $count && $to >= $count){
				$temp[] = $val;
			}
			$count++;
		}
		return $temp;
	}
	//現在のページを取得する
	function getPage(){
		if(empty($_GET["page"])){
			$page = 1;
		} else {
			$page = $_GET["page"];
		}
		return $page;

	}


    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @access  public
     * @return  string
     */
    function create_links()
    {
        if ($this->total_rows == 0 OR $this->per_page == 0)
        {
           return '';
        }

        // トータルのページ数
        $this->num_pages = ceil($this->total_rows / $this->per_page);

        if ($this->num_pages == 1)
        {
            return '';
        }

        // セグメントまたはパラメータからページ番号を取得する
        $CI =& get_instance();

        if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
        {
            if ($CI->input->get($this->query_string_segment) != 0)
            {
                $this->cur_page = $CI->input->get($this->query_string_segment);

                $this->cur_page = (int) $this->cur_page;
            }
        }
        else
        {
            if ($CI->uri->segment($this->uri_segment) != 0)
            {
                $this->cur_page = $CI->uri->segment($this->uri_segment);

                $this->cur_page = (int) $this->cur_page;
            }
        }

        $this->num_links = (int)$this->num_links;

        // セグメントまたはパラメータのページ番号が数字じゃない場合は0ページとする
        if ( ! is_numeric($this->cur_page) OR $this->cur_page == 0)
        {
            $this->cur_page = 1;
        }

        // トータルレコード数より現在のページ番号が大きい場合
        // セグメントの値をページ番号にする
        if ($this->cur_page > $this->num_pages)
        {
            $this->cur_page = $this->num_pages;
        }

        // base url の生成
        if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
        {
            $this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
        }
        else
        {
            $this->base_url = rtrim($this->base_url, '/') .'/';
        }

        $output = '';

        // 表示ページ数が0の場合、次へ、前へのみのリンクとする
        // 携帯の場合に使う？
        if ($this->num_links < 1)
        {
            // 最初へのリンクを生成
            $output .= $this->_start_link();

            // 前へのリンクを生成
            $output .= $this->_prev_link();

            // 次へのリンクを生成
            $output .= $this->_next_link();

            // 最後へのリンクを生成
            $output .= $this->_last_link();
        }
        else
        {
            //最初のリンク
            $output .= $this->_start_link();

            // 前へのリンクを生成
            $output .= $this->_prev_link();

            // ページ番号リンクの生成
            $output .= $this->_generate_page_link();

            // 次へのリンクを生成
            $output .= $this->_next_link();

            // 最期のリンクを作成
            $output .= $this->_last_link();
        }

        $output = preg_replace("#([^:])//+#", "\\1/", $output);

        $output = $this->full_tag_open.$output.$this->full_tag_close;

        return $output;
    }

    // 最初のリンクを生成
    private function _start_link()
    {
        if  ($this->cur_page > $this->num_links)
        {
            return $this->first_tag_open
                  .'<a href="'.$this->base_url.'">'.$this->first_link.'</a>'
                  .$this->first_tag_close;
        }
        return '';
    }

    // 最後のリンクを生成
    private function _last_link()
    {
        if (($this->cur_page + $this->num_links) < $this->num_pages)
        {
            return $this->last_tag_open
                  .'<a href="'.$this->base_url.$this->num_pages.'">'.$this->last_link.'</a>'
                  .$this->last_tag_close;
        }
        return '';
    }

    // 前へのリンクを生成
    private function _prev_link()
    {
        if  ($this->cur_page > 1)
        {
            $i = $this->cur_page - 1;
            if ($i == 0) $i = '';
            return $this->prev_tag_open
                  .'<a href="'.$this->base_url.$i.'">'.$this->prev_link.'</a>'
                  .$this->prev_tag_close;
        }
        return '';
    }

    // 次へのリンクを生成
    private function _next_link()
    {
        $tag = '';
        if ($this->cur_page < $this->num_pages)
        {
            $i = $this->cur_page + 1;
            $tag = $this->next_tag_open
                  .'<a href="'.$this->base_url.$i.'">'.$this->next_link.'</a>'
                  .$this->next_tag_close;
        }
        return $tag;
    }

    // ページ番号リンク生成時の最初の頁番号
    private function _start_page()
    {
        /*if ($this->jumpping)
        {
        }
        else
        {*/
            if (($this->cur_page - $this->num_links) > 0)
            {
                return $this->cur_page - $this->num_links;
            }
            else
            {
                return 1;
            }
        /*}*/
    }

    // ページ番号リンク生成時の最後の頁番号
    private function _end_page()
    {
        /*if ($this->jumpping)
        {

        }
        else
        {*/
            if (($this->cur_page + $this->num_links) < $this->num_pages)
            {
                return $this->cur_page + $this->num_links;
            }
            else
            {
                return $this->num_pages;
            }
        /*}*/
    }

    private function _generate_page_link()
    {
        $start = $this->_start_page();
        $end   = $this->_end_page();
        $tag   = '';

        // ページ番号リンクの生成
        for ($loop = $start; $loop <= $end; $loop++)
        {
            if ($this->cur_page == $loop)
            {
                // 現在のページの生成
                $tag .= $this->cur_tag_open.$loop.$this->cur_tag_close;
            }
            else
            {
                $tag .= $this->num_tag_open
                         . '<a href="'.$this->base_url.$loop.'">'.$loop.'</a>'
                         . $this->num_tag_close;
            }
        }
        return $tag;
    }

  function paginate($page, $total, $link, $limit = 10, $adjacents = 1) {
    $prev = $page - 1;
    $next = $page + 1;

    $param = "";
    $find = false;
    foreach(request()->getGet() as $key => $val){
      if($key != "page"){
        if($param == ""){
          $param .= "?".$key."=".$val;
        } else {
          $param .= "&".$key."=".$val;
        }
      } else{
        $find =true;
      }
    }
    if($param !=""){
      $param.= "&page=";
    } else {
      $param.= "?page=";
    }
    $lastpage = ceil($total / $limit);
    $lpm1 = $lastpage - 1;
    if ($lastpage > 1) {
      $out = '<div class="mt-3"><ul class="pagination  justify-content-center">';
      $out .= ($page > 1) ? "<li class='page-item'><a  class='page-link' href={$link}{$param}{$prev}>前へ</a></li>" : '';
      if ($lastpage < 7 + ($adjacents * 2)) {
        for ($counter = 1; $counter <= $lastpage; $counter++) {
          $out .= ($counter == $page) ? '<li class="active page-item"><a  class="page-link" href="'.$link.$param.$counter.'">' . $counter . '</a></li>' : '<li class="page-item"><a  class="page-link" href="'.$link.$param.$counter.'">'.$counter.'</a></li>';
        }
      } else if ($lastpage >= 7 + ($adjacents * 2)) {
        if ($page < 1 + ($adjacents * 3)) {
          for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
            $out .= ($counter == $page) ? '<li class="active page-item"><a class="page-link" href="'.$link.$param.$counter.'">' . $counter . '</a></li>' : '<li class="page-item"><a  class="page-link" href="'.$link.$param.$counter.'">'.$counter.'</a></li>';
          }
          $out .= '<li class="page-item disabled"><a  class="page-link" href="#" >･･･</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.$lpm1.'">'.$lpm1.'</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.$lastpage.'">'.$lastpage.'</a></li>';
        } else if ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.'1'.'">1</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.'2'.'">2</a></li>';
          $out .= '<li class="page-item disabled"><a class="page-link" href="#">･･･</a></li>';
          for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
            $out .= ($counter == $page) ? '<li  class="page-item active"><a class="page-link" href="'.$link.$param.$counter.'">' . $counter . '</a></li>' : '<li class="page-item"><a class="page-link" href="'.$link.$param.$counter.'">'.$counter.'</a></li>';
          }
          $out .= '<li class="page-item disabled"><a class="page-link" href="#">･･･</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.$lpm1.'">'.$lpm1.'</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.$lastpage.'">'.$lastpage.'</a></li>';
        } else {
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.'1'.'">1</a></li>';
          $out .= '<li class="page-item"><a class="page-link" href="'.$link.$param.'2'.'">2</a></li>';
          $out .= '<li class="page-item adisabledctive"><a class="page-link" href="#">･･･</a></li>';
          for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
            $out .= ($counter == $page) ? '<li class="page-item active"><a class="page-link" href="'.$link.$param.$counter.'">' . $counter . '</a></li>' : '<li class="page-itme"><a class="page-link" href="'.$link.$param.$counter.'">'.$counter.'</a></li>';
          }
        }
      }
      $out .= ($page < $counter - 1) ? '<li class="page-item" ><a class="page-link" href="'.$link.$param.$next.'">次へ</a></li>' : '';
      $out .= "</ul></div>";
    }
    if($total <=  $limit){
      $out = "";
    }
    return @$out;
  }
}
?>
