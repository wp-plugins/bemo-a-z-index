=== BEMO A-Z Index ===
Contributors: bemoore
Donate link:  http://www.bemoore.com/bemo-a-z-index/
Tags: A-Z Index, Index, Alphabetical Index, Letters Filter, A-Z filter, AZ Filter
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 4.3
License: Commercial, All Rights Reserved. Copyright Bob Moore 2014.
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple plugin that provides an A-Z index of the posts displayed on a particular page based on the post title.

== Description ==
To use this plugin just put the shortcode [azindex] on the page you want to display an A-Z index on.
This can then be used anywhere posts are being listed.

It also allows you to define a custom index type like this:
[azindex index="A-E,F-J,K-M,N-R,S-W,X-Z,0,1,2,3,4,5,6,7,8,9"]

If you also want to generate output, rather than filter existing posts you have to use the shortcode
[azindexoutput]

You can also define what to search, default is the first letter of the post or page title:

[azindex filter="title"] -> Filters on first letter of title

[azindex filter="content"] -> Filters on first letter of content

[azindex filter="excerpt"] -> Filters on first letter of the excerpt

[azindex filter="slug"] -> Filters on first letter of the slug

If you want to list posts from your site on a page from a specific category as well you can do so like this:
[azindex category="post-listing"]

Or for all categories :
[azindex category="*"]

There is also a built in custom post type called “A-Z Index Posts” on the admin side that you can add items to and then list on the front page as follows:
[azindex posttype="azindexcustom"]

or for a listing all custom categories:
[azindex posttype="azindexcustom" category="*"]

If you have a custom post type, you can do the following:
[azindex category="your-custom-category-name" posttype="my-post-type"]

You can also specify pagination by putting in postcount parameter which represents a number of posts to break on, e.g.
[azindex category="your-custom-category-name" posttype="my-post-type" postcount="4"]

You can create your own templates for various uses. In your theme create a folder called BEMOAZIndexPro and copy the file templates/listing.php in there. You can clone this and then specify other template names as follows:
[azindexoutput template="bibliography.php"]

There are css styles that you can override in your own template.

There are also 2 widgets, the index widget and the index output widget that replicate the functionality discussed here.

To remove the watermark, you need to buy the pro version.
== Installation ==
This plugin is installed in the standard way.

== Frequently Asked Questions ==

= I want to have a page that is a list of artists. Each artists page will have a picture and a description. I also want to have a page that has lyrics. 

What do I need to do to set this up? I can't figure it out. Do I create a page for each artist and then do what to make them show up on a page called artists?

So basically it will be a page called http://xxxxx/artists

On that page will be the A-Z

When you click on the appropriate letter, it shows the artists pictures under that letter.

How do I do this? =

You should put your artists in A-Z Index Posts (left sidebar) and set the featured image for each post.  Try 3 or 4 to start.

Then make a page called Artists (or whatever you want to call it).

Then put the following on that page:

[azindex posttype="azindexcustom" category="*"]

[azindexoutput]

The first code will display the A-Z for all categories.  The second one will display the actual listings.
In order to get the format you want, you would need to know something about PHP to get the picture appearing etc. where you could make a file like listing.php in the templates subdir.

== Screenshots ==
Coming soon

== Changelog ==

= 0.0.9 =
* Added more info to the FAQ.
* Tidied up the description.

= 0.0.8 =
* Tested for 4.0
* Release to have the same as pro except for watermark.

= 0.0.7 =
* Fixed error in the FAQ

= 0.0.6 =
* Added css file for proper styling

= 0.0.5 =
* Bug with protected function fixed
* Added some FAQ items

= 0.0.4 =
* Added a widget

= 0.0.3 =
* Added custom indexes to the original index.
* Added filter types

= 0.0.2 =
* Added post types to the original index.

= 0.0.1 =
* The very first release

== Upgrade Notice ==
