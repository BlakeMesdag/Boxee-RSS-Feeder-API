<?php
class BoxeeRSSFeeder {
    private $feedUrl, $feedTitle, $feedDescription, $feedLink, $feedImage, $ttl;
    private $feedItemList;

    function __construct($title, $description = "", $link = "", $image = "", $ttl = 2)
    {
        $this->feedTitle = $title;
        $this->feedDescription = $description;
        $this->feedLink = $link;
        $this->feedImage = $image;
        $this->ttl = $ttl;
    }

    private function outputHeader()
    {
        echo '<?xml version="1.0"?>';
	echo '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:boxee="http://boxee.tv/spec/rss/" xmlns:dcterms="http://purl.org/dc/terms/">';
	echo '<channel>';
	echo "<title>$this->feedTitle</title>";
	echo "<description>$this->feedDescription</description>";
        echo "<link>$this->feedLink</link>";
        echo "<image>$this->feedImage</image>";
	echo "<ttl>$this->ttl</ttl>";
    }

    function loadFromDir($dir, $ext)
    {
        $find = `find $dir -iname "*.$ext"`;
	$items = explode("\n", $find);

	for($i = 0; $i < count($items) -1; $i++)
	{
            $test = $items[$i];
            $fileInfo = pathinfo($test);
            $path = explode("/", $fileInfo['dirname']);
            $folderName = $path[count($path) - 1] != "UNRAR" && $path[count($path) - 1] != "Sample" ? $path[count($path) - 1]:  $path[count($path) - 2];
            $filename = $fileInfo['basename'];
            $fullPath = $fileInfo['dirname'];

            //file_exists("$fullPath/$filename.thumb.jpg") ? null : `ffmpeg  -itsoffset -4  -i $fullPath/$filename -vcodec mjpeg -vframes 1 -an -f rawvideo -s 320x240 $fullPath/$filename.thumb.jpg` ;

            $link = $pathToGetFileScript . "?passkey=" . $_GET['passkey'] . "%26file=/TV/";

            //$width = trim(`exiftool $test | egrep "Image Width.*" | grep -o "[0-9]*"`);
            //$height = trim(`exiftool $test | egrep "Image Height.*" | grep -o "[0-9]*"`);

            for( $x = 0; $x < (count($path) - 6); $x++ )
            {
                    $link = $link . urlencode($path[$x + 6] . "/");
            }
            $link = $link . urlencode($filename);

            $this->feedItemList[$i] = new BoxeeRSSFeedItem($folderName, $fileInfo['filename'], $link, "$link.thumb.jpg");
	}
    }

    private function outputItem($item)
    {
        echo "<item>";
        echo "<title>" . $item->title . "</title>";
        echo "<description>" . $item->description . "</description>";
        //echo "<media:content url=\"$link\" type=\"video/x-msvideo\" height=\"$height\" width=\"$width\" lang=\"en-us\" />";
        echo "<link>$item->link</link>";
        //echo "<image>$item->thumbnail</image>";
        //echo "<media:thumbnail url=\"$item->thumbnail\"/>";
        echo "</item>";
    }

    private function outputFooter()
    {
        echo '</channel>';
	echo '</rss>';
    }

    function outputRss()
    {
        $this->outputHeader();

        usort($this->feedItemList, array("BoxeeRSSFeedItem", "comparison"));

        for($i = 0; $i < count($this->feedItemList); $i++)
        {
            $item = $this->feedItemList[$i];
            $this->outputItem($item);
        }

        $this->outputFooter();
    }
}
?>
