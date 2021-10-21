<?php

/**
 * Change relative path to absolute
 * @param $relative_path
 * @param $base
 * @return string
 */
function path_to_absolute($relative_path, $base) {
    $path = "";
    /* return if there is already absolute URL */
    if (parse_url($relative_path, PHP_URL_SCHEME) != '') return $relative_path;

    /* queries and anchors */
    if ($relative_path[0]=='#' || $relative_path[0]=='?') return $base.$relative_path;

    /* parsing the base URL and convert to local variables: $scheme, $host, $path */
    extract(parse_url($base));

    /* removing non-directory element from path */
    $path = preg_replace('#/[^/]*$#', '', $path);

    /* destroy path if relative url points to root */
    if ($relative_path[0] == '/') $path = '';

    /* dirty absolute URL */
    $abs = "$host$path/$relative_path";

    $replace = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n = 1; $n > 0; $abs = preg_replace($replace, '/', $abs, -1, $n)) {}

    /* Final absolute URL */
    return $scheme.'://'.$abs;
}

/**
 * Fetch average word count of specific web page
 * @param $html
 * @return string
 */
function get_average_word_count($html) {
    $word_count = $word_length = 0;
    $words = preg_split('/\s+/',$html,-1,PREG_SPLIT_NO_EMPTY);
    foreach ($words as $word) {
        $word_count++;
        $word_length += strlen($word);
    }

    return sprintf("The average word length over %d words is %.02f characters.",
        $word_count,
        $word_length/$word_count);
}

/**
 * Fetch title of the specific web page
 * @param $doc
 * @return array
 */
function get_title_and_length($doc) {
    $title = $doc->getElementsByTagName('title')->item(0)->textContent;
    return [
        'title' => $title,
        'length' => str_word_count($title),
    ];
}

/**
 * Fetch how many unique images exists on specific web page
 * @param $doc
 * @return int
 */
function get_image_count($doc) {
    $images = $doc->getElementsByTagName('img');
    return count($images);
}

/**
 * Index function
 * @return array
 */
function main_function() {
    $internalLinks = $externalLinks = $totalLinks = 0;


    // Created array of pages we want to crawl/scrap (In this case, we are crawling 3 pages)
    $pages = [
        0 => "https://agencyanalytics.com/feature/automated-marketing-reports",
        1 => "https://agencyanalytics.com/feature/instagram-dashboard-2",
        2 => "https://agencyanalytics.com/feature/ecommerce-reporting-software",
    ];

    $crawledPagesCount =  count($pages);
    echo "<h3 class='tableHeading'>Number of pages crawled : $crawledPagesCount </h3>  <br>";

    $pageNum = 0;
    foreach ($pages as $key => $page ) {

        // Get content of page
        $html = file_get_contents($page);
        $doc=new DOMDocument();

        // Load html
        @$doc->loadHTML($html);

        // Get all the links on website
        $xml=simplexml_import_dom($doc); // just to make xpath more simple
        $strings=$xml->xpath('//a');

        // Looping through all the internal and external links
        foreach ($strings as $string) {
            $attrs = $string->attributes();

            $pathToReplace = path_to_absolute( $attrs['href'], $page, true );

            $parsedUrl = parse_url($pathToReplace, PHP_URL_HOST);

            $link1 = str_replace("www.", "", $parsedUrl);
            $link2 = parse_url($page, PHP_URL_HOST);

            if($link1 == $link2){
                $internalLinks++;
            }else{
                $externalLinks++;
            }
            $totalLinks++;
        }


        // Return images count on web page
        $imagesCount = get_image_count($doc);

        // Get the title and length of web page
        $pageTitle = get_title_and_length($doc);

        // Return average word count of web page
        $avgWordCount =  get_average_word_count($html);
        $pageNum++;
        $array[$key]= [
            'pagesCrawled' => $pages,
            'pageNum' => $pageNum,
            'imagesCount' => $imagesCount,
            'internalLinks' => $internalLinks,
            'externalLinks' => $externalLinks,
            'avgPageLoad' => $externalLinks,
            'avgWordCount' => $avgWordCount,
            'pageTitle' => $pageTitle['title'],
            'pageTitleLength' => $pageTitle['length'],
        ];
    }
    return $array;
}





