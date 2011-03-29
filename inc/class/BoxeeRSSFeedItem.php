<?php
class BoxeeRSSFeedItem {
    public $title, $link, $description, $thumbnail;

    function __construct($title = "", $desc = "", $link = "", $thumb = "")
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $desc;
        $this->thumbnail = $thumb;
    }

    static function comparison($a, $b)
    {
	if(strcmp($a->title, $b->title) == 0)
		return strcmp($a->description, $b->description);
        return strcmp($a->title, $b->title);
    }
}
?>
