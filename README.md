wkipe
=====

URL shortener for Wikipedia, available at http://wki.pe

The faq.html page goes into detail on what the project is and how to use it, so I will reserve this for technical docs.

The shortener uses a mysql backend. The scripts in inc/ handle database connections and most functions.
generate.php and redirect.php are the frontend scripts that are actually accessed.

The .htacess file contains all the super-important RewriteRules for Apache's mod_rewrite. The shortness of the urls hinges
on mod_rewrite.
