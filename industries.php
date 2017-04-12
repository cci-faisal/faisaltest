<?php
require_once('includes/midas.inc.php');

$left_arrow_icon = block_content("breadcrum left arrow icon alt");

//$JavascriptIncludesArray = array('includes/scriptaculous/lib/prototype.js','popup.js','faq.js');

?>
<?php

//check
//$PageTitle = block_content('Programs Page Title');//'Canadian Government Grants &amp; Loans';
//$Section = block_content('Programs Page Section Text');//'Programs';
//$JavascriptIncludesArray = array('');
$HeadJavascript = '';
$BodyTagJavascript ='';
$Breadcrumb ='<p class="breadcrumb"><a href="/'.get_url_path("index.php").'">'.get_url_text("index.php","Accueil").'</a> <img alt="'.$left_arrow_icon .'" src="images/breadcrumb_left_arrow.gif" width="12" height="7" /> '.Industries.'  </p>' . "\n";
@extract(initialize_meta_tags('', '',''));

?>
<?php require('inc.header.php'); ?>
    <div id="LeftColumn"><!-- Left Column-->
    <?php require('inc.left.php'); ?>
        </div><!--  End Left Column-->

        <div id="CentreColumn"><!-- Centre Column-->

            <?php echo replace_tokens(block_content('Industry Funding Cover Page', true));?>

            <ul class="industry-title-list">
            <?php
            $sql = "select * from odc_v2_glc_options_phase4 where qid = '6'";

            $result = db_query($sql);
            if(mysql_num_rows($result) > 0){
                while($line_raw = mysql_fetch_array($result)){
                        $line_raw = ms_display_value($line_raw);
                        @extract($line_raw);

                        $url = replaceAccents(utf8_encode($option_text));
                        $url = preg_replace('/&#0*39;|\//', "-", $url);
                        $url = implode("-", explode(" ", preg_replace('/[^0-9a-z\s\-]/i', '', $url) ) );
                        $url = strtolower( str_replace("--", "-", $url) );
                        ?>
                                <li class="industry-title">
                                    <a href="/industrie/<?php echo $url; ?>">
                                        <img src="images/industry-header-<?php echo $url; ?>.png">
                                        <span><?php echo $option_text; ?></span>
                                    </a>
                                </li>
                        <?php
                }
            }
            ?>
            </ul>

        </div><!-- End Centre Column-->

    <div id="RightColumn"><!-- Right Column-->
            <?php require('inc.right.php'); ?>
    </div><!-- End Right Column-->

<?php require('inc.footer.php'); ?>