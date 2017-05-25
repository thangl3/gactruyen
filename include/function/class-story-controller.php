<?php
    require_once('class-base-controller.php');
    
    class StoryController extends BaseController {
        protected $function;
        protected $category;
        protected $story;
        protected $chapter;
        protected $option;
        

        protected $loader;
        
        protected $uri;
        
        public function __construct() {
            require_once(ABSPATH .'/include/function/loader/class-load-category.php');
            
            $this->loader = LoadCategory::getInstance();
        }
        
        public function redirect($permalinks) {
            //un-comment when use at localhost, if up to host don't need nesesary'
            //cut the first index of array 
            //array_shift($permalinks);
            
            // host at index 0
            // functrion at index 1
            // category at index 2

            $this->host = $permalinks[0];
            $this->function = $permalinks[1];
            $this->category = $permalinks[2];
            
            if ($this->category == 'index' || $this->category == null) {
                self::index();
                
            } else {
                // Lấy danh sách link của category
                $list_category_slug = $this->loader->load_category_slug();
                
                /**
                 * Kiểm tra url của người dùng có tồn tại thể loại đó không
                 * 
                 */
                while ($item = $list_category_slug->fetch() ) {
                    if ($this->category == $item['slug']) {
                        
                        // Nếu có category người dùng yêu cầu, thì sẽ gọi hàm load category và exit chương trình
                        self::load_category($item['category_id'], $permalinks);
                        exit;
                    }
                }
                
                //Nếu không có thì sẽ gọi hàm lỗi 404
                parent::load_404();
            }
               
        }
        
        public function get_uri($permalinks) {
            
        }
        
        public function index() {
            //ob_end_clean();
            header('HTTP/1.0 200 OK');
            
            $list_category = $this->loader->load_category_all();
            
            include_once(ABSPATH .'/story/story.php');

        }
        
        public function load_category($category_id, $permalinks) {
            $size = count($permalinks);
            
            if ($size > 3) {
                
                if ($permalinks[3] != null || $permalinks[3] != '') {
                    echo '<script>alert(' .'"load manga"'. ')</script>';
                    require_once(ABSPATH .'/story/controller/class-manga-controller.php');
                    
                    $obj_manga = new MangaController();
                    
                    $obj_manga->redirect($permalinks);
                } else {
                    require_once(ABSPATH .'/story/controller/class-category-controller.php');
            
                    $obj_category = new CategoryController($this->host, $this->function, $category_id);
                    
                    $obj_category->view_category();
                }
            } else {
                require_once(ABSPATH .'/story/controller/class-category-controller.php');
            
                $obj_category = new CategoryController($this->host, $this->function, $category_id);
                
                $obj_category->view_category();
            }

        }
    }
?>