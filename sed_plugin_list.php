<?php

$plugin['name'] = 'sed_plugin_list';
$plugin['version'] = '0.1';
$plugin['author'] = 'Netcarver';
$plugin['author_uri'] = 'http://txp-plugins.netcarving.com';
$plugin['description'] = 'Lists plugins used by your site';
$plugin['type'] = 0;

@include_once('../zem_tpl.php');

# --- BEGIN PLUGIN CODE ---

#===============================================================================
#	Public-side features...
#===============================================================================
if( 'public' === @txpinterface )
	{
	function sed_plugin_list( $atts )
		{
		extract(lAtts(array(
			'debug' => 0,
			'type' => '',
			'link_name' => 1,
			'show_author' => 1,
			'link_author' => 0,
			'show_description' => 1,
			'descriptionwrap' => 'p',
			'descriptionclass' => 'plugin-description',
			'show_version' => 1,
			'versionwrap' => 'span',
			'versionclass' => 'plugin-version',
			'hide_disabled' => 1,
			'sort_dir' => 'asc',
			'sort_field' => 'name',
			'wraptag' => 'ul',
			'wrapclass' => 'plugin-list',
			'break' => 'li',
			'breakclass' => 'plugin-item',
			'show_count' => 0,
			'exclusions' => '',
			),$atts));

		$exclusions = explode( ',' , $exclusions );

		#
		#	Create out plugin search criteria...
		#
		$where = '';
		$w = array();

		if( '' !== $type )
			{
			$type = 'type=\''. doSlash($type) . '\'';
			$w[] = $type;
			}

		if( $hide_disabled )
			$w[] = 'status=\'1\'';

		$where = join( ' and ' , $w );

		if( empty( $where ) )
			$where = '1=1';

		$sort = '';
		if( '' !== $sort_field )
			{
			$sort = ' order by `'.doSlash($sort_field).'` '.doSlash($sort_dir);
			}

		#
		#	Grab the actual data...
		#
		$plugins = safe_rows( 'name,author,author_uri,version,description,status,type' , 'txp_plugin' , '('.$where.')'. $sort , $debug );

		#
		#	Generate the XHTML results...
		#
		if( $plugins )
			{
			foreach($plugins as $plugin)
				{
				if( in_array( $plugin['name'] , $exclusions ) )
					continue;

				$item = tag( $plugin['name'] , 'span' , ' class="plugin-name" ' );

				if( $link_name )
					$item = tag( $item , 'a' , ' href="'.$plugin['author_uri'].'" rel="nofollow" ' );

				if( $show_version )
					$item .= tag( ' v'.$plugin['version'] , $versionwrap , ' class="'.$versionclass.'" ' );

				if( $show_description )
					$item .= tag( $plugin['description'] , $descriptionwrap , ' class="'.$descriptionclass.'" ');

				$o[] = tag( $item , $break , ' class="'.$breakclass.'" ' );
				}
			}

		$o = n.join( n , $o );

		return n.tag( $o , $wraptag , ' class="'.$wrapclass.'" ' ).n.n;
		}
	}

# --- END PLUGIN CODE ---
/*
# --- BEGIN PLUGIN CSS ---
	<style type="text/css">
	div#sed_help td { vertical-align:top; }
	div#sed_help code { font-weight:bold; font: 105%/130% "Courier New", courier, monospace; background-color: #FFFFCC;}
	div#sed_help code.sed_code_tag { font-weight:normal; border:1px dotted #999; background-color: #f0e68c; display:block; margin:10px 10px 20px; padding:10px; }
	div#sed_help a:link, div#sed_help a:visited { color: blue; text-decoration: none; border-bottom: 1px solid blue; padding-bottom:1px;}
	div#sed_help a:hover, div#sed_help a:active { color: blue; text-decoration: none; border-bottom: 2px solid blue; padding-bottom:1px;}
	div#sed_help h1 { color: #369; font: 20px Georgia, sans-serif; margin: 0; text-align: center; }
	div#sed_help h2 { border-bottom: 1px solid black; padding:10px 0 0; color: #369; font: 17px Georgia, sans-serif; }
	div#sed_help h3 { color: #693; font: bold 12px Arial, sans-serif; letter-spacing: 1px; margin: 10px 0 0;text-transform: uppercase;}
	div#sed_help ul ul { font-size:85%; }
	div#sed_help h3 { color: #693; font: bold 12px Arial, sans-serif; letter-spacing: 1px; margin: 10px 0 0;text-transform: uppercase;}
	</style>
# --- END PLUGIN CSS ---
-->
<!-- HELP SECTION
# --- BEGIN PLUGIN HELP ---
<div id="sed_help">

h1(#top). SED Plugin List.

Simple plugin to generate lists of installed plugins.


h2(#changelog). Change Log

v0.1

* Initial implementation.

 <span style="float:right"><a href="#top" title="Jump to the top">top</a></span>

</div>
# --- END PLUGIN HELP ---
*/
?>
