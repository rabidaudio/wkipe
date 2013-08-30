wkipe
=====

URL shortener for Wikipedia, available at http://wki.pe

Licence : GPLv3 [http://www.gnu.org/licenses/gpl-3.0.html]

NOTICE
======
This service is offered in the hope that it will be useful, but without any warranty; without even the implied warranty of merchantability or fitness for a particular purpose. I have no affiliation to wikipedia.org, the Wikimedia Foundation, MediaWiki, or any other organization.

Overview
========

The faq.html page goes into detail on what the project is and how to use it, so I will reserve this for technical docs.

The shortener uses a mysql backend. The .htacess file contains all the super-important RewriteRules for Apache's mod_rewrite. The shortness of the urls hinges on mod_rewrite.

All support elements are included in the wkipe-dir directory. This was to ensure that I wouldn't be keeping people from accessing an article because it shared a name with one of my files.

Pages are stored in wkipe-dir/pages as HTML fragments with the language code as the suffix. These are gotten using AJAX calls to wkipe-dir/php/getpage.php.
