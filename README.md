Get! Set! Go! - An ExpressionEngine plugin for manipulating query strings
=============

**Current Version: 0.9 beta**

What?
-----

Get! Set! Go! makes manipulating/outputting query strings in your EE templates easier.

How?
----

If the query string on page load is this:

    http://example.com/group/template/search?category=fruit&order=product_price+asc&count=10

A GSG tag like this:

    {exp:get_set_go unset="category" count="20" }

Would produce the following output

    /group/template/search?order=product_price+asc&count=20    

Why?
----

Search addons like Solspace Super Search can make use of query strings/GET params for bookmarkable/indexable results pages, but outputting html links to allow the user to modify the current query string can be an ugly, messy business.

GSG makes it easy to output paths in your templates that derive from the current query string (or POST array, translated to GET format) but differ in arbitrary ways. Set new parameters and unset existing ones at will, and GSG will output consistent paths in your templates to your requirements.

Example use cases include "results per-page" links, search result filters etc - basically anywhere that you want to manipulate the current page's query string and output it back to your template.

Parameters
----------

**unset="foo|bar|baz"**: An optional, bar (|) delimited list of keys to remove from the generated query string.

**use="get|post|both"**: Should the starting query string be built from $_GET, $_POST, or the merged contents of the two. Defaults to "get".

**format="url|uri|relative_uri|query_string|string"**: What format should be used for the output? Select from (full) URL, site relative URI, page relative URI, query string (with trailing "?"), or string (just the url encoded .string, no trailing "?"). Defaults to "query_string"

Requirements
------------

EE 2.5.x (2.5.3 & 2.5.5 tested)

How stable is this thing?
-------------------------

I'm using it in production on a relatively decent sized site without issue, but given the relatively limited testing it's had in it's short life so far, it should probably be considered beta quality for now.

Bugs & Improvements
-------------------

For all bug reports please open a new issue on GitHub. Patches/pull requests welcome!