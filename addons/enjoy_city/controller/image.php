<?php
class Image {
	const CENTER = 1;
	const TOP = 2;
	const LEFT = 3;
	const BOTTOM = 4;
	const RIGHT = 5;
	const TOP_LEFT = 6;
	const TOP_RIGHT = 7;
	const BOTTOM_LEFT = 8;
	const BOTTOM_RIGHT = 9;
	const WATERMARK_DIAGONAL_POS = "pos";
	const WATERMARK_DIAGONAL_NEG = "neg";
	/**
	 * @var int Default output image quality
	 *
	 */
	public $quality = 75;
	private $image, $filename, $original_info, $imagestring;
	private $width, $height, $scale, $fixed_given_size, $keep_ratio, $given_width, $given_height, $bg_color, $angle;
	private $watermark = array();

	/**
	 * Destroy image resource
	 *
	 */
	function __destruct() {
		$this->destructImg($this->image);
	}
	public function destructImg($img) {
		if( $img !== null && get_resource_type($img) === 'gd' ) {
			imagedestroy($img);
		}
	}
	/**
	 * Load an image
	 *
	 * @param string $filename
	 *        	Path to image file
	 * @return \com\jdk5\blog\Image\Image
	 * @throws Exception
	 */
	function load($filename) {
		// Require GD library
		if (! extension_loaded ( 'gd' )) {
			throw new \Exception ( 'Required extension GD is not loaded.' );
		}
		$this->filename = $filename;
		return $this->get_meta_data ();
	}

	/**
	 * Get meta data of image or base64 string
	 *
	 * @param string|null $imagestring
	 *        	If omitted treat as a normal image
	 * @return \com\jdk5\blog\Image\Image
	 * @throws Exception
	 *
	 */
	protected function get_meta_data() {
		// gather meta data
		if (empty ( $this->imagestring )) {
			$info = getimagesize ( $this->filename );

			switch ($info ['mime']) {
				case 'image/gif' :
					$this->image = imagecreatefromgif ( $this->filename );
					break;
				case 'image/jpeg' :
					$this->image = imagecreatefromjpeg ( $this->filename );
					break;
				case 'image/png' :
					$this->image = imagecreatefrompng ( $this->filename );
					break;
				default :
					throw new \Exception ( 'Invalid image: ' . $this->filename );
					break;
			}
		} elseif (function_exists ( 'getimagesizefromstring' )) {
			$info = getimagesizefromstring ( $this->imagestring );
		} else {
			throw new \Exception ( 'PHP 5.4 is required to use method getimagesizefromstring' );
		}

		$this->original_info = array (
			'width' => $info [0],
			'height' => $info [1],
			'orientation' => $this->get_orientation (),
			'exif' => function_exists ( 'exif_read_data' ) && $info ['mime'] === 'image/jpeg' && $this->imagestring === null ? $this->exif = @exif_read_data ( $this->filename ) : null,
			'format' => preg_replace ( '/^image\//', '', $info ['mime'] ),
			'mime' => $info ['mime'],
			'info' => $info
		);
		$this->width = 0;
		$this->height = 0;
		$this->scale = 0;
		$this->fixed_given_size = false;
		$this->keep_ratio = false;
		$this->given_width = 0;
		$this->given_height=0;
		$this->bg_color = null;
		$this->angle = 0;
		$this->watermark = array();

		imagesavealpha ( $this->image, true );
		imagealphablending ( $this->image, true );

		return $this;
	}

	/**
	 * Get the current orientation
	 * @return string portrait|landscape|square
	 */
	function get_orientation() {
		if (imagesx ( $this->image ) > imagesy ( $this->image )) {
			return 'landscape';
		}
		if (imagesx ( $this->image ) < imagesy ( $this->image )) {
			return 'portrait';
		}
		return 'square';
	}

	/**
	 * set generete image is fixed given size
	 * @param boolean $fixed_given_size
	 * @return \com\jdk5\blog\Image\Image
	 */
	function fixed_given_size($fixed_given_size) {
		$this->fixed_given_size = $fixed_given_size;
		return $this;
	}

	/**
	 * set generete image is fixed_given_size
	 * @param boolean $fixed_given_size
	 * @return \com\jdk5\blog\Image\Image
	 */
	function keep_ratio($keep_ratio) {
		$this->keep_ratio = $keep_ratio;
		return $this;
	}

	/**
	 * set image width
	 * @param int $width
	 * @return \com\jdk5\blog\Image\Image
	 */
	function width($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * set image height
	 * @param int $height
	 * @return \com\jdk5\blog\Image\Image
	 */
	function height($height) {
		$this->height = $height;
		return $this;
	}

	/**
	 * set image width and height
	 * @param int $width
	 * @param int $height
	 * @return \com\jdk5\blog\Image\Image
	 */
	function size($width, $height){
		$this->width = $width;
		$this->height = $height;
		return $this;
	}

	/**
	 * set image scale
	 * @param double $scale
	 * @return \com\jdk5\blog\Image\Image
	 */
	function scale($scale){
		$this->width = $this->original_info['width'] * $scale;
		$this->height = $this->original_info['height'] * $scale;
		return $this;
	}

	/**
	 * Save an image
	 *
	 * The resulting format will be determined by the file extension.
	 *
	 * @param null|string $filename
	 *        	If omitted - original file will be overwritten
	 * @param null|int $quality
	 *        	Output image quality in percents 0-100
	 * @param null|string $format
	 *        	The format to use; determined by file extension if null
	 *
	 * @return \com\jdk5\blog\Image\Image
	 * @throws Exception
	 *
	 */
	function save($filename = null, $quality = null, $format = null) {
		if ($this->angle) {
			$image = $this->do_rotate($this->image, $this->angle, $this->bg_color);
			$this->destructImg($this->image);
			$this->image = $image;
			// Update meta data
			$this->original_info['width'] = imagesx($this->image);
			$this->original_info['height'] = imagesy($this->image);
		}
		$this->resize();
		if ($this->keep_ratio) {
			$this->resize_image_and_keep_ratio();
		}
		if (!empty($this->watermark)) {
			$this->do_watermark();
		}
		// Determine quality, filename, and format
		$quality = $quality ?  : $this->quality;
		$filename = $filename ?  : $this->filename;
		if (! $format) {
			$format = $this->file_ext ( $filename ) ?  : $this->original_info ['format'];
		}

		// Create the image
		switch (strtolower ( $format )) {
			case 'gif' :
				$result = imagegif ( $this->image, $filename );
				break;
			case 'jpg' :
				imageinterlace ( $this->image, true );
				$result = imagejpeg ( $this->image, $filename, round ( $quality ) );
				break;
			case 'jpeg' :
				imageinterlace ( $this->image, true );
				$result = imagejpeg ( $this->image, $filename, round ( $quality ) );
				break;
			case 'png' :
				$result = imagepng ( $this->image, $filename, round ( 9 * $quality / 100 ) );
				break;
			default :
				throw new \Exception ( 'Unsupported format' );
		}

		if (! $result) {
			throw new \Exception( 'Unable to save image: ' . $filename );
		}

		return $this;
	}

	private function do_rotate($image, $angle, $color = null) {
		// Perform the rotation
		if (empty($color)) {
			$bg_color = imagecolorallocatealpha($image, 0, 0, 0, 127);
		} else {
			$rgba = $this->normalize_color($color);
			$bg_color = imagecolorallocatealpha($image, $rgba['r'], $rgba['g'], $rgba['b'], $rgba['a']);
		}
		$new = imagerotate($image, -($this->keep_within($angle, -360, 360)), $bg_color, 0);
		imagesavealpha($new, true);
		imagealphablending($new, true);

		return $new;
	}

	/**
	 * Converts a hex color value to its RGB equivalent
	 *
	 * @param string        $color  Hex color string, array(red, green, blue) or array(red, green, blue, alpha).
	 *                              Where red, green, blue - integers 0-255, alpha - integer 0-127
	 *
	 * @return array|bool
	 *
	 */
	protected function normalize_color($color) {

		if (is_string($color)) {

			$color = trim($color, '#');

			if (strlen($color) == 6) {
				list($r, $g, $b) = array(
					$color[0].$color[1],
					$color[2].$color[3],
					$color[4].$color[5]
				);
			} elseif (strlen($color) == 3) {
				list($r, $g, $b) = array(
					$color[0].$color[0],
					$color[1].$color[1],
					$color[2].$color[2]
				);
			} else {
				return false;
			}
			return array(
				'r' => hexdec($r),
				'g' => hexdec($g),
				'b' => hexdec($b),
				'a' => 0
			);

		} elseif (is_array($color) && (count($color) == 3 || count($color) == 4)) {

			if (isset($color['r'], $color['g'], $color['b'])) {
				return array(
					'r' => $this->keep_within($color['r'], 0, 255),
					'g' => $this->keep_within($color['g'], 0, 255),
					'b' => $this->keep_within($color['b'], 0, 255),
					'a' => $this->keep_within(isset($color['a']) ? $color['a'] : 0, 0, 127)
				);
			} elseif (isset($color[0], $color[1], $color[2])) {
				return array(
					'r' => $this->keep_within($color[0], 0, 255),
					'g' => $this->keep_within($color[1], 0, 255),
					'b' => $this->keep_within($color[2], 0, 255),
					'a' => $this->keep_within(isset($color[3]) ? $color[3] : 0, 0, 127)
				);
			}

		}
		return false;
	}

	/**
	 * Ensures $value is always within $min and $max range.
	 *
	 * If lower, $min is returned. If higher, $max is returned.
	 *
	 * @param int|float $value
	 * @param int|float $min
	 * @param int|float $max
	 *
	 * @return int|float
	 *
	 */
	protected function keep_within($value, $min, $max) {
		if ($value < $min) {
			return $min;
		}

		if ($value > $max) {
			return $max;
		}

		return $value;
	}

	/**
	 * Resize an image to the specified dimensions
	 *
	 * @param int $width
	 * @param int $height
	 *
	 * @return \com\jdk5\blog\Image\Image
	 *
	 */
	private function resize() {
		$width = $this->original_info['width'];
		$height = $this->original_info['height'];
		if ($this->width > 0 && $this->height > 0) {
			if ($this->fixed_given_size) {
				$this->given_width = $this->width;
				$this->given_height = $this->height;
				if (!$this->keep_ratio){
					$width = $this->width;
					$height = $this->height;
				}
			}
			if ($this->keep_ratio){
				$drawWidth = $this->width;
				$drawHeight = $this->height;
				$sourceRatio = doubleval( $width / $height);
				$targetRatio = doubleval( $this->width / $this->height);

				if ($sourceRatio != $targetRatio) {
					if ($sourceRatio > $targetRatio) {
						$drawHeight = round(round($this->width / $sourceRatio));
					} else {
						$drawWidth = round(round($this->height * $sourceRatio));
					}
				}
				if (!$this->fixed_given_size) {
					$this->given_width = $drawWidth;
					$this->given_height = $drawHeight;
				}
				$width = $drawWidth;
				$height = $drawHeight;
			}
		} else if ($this->scale > 0) {
			$width = round($width * $this->scale);
			$height = round($height * $this->scale);
		} else if ($this->width > 0 && $this->height == 0) {
			$height = round($this->width * $height / $width);
			$width = round($this->width);
		} else if ($this->width == 0 && $this->height > 0) {
			$width = round($this->height * $width / $height);
			$height = round($this->height);
		}
		if ($width <= 1 || $height <= 1) {
			throw new \Exception("width or height value error!");
		}
		$this->width = $width;
		$this->height = $height;

		$this->given_width = ($this->given_width == 0 ? $width : $this->given_width);
		$this->given_height = ($this->given_height == 0 ? $height : $this->given_height);

		$this->copy($width, $height, 0, 0, 0, 0, $width, $height);
	}

	/**
	 * copy original image to new size
	 * @param int $dst_w
	 * @param int $dst_h
	 * @param int $dst_x
	 * @param int $dst_y
	 * @param int $src_x
	 * @param int $src_y
	 * @param int $draw_w
	 * @param int $draw_h
	 * @return boolean
	 */
	private function copy($dst_w, $dst_h, $dst_x=0, $dst_y=0, $src_x=0, $src_y=0, $draw_w=0, $draw_h=0){
		// Generate new GD image
		$new = imagecreatetruecolor($dst_w, $dst_h);
		$draw_w = $draw_w == 0 ? $this->original_info['width'] : $draw_w;
		$draw_h = $draw_h == 0 ? $this->original_info['height'] : $draw_h;
		$this->setTransparency($new, $this->original_info['info'], $this->image);
		if (!empty($this->bg_color)) {
			$bg = imagecolorallocate($new, $this->bg_color['r'], $this->bg_color['g'], $this->bg_color['b']);
			imagefill($new, 0, 0, $bg);
		}
		// Resize
		$flag = imagecopyresampled ( $new, $this->image, $dst_x, $dst_y, $src_x, $src_y, $draw_w, $draw_h,
			$this->original_info['width'], $this->original_info['height'] );
		if ($flag) {
			$this->destructImg($this->image);
			$this->image = $new;
			$this->original_info['width'] = $dst_w;
			$this->original_info['height'] = $dst_h;
		} else {
			throw new \Exception ( 'copy image error' );
		}
		return $flag;
	}

	/**
	 * 设置透明背景
	 * @param image
	 * @param array
	 * @param image
	 */
	function setTransparency($dst, $info, $src) {
		switch ($info ['mime']) {
			case 'image/gif' :
				// Preserve transparency in GIFs
				$transparent_index = imagecolortransparent($src);
				$palletsize = imagecolorstotal($src);
				if ($transparent_index >= 0 && $transparent_index < $palletsize) {
					$transparent_color = imagecolorsforindex($src, $transparent_index);
					$transparent_index = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
					imagefill($dst, 0, 0, $transparent_index);
					imagecolortransparent($dst, $transparent_index);
				}
				break;
			default:
				imagealphablending($dst, false);
				imagesavealpha($dst, true);
				$color = imagecolorallocatealpha($dst, 0, 0, 0, 127);
				imagefill($dst, 0, 0, $color);
				break;
		}
	}

	/**t
	 * 等比例压缩图片,支持图片格式jpg,jpeg,png
	 * @param string $dst_dir	上传的文件夹
	 * @param string $dst_name	上传后的名称，不包括扩展名
	 * @param int $maxWidth	如果需要等比例压缩图片，指定压缩后的最大宽度，默认为200
	 * @param int $maxHeight	如果需要等比例压缩图片，指定压缩后的最大高度，默认为200
	 * @return boolean	成功返回true，否则返回false
	 */
	private function resize_image_and_keep_ratio() {
		//设置描绘的x、y坐标，高度、宽度
		$dst_x = $dst_y = $src_x = $src_y = 0;
		$ratio = min ( $this->given_height / $this->original_info['height'], $this->given_width / $this->original_info['width'] );
		$dst_h = ceil ( $this->original_info['height'] * $ratio );
		$dst_w = ceil ( $this->original_info['width'] * $ratio );
		$dst_x = ($this->given_width - $dst_w)/2;
		$dst_y = ($this->given_height - $dst_h)/2;
		$this->width = $this->given_width;
		$this->height = $this->given_height;
		return $this->copy($this->given_width, $this->given_height, $dst_x, $dst_y, $src_x, $src_y,
			$dst_w, $dst_h);
	}

	/**
	 * watermark
	 *
	 * Overlay an image on top of another, works with 24-bit PNG alpha-transparency
	 *
	 * @param string        $overlay        An image filename or a Image object
	 * @param string        $position       center|top|left|bottom|right|top left|top right|bottom left|bottom right
	 * @param float|int     $opacity        Overlay opacity 0-1
	 * @param int           $x_offset       Horizontal offset in pixels
	 * @param int           $y_offset       Vertical offset in pixels
	 *
	 * @return Image
	 *
	 */
	private function do_watermark() {
		$img;
		if (!empty ( $this->watermark['filename'])) {
			$info = getimagesize ( $this->watermark['filename'] );
			$isgif = false;
			switch ($info ['mime']) {
				case 'image/gif' :
					$img = imagecreatefromgif ( $this->watermark['filename'] );
					$isgif = true;
					break;
				case 'image/jpeg' :
					$img = imagecreatefromjpeg ( $this->watermark['filename'] );
					break;
				case 'image/png' :
					$img = imagecreatefrompng ( $this->watermark['filename'] );
					break;
				default :
					throw new \Exception ( 'Invalid image: ' . $this->watermark['filename'] );
					break;
			}
		} elseif (function_exists ( 'getimagesizefromstring' )) {
			$info = getimagesizefromstring ( $this->imagestring );
		} else {
			throw new \Exception ( 'PHP 5.4 is required to use method getimagesizefromstring' );
		}

		$ww = $info [0];
		$wh = $info [1];
		if (!empty($this->watermark['angle'])) {
			switch ($this->watermark['angle']) {
				case self::WATERMARK_DIAGONAL_POS :
					$rad = atan($this->height / $this->width);
					$angle  = rad2deg($rad);
					break;
				case self::WATERMARK_DIAGONAL_NEG :
					$rad = atan($this->height / $this->width);
					$angle  = -rad2deg($rad);
					break;
				default:
					$angle = intval($this->watermark['angle']);
					break;
			}
			$i = $this->do_rotate($img, $angle);
			$this->destructImg($img);
			$img = $i;
			$ww = imagesx($img);
			$wh = imagesy($img);
		}
		imagesavealpha ( $img, true );
		imagealphablending ( $img, true );
		if($ww <= $this->width && $wh <= $this->height) {
			$drawWidth = $ww;
			$drawHeight = $wh;
		} else {
			$drawWidth = $this->width;
			$drawHeight = $this->height;
			$sourceRatio = doubleval( $ww / $wh );
			$targetRatio = doubleval( $this->width / $this->height );

			if ($sourceRatio != $targetRatio) {
				if ($sourceRatio > $targetRatio) {
					$drawHeight = round ($drawWidth * $wh / $ww);
				} else {
					$drawWidth = round(round($ww * $drawHeight / $wh));
				}
			}
		}
		$dst = imagecreatetruecolor($drawWidth, $drawHeight);
		$this->setTransparency($dst, $info, $this->image);
		imagecopyresampled($dst, $img, 0, 0, 0, 0, $drawWidth, $drawHeight, $ww, $wh);
		// Convert opacity
		$opacity = $this->watermark['opacity'] * 100;
		// Determine position
		switch ($this->watermark['position']) {
			case self::TOP_LEFT:
				$x = 0 + $this->watermark['x_offset'];
				$y = 0 + $this->watermark['y_offset'];
				break;
			case self::TOP_RIGHT :
				$x = $this->width - $drawWidth + $this->watermark['x_offset'];
				$y = 0 + $this->watermark['y_offset'];
				break;
			case self::TOP:
				$x = ($this->width / 2) - ($drawWidth / 2) + $this->watermark['x_offset'];
				$y = 0 + $this->watermark['y_offset'];
				break;
			case self::BOTTOM_LEFT:
				$x = 0 + $this->watermark['x_offset'];
				$y = $this->height - $drawHeight + $this->watermark['y_offset'];
				break;
			case self::BOTTOM_RIGHT:
				$x = $this->width - $drawWidth + $this->watermark['x_offset'];
				$y = $this->height - $drawHeight + $this->watermark['y_offset'];
				break;
			case self::BOTTOM:
				$x = ($this->width / 2) - ($drawWidth / 2) + $this->watermark['x_offset'];
				$y = $this->height - $drawHeight + $this->watermark['y_offset'];
				break;
			case self::LEFT:
				$x = 0 + $this->watermark['x_offset'];
				$y = ($this->height / 2) - ($drawHeight / 2) + $this->watermark['y_offset'];
				break;
			case self::RIGHT:
				$x = $this->width - $drawWidth + $this->watermark['x_offset'];
				$y = ($this->height / 2) - ($drawHeight / 2) + $this->watermark['y_offset'];
				break;
			case self::CENTER:
			default:
				$x = ($this->width / 2) - ($drawWidth / 2) + $this->watermark['x_offset'];
				$y = ($this->height / 2) - ($drawHeight / 2) + $this->watermark['y_offset'];
				break;
		}
		// Perform the overlay
		$this->imagecopymerge_alpha($this->image, $dst, $x, $y, 0, 0, $drawWidth, $drawHeight, $opacity);
		$this->destructImg($dst);
		return $this;
	}

	/**
	 * Same as PHP's imagecopymerge() function, except preserves alpha-transparency in 24-bit PNGs
	 *
	 * @param $dst_im
	 * @param $src_im
	 * @param $dst_x
	 * @param $dst_y
	 * @param $src_x
	 * @param $src_y
	 * @param $src_w
	 * @param $src_h
	 * @param $pct
	 *
	 * @link http://www.php.net/manual/en/function.imagecopymerge.php#88456
	 *
	 */
	protected function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		// Get image width and height and percentage
		$pct /= 100;
		$w = imagesx($src_im);
		$h = imagesy($src_im);
		// Turn alpha blending off
		imagealphablending($src_im, false);
		// Find the most opaque pixel in the image (the one with the smallest alpha value)
		$minalpha = 127;
		for ($x = 0; $x < $w; $x++) {
			for ($y = 0; $y < $h; $y++) {
				$alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
				if ($alpha < $minalpha) {
					$minalpha = $alpha;
				}
			}
		}
		// Loop through image pixels and modify alpha for each
		for ($x = 0; $x < $w; $x++) {
			for ($y = 0; $y < $h; $y++) {
				// Get current alpha value (represents the TANSPARENCY!)
				$colorxy = imagecolorat($src_im, $x, $y);
				$alpha = ($colorxy >> 24) & 0xFF;
				// Calculate new alpha
				if ($minalpha !== 127) {
					$alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
				} else {
					$alpha += 127 * $pct;
				}
				// Get the color index with new alpha
				$alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
				// Set pixel with the new color + opacity
				if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
					return;
				}
			}
		}
		// Copy it
		imagesavealpha($dst_im, true);
		imagealphablending($dst_im, true);
		imagesavealpha($src_im, true);
		imagealphablending($src_im, true);
		imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	}

	/**
	 * Returns the file extension of the specified file
	 *
	 * @param string $filename
	 *
	 * @return string
	 *
	 */
	protected function file_ext($filename) {
		if (! preg_match ( '/\./', $filename )) {
			return '';
		}

		return preg_replace ( '/^.*\./', '', $filename );
	}

	/**
	 * Changes the quality of the image
	 * @param float|int $opacity 0-100
	 * @return \com\jdk5\blog\Image\Image
	 */
	function quality($quality) {
		$this->quality = $quality;
		return $this;
	}

	/**
	 * Rotate an image
	 *
	 * @param int           $angle      0-360
	 * @param string        $bg_color   Hex color string, array(red, green, blue) or array(red, green, blue, alpha).
	 *                                  Where red, green, blue - integers 0-255, alpha - integer 0-127
	 *
	 * @return Image
	 *
	 */
	function rotate($angle) {
		$this->angle = $angle;
		return $this;
	}

	/**
	 * set background color
	 * @param array $bgcolor
	 * @return \com\jdk5\blog\Image\Image
	 */
	function bg_color($bg_color){
		$this->bg_color = $this->normalize_color($bg_color);
		return $this;
	}

	/**
	 * watermark
	 *
	 * Overlay an image on top of another, works with 24-bit PNG alpha-transparency
	 *
	 * @param array			$params		example:
	 * array(
	 * 		"filename" => "watermarkater.png",	//水印文件
	 * 		"position" => self::CENTER,	//水印的位置，分别为:center|top|left|bottom|right|top left|top right|bottom left|bottom right
	 * 		"opacity" => 1,	//水印的透明度，可以为0-1的任意数值，默认为1
	 * 		"x_offset" => 0,	//加水印的x轴偏移量，默认为0
	 * 		"y_offset" => 0,	//加水印的y轴偏移量，默认为0
	 * 		"angle" => self::WATERMARK_DIAGONAL_NEG	//水印的旋转角度，可以为-360-360，如果为WATERMARK_DIAGONAL_POS或WATERMARK_DIAGONAL_NEG，则沿着生成图片的对角线旋转，默认为0
	 * @return Image
	 */
	public function set_watermark($params) {
		//$filename, $position = self::CENTER, $opacity = 1, $x_offset = 0, $y_offset = 0, $angle = ""
		if (!file_exists($params['filename'])) {
			throw new \Exception ( 'watermark file not exist!' );
		}
		isset($params['opacity']) ? "" : $params['opacity'] = 1;
		isset($params['x_offset']) ? "" : $params['x_offset'] = 0;
		isset($params['y_offset']) ? "" : $params['y_offset'] = 0;
		isset($params['angle']) ? "" : $params['angle'] = "";
		$this->watermark = $params;
		return $this;
	}
}