<?php
	require_once(ABSPATH .'/include/function/library/database-pdo.php');
    
    /**
     * LoadChapter
     * 
     * @package   
     * @author j1nz
     * @copyright gactruyen
     * @version 2017
     * @access public
     */
    class LoadChapter {
        private static $_instance;
        
        /**
         * LoadChapter::__construct()
         * 
         * @since 2017-05-30
         * @return
         */
        public function __construct() {
            
        }
        
        /**
         * LoadChapter::getInstance()
         * 
         * @since 2017-05-30
         * @return
         */
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        /**
         * LoadChapter::check_chapter_by_slug()
         * 
         * @since 2017-05-30
         * @param mixed $story_id
         * @param mixed $slug
         * @return
         */
        function check_chapter_by_slug($story_id, $slug) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'SELECT chapter_id FROM chapter WHERE slug = :link AND story_id = :story_id';
            $__paremeter = array(
                'link' => $slug,
                'story_id' => $story_id
            );
            
            $__result = $db_pdo->q_item_with_param($__sql, $__paremeter);
            
            if ($__result == true) {
                return true;
            }
            
            return false;
        }
        
        function get_chapter_id_by_slug($slug, $story_id) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'select chapter_id, story_id from chapter where story_id = :storyId AND slug = :slug';
            $__paremeter = array(
                'storyId' => $story_id,
                'slug' => $slug
            );
            
            $__chapter = $db_pdo->q_item_with_param($__sql, $__paremeter);
            
            return $__chapter;
        }
        
        /**
         * LoadChapter::get_chapter_by_slug()
         * 
         * @since 2017-05-30
         * @param mixed $story_id
         * @param mixed $slug
         * @version 1.1
         * @return
         */
        function get_chapter_by_id($story_id, $chapter_id) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'select chapter_id, story_id, chapter_name, chapter_number, slug, content from chapter where story_id = :storyId AND chapter_id = :chapterId';
            $__paremeter = array(
                'storyId' => $story_id,
                'chapterId' => $chapter_id
            );
            
            $__chapter = $db_pdo->q_item_with_param($__sql, $__paremeter);
            
            return $__chapter;
        }
        
        
        function get_content_chapter_by_id($story_id, $index_chap) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'select content from chapter where story_id = :storyId AND index_chap = :index_chap';
            $__paremeter = array(
                'storyId' => $story_id,
                'index_chap' => $index_chap
            );
            
            $__chapter = $db_pdo->q_item_with_param($__sql, $__paremeter);
            
            return $__chapter;
        }
        
        /**
         * LoadChapter::get_total_chapter_of_story()
         * 
         * @since 2017-05-30
         * @param mixed $story_id
         * @return
         */
        public function get_total_chapter_of_story($story_id) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'SELECT count(chapter_id) as total FROM chapter WHERE story_id = :story_id';
            
            $__paremeter = array(
                'story_id' => $story_id
                
            );
            
            $__result = $db_pdo->q_all_with_param($__sql, $__paremeter);
            
            foreach ($__result as $row) {
                return $row['total'];
            }
            
            return 1;
        }
        
        /**
         * LoadChapter::get_chapter_of_story()
         * 
         * @since 2017-05-30
         * @param mixed $story_id
         * @return
         */
        function get_chapter_of_story($story_id) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'select chapter_id, chapter_number, chapter_name, slug from chapter where story_id = :id order by index_chap';
            $__paremeter = array(
                'id' => $story_id
            );
            
            $__result = $db_pdo->q_all_with_param($__sql, $__paremeter);
            
            return $__result;
        }
        
        /**
         * LoadChapter::get_content_of_story()
         * 
         * @since 2017-05-30
         * @param mixed $story_id
         * @param mixed $chapter_id
         * @return
         */
        function get_content_of_story($story_id, $chapter_id) {
            $db_pdo = PdoConnection::getInstance();
            $db_pdo->get_conect_pdo();
            
            $__sql = 'select chapter_id, story_id, chapter_name, slug, content from chapter where story_id = :story_id and chapter_id = :chapter_id';
            $__paremeter = array(
                'story_id' => $story_id,
                'chapter_id' => $chapter_id
            );
            
            $__result = $db_pdo->q_all_with_param($__sql, $__paremeter);
            
            return $__result;
        }
    }
?>