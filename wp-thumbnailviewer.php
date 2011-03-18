<?php
/*
Plugin Name: Thumbnail Viewer
Plugin URI: http://www.longren.org/wordpress/thumbnail-viewer/
Description: <a href="http://www.longren.org/wordpress/thumbnail-viewer/">Thumbnail Viewer</a> is a simple plugin for showing larger images of your thumbnails. It's very similar to Lightbox, only much smaller. Add the rel="thumbnail" attribute to any link tag to activate the thumbnail viewer. It's based the <a href="http://zeo.unic.net.my/notes/lightbox-js-version-20/">wp-lightbox plugin</a> and on the <a href="http://www.dynamicdrive.com/dynamicindex4/thumbnail.htm">Image Thumbnail Viewer from Dynamic Drive</a>.
Version: 1.3
Author: Tyler Longren
Author URI: http://www.longren.org/
License: Creative Commons Attribution-ShareAlike
*/

define("IMAGE_FILETYPE", "(bmp|gif|jpeg|jpg|png)", true);

function wp_ddimageviewer_init() {
	$url = get_bloginfo('wpurl');
?>
	<!-- WP Dynamic Drive Thumbnail Image Viewer Plugin -->
	<?php
	echo '<script type="text/javascript">'."\n";
	echo '/* <![CDATA[ */'."\n";
	echo "\t".'var loading_url = \''.get_option('siteurl').'/wp-content/plugins/thumbnail-viewer/images/loadingImage.gif\';'."\n";
	echo '/* ]]> */'."\n";
	echo '</script>'."\n";
	?>
	<link rel="stylesheet" href="<?php echo $url; ?>/wp-content/plugins/thumbnail-viewer/css/thumbnailviewer.css" type="text/css" media="screen" />
	<script src="<?php echo $url; ?>/wp-content/plugins/thumbnail-viewer/js/thumbnailviewer.js" type="text/javascript">
	</script>
<?php }

function wp_ddimageviewer_add_quicktag() {
	if (strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php')) {
?>
<script type="text/javascript">//<![CDATA[
	var toolbar = document.getElementById("ed_toolbar");
<?php
	edit_insert_button("Thumbnail Viewer", "wp_ddimageviewer_handler", "Thumbnail Viewer with Caption");
?>
	var state_my_button = true;

function wp_ddimageviewer_handler() {
	if (state_my_button) {
		var myURL = prompt('Enter the original image URL (required)', 'http://');
		var myCaption = prompt('Enter image caption');
		var myIMG = prompt('Enter the Image thumbnail (required)', 'http://');
		var myWidth = prompt('Enter Width of image thumbnail (required)');
		var myHeight = prompt('Enter Height of image thumbnail (required)');
		var myAlt = prompt('Enter a description of the image');
		if (myURL && myIMG && myWidth && myHeight) {
			myValue = '<a href="'+myURL+'" rel="thumbnail" title="'+myCaption+'"><img src="'+myIMG+'" width="'+myWidth+'" height="'+myHeight+'" alt="'+myAlt+'" /></a>';
			edInsertContent(edCanvas, myValue); 
		}
	}
}
//]]></script>

<?php } }

if (!function_exists('edit_insert_button')) {
	//edit_insert_button: Inserts a button into the editor
	function edit_insert_button($caption, $js_onclick, $title = '')	{
	?>
	if (toolbar) {
		var theButton = document.createElement('input');
		theButton.type = 'button';
		theButton.value = '<?php echo $caption; ?>';
		theButton.onclick = <?php echo $js_onclick; ?>;
		theButton.className = 'ed_button';
		theButton.title = "<?php echo $title; ?>";
		theButton.id = "<?php echo "ed_{$caption}"; ?>";
		toolbar.appendChild(theButton);
	}
	
<?php } }

function wp_ddimageviewer_replace($string) {
	$pattern = '/(<a(.*?)href="([^"]*.)'.IMAGE_FILETYPE.'"(.*?)><img)/ie';
  	$replacement = '(strstr("\2\5","rel=") ? "\1" : "<a\2href=\"\3\4\"\5 rel=\"thumbnail\"><img")';
	return preg_replace($pattern, $replacement, $string);
}

add_action('wp_head', 'wp_ddimageviewer_init');
add_filter('admin_footer', 'wp_ddimageviewer_add_quicktag');
add_filter('the_content', 'wp_ddimageviewer_replace');

?>
