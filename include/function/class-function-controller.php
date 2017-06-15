<?php
    require_once('class-base-controller.php');
    require_once(ABSPATH .'/include/function/class-story-controller.php');
    require_once(ABSPATH .'/include/function/loader/class_load_function.php');
    require_once(ABSPATH .'/story/controller/class-chapter-controller.php');
    require_once(ABSPATH .'/story/model/class-uri-story.php');
    
    class FunctionController extends BaseController {
        private $host;
        private $function;
        private $category;
        private $story;
        private $chapter;
        private $option;
        
        private $size;
        
        private $obj_uri_model;
        private $obj_story_controller;
        
        public function __construct() {
            $this->obj_uri_model = UriStoryModel::getInstance();
            $this->obj_story_controller = StoryController::getInstance();
        }
        
        public function process_manga_link($permalinks) {
            // Check and explode request and parameter
            $check_category_or_story = parent::get_param_url($permalinks[3]);
            
            // Truoc dau '?' có ký tu nào không, neu có (khác null) thì vào if
            if ($check_category_or_story != null) {
                // request dau tien co chua chuoi la index hoac index.php
                if ($check_category_or_story == 'index' || $check_category_or_story == 'index.php'){
                    //self::load_view_category($category_id);
                    $this->obj_story_controller->redirect_story($this->obj_uri_model);
                    exit;
                } else {
                    if ($this->size > 4) {
                        self::process_chapter_link($permalinks);
                        exit;
                    }
                    self::load_manga();
                    exit;
                }
                
            } else {
                //self::load_view_category($category_id);
                $this->obj_story_controller->redirect_story($this->obj_uri_model);
                exit;
            }
        }
        
        private function process_chapter_link($permalinks) {
            $check_story_or_chapter = parent::get_param_url($permalinks[4]);
            
            // Tru?c d?u '?' có ký t? nào không n?u có (khác null) thì vào if
            if ($check_story_or_chapter != null) {
                if ($check_story_or_chapter == 'index' || $check_story_or_chapter == 'index.php'){
                    self::load_manga();
                    exit;
                } else {
                    
                    self::load_chapter();
                    exit;
                }
            } else {
                self::load_manga();
                exit;
            }
        }
        
        public function load_manga() {
            $load_manga = MangaController::getInstance();
            $load_manga->redirect_manga();
        }
        
        public function load_chapter() {
            $load_chapter = ChapterController::getInstance();
            $load_chapter->redirect();
        }
        
        public function redirect_function($permalinks) {

            //cut the first index of array 
            //array_shift($permalinks);
            
            // host at index 0
            // functrion at index 1
            // category at index 2
            
            $this->host = $permalinks[0];
            $this->function = $permalinks[1];

            // If function is index, redirect to index page of host
            if ($this->function == 'index') {
                parent::load_index($this->host);
            } else {
                $this->function = parent::get_param_url($this->function);
                
                /**
                 * get information of function slug FROM db -> function TABlE
                 * @since 2017-06-10
                 */
                $obj_load_function = LoadFunction::getInstance();
                $obj_result_function = $obj_load_function->get_function_by_slug($this->function);
                
                /**
                 * switch function request
                 * @since
                 * @version 1.0 
                 */
                switch($obj_result_function['function_slug']) {
                    // case request of story
                    case 's' :
                    
                        //check category request
                        if (isset($permalinks[2])) {
                            $this->category = parent::get_param_url($permalinks[2]);
                        } else {
                            $this->category = null;
                        }
                        
                        // initial object uri request
                        $this->obj_uri_model->setHost($this->host);
                        $this->obj_uri_model->setFunction($this->function);
                        $this->obj_uri_model->setCategory($this->category);
                        
                        $this->size = count($permalinks);
                        if ($this->size > 3) {
                            $this->story = parent::get_param_url($permalinks[3]);
                            self::process_manga_link($permalinks);
                        }

                        $this->obj_story_controller->redirect_story($this->obj_uri_model);

                        break;
                        
                    default :
                        parent::load_404();
                        break;
                } //end switch
            } // end if else
        } // end function
    } // end class
?>