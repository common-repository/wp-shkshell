=== WP-ShkShell ===
Contributors: msimone, shk.schneider
Donate link: http://www.shkschneider.me
Tags: shell, terminal, unix, console, command, linux
Requires at least: 2.0
Tested up to: 3.3.1
Stable tag: 0.6.0

WP-ShkShell provides a terminal-like box for embedding terminal commands within pages or posts.

It also support multi-lines, multi-commands and has syntax hightlight.

== Description ==

WP-ShkShell provides a terminal-like box for embedding terminal commands within pages or posts.
It also support multi-lines, multi-commands and has syntax hightlight.

The code is a modification of WP-Terminal (http://wordpress.org/extend/plugins/wp-terminal/).

== Installation ==

1. Upload wp-shkshell.zip to your Wordpress plugins directory, usually `wp-content/plugins/` and unzip the file.  It will create a `wp-content/plugins/wp-shkshell/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Create a post/page that contains a code snippet following the proper usage syntax.

== Frequently Asked Questions ==

== Screenshots ==

Go visit my website, which uses it: http://www.shkschneider.me/blog/1110/wp-shell-my-first-public-wordpress-plugin

== Usage ==

Wrap terminal blocks with `<pre lang="shell" prompt="$">` and `</pre>`.

**Example 1: Default prompt**

    <pre lang="shell" prompt="$">
      ls -a
    </pre>

**Example 2: Customized prompt**

    <pre lang="shell" prompt="#">
      ls -a
    </pre>

**Example 3: Another customized prompt**

    <pre lang="shell" prompt="user@machine$">
      ls -a
    </pre>

**Example 4: Comments**

    <pre lang="shell" prompt="user@machine$">
      ls -a
      # will also list hidden files
    </pre>

**Example 5: Multiline commands**

    <pre lang="shell">
      ls
      <br>ls -a
    </pre>

**Example 6: Multiline lines, multiple commands**

    <pre lang="shell">
      ls
      file1 file2 file3
      <br>ls -A
      .file0 file1 file2 file3
    </pre>

== Changelog ==

= 0.6.0 =
* Added support for comments (^#)

= 0.5.3 =
* Extended variables names: [A-Z_]+ to [a-zA-Z0-9_]+

= 0.5.2 =
* Path fix (thx to James House). Was causing bad parsing without space before ';'

= 0.5.1 =
* Added sub-shell commands recognition
  So that in <code>VAR=$(cmd ...)</code>, <code>cmd</code> will be highlighted as a command

= 0.5 =
* Fixed 0.4.1 (backquotes)

= 0.4.3 =
* Fixed 0.4.2.5 (commands detection)

= 0.4.2.5 =
* Improved commands detection

= 0.4.2 =
* Removed conditions
* Improved CSS and README

= 0.4.1 =
* Added support for backquotes

= 0.4.0 =
* Fixed path to CSS (was hard-coded and case-insensitive)

= 0.3.8.5 =
* Added support for special variables $* $@ etc.

= 0.3.8 =
* Added support for conditions []

= 0.3.7.5 =
* Fixed strings

= 0.3.7 =
* Added support for strings '' and ""

= 0.3.6 =
* Added support for variables $... and $(...)

= 0.3.5 =
* Added support for fullpath commands

= 0.3.4 =
* Added support for commands with -

= 0.3.3 =
* Added support for ; and commands with -

= 0.3.2 =
* Fixed && and ||

= 0.3.1 =
* Perfect multi-line support

= 0.3 =
* Tag pre lang="shell", with prompt

= 0.2 =
* Added multiline commands, pre class=""

= 0.1 =
* First release, pre id=""
