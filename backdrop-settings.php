<?php
$settings = backdrop::getSettings();
if(isset($_POST['update'])){
	unset($_POST['update']);
	if(isset($_POST['background_type'])){ // Your updateing the background, I could have checked update but I diden't
		// Heres all the code that determines what is needed and makes the file that will be used.
		require_once(WP_PLUGIN_DIR.'/backdrop/generator.class.php');
		$generator = new backdropGenerator();
		$generator->_set_zindex($settings['z-index']);
		$_POST['needs_foot']='no';
		$_POST['needs_js']='no';
		switch($_POST['background_type']){
			case 'color':
				$generator->color($_POST['backdrop']['color']);
			break;
			case 'image':
				if($_POST['backdrop']['image']['attachment']=='parallax' || $_POST['backdrop']['image']['attachment']=='slide'){
					$_POST['needs_js']='yes';
				}
				if($_POST['backdrop']['image']['attachment']=='fullscreen'){
					$_POST['needs_foot']='yes';
				}
				$generator->image($_POST['backdrop']['image']);
			break;
			case 'css':
				if($_POST['backdrop']['css']['application']=='custom'){
					$_POST['needs_foot']='true';
				}
				$generator->css($_POST['backdrop']['css']);
			break;
			default:
				$_POST['background_type']='nulled';
			break;
		}
		$generator->write();
		$_POST['last_updated']=time();
		echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Site Background Updated<em>!</em></strong></p></div>';
	}else{ // Must be talking about advanced settings
		echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Advanced Settings Updated<em>!</em></strong></p></div>';
	}
	$settings = backdrop::updateSettings($_POST);
}
?>
<style>
h2 > small{
	font-size:.5em;
	color:#999999;
}

input[type="number"]{
	width:50px;
}
textarea{
	width: 540px;
	height: 100px;
}
</style>
<div class="wrap" id="custom-background">
<div id="icon-themes" class="icon32"><br></div><h2>Backdrop <small><em>Version <?= $settings['version'] ?></em></small></h2>

<form method="post" action="">
	<input type="hidden" name="update" value="backdrop" />
	<h3>Site Background</h3>

	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">Background Type</th>
				<td>
					<input type="radio" <?= ($settings['background_type']=='color'?'checked="checked"':'') ?> name="background_type" value="color" id="background_type_color" /> <span class="description"><label for="background_type_color">Color</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= ($settings['background_type']=='image'?'checked="checked"':'') ?> name="background_type" value="image" id="background_type_image" /> <span class="description"><label for="background_type_image">Image</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= ($settings['background_type']=='css'?'checked="checked"':'') ?> name="background_type" value="css" id="background_type_css" /> <span class="description"><label for="background_type_css">CSS</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
		</tbody>
	</table>

	<table class="form-table backdrop_options" id="color" style="display:none;">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="backdrop_color_color">Color</label></th>
				<td>
					<input type="color" id="backdrop_color_color" name="backdrop[color][color]" value="<?= $settings['backdrop']['color']['color'] ?>" style="vertical-align:middle;"/>
					<span class="description"> Any valid CSS color (HEX or RGB(A), cool browsers see a color picker here).
				</td>
			</tr>
		</tbody>
	</table>

	<table class="form-table backdrop_options" id="image" style="display:none;">
		<tbody>
			<tr valign="top" class="all">
				<th scope="row"><label for="itm">Image</label></th>
				<td>
					<input id="backdrop_upload_button" value="Upload/Select Image" type="button" class="button" />
					<input type="hidden" name="backdrop[image][img]" id="backdrop_image_img" value="<?= $settings['backdrop']['image']['img'] ?>" />
					<span class="description">Selected: <a href="<?= $settings['backdrop']['image']['img'] ?>" target="_blank"><?= $settings['backdrop']['image']['img'] ?></a></span>
				</td>
			</tr>
			<tr valign="top" class="adaptor">
				<th scope="row">Image Attachment</th>
				<td>
					<input type="radio" <?= @($settings['backdrop']['image']['attachment']=='scroll'?'checked="checked"':'') ?> name="backdrop[image][attachment]" value="scroll" id="background_image_attachment_scroll" /> <span class="description"><label for="background_image_attachment_scroll">Scroll</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['attachment']=='parallax'?'checked="checked"':'') ?> name="backdrop[image][attachment]" value="parallax" id="background_image_attachment_parallax" /> <span class="description"><label for="background_image_attachment_parallax">Parallax</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['attachment']=='fixed'?'checked="checked"':'') ?> name="backdrop[image][attachment]" value="fixed" id="background_image_attachment_fixed" /> <span class="description"><label for="background_image_attachment_fixed">Fixed</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['attachment']=='slide'?'checked="checked"':'') ?> name="backdrop[image][attachment]" value="slide" id="background_image_attachment_slide" /> <span class="description"><label for="background_image_attachment_slide">Slide</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['attachment']=='fullscreen'?'checked="checked"':'') ?> name="backdrop[image][attachment]" value="fullscreen" id="background_image_attachment_fullscreen" /> <span class="description"><label for="background_image_attachment_fullscreen">Fullscreen</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="fixed scroll parallax slide">
				<th scope="row">Image Repeat</th>
				<td>
					<input type="radio" <?= @($settings['backdrop']['image']['repeat']=='xy'?'checked="checked"':'') ?> name="backdrop[image][repeat]" value="xy" id="background_image_repeat_xy" /> <span class="description"><label for="background_image_repeat_xy">Repeat X + Y</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['repeat']=='x'?'checked="checked"':'') ?> name="backdrop[image][repeat]" value="x" id="background_image_repeat_x" /> <span class="description"><label for="background_image_repeat_x">Repeat X</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['repeat']=='y'?'checked="checked"':'') ?> name="backdrop[image][repeat]" value="y" id="background_image_repeat_y" /> <span class="description"><label for="background_image_repeat_y">Repeat Y</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['repeat']=='none'?'checked="checked"':'') ?> name="backdrop[image][repeat]" value="none" id="background_image_repeat_none" /> <span class="description"><label for="background_image_repeat_none">No Repeat</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="scroll parallax fixed">
				<th scope="row">Image Horizontal Position</th>
				<td>
					<input type="radio" <?= @($settings['backdrop']['image']['hpos']=='left'?'checked="checked"':'') ?> name="backdrop[image][hpos]" value="left" id="background_image_hpos_left" /> <span class="description"><label for="background_image_hpos_left">Left</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['hpos']=='center'?'checked="checked"':'') ?> name="backdrop[image][hpos]" value="center" id="background_image_hpos_center" /> <span class="description"><label for="background_image_hpos_center">Center</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['hpos']=='right'?'checked="checked"':'') ?> name="backdrop[image][hpos]" value="right" id="background_image_hpos_right" /> <span class="description"><label for="background_image_hpos_right">Right</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="scroll fixed">
				<th scope="row">Image Vertical Position</th>
				<td>
					<input type="radio" <?= @($settings['backdrop']['image']['vpos']=='top'?'checked="checked"':'') ?> name="backdrop[image][vpos]" value="top" id="background_image_vpos_top" /> <span class="description"><label for="background_image_vpos_top">Top</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['vpos']=='center'?'checked="checked"':'') ?> name="backdrop[image][vpos]" value="center" id="background_image_vpos_center" /> <span class="description"><label for="background_image_vpos_center">Center</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= @($settings['backdrop']['image']['vpos']=='bottom'?'checked="checked"':'') ?> name="backdrop[image][vpos]" value="bottom" id="background_image_vpos_bottom" /> <span class="description"><label for="background_image_vpos_bottom">Bottom</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="fixed scroll parallax slide">
				<th scope="row">Retinize Background</th>
				<td>
					<input type="hidden" name="backdrop[image][retinize]" value="no" />
					<input type="checkbox" name="backdrop[image][retinize]" id="backdrop_image_retinize" value="yes" <?= @($settings['backdrop']['image']['retinize']=='yes'?'checked="checked"':'') ?> /> <span class="description"><label for="backdrop_image_retinize">Will cut background in half to maintain a sharp image on retina displays (at the cost of larger file size and smaller patterns).</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="parallax">
				<th scope="row"><label for="backdrop_image_parallax_adjustment">Parallax Adjustment</a></th>
				<td>
					<input type="number" min="0" max="100" step="1" name="backdrop[image][parallax_adjustment]" value="<?= $settings['backdrop']['image']['parallax_adjustment'] ?>" id="backdrop_image_parallax_adjustment" />
					<span class="description">Controls how different the parallax scroll speed is, 0 would be the same as fixed, 100 would be normal scroll behavior.</span>
				</td>
			</tr>
			<tr valign="top" class="slide">
				<th scope="row">Image Slide Direction</th>
				<td>
					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='up'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="up" id="background_image_slide_up" /> <span class="description"><label for="background_image_slide_up">Up</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='up-right'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="up-right" id="background_image_slide_up-right" /> <span class="description"><label for="background_image_slide_up-right">Up-Right</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='right'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="right" id="background_image_slide_right" /> <span class="description"><label for="background_image_slide_right">Right</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='down-right'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="down-right" id="background_image_slide_down-right" /> <span class="description"><label for="background_image_slide_down-right">Down-Right</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='down'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="down" id="background_image_slide_down" /> <span class="description"><label for="background_image_slide_down">Down</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='down-left'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="down-left" id="background_image_slide_down-left" /> <span class="description"><label for="background_image_slide_down-left">Down-Left</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='left'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="left" id="background_image_slide_left" /> <span class="description"><label for="background_image_slide_left">Left</label></span> &nbsp; &nbsp; &nbsp;

					<input type="radio" <?= @($settings['backdrop']['image']['slide']=='up-left'?'checked="checked"':'') ?> name="backdrop[image][slide]" value="up-left" id="background_image_slide_up-left" /> <span class="description"><label for="background_image_slide_up-left">Up-Left</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top" class="slide">
				<th scope="row"><label for="backdrop_image_slide_speed">Slide Speed</a></th>
				<td>
					<input type="number" min="0" max="1000" step="1" name="backdrop[image][slide_speed]" value="<?= $settings['backdrop']['image']['slide_speed'] ?>" id="backdrop_image_slide_speed" />
					<span class="description">Controls how fast the image slides, 0 is no slide, 100 is 1-for-1 with downward scroll speed.</span>
				</td>
			</tr>

			<tr valign="top" class="all">
				<th scope="row"><label for="backdrop_image_color">Backup Color</label></th>
				<td>
					<input type="color" id="backdrop_image_color" name="backdrop[image][color]" value="<?= $settings['backdrop']['image']['color'] ?>" style="vertical-align:middle;"/>
					<span class="description"> Seen if image is slow loading, is set to repeate other than X + Y, or if it 404s, any valid CSS color (HEX or RGB(A), cool browsers see a color picker here).
				</td>
			</tr>
		</tbody>
	</table>

	<table class="form-table backdrop_options" id="css" style="display:none;">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="backdrop_css_css">CSS Code</label></th>
				<td>
					<textarea id="backdrop_css_css" name="backdrop[css][css]"><?= stripslashes($settings['backdrop']['css']['css']) ?></textarea><br/>
					<span class="description">Use whatever custom css you want, Selectors will automatically be added based on below selection.
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="backdrop_css_apply_styles_to">Apply Styles To</label></th>
				<td>
					<input type="radio" <?= ($settings['backdrop']['css']['application']=='body'?'checked="checked"':'') ?> name="backdrop[css][application]" value="body" id="backdrop_css_application_body" /> <span class="description"><label for="backdrop_css_application_body">Body Element</label></span> &nbsp; &nbsp; &nbsp;
					<input type="radio" <?= ($settings['backdrop']['css']['application']=='custom'?'checked="checked"':'') ?> name="backdrop[css][application]" value="custom" id="backdrop_css_application_custom" /> <span class="description"><label for="backdrop_css_application_custom">Custom Element (see notes below)</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="backdrop_css_helpfull_links">Helpfull Links</label></th>
				<td>
					<span class="description">
						Make CSS3 image gradients using the <a href="http://www.colorzilla.com/gradient-editor/" target="_blank">Colorzilla Ultimate CSS Gradient Generaor</a>, the best gradient genrator around.<br/>
						Try out some fancy CSS3 patters, Lea Verou has gathered a great selection at her <a href="http://lea.verou.me/css3patterns/" target="_blank">CSS3 Patterns Gallery</a>.<br/>
						Don't forget all those browser specific prefixes, you might want to run it through <a href="http://prefixr.com/" target="_blank">Prefixr</a>.
					</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="backdrop_css_notes">Notes</label></th>
				<td>
					<span class="description">Where you apply your custom CSS background generally controls how it behaves when scrolling. If applied to the body, it will move with the page (same as an image background set to scroll), if it's applied to the custom element it will say where it is (same as an image background set to fixed).<br/><br/>
					The custom element has base styles applied to it to make it fill the page and places it beheind the main page content (using the z-index defined in the advanced settings below). Placing it in a custom element also allows for the ability to run more than one background simultanously (using a semi-transparent custom background with a default image background for instance), although this technique is more advanced and requires CSS knowledge.</span>
				</td>
			</tr>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" class="button-primary" value="Update Backdrop">
	</p>
</form>

<form method="post" action="">
	<input type="hidden" name="update" value="advanced_settings" />
	<h3>Advanced Settings</h3>

	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="adv_zindex">Foot Overlay z-index</a></th>
				<td>
					<input type="number" min="-1000000" max="1000000" step="1" name="z-index" value="<?= $settings['z-index'] ?>" id="adv_zindex" />
					<span class="description">Controls the Z positioning of the background overlay div, usually -1 works, but you can change this number if your theme is not normal.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Stock Background Customizer</th>
				<td>
					<input type="hidden" name="show_default" value="no" />
					<input type="checkbox" name="show_default" id="show_default" value="yes" <?= @($settings['show_default']=='yes'?'checked="checked"':'') ?> /> <span class="description"><label for="show_default">Will bring back the default background options in aidition to the backdrop options, lets you get fancy with dual backgrounds if thats your thing.</label></span> &nbsp; &nbsp; &nbsp;
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Active Includes</th>
				<td>
					<!-- There would be one for CSS here, but it always needs to be active -->
					<input type="hidden" name="needs_js" value="no" />
					<input type="hidden" name="needs_foot" value="no" />
					<input type="checkbox" checked="checked" name="needs_css" value="yes" id="adv_needs_css" disabled="disabled" /> <span class="description"><label for="adv_needs_css">CSS</label></span> &nbsp; &nbsp; &nbsp;
					<input type="checkbox" <?= ($settings['needs_js']=='yes'?'checked="checked"':'') ?> name="needs_js" value="yes" id="adv_needs_js" /> <span class="description"><label for="adv_needs_js">JavaScript</label></span> &nbsp; &nbsp; &nbsp;
					<input type="checkbox" <?= ($settings['needs_foot']=='yes'?'checked="checked"':'') ?> name="needs_foot" value="yes" id="adv_needs_foot" /> <span class="description"><label for="adv_needs_foot">Footer Div</label></span> &nbsp; &nbsp; &nbsp;
					<span class="description">What is currently being added to your page. This is automatically updated when the backdrop is, changing these will probably break things.</span>
				</td>
			</tr>
			<!-- This is actually very usefull, woudlrather have a shorter page
			<tr valign="top">
				<th scope="row">Show Debug Information</th>
				<td>
					<input type="checkbox" name="show_debug" id="show_debug" value="show" onclick="$('pre#debug_data_box').toggle()" />
					<span class="description"><label for="show_debug">This option does not save and does not change anything, it simply toggles a box below with your plugin settings and POST data.</label></span><br/>
					<pre id="debug_data_box" style="font-family:monospace;font-size:.75em;line-height:1.25em;display:none;"><?php
						print_r($settings);
						print_r($_POST);
					?></pre>
				</td>
			</tr> -->

		</tbody>
	</table>

	<p class="submit">
		<input type="submit" class="button-primary" value="Update Advanced Settings">
	</p>
</form>

<h3>Plugin Miscellanea</h3>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="active_includes_notes">Contact the Plug-in Author</label></th>
			<td>
				<span class="description">If you have any questions or problems I can be contacted directly at <a href="mailto:phillip.gooch@gmail.com?subject=Backdrop%20WordPress%20Plugin%20-%20version%20<?= $settings['version'] ?>">phillip.gooch@gmail.com</a> or with the handy dandy contact for located at <a href="http://www.fatfolderdesign.com/contact-me" target="_blank">FatFolderDesign.com &mdash; Contact</a>.</span>
			</td>
		</tr>

		<!-- This really isn't important
		<tr valign="top">
			<th scope="row"><label for="active_includes_notes">Donate to the Author</label></th>
			<td>
				<span class="description">If you like the plugin so much that you feel like donating a $1 that would be grand, and $2 would be even grand-er. I don't currently have that sorta thing set up (it's only come up once so far). If you'd like to do something that makes me giddy as a schoolgirl at a N*Sync concert (or whatever the blazes school girls listen to these days) send me an email telling me what you like about the plugin (or what you don't like, or what you wish it could do). Also, hitting that "Allow Analytics" button up there would be pretty awesome.</span>
			</td>
		</tr>
		-->
	</tbody>
</table>

<script>
	var $ = jQuery;
	// Handels switching based on background type
	$(document).ready(function(){$('input[name="background_type"]:checked').click();});
	$('input[name="background_type"]').click(function(){
		$('table.backdrop_options').hide();
		$('table#'+$(this).val()).show();
	});
	// Handels switching based on any sub type (remvoed all table rows that don't have the class "all" or input value)
	$(document).ready(function(){$('tr.adaptor input:checked').click();});
	$('tr.adaptor input').click(function(){
		var selector = $(this).val();
		$(this).parent().parent().parent().parent().find('tr').show();
		$(this).parent().parent().parent().parent().find('tr:not(.all, .adaptor, .'+selector+')').hide();
	});
	// Handels image uploading
	$(document).ready(function(){
		$("input#backdrop_upload_button").click(function(){
			tb_show('','media-upload.php?type=image&post_id=1&TB_iframe=true&flash=0&backdrop=true');
			return false;
		})
		window.send_to_editor=function(html){
			var img = $('<div>'+html+'</div>').find('img').attr('src');
			$('#backdrop_image_img').val(img);
			tb_remove();
		};
	});
</script>