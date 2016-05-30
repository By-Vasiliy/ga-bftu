# GA–Spider ![](https://ga-spi.appspot.com/t/ga-spider/readme?gtid=UA-77529928-5)
(*GA–Spider — Application for tracking users through pictures and proxy links*)

# Installation in Google App Engine
Use commands in cloud shell:
```
git clone https://github.com/By-Vasiliy/ga-spider.git gas
cd gas
appcfg.py -A Your_App_Name update .
```

# Using
First, log in to your Google Analytics account and [set up a new property](https://ga-spi.appspot.com/t/ga-spider/readme?gtid=UA-77529928-5&mr&mgo&go=https://support.google.com/analytics/answer/1042508?hl=en):

* Select "Website", use new "Universal Analytics" tracking
* **Website name:** anything you want (e.g. GitHub tracking)
* **WebSite URL: [https://ga-spider.appspot.com/](https://ga-spi.appspot.com/t/ga-spider/readme?gtid=UA-77529928-5&mr&mgo&go=https://ga-spider.appspot.com/)**
* Click "Get Tracking ID", copy the `UA-XXXXX-X` ID on next page

Next, add a tracking image to the pages you want to track:

* **https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X**
* `UA-XXXXX-X` should be your tracking ID
* `test/path` is an arbitrary path. For best results specify a meaningful and self-descriptive path. You have to do this manually, the beacon won't automatically record the page path it's embedded on.

You can also use links to track when you click on it

* https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X?go=goaddress
* `UA-XXXXX-X` should be your tracking ID
* `goaddress` link to go


Example tracker markup if you are using Markdown:

```
[![Analytics](https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X)](https://github.com/By-Vasiliy/ga-spider)
```

```
[goaddress](https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X?go=goaddress)
```

Or RDoc:

```
{<img src="https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X" />}[https://github.com/By-Vasiliy/ga-spider]
```

```
{goaddress}[https://ga-spider.appspot.com/t/test/path?gtid=UA-XXXXX-X?go=goaddress]
```

# License
Software distributed under the [MIT](https://ga-spi.appspot.com/t/ga-spider/readme?gtid=UA-77529928-5&mr&mgo&go=https://git.io/vrz7B) license.

# Copyright (c) 2016 Vasilyuk Vasiliy