<?php
include('function.php');
$pageDetails = main_function();
?>

<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css" type="text/css"></head>
<body>

<?php  foreach($pageDetails as $page) { ?>
    <div class='aa-bold-text'>Page <?php echo $page['pageNum'];?>  Details </div>
    <div class='aa-div-row'>Number of a unique images:  <span><?php echo $page['imagesCount'];?></span> </div>
    <div class='aa-div-row'>Number of unique internal links: <span><?php echo $page['internalLinks'];?></span> </div>
    <div class='aa-div-row'>Number of unique external links:  <span><?php echo $page['externalLinks'];?></span> </div>
    <div class='aa-div-row'>Avg page load:  <span><?php echo $page['avgPageLoad'];?></span> </div>
    <div class='aa-div-row'>Avg word count:  <span><?php echo $page['avgWordCount'];?></span> </div>
    <div class='aa-div-row'>Page Title:  <span><?php echo $page['pageTitle'];?></span> </div>
    <div class='aa-div-row'>Avg Title length:  <span><?php echo $page['pageTitleLength'];?></span> </div>
    <hr/>
<?php } ?>

<h1 class="tableHeading">Table to display each page crawled and it's status code</h1>
<table>
    <thead>
    <tr>
        <th>Crawled Page URL</th>
        <th>Page Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pageDetails[0]['pagesCrawled'] as $row) { ?>
        <?php
        $header_check = get_headers($row);
        $response_code = $header_check[0];
        ?>
        <tr>
            <td><?php echo $row; ?></td>
            <td><?php echo $response_code; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
