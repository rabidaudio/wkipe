wkipe
=====

URL shortener for Wikipedia, available at http://wki.pe

Licence : [GPLv3][http://www.gnu.org/licenses/gpl-3.0.html]
See http://wki.pe/#!/legal for more information.

NOTICE
======
This service is offered in the hope that it will be useful, but without any warranty; without even the implied warranty of merchantability or fitness for a particular purpose. I have no affiliation to wikipedia.org, the Wikimedia Foundation, MediaWiki, or any other organization.

Overview
--------

The http://wki.pe/#!/faq page goes into detail on what the project is and how to use it, so I will reserve this for technical docs.

The shortener uses a mysql backend. The .htacess file contains all the super-important RewriteRules for Apache's mod_rewrite. The shortness of the urls hinges on mod_rewrite.

All support elements are included in the wkipe-dir directory. This was to ensure that I wouldn't be keeping people from accessing an article because it shared a name with one of my files.

Pages are stored in wkipe-dir/pages as HTML fragments with the language code as the suffix. These are gotten using AJAX calls to wkipe-dir/php/getpage.php.

API
---

Wki.pe now has an API for generating shortened URLs. Simply make a GET call to http://wki.pe/wkipe-dir/php/api.php with the following parameters:
* *article*
    > the full URL or name of the article to link to
* *lang (optional)*
    > Wikipedia language version character code (e.g. "en"); if it is blank or missing, we will try and detect the right language based on the location of the user following the link
* *aliased*
    > boolean ("true" or "false") If you want to have set alias for the link, set this to "true"
* *alias (optional)*
    > The alias to use. Will be ignored unless *aliased* is set to "true"

The JSON object response will have the following parameters:
* *url*
    > The output URL. It will be blank if we couldn't generate it.
* *error*
    > Will be false if there were no errors, else true.
* *error_num*
    > An array of error/warning messages. Will be empty if error=false.
* *error_mesg*
    > The corrisponding error/warning message for each *error_num.* Will be empty if error=false.

The following are error/warning numbers and their meanings. If they are only warnings, a URL will probably still be generated. Surely new error codes will be added as new features are implemented.
* 1
    > Missing/Invalid URL
* 2
    > aliased was "true", but alias wasn't included (only warning)
* 3
    > One or more HTML tags were detected and removed for security purposes. (only warning)

With jQuery for example, you can do something like this:

```javascript
$(document).ready(function() {
	var url = "http://wki.pe/wkipe-dir/php/api.php";
	$.getJSON(url+"?callback=?", {
			article: "my_article",
			lang: "ru",
			aliased: true,
			alias: "my_alias"
		}, function(data) {
			if (data.error){
				for(var i=0;i &lt; data.error_num.length;i++){
					alert("Error "+data.error_num[i]+": "+error_mesg[i]);
				}
			}else{
				alert(data.url);
			}
	});
});
```


TODO
----

- [x] Convert all pages to jquery-ui with mediawiki css
- [x] add legal page
- [x] add contact page
- [x] get autocomplete working for other languages
- [x] implement language selector
- [ ] fix weirdass unicode bugs
- [x] cleanup verbage
- [x] mobile-friendly
- [x] iframe selected article
- [x] ~~start using bootstrap~~ (not good for this project) :/
- [x] make single page app
- [x] generate in dialog window
- [x] select/copy text
- [x] move images to directory
- [x] reorganize file structure
- [ ]nadd all languages with aliasing
- [x] fix IE9 serialize bug (try to serialize manually?) http://bugs.jquery.com/ticket/11594
- [ ] protect against CSRF (and add API key feature)
- [x] fix href for local links so spiders don't crawl nonexistant pages
- [x] add ajax crawlability for SEO: https://developers.google.com/webmasters/ajax-crawling/docs/getting-started
- [ ] http://stackoverflow.com/questions/11299006/header-location-delay
- [ ] move from <meta> redirect to 302 (?)
- [x] add API documentation to README
- [x] add sidebar of recently accessed links (neat-o, right!?)

SPREAD THE WORD
