=== BEMO A-Z Index ===
Contributors: bemoore
Donate link:  http://www.bemoore.com/bemo-a-z-index/
Tags: A-Z Index, Index, Alphabetical Index, Letters Filter, A-Z filter, AZ Filter
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 4.3
License: Commercial, All Rights Reserved. Copyright Bob Moore 2014.
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin that provides a customizable A-Z index of the posts displayed on a particular page, category or product listing.

== Description ==
I have added a new interface to make it easier to use this plugin. All you have to do is open up your page editor and you will see an A-Z index icon (see screenshot 1).

This plugin works in 3 situations now:
1. An existing page that has some sort of a list on it already (e.g. a testimonials page, or a WooCommerce product listing)
2. An existing category page (e.g. a blog category)
3. A blank page that you want to list something in WordPress on.

To use this plugin just put the shortcode [azindex] on the page you want to display an A-Z index on.
This can then be used anywhere posts are being listed.

It also allows you to define a custom index type like this:
[azindex index="A-E,F-J,K-M,N-R,S-W,X-Z,0,1,2,3,4,5,6,7,8,9"]

You can set what field to order by:
[azindex orderby="post_title"]

Filtering posts in a category page:

Let's assume your category is called blog ....

For this to work, in your theme, you need to put the following code in the file
category-blog.php if it exists or category.php if category-blog.php doesn't exist where you want the index to appear:
<?php azindex_category(); ?>
Then you also need to go into the category and change the settings as shown in screenshot 5.

You can also index pages:
[azindex index="A-E,F-J,K-M,N-R,S-W,X-Z,0,1,2,3,4,5,6,7,8,9" posttype="page"]

Also works with WooCommerce:
[azindex index="A-E,F-J,K-M,N-R,S-W,X-Z,0,1,2,3,4,5,6,7,8,9" posttype="product"]

You can also define what to search, default is the first letter of the post or page title:

[azindex filter="title"] -> Filters on first letter of title

[azindex filter="content"] -> Filters on first letter of content

[azindex filter="excerpt"] -> Filters on first letter of the excerpt

[azindex filter="slug"] -> Filters on first letter of the slug

If you also want to generate output, rather than filter existing posts you have to use the shortcode
[azindex content="true"]

If you want to list posts from your site on a page from a specific category as well you can do so like this:
[azindex content="true" category="post-listing"]

Or for all categories :
[azindex content="true"]

There is also a built in custom post type called "A-Z Index Posts" on the admin side that you can add items to and then list on the front page as follows:
[azindex content="true" posttype="azindexcustom"]

or for a listing all custom categories:
[azindex content="true" posttype="azindexcustom"]

If you have a custom post type, you can do the following:
[azindex content="true" category="your-custom-category-name" posttype="my-post-type"]

You can also specify pagination by putting in postcount parameter which represents a number of posts to break on, e.g.
[azindex content="true" postcount="4"]

You can create your own templates for various uses. In your theme create a folder called BEMOAZIndex and copy the file templates/listing.php in there. You can clone this and then specify other template names as follows:
[azindex content="true" template="bibliography.php"]

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

[azindex content="true" posttype="azindexcustom"]


The first code will display the A-Z for all categories.  The second one will display the actual listings.
In order to get the format you want, you would need to know something about PHP to get the picture appearing etc. where you could make a file like listing.php in the templates subdir.


== Screenshots ==
1. Sample of the A-Z Index listing in action
2. Click the A-Z index button to get the interface to easily add A-Z indexes 
3. Generate content on a blank page, or a page with no existing listing.
4. Filter existing list on a page
5. Category filter

== Changelog ==

= 1.0.1 =
* Small FAQ update

= 1.0.0 =
* Major new release with UI and category / tag filtering

= 0.1.6 =
* Fixed a few more bugs.

= 0.1.5 =
* Disabled function that stopped multiple queries being affected as it doesn't work right.

= 0.1.4 =
* Lots more fixes for 404 errors.
* order by working properly.

= 0.1.3 =
* Fixed this not working in category pages.

= 0.1.2 =
* Fixed pagination in template

= 0.1.1 =
* Fixed some more bugs in the listings

= 0.1.0 =
* Fixed some bugs in the listings

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
