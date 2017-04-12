<?php  
require_once('includes/midas.inc.php');
$sql_content = " select * from gl_static_pages where 1 and page_name='home' ";
$result_content = db_query($sql_content);
$Section = 'Home';
while($line_raw_content = mysql_fetch_array($result_content)){
	//$line_raw_content = ms_display_value($line_raw_content);
	@extract($line_raw_content);
} ?>
<? require('inc.header.php'); ?>
<div id="LeftColumn">
  <!-- Left Column-->
  <?php require('inc.left-861v2.php'); ?>
</div>
<!--  End Left Column-->
<div id="CentreColumn">
    <div style="float:left; width:460px;">
		<?php
            $content = html_entity_decode($page_content);
            $content = preg_split('/<!--([a-zA-Z\s0-9]+)-->/',$content);
            $the_page_content[0] = replace_tokens($content[0]);
            $the_page_content[1] = replace_tokens($content[1]);
            include GRANT_LOANS_CALCULATOR_DIR."/index.php";
        ?>
    </div>
    <div style="float:left; width:460px;">
                <?php
            echo str_replace('&','&amp;',$the_page_content[1]);//Print the second part of the home page content
        ?>
    </div>
    <div style="float:left; width:460px;">
    <h5>
      <?=block_content('Find out how')?>
    </h5>
    <div id="show">
<?php
        $video_count = mysql_num_rows(db_query("select * from gl_videos"));  
	//Check whether to retrieve images stories or videos stories
	if(!isset($_SESSION['images_or_videos'])){
		$_SESSION['images_or_videos'] = 1;
		$images_or_videos_query = " AND st_video_id != ''";
	}else if($_SESSION['images_or_videos'] == 1){
		$_SESSION['images_or_videos'] = 2;
		$images_or_videos_query = " AND st_video_id = ''";
	}else if($_SESSION['images_or_videos'] == 2){
		$_SESSION['images_or_videos'] = 1;
		$images_or_videos_query = " AND st_video_id != ''";
	}
        if ($video_count == 0) {
	  $images_or_videos_query =" ";
        }
	$mySqlDateTime= date("Y-m-d H:i:s",  $_SERVER['REQUEST_TIME']);
	$sqlInterviews = "SELECT * FROM gl_story where st_status='Active' and st_image !='' and st_video_id = '' and st_datetime <= '".$mySqlDateTime."' ORDER BY RAND() LIMIT 5";
	$varResultInterviews = db_query($sqlInterviews);
	if($varResultInterviews != false && mysql_num_rows($varResultInterviews)>0){
		$n=0;
		if(mysql_num_rows($varResultInterviews)>0){
			while($line_raw_interviews = mysql_fetch_array($varResultInterviews))
			{
				$n=$n+1;
				$line_interviews = ms_display_value($line_raw_interviews);
				@extract($line_interviews);
				if($st_video_id) {
					$left_width = 140;
				} else {
					$left_width= 114; 
				}
				$right_width = 454 - $left_width;
				?>
		  <div class="article_content">
			<div class="article_content_left" style="width:<?=$left_width?>px">
			  <?php
					if($st_video_id) {
						// get thumbnail
						$sqlVid = "select * from gl_videos where 1 and id='$st_video_id'";
						$resultVid = db_query($sqlVid);
						$lineVid = mysql_fetch_array($resultVid);
						$thumb = $lineVid['image'];
						?>
			  <a href="<?=get_url_path("articleview.php")?>?id=<?=$st_id?>&t=<?=toAscii($st_title)?>"><img border="0" src="images/videos/<?=$thumb?>" width="133" height="100" title="<?=$st_title?>" alt="<?=$st_title?>" /></a>
			  <?php
					}elseif(!empty($st_image)){ ?>
			  <a href="<?=get_url_path("articleview.php")?>?id=<?=$st_id?>&t=<?=toAscii($st_title)?>"><img border="0" src="<?=show_thumb(UP_FILES_WS_PATH.'/money_images/'.$st_image,100,100, resize)?>" alt="<?=$st_title?>" title="<?=$st_title?>"/></a>
			  <?php
					}else{
					?>
			  <img src="<?=show_thumb(UP_FILES_WS_PATH.'/no_images.jpg',100,100, resize)?>"/>
			  <?php
					}
					?>
			</div>
			<div class="article_content_right" style="width:<?=$right_width?>px">
			  <?
					$title = html_entity_decode(strip_tags($st_title));
					$to_remove=0;
					if (strlen($title)>60) {$to_remove = 60;};
					if (strlen($title)>120) {$to_remove = 120;};
					if (strlen($title)>180) {$to_remove = 180;};
					?>
			  <h3><a href="<?=get_url_path("articleview.php")?>?id=<?=$st_id?>&t=<?=toAscii($st_title)?>">
				<?=str_replace('&','&amp;',$title); ?>
				</a></h3>
				<?php 
					if(FILE_NAME_POSTFIX == 'nz'){//Grab part of the first paragraph and use it as the summary
						$original_st_description = preg_split('/(<P>|<p>)/',$st_description);//story description that contains paragraphs well formatted i.e are closed too.
						//The first paragraph is on index 1 of the array since index 0 will contain the title
						$st_description = preg_replace('/(<\/P>|<\/p>)/','',$original_st_description[1]);//Remove the closing p tags
						echo(str_stop(trim(strip_tags(html_entity_decode(preg_replace("/[ \t\r\n]+/", " ", $st_description)))), 280-$to_remove));
					}else{
						echo(str_stop(trim(strip_tags(html_entity_decode(preg_replace("/[ \t\r\n]+/", " ", $st_description)))), 280-$to_remove));
					}
			  ?>
			  &lt;<a href="<?=get_url_path("articleview.php")?>?id=<?php echo $st_id;?>&t=<?=toAscii($st_title)?>" class="more"><?=block_content('More Link');?></a>&gt; </div>
		  </div>
		  <?php
			}
		}
	}
?>
    </div>
  </div>
  <br clear="all"/>
  <br clear="all" />
  
  <?php
$mySqlDateTime= date("Y-m-d H:i:s",  $_SERVER['REQUEST_TIME']);
$sql = "  select st_id,st_title from gl_story where 1 and st_status='Active' and st_datetime <= '".$mySqlDateTime."' order by st_datetime desc limit 0,2 ";
$result=db_query($sql);

if(mysql_num_rows($result) > 0)
{
?>
  <div class="small_business">
    <div class="small_business_content">
      <h5 style="margin: 2px 0px 5px 0px;"><?=block_content('Latest Small Business Success Stories');?> <?=midas_date_format($mySqlDateTime)?>
</h5>
      <?php
	$i=0;
	while($line_raw = mysql_fetch_array($result))
	{
		@extract($line_raw);
		$i++;
		if($i%2==1)
		{
	?>
      <div class="greybg"><a href="<?=get_url_path("articleview.php")?>?id=<?php echo $st_id; ?>&t=<?=toAscii($st_title)?>"> <?php echo str_stop($st_title,125);?> </a></div>
      <?php
		}
		else
		{
	?>
      <div class="lightredbg"><a href="<?=get_url_path("articleview.php")?>?id=<?php echo $st_id; ?>&t=<?=toAscii($st_title)?>"><?php echo str_stop($st_title,125);?></a></div>
      <?php
		}
	}
	?>
    </div>
    <?php
}

$sql = "  select gw_id,gw_title from gl_grant_watch where 1 and gw_status='Active' order by gw_date desc limit 0,2 ";
$result=db_query($sql);

if(mysql_num_rows($result) > 0)
{
?>
    <div class="small_business_content">
      <h6><?=block_content('Latest Grant Watch');?></h6>
      <?php
	$i=0;
	while($line_raw = mysql_fetch_array($result))
	{
		@extract($line_raw);
		$i++;
		if($i%2==1)
		{
	?>
      <div class="greybg"><a href="<?=get_url_path("grantwatchview.php")?>?id=<?php echo $gw_id; ?>"><?php echo str_stop($gw_title,125);?> </a></div>
      <?php
		}
		else
		{
	?>
      <div class="lightredbg"><a href="<?=get_url_path("grantwatchview.php")?>?id=<?php echo $gw_id; ?>"><?php echo str_stop($gw_title,125);?> </a></div>
      <?php
		}
	}
	?>
    </div>
    <?php
}

$sql = "  select res_id,res_title from gl_resource where 1 and res_status='Active' order by res_date desc limit 0,2 ";
$result=db_query($sql);

if(mysql_num_rows($result) > 0) {
?>
    <div class="small_business_content">
      <h5 style="margin-bottom:5px;"><?=block_content('Latest Small Business Resources');?> <?=midas_date_format($mySqlDateTime)?>
</h5>
      <?php
	$i=0;
	while($line_raw = mysql_fetch_array($result)) {
		@extract($line_raw);
		$i++;
		if($i%2==1) {
			?>
      <div class="greybg"><a href="<?=get_url_path("resourceview.php")?>?id=<?php echo $res_id; ?>&t=<? echo toAscii($res_title)?>"><?php echo str_stop($res_title,125);?></a></div>
      <?php
		}
		else {
			?>
      <div class="lightredbg"><a href="<?=get_url_path("resourceview.php")?>?id=<?php echo $res_id; ?>&t=<? echo toAscii($res_title)?>"><?php echo str_stop($res_title,125);?></a></div>
      <?php
		}
	}
	?>
    </div>
    
  </div>
  <?php

}
?>

<!--
<div style="margin-top: 40px; width:450px; padding-left:10px; position:relative; float:left;border-style: solid; border-color: #000; background:url('/images/compass_image.jpg') no-repeat; background-position:right bottom; cursor:pointer;" onclick="location.href='/<? //=get_url_path("grants-loans-calculator/grant-finder.php");?>'">
    <div style="float:left; width: 450px; padding-top:15px;">
        <h2 style="padding-top:0px; margin-top:0px;">
            <a href="<? //=SITE_WS_PATH?>/<? //=get_url_path("grants-loans-calculator/grant-finder.php")?>"><? //=block_content('Grant Finder')?></a>
        </h2>
    </div>
    <div style="float:left; width: 255px;">
        <? //=block_content('Grants Loans Calculator')?>
    </div>
    <div style="float:left; width:450px; padding-bottom:10px; padding-top:10px;" >  
        <a href="<? //=SITE_WS_PATH?>/<? //=get_url_path("grants-loans-calculator/grant-finder.php")?>">
            <img src="/images_<? //=FILE_NAME_POSTFIX?>/btn/gofinder_button.gif" alt="";>
        </a> 
    </div>
</div>
-->
</div>
<!-- End Centre Column-->
<div id="RightColumn">
  <!-- Right Column-->
  <?php require('inc.right.php'); ?>
</div>
<!-- End Right Column-->
<?php require('inc.footer.php'); ?>
