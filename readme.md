# Simple captcha generator

Captcha generator

## Requirements
Please make sure you have installed and configured the following:
0. PHP (min v.4.3.0)
1. GD lib installed and enabled.
2. Replace ```fontname.ttf``` on this line

```$selectedFont = "fontname.ttf";``` with your favourite font.

If you're using sharing hosting, please contact your provider for confirmation you have installed those.

## Installation and usage
This generator generates an image (PNG).

In your form you can use as described in the code below:
```
<img src="captcha.php" />
```

### Digging deeper
The file captcha.php has comments inside which will guide you how to set up the captcha generator at your wish.