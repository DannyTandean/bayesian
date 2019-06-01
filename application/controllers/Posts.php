<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // Load post model
        $this->load->model('post');
        
        // Per page limit
        $this->perPage = 4;
    }
    
    public function index(){
        $data = array();
        
        // Get posts data from the database
        $conditions['order_by'] = "id DESC";
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->post->getRows($conditions);
        
        // Pass the post data to view
        $this->load->view('posts/index', $data);
    }
    
    function loadMoreData(){
        $conditions = array();
        
        // Get last post ID
        $lastID = $this->input->post('id');
        
        // Get post rows num
        $conditions['where'] = array('id <'=>$lastID);
        $conditions['returnType'] = 'count';
        $data['postNum'] = $this->post->getRows($conditions);
        
        // Get posts data from the database
        $conditions['returnType'] = '';
        $conditions['order_by'] = "id DESC";
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->post->getRows($conditions);
        
        $data['postLimit'] = $this->perPage;
        
        // Pass data to view
        $this->load->view('posts/load-more-data', $data, false);
    }
}