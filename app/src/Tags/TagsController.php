<?php

namespace Anax\Tags;

/**
 * To view tags
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;




    /**
     * Find all tags and manipulate them into a string
     *
     * @return string all tags wrapped in quotations marks
     */
    public function findAction()
    {
        $all = $this->tags->findTags();

        $arr_tags = null;
        foreach ($all as $tag) {
            $arr_tags[] = (array) $tag;
        }

        $arr_tags_ = null;
        foreach ($arr_tags as $tag => $value) {
            $arr_tags_[] = $value['tag'];
        }

        $str_tags = implode(',', $arr_tags_);
        $tags = str_replace(",", "' ,'", $str_tags);

        return $tags;
    }



    /**
     * Show all tags.
     *
     * @return void
     */
    public function showAction()
    {
        $tags = $this->tags->returnTagsAsObject();

        $this->views->add('tags/all', ['title' => 'All tags', 'tags' => $tags]);
    }



    /**
     * Fetch all questions and manipulate the data to count tags
     *
     * @return void
     */
    public function getTopTagsAction()
    {
        $data = $this->questions->findQuestions();

        foreach ($data as $key => $value) {
            $tags[] = explode(',', $value->tags);
        }

        foreach ($tags as $key => $value) {
            foreach ($value as $key) {
                $vals[] = $key;
            }
        }

        $all = array_count_values($vals);
        arsort($all);

        $this->views->add('tags/list', ['title' => 'Top tags', 'items' => $all], 'box-2');
    }
}
