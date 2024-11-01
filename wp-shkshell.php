<?php
/*
Plugin Name: WP-ShkShell
Plugin URI: http://www.shkschneider.me/blog/1110/wp-shell-my-first-public-wordpress-plugin
Description: WP-ShkShell generates a terminal-like box around your terminal commands, with basic syntax hightlight. Wrap terminal blocks with <code>&lt;pre lang="shell" prompt="$"&gt;</code> and <code>&lt;/pre&gt;</code>.
Author: shk.schneider
Version: 0.6.0
Author URI: http://www.shkschneider.me
*/

#
#  Copyright (c) 2012 Alan SCHNEIDER
#  Copyright (c) 2009 Mariano Simone
#
#  This file is part of wp-shk-shell.
#  WP-ShkShell is based off WP-Terminal by Mariano Simone.
#
#  WP-ShkShell is free software; you can redistribute it and/or modify it under
#  the terms of the GNU General Public License as published by the Free
#  Software Foundation; either version 2 of the License, or (at your option)
#  any later version.
#
#  WP-ShkShell is distributed in the hope that it will be useful, but WITHOUT ANY
#  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
#  FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
#  details.
#
#  You should have received a copy of the GNU General Public License along
#  with WP-ShkShell; if not, write to the Free Software Foundation, Inc., 59
#  Temple Place, Suite 330, Boston, MA 02111-1307 USA
#

// Override allowed attributes for pre tags in order to use <pre prompt=""> in
// comments. For more info see wp-includes/kses.php
if (!CUSTOM_TAGS) {
  $allowedposttags['pre'] = array(
    'prompt' => array(),
    'style' => array(),
    'width' => array(),
  );
  //Allow plugin use in comments
  $allowedtags['pre'] = array(
    'prompt' => array(),
    'escaped' => array(),
  );
}

function wp_shkshell_head()
{
  $css_url = plugins_url('wp-shkshell.css' , __FILE__);
  echo "\n".'<link rel="stylesheet" href="' . $css_url . '" type="text/css" media="screen" />'."\n";
}

function wp_shkshell_substitute(&$match)
{
  global $wp_shkshell_token;
  global $wp_shkshell_matches;

  $i = count($wp_shkshell_matches);
  $wp_shkshell_matches[$i] = $match;
  return "\n\n<p>" . $wp_shkshell_token . sprintf("%03d", $i) . "</p>\n\n";
}

function wp_shkshell_highlight($match)
{
  global $wp_shkshell_matches;

  $match = $wp_shkshell_matches[intval($match[1])];
  $prompt = trim($match[2]) . "";
  $prompt = $prompt ? $prompt : "$";
  $code = $match[3];
  $commands =  preg_split("#(<br>|<br/>|<br />)(\n|\r\n)*#", $code);

  $output = "\n<div class=\"wp-shkshell\">";
  foreach ($commands as $command)
    {
      $output .= "<span class=\"wp-shkshell-prompt\">" . $prompt . "</span> ";
      // specials
      $command = preg_replace("#(\||&|;|<|>)#", '<span class="wp-shkshell-special">\\1</span>', $command);
      // first command
      $command = preg_replace("#^([\w\-]+)#", '<span class="wp-shkshell-command">\\1</span>', $command);
      $command = preg_replace("#^([~/][^ ]+)#", '<span class="wp-shkshell-command">\\1</span>', $command);

      $lines = preg_split("#(\n|\r\n)#", $command);
      foreach ($lines as $line)
	{
	  // commands
	  $line = preg_replace("#\\$\(([^\s]+)#", '$(<span class="wp-shkshell-command">\\1</span>', $line);
	  // paths
	  $line = preg_replace("# ([~/][^\s<]+)#", ' <span class="wp-shkshell-path">\\1</span>', $line);
	  // pipe, and, or, ;
	  $line = preg_replace("#(\||&|;)</span> ([\w\-]+)#", '\\1</span> <span class="wp-shkshell-command">\\2</span>', $line);
	  $line = preg_replace("#(\[\s*)([\-\w\-]+)#", '\\1<span class="wp-shkshell-command">\\2</span>', $line);
	  // strings
	  $line = preg_replace("# ('[^']+')#", ' <span class="wp-shkshell-string">\\1</span>', $line);
	  $line = preg_replace("# (\"[^\"]+\")#", ' <span class="wp-shkshell-string">\\1</span>', $line);
	  // variables
	  $line = preg_replace("#\\$(\{][^\}]+\})#", '<span class="wp-shkshell-variable">$\\1</span>', $line);
	  $line = preg_replace("#\\$([a-zA-Z0-9_]+)#", '<span class="wp-shkshell-variable">$\\1</span>', $line);
	  // others
	  $line = preg_replace("#(\\$[@\*\#\\$\!\?])#", '<span class="wp-shkshell-other">\\1</span>', $line);
	  $line = preg_replace("#(`[^`]+`)#", '<span class="wp-shkshell-other">\\1</span>', $line);
	  // comments
	  $line = preg_replace("#(^\#.+)#", '<span class="wp-shkshell-comment">\\1</span>', $line);
	  // trim
	  $line = trim($line . "<br />");

	  $output .= $line;
	}
    }
  $output = preg_replace("#(<br>|<br/>|<br />){2}#", "<br />", $output);
  $output .= "</div>\n";

  return $output;
}

function wp_shkshell_before_filter($content)
{
    return preg_replace_callback(
        "/\s*<pre lang=[\"']shell[\"'](\s+prompt=[\"']([^\"']*)[\"'])?>(.*)<\/pre>\s*/siU",
        "wp_shkshell_substitute",
        $content
    );
}

function wp_shkshell_after_filter($content)
{
    global $wp_shkshell_token;

     $content = preg_replace_callback(
         "/<p>\s*".$wp_shkshell_token."(\d{3})\s*<\/p>/si",
         "wp_shkshell_highlight",
         $content
     );

    return $content;
}

$wp_shkshell_token = md5(uniqid(rand()));

// Add styling
add_action('wp_head', 'wp_shkshell_head', -1);

// We want to run before other filters; hence, a priority of 0 was chosen.
// The lower the number, the higher the priority.  10 is the default and
// several formatting filters run at or around 6.
add_filter('the_content', 'wp_shkshell_before_filter', 0);
add_filter('the_excerpt', 'wp_shkshell_before_filter', 0);
add_filter('comment_text', 'wp_shkshell_before_filter', 0);

// We want to run after other filters; hence, a priority of 99.
add_filter('the_content', 'wp_shkshell_after_filter', 99);
add_filter('the_excerpt', 'wp_shkshell_after_filter', 99);
add_filter('comment_text', 'wp_shkshell_after_filter', 99);

?>
