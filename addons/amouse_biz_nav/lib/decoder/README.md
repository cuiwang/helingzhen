#php qr decoder
> php识别二维码, 不需要安装扩展


###使用
```
include_once('./lib/QrReader.php');
$qrcode = new QrReader('path/to_image');  //图片路径
$text = $qrcode->text(); //返回识别后的文本
```

### 需要
```
PHP >= 5.3
GD Library
```