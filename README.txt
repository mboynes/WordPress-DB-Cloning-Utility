 ============
 = Overview =
 ============
This is a little program used to copy data from a Multisite WordPress MySQL database to some other Single-site Wordpress Database. Or at least, that's what it's intended to do... but it's got some moxie, so bend it to do as you please.


 ===================
 = Getting Started =
 ===================
1. Create config.php (use config.sample.php as your guide) with the database info and the path to your local mysql and mysqldump binaries.
2. Make your /data/ directory writable by apache
3. Navigate to the path in your browser.

This is pretty cut and dry at the moment. In the pipeline, I'll tweak this to allow you to be able to...
1. Use this via CLI


 ===========
 = License =
 ===========
Copyright (c) 2010 Matt Boynes

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.