<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use App\Models\Articles;
use App\Models\Categories;
use Config\Database;
use Config\Services;

class Kb
{
    private $allowed_cats;
    private $db;
    private $categoryModel;
    private $articlesModel;
    public function __construct()
    {
        $this->db = Database::connect();
        $this->categoryModel = new Categories();
        $this->articlesModel = new Articles();
        $this->allowed_cats = $this->publicCatList();
    }
    /*
     * *****************************************************************
     * Staff
     * *****************************************************************
     */


    public function kb_article_selector($category_id=0, $level=0)
    {
        $html = '';
        $padding_article = $level*15;
        if($articles = $this->getArticles($category_id, false)){
            foreach ($articles as $article){
                $html .= '<option value="'.$article->id.'">'.str_repeat('&nbsp;',$level*6).$article->title.'</option>';
            }
        }
        if($sub_categories = $this->getCategories($category_id, false)){
            foreach ($sub_categories as $sub_category){
                $total_articles = $this->countArticles($sub_category->id, false);
                if($total_articles > 0){
                    $html .= '<optgroup label="'.str_repeat('&nbsp;',$level*3).$sub_category->name.'"></optgroup>';
                    $html .= $this->kb_article_selector($sub_category->id, $level+1);
                }

            }
            return $html;
        }
        if($html == ''){
            return null;
        }
        return $html;
    }

    /*
     * ---------------------------------------------
     * Categories
     * ---------------------------------------------
     */
    private function publicCatList($parent=0, $public=true)
    {
        $q = $this->categoryModel->where('parent', $parent)
            ->where('public', 1)
            ->orderBy('position', 'asc')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $list = array();
        $result = $q->getResult();
        $q->freeResult();
        foreach ($result as $item){
            $list[] = $item->id;
            if($subcat = $this->publicCatList($item->id, $public)){
                $list = array_merge($list, $subcat);
            }
        }
        return $list;
    }

    public function publicCategories()
    {

        return $this->allowed_cats;
    }

    public function getCategory($id, $public=true)
    {
        if($public){
            $this->categoryModel->where('public', 1);
        }
        if($category = $this->categoryModel->find($id)){
            return $category;
        }
        return null;
    }

    public function getParents($parent_id)
    {
        if(!$category = $this->categoryModel->find($parent_id)){
            return null;
        }
        $list[] = $category;
        if($sub_parent = $this->getParents($category->parent)){
            $list = array_merge($list, $sub_parent);
        }
        return $list;
    }

    public function getChildren($parent=0, $public=true, $level=1, $prepend='')
    {
        if($public){
            $this->categoryModel->where('public', 1);
        }
        $q = $this->categoryModel->where('parent', $parent)
            ->orderBy('position', $parent)
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $list = array();
        $result = $q->getResult();
        $q->freeResult();;
        foreach ($result as $item){
            $item->name = str_repeat($prepend, $level).$item->name;
            $list[] = $item;
            if($subcat = $this->getChildren($item->id, $public, $level+1, $prepend)){
                $list = array_merge($list, $subcat);
            }
        }
        return $list;
    }

    public function getCategories($parent=0, $public=true)
    {
        if($public){
            $this->categoryModel->where('public', 1);
        }
        $q = $this->categoryModel->where('parent', $parent)
            ->orderBy('position')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();;
        return $r;
    }

    public function insertCategory($name, $parent=0, $public=1)
    {
        $q = $this->categoryModel->select('position')
            ->where('parent', $parent)
            ->orderBy('position', 'desc')
            ->get(1);
        if($q->resultID->num_rows == 0){
            $position = 1;
        }else{
            $r = $q->getRow();
            $position = $r->position+1;
        }
        $this->categoryModel->protect(false);
        $this->categoryModel->insert([
            'name' => esc($name),
            'position' => $position,
            'parent' => $parent,
            'public' => $public
        ]);
        $this->categoryModel->protect(true);
        return $this->categoryModel->getInsertID();
    }

    public function updateCategory($data, $id)
    {
        $this->categoryModel->protect(false);
        $this->categoryModel->update($id, $data);
        $this->categoryModel->protect(true);
    }

    public function moveCategory($category_id, $up=true)
    {
        $category = $this->getCategory($category_id, false);
        if($up){
            $q = $this->categoryModel->select('id, position')
                ->where('parent', $category->parent)
                ->where('position<', $category->position)
                ->orderBy('position', 'desc')
                ->get(1);
        }else{
            $q = $this->categoryModel->select('id, position')
                ->where('parent', $category->parent)
                ->where('position>', $category->position)
                ->orderBy('position', 'asc')
                ->get(1);

        }
        if($q->resultID->num_rows == 0){
            return false;
        }
        $other = $q->getRow();
        $this->updateCategory([
            'position' => $other->position
        ], $category->id);
        $this->updateCategory([
            'position' => $category->position
        ], $other->id);
        return true;
    }

    public function moveUpOrDownLink($category_id, $parent)
    {
        $q = $this->categoryModel->select('id, position')
            ->where('parent', $parent)
            ->orderBy('position','desc')
            ->get(1);
        $last = $q->getRow();
        $q = $this->categoryModel->select('id, position')
            ->where('parent', $parent)
            ->orderBy('position','asc')
            ->get(1);
        $first = $q->getRow();
        if($last->id == $first->id)
        {
            return null;
        }
        $html = '';
        if($last->id != $category_id){
            $html .= '<a class="btn btn-outline-primary" href="'.current_url().'?action=move_down&id='.$category_id.'"><i class="fa fa-angle-down"></i></a>';
        }
        if($first->id != $category_id){
            $html .= '<a class="btn btn-outline-primary" href="'.current_url().'?action=move_up&id='.$category_id.'"><i class="fa fa-angle-up"></i></a>';
        }
        return $html;
    }

    public function removeCategory($category_id)
    {
        if($articles = $this->getArticles($category_id, false)){
            foreach ($articles as $article)
            {
                $this->removeArticle($article->id);
            }
        }
        if($children = $this->getChildren($category_id, false)){
            foreach ($children as $item){
                $this->removeCategory($item->id);
            }
        }
        $this->categoryModel->delete($category_id);
    }



    /*
     * ---------------------------------------------------
     * Articles
     * ----------------------------------------------------
     */
    public function countArticles($cat, $public=true)
    {
        if($categories = $this->getChildren($cat, $public)){
            foreach ($categories as $item){
                $this->articlesModel->orWhere('category', $item->id);
            }
        }

        return $this->articlesModel->orWhere('category', $cat)
            ->countAllResults();
    }

    public function totalArticlesInCat($cat, $public=true)
    {
        return $this->articlesModel->where('category', $cat)
            ->countAllResults();
    }

    public function articlesUnderCategory($cat, $pubic=true)
    {
        if($categories = $this->getChildren($cat, $pubic)){
            foreach ($categories as $item){
                $this->articlesModel->orWhere('category', $item->id);
            }
        }
        $q = $this->articlesModel->orWhere('category', $cat)
            ->get(Services::settings()->config('kb_articles'));

        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getArticles($cat_id, $public=true)
    {
        if($public){
            $this->articlesModel->where('public', 1);
        }
        $q = $this->articlesModel->where('category', $cat_id)
            ->orderBy('date','desc')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }


    public function popularArticles($public = 1)
    {
        if($public){
            $this->articlesModel->groupStart();
            if($list = $this->publicCategories()){
                foreach ($list as $cat_id){
                    $this->articlesModel->orWhere('category', $cat_id);
                }
            }
            $this->articlesModel->orWhere('category', 0)
                ->groupEnd()
                ->where('public', 1);
        }

        $q = $this->articlesModel->select('id, title')
            ->orderBy('views','desc')
            ->get(Services::settings()->config('kb_popular'));

        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function newestArticles($public=1)
    {

        if($public){
            $this->articlesModel->groupStart();
            if($list = $this->publicCategories()){
                foreach ($list as $cat_id){
                    $this->articlesModel->orWhere('category', $cat_id);
                }
            }
            $this->articlesModel->orWhere('category', 0)
                ->groupEnd()
                ->where('public', 1);
        }

        $q = $this->articlesModel->select('id, title')
            ->orderBy('date','desc')
            ->get(Services::settings()->config('kb_latest'));
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getArticle($article_id, $public=true)
    {
        if($public)
        {
            $this->articlesModel->where('public', 1);
        }
        if($article = $this->articlesModel->find($article_id)){
            return $article;
        }
        return null;
    }

    public function addView($article_id)
    {
        $this->articlesModel->protect(false);
        $this->articlesModel->set('views','views+1', false)
            ->update($article_id);
        $this->articlesModel->protect(true);
    }

    public function searchArticles($word, $public=true)
    {
        if($public){
            $this->articlesModel->groupStart();
            if($list = $this->publicCategories()){
                foreach ($list as $cat_id){
                    $this->articlesModel->orWhere('category', $cat_id);
                }
            }
            $this->articlesModel->orWhere('category', 0)
                ->groupEnd()
                ->where('public', 1);
        }

        $q = $this->articlesModel->groupStart()
            ->like('content', $word)
            ->orLike('title', $word)
            ->groupEnd()
            ->orderBy('date','desc')
            ->get();

        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function removeArticle($id)
    {
        $attachments = Services::attachments();
        $attachments->deleteFiles([
            'article_id' => $id
        ]);
        $this->articlesModel->delete($id);
    }

    public function addArticle($title, $content, $category, $public)
    {
        $this->articlesModel->protect(false);
        $this->articlesModel->insert([
            'title' => esc($title),
            'content' => $content,
            'category' => $category,
            'public' => $public,
            'staff_id' => Services::staff()->getData('id'),
            'date' => time()
        ]);
        $this->articlesModel->protect(true);
    }

    public function updateArticle($article_id, $title, $content, $category, $public)
    {
        $this->articlesModel->protect(false);
        $this->articlesModel->update($article_id, [
            'title' => esc($title),
            'content' => $content,
            'category' => $category,
            'public' => $public,
            'staff_id' => Services::staff()->getData('id'),
            'last_update' => time()
        ]);
        $this->articlesModel->protect(true);
    }

    public function articlesPagination($category_id=0)
    {
        $settings = Services::settings();
        $db = Database::connect();
        if($category_id > 0){
            $this->articlesModel->where('articles.category', $category_id);
        }
        $result = $this->articlesModel->select('articles.*, c.name as category_name, (SELECT fullname FROM '.$db->prefixTable('staff').' WHERE id=staff_id) as author')
            ->orderBy('articles.last_update','desc')
            ->orderBy('articles.date','desc')
            ->join('kb_category as c','c.id=articles.category')
            ->paginate($settings->config('page_size'));
        return [
            'result' => $result,
            'pager' => $this->articlesModel->pager
        ];
    }

}