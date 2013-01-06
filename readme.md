Dropbox Conflict Merger
=======================

A Dropbox Webapp that searches for Conflicted Copys in your entire box and presents
a dialog to resolve this conflicts.


Version
-------

0.2-beta

This is a Beta release.  
Please ensure that the merge results contain the data you wanted and give me some [feedback](https://github.com/Xiphe/DropboxConflictMerger/issues) about how this is working for you. Thank you!


Support
-------

I've written this project for my own needs and don't want money from you to use it. So i am not willing to give full support. Anyway, i am very interested in any bugs, hints, requests or whatever.


Dependencies
-----------

Relies on [Dropbox-PHP](http://www.dropbox-php.com/) witch requires [PHP OAuth extension](http://pecl.php.net/package/oauth) or [PEAR's HTTP_OAUTH package](http://pear.php.net/package/http_oauth)


Uses
----

* [Eden](http://www.eden-php.com/)
* [HTML](https://github.com/Xiphe/HTML)
* THETOOLS
* [FirePHP](http://www.firephp.org/)


Installation/Usage
------------------

I host this project [here](http://dbcm.xiphe.net/).  
It's Open Souce so you can see what i'm doing, give me some tips or take parts of this work for your own project.  
I please you not to host this as it is on just another domain. We do not want to have the same service on two locations. - Thank you.


Changelog
---------

* 0.2-beta: First Public version.


Todo
----

* Enhance code structures. Things got a bit messed up.
    * The Main logic in `src/Xiphe/DropboxConflictMerger/` is not autonomous.
    * Css/Js are not minified and not placed in relation to their modules.
* Correct Spelling (Help please)
* Code Documentation
* Ensure THEDEBUG is not used in productive mode.
* Pages should not be in the views folder of dbcm.


License
-------

Copyright (C) 2013 Hannes Diercks

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.