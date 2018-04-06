# PHP-Identicons

![a single icon](http://lehollandaisvolant.net/img/3b/2.png)

PHP-Identicons is a lightweight PHP implementation of Don Park's
original identicon code for visual representation of MD5 hash values.
The program uses the PHP GD library for image processing.

The code can be used to generate unique identicons, avatars, and
system-assigned images based on a user's e-mail address, user ID, etc.

![multiple icons examples](https://lehollandaisvolant.net/img/87/multi.png)


## INSTALLATION

Simply save identicon.php somewhere accessible thru a Web URL.


## USAGE

PHP-Identicons requires the size (in pixels) and an MD5 hash of
anything that will uniquely identify a user - usually an e-mail address
or a user ID. You can also use MD5 hashes of IP addresses.

Insert the URL in your HTML image tag that looks something like:

```html
<img src="path/to/identicon.php?size=48&hash=e4d909c290d0fb1ca068ffaddf22cbd0" />
```

And that's all there is to it!


## LICENSE

PHP-Identicons is distributed under the [GPLv3 License](http://www.gnu.org/licenses/gpl-3.0.fr.html).


## HISTORY

This code was created by Bong Costa in 2009.
It has been forked from its [project page on SourceForge](https://sourceforge.net/projects/identicons/) as I intend to enhance it a bit for personal usage.

