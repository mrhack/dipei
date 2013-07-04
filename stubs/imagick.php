<?php
class Imagick implements Iterator , Traversable {

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param int $channel
	**/
	public function adaptiveBlurImage ( $radius, $sigma, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	* @param bool $bestfit
	**/
	public function adaptiveResizeImage ( $columns, $rows, $bestfit = false ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param int $channel
	**/
	public function adaptiveSharpenImage ( $radius, $sigma, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $offset
	**/
	public function adaptiveThresholdImage ( $width, $height, $offset ) {
	}

	/**
	* @return bool
	* @param Imagick $source
	**/
	public function addImage ( $source ) {
	}

	/**
	* @return bool
	* @param int $noise_type
	* @param int $channel
	**/
	public function addNoiseImage ( $noise_type, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param ImagickDraw $matrix
	**/
	public function affineTransformImage ( $matrix ) {
	}

	/**
	* @return bool
	* @param string $x_server
	**/
	public function animateImages ( $x_server ) {
	}

	/**
	* @return bool
	* @param ImagickDraw $draw_settings
	* @param float $x
	* @param float $y
	* @param float $angle
	* @param string $text
	**/
	public function annotateImage ( $draw_settings, $x, $y, $angle, $text ) {
	}

	/**
	* @return Imagick
	* @param bool $stack
	**/
	public function appendImages ( $stack ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function averageImages (  ) {
	}

	/**
	* @return bool
	* @param mixed $threshold
	**/
	public function blackThresholdImage ( $threshold ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param int $channel
	**/
	public function blurImage ( $radius, $sigma, $channel ) {
	}

	/**
	* @return bool
	* @param mixed $bordercolor
	* @param int $width
	* @param int $height
	**/
	public function borderImage ( $bordercolor, $width, $height ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	**/
	public function charcoalImage ( $radius, $sigma ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function chopImage ( $width, $height, $x, $y ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function clear (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function clipImage (  ) {
	}

	/**
	* @return bool
	* @param string $pathname
	* @param bool $inside
	**/
	public function clipPathImage ( $pathname, $inside ) {
	}

	/**
	* @return bool
	* @param Imagick $lookup_table
	* @param float $channel
	**/
	public function clutImage ( $lookup_table, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function coalesceImages (  ) {
	}

	/**
	* @return bool
	* @param mixed $fill
	* @param float $fuzz
	* @param mixed $bordercolor
	* @param int $x
	* @param int $y
	**/
	public function colorFloodfillImage ( $fill, $fuzz, $bordercolor, $x, $y ) {
	}

	/**
	* @return bool
	* @param mixed $colorize
	* @param mixed $opacity
	**/
	public function colorizeImage ( $colorize, $opacity ) {
	}

	/**
	* @return Imagick
	* @param int $channelType
	**/
	public function combineImages ( $channelType ) {
	}

	/**
	* @return bool
	* @param string $comment
	**/
	public function commentImage ( $comment ) {
	}

	/**
	* @return array
	* @param Imagick $image
	* @param int $channelType
	* @param int $metricType
	**/
	public function compareImageChannels ( $image, $channelType, $metricType ) {
	}

	/**
	* @return Imagick
	* @param int $method
	**/
	public function compareImageLayers ( $method ) {
	}

	/**
	* @return array
	* @param Imagick $compare
	* @param int $metric
	**/
	public function compareImages ( $compare, $metric ) {
	}

	/**
	* @return bool
	* @param Imagick $composite_object
	* @param int $composite
	* @param int $x
	* @param int $y
	* @param int $channel
	**/
	public function compositeImage ( $composite_object, $composite, $x, $y, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return Imagick
	* @param mixed $files
	**/
	public function __construct ( $files ) {
	}

	/**
	* @return bool
	* @param bool $sharpen
	**/
	public function contrastImage ( $sharpen ) {
	}

	/**
	* @return bool
	* @param float $black_point
	* @param float $white_point
	* @param int $channel
	**/
	public function contrastStretchImage ( $black_point, $white_point, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param array $kernel
	* @param int $channel
	**/
	public function convolveImage ( $kernel, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function cropImage ( $width, $height, $x, $y ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	**/
	public function cropThumbnailImage ( $width, $height ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function current (  ) {
	}

	/**
	* @return bool
	* @param int $displace
	**/
	public function cycleColormapImage ( $displace ) {
	}

	/**
	* @return bool
	* @param string $passphrase
	**/
	public function decipherImage ( $passphrase ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function deconstructImages (  ) {
	}

	/**
	* @return bool
	* @param string $artifact
	**/
	public function deleteImageArtifact ( $artifact ) {
	}

	/**
	* @return void
	* @param float $threshold
	**/
	public function deskewImage ( $threshold ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function despeckleImage (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function destroy (  ) {
	}

	/**
	* @return bool
	* @param string $servername
	**/
	public function displayImage ( $servername ) {
	}

	/**
	* @return bool
	* @param string $servername
	**/
	public function displayImages ( $servername ) {
	}

	/**
	* @return bool
	* @param int $method
	* @param array $arguments
	* @param bool $bestfit
	**/
	public function distortImage ( $method, $arguments, $bestfit ) {
	}

	/**
	* @return bool
	* @param ImagickDraw $draw
	**/
	public function drawImage ( $draw ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function edgeImage ( $radius ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	**/
	public function embossImage ( $radius, $sigma ) {
	}

	/**
	* @return bool
	* @param string $passphrase
	**/
	public function encipherImage ( $passphrase ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function enhanceImage (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function equalizeImage (  ) {
	}

	/**
	* @return bool
	* @param int $op
	* @param float $constant
	* @param int $channel
	**/
	public function evaluateImage ( $op, $constant, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return array
	* @param int $x
	* @param int $y
	* @param int $width
	* @param int $height
	* @param string $map
	* @param int $STORAGE
	**/
	public function exportImagePixels ( $x, $y, $width, $height, $map, $STORAGE ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function extentImage ( $width, $height, $x, $y ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function flattenImages (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function flipImage (  ) {
	}

	/**
	* @return bool
	* @param mixed $fill
	* @param float $fuzz
	* @param mixed $target
	* @param int $x
	* @param int $y
	* @param bool $invert
	* @param int $channel
	**/
	public function floodFillPaintImage ( $fill, $fuzz, $target, $x, $y, $invert, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function flopImage (  ) {
	}

	/**
	* @return bool
	* @param mixed $matte_color
	* @param int $width
	* @param int $height
	* @param int $inner_bevel
	* @param int $outer_bevel
	**/
	public function frameImage ( $matte_color, $width, $height, $inner_bevel, $outer_bevel ) {
	}

	/**
	* @return boolean
	* @param integer $function
	* @param array $arguments
	**/
	public function functionImage ( $function, $arguments ) {
	}

	/**
	* @return Imagick
	* @param string $expression
	* @param int $channel
	**/
	public function fxImage ( $expression, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param float $gamma
	* @param int $channel
	**/
	public function gammaImage ( $gamma, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param int $channel
	**/
	public function gaussianBlurImage ( $radius, $sigma, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getColorspace (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getCompression (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getCompressionQuality (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getCopyright (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getFilename (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getFont (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getFormat (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function getGravity (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getHomeURL (  ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function getImage (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageAlphaChannel (  ) {
	}

	/**
	* @return bool
	* @param string $artifact
	**/
	public function getImageArtifact ( $artifact ) {
	}

	/**
	* @return ImagickPixel
	* @param void
	**/
	public function getImageBackgroundColor (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImageBlob (  ) {
	}

	/**
	* @return array
	* @param float $x
	* @param float $y
	**/
	public function getImageBluePrimary ( $x, $y ) {
	}

	/**
	* @return ImagickPixel
	* @param void
	**/
	public function getImageBorderColor (  ) {
	}

	/**
	* @return int
	* @param int $channel
	**/
	public function getImageChannelDepth ( $channel ) {
	}

	/**
	* @return float
	* @param Imagick $reference
	* @param int $channel
	* @param int $metric
	**/
	public function getImageChannelDistortion ( $reference, $channel, $metric ) {
	}

	/**
	* @return double
	* @param Imagick $reference
	* @param int $metric
	* @param int $channel
	**/
	public function getImageChannelDistortions ( $reference, $metric, $channel ) {
	}

	/**
	* @return array
	* @param int $channel
	**/
	public function getImageChannelExtrema ( $channel ) {
	}

	/**
	* @return array
	* @param int $channel
	**/
	public function getImageChannelKurtosis ( $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return array
	* @param int $channel
	**/
	public function getImageChannelMean ( $channel ) {
	}

	/**
	* @return bool
	* @param int $channel
	**/
	public function getImageChannelRange ( $channel ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageChannelStatistics (  ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function getImageClipMask (  ) {
	}

	/**
	* @return ImagickPixel
	* @param int $index
	**/
	public function getImageColormapColor ( $index ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageColors (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageColorspace (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageCompose (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageCompression (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getCompressionQuality (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageDelay (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageDepth (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageDispose (  ) {
	}

	/**
	* @return float
	* @param MagickWand $reference
	* @param int $metric
	**/
	public function getImageDistortion ( $reference, $metric ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageExtrema (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImageFilename (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImageFormat (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getImageGamma (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageGeometry (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function getImageGravity (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageGreenPrimary (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageHeight (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageHistogram (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageIndex (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageInterlaceScheme (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageInterpolateMethod (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageIterations (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageLength (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImageMagickLicense (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageMatte (  ) {
	}

	/**
	* @return ImagickPixel
	* @param void
	**/
	public function getImageMatteColor (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageOrientation (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImagePage (  ) {
	}

	/**
	* @return ImagickPixel
	* @param int $x
	* @param int $y
	**/
	public function getImagePixelColor ( $x, $y ) {
	}

	/**
	* @return string
	* @param string $name
	**/
	public function getImageProfile ( $name ) {
	}

	/**
	* @return array
	* @param string $pattern
	* @param bool $only_names
	**/
	public function getImageProfiles ( $pattern = "*", $only_names = true ) {
	}

	/**
	* @return array
	* @param string $pattern
	* @param bool $only_names
	**/
	public function getImageProperties ( $pattern = "*", $only_names = true ) {
	}

	/**
	* @return string
	* @param string $name
	**/
	public function getImageProperty ( $name ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageRedPrimary (  ) {
	}

	/**
	* @return Imagick
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function getImageRegion ( $width, $height, $x, $y ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageRenderingIntent (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageResolution (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImagesBlob (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageScene (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getImageSignature (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageSize (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageTicksPerSecond (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getImageTotalInkDensity (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageType (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageUnits (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageVirtualPixelMethod (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getImageWhitePoint (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getImageWidth (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getInterlaceScheme (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getIteratorIndex (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getNumberImages (  ) {
	}

	/**
	* @return string
	* @param string $key
	**/
	public function getOption ( $key ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getPackageName (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getPage (  ) {
	}

	/**
	* @return ImagickPixelIterator
	* @param void
	**/
	public function getPixelIterator (  ) {
	}

	/**
	* @return ImagickPixelIterator
	* @param int $x
	* @param int $y
	* @param int $columns
	* @param int $rows
	**/
	public function getPixelRegionIterator ( $x, $y, $columns, $rows ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getPointSize (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getQuantumDepth (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getQuantumRange (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getReleaseDate (  ) {
	}

	/**
	* @return int
	* @param int $type
	**/
	public function getResource ( $type ) {
	}

	/**
	* @return int
	* @param int $type
	**/
	public function getResourceLimit ( $type ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getSamplingFactors (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getSize (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getSizeOffset (  ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getVersion (  ) {
	}

	/**
	* @return boolean
	* @param Imagick $clut
	* @param int $channel
	**/
	public function haldClutImage ( $clut, $channel ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function hasNextImage (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function hasPreviousImage (  ) {
	}

	/**
	* @return array
	* @param bool $appendRawOutput
	**/
	public function identifyImage ( $appendRawOutput = false ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function implodeImage ( $radius ) {
	}

	/**
	* @return bool
	* @param int $x
	* @param int $y
	* @param int $width
	* @param int $height
	* @param string $map
	* @param int $storage
	* @param array $pixels
	**/
	public function importImagePixels ( $x, $y, $width, $height, $map, $storage, $pixels ) {
	}

	/**
	* @return bool
	* @param string $label
	**/
	public function labelImage ( $label ) {
	}

	/**
	* @return bool
	* @param float $blackPoint
	* @param float $gamma
	* @param float $whitePoint
	* @param int $channel
	**/
	public function levelImage ( $blackPoint, $gamma, $whitePoint, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param float $blackPoint
	* @param float $whitePoint
	**/
	public function linearStretchImage ( $blackPoint, $whitePoint ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param float $delta_x
	* @param float $rigidity
	**/
	public function liquidRescaleImage ( $width, $height, $delta_x, $rigidity ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function magnifyImage (  ) {
	}

	/**
	* @return bool
	* @param Imagick $map
	* @param bool $dither
	**/
	public function mapImage ( $map, $dither ) {
	}

	/**
	* @return bool
	* @param float $alpha
	* @param float $fuzz
	* @param mixed $bordercolor
	* @param int $x
	* @param int $y
	**/
	public function matteFloodfillImage ( $alpha, $fuzz, $bordercolor, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function medianFilterImage ( $radius ) {
	}

	/**
	* @return bool
	* @param int $layer_method
	**/
	public function mergeImageLayers ( $layer_method ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function minifyImage (  ) {
	}

	/**
	* @return bool
	* @param float $brightness
	* @param float $saturation
	* @param float $hue
	**/
	public function modulateImage ( $brightness, $saturation, $hue ) {
	}

	/**
	* @return Imagick
	* @param ImagickDraw $draw
	* @param string $tile_geometry
	* @param string $thumbnail_geometry
	* @param int $mode
	* @param string $frame
	**/
	public function montageImage ( $draw, $tile_geometry, $thumbnail_geometry, $mode, $frame ) {
	}

	/**
	* @return Imagick
	* @param int $number_frames
	**/
	public function morphImages ( $number_frames ) {
	}

	/**
	* @return Imagick
	* @param void
	**/
	public function mosaicImages (  ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param float $angle
	* @param int $channel
	**/
	public function motionBlurImage ( $radius, $sigma, $angle, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param bool $gray
	* @param int $channel
	**/
	public function negateImage ( $gray, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param int $cols
	* @param int $rows
	* @param mixed $background
	* @param string $format
	**/
	public function newImage ( $cols, $rows, $background, $format ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	* @param string $pseudoString
	**/
	public function newPseudoImage ( $columns, $rows, $pseudoString ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function nextImage (  ) {
	}

	/**
	* @return bool
	* @param int $channel
	**/
	public function normalizeImage ( $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function oilPaintImage ( $radius ) {
	}

	/**
	* @return bool
	* @param mixed $target
	* @param mixed $fill
	* @param float $fuzz
	* @param bool $invert
	* @param int $channel
	**/
	public function opaquePaintImage ( $target, $fill, $fuzz, $invert, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function optimizeImageLayers (  ) {
	}

	/**
	* @return bool
	* @param string $threshold_map
	* @param int $channel
	**/
	public function orderedPosterizeImage ( $threshold_map, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param mixed $fill
	* @param float $fuzz
	* @param mixed $bordercolor
	* @param int $x
	* @param int $y
	* @param int $channel
	**/
	public function paintFloodfillImage ( $fill, $fuzz, $bordercolor, $x, $y, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param mixed $target
	* @param mixed $fill
	* @param float $fuzz
	* @param int $channel
	**/
	public function paintOpaqueImage ( $target, $fill, $fuzz, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param mixed $target
	* @param float $alpha
	* @param float $fuzz
	**/
	public function paintTransparentImage ( $target, $alpha, $fuzz ) {
	}

	/**
	* @return bool
	* @param string $filename
	**/
	public function pingImage ( $filename ) {
	}

	/**
	* @return bool
	* @param string $image
	**/
	public function pingImageBlob ( $image ) {
	}

	/**
	* @return bool
	* @param resource $filehandle
	* @param string $fileName
	**/
	public function pingImageFile ( $filehandle, $fileName ) {
	}

	/**
	* @return bool
	* @param ImagickDraw $properties
	* @param float $angle
	**/
	public function polaroidImage ( $properties, $angle ) {
	}

	/**
	* @return bool
	* @param int $levels
	* @param bool $dither
	**/
	public function posterizeImage ( $levels, $dither ) {
	}

	/**
	* @return bool
	* @param int $preview
	**/
	public function previewImages ( $preview ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function previousImage (  ) {
	}

	/**
	* @return bool
	* @param string $name
	* @param string $profile
	**/
	public function profileImage ( $name, $profile ) {
	}

	/**
	* @return bool
	* @param int $numberColors
	* @param int $colorspace
	* @param int $treedepth
	* @param bool $dither
	* @param bool $measureError
	**/
	public function quantizeImage ( $numberColors, $colorspace, $treedepth, $dither, $measureError ) {
	}

	/**
	* @return bool
	* @param int $numberColors
	* @param int $colorspace
	* @param int $treedepth
	* @param bool $dither
	* @param bool $measureError
	**/
	public function quantizeImages ( $numberColors, $colorspace, $treedepth, $dither, $measureError ) {
	}

	/**
	* @return array
	* @param ImagickDraw $properties
	* @param string $text
	* @param bool $multiline
	**/
	public function queryFontMetrics ( $properties, $text, $multiline ) {
	}

	/**
	* @return array
	* @param string $pattern
	**/
	public function queryFonts ( $pattern = "*" ) {
	}

	/**
	* @return array
	* @param string $pattern
	**/
	public function queryFormats ( $pattern = "*" ) {
	}

	/**
	* @return bool
	* @param float $angle
	* @param int $channel
	**/
	public function radialBlurImage ( $angle, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	* @param bool $raise
	**/
	public function raiseImage ( $width, $height, $x, $y, $raise ) {
	}

	/**
	* @return bool
	* @param float $low
	* @param float $high
	* @param int $channel
	**/
	public function randomThresholdImage ( $low, $high, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param string $filename
	**/
	public function readImage ( $filename ) {
	}

	/**
	* @return bool
	* @param string $image
	* @param string $filename
	**/
	public function readImageBlob ( $image, $filename ) {
	}

	/**
	* @return bool
	* @param resource $filehandle
	* @param string $fileName
	**/
	public function readImageFile ( $filehandle, $fileName = null ) {
	}

	/**
	* @return bool
	* @param array $matrix
	**/
	public function recolorImage ( $matrix ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function reduceNoiseImage ( $radius ) {
	}

	/**
	* @return void
	* @param Imagick $replacement
	* @param int $DITHER
	**/
	public function remapImage ( $replacement, $DITHER ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function removeImage (  ) {
	}

	/**
	* @return string
	* @param string $name
	**/
	public function removeImageProfile ( $name ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function render (  ) {
	}

	/**
	* @return bool
	* @param float $x_resolution
	* @param float $y_resolution
	* @param int $filter
	* @param float $blur
	**/
	public function resampleImage ( $x_resolution, $y_resolution, $filter, $blur ) {
	}

	/**
	* @return bool
	* @param string $page
	**/
	public function resetImagePage ( $page ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	* @param int $filter
	* @param float $blur
	* @param bool $bestfit
	**/
	public function resizeImage ( $columns, $rows, $filter, $blur, $bestfit = false ) {
	}

	/**
	* @return bool
	* @param int $x
	* @param int $y
	**/
	public function rollImage ( $x, $y ) {
	}

	/**
	* @return bool
	* @param mixed $background
	* @param float $degrees
	**/
	public function rotateImage ( $background, $degrees ) {
	}

	/**
	* @return bool
	* @param float $x_rounding
	* @param float $y_rounding
	* @param float $stroke_width
	* @param float $displace
	* @param float $size_correction
	**/
	public function roundCorners ( $x_rounding, $y_rounding, $stroke_width = 10, $displace = 5, $size_correction = -6 ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	**/
	public function sampleImage ( $columns, $rows ) {
	}

	/**
	* @return bool
	* @param int $cols
	* @param int $rows
	* @param bool $bestfit
	**/
	public function scaleImage ( $cols, $rows, $bestfit = false ) {
	}

	/**
	* @return void
	* @param int $COLORSPACE
	* @param float $cluster_threshold
	* @param float $smooth_threshold
	* @param boolean $verbose
	**/
	public function segmentImage ( $COLORSPACE, $cluster_threshold, $smooth_threshold, $verbose ) {
	}

	/**
	* @return bool
	* @param int $channel
	**/
	public function separateImageChannel ( $channel ) {
	}

	/**
	* @return bool
	* @param float $threshold
	**/
	public function sepiaToneImage ( $threshold ) {
	}

	/**
	* @return bool
	* @param mixed $background
	**/
	public function setBackgroundColor ( $background ) {
	}

	/**
	* @return bool
	* @param int $COLORSPACE
	**/
	public function setColorspace ( $COLORSPACE ) {
	}

	/**
	* @return bool
	* @param int $compression
	**/
	public function setCompression ( $compression ) {
	}

	/**
	* @return bool
	* @param int $quality
	**/
	public function setCompressionQuality ( $quality ) {
	}

	/**
	* @return bool
	* @param string $filename
	**/
	public function setFilename ( $filename ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function setFirstIterator (  ) {
	}

	/**
	* @return bool
	* @param string $font
	**/
	public function setFont ( $font ) {
	}

	/**
	* @return bool
	* @param string $format
	**/
	public function setFormat ( $format ) {
	}

	/**
	* @return bool
	* @param int $gravity
	**/
	public function setGravity ( $gravity ) {
	}

	/**
	* @return bool
	* @param Imagick $replace
	**/
	public function setImage ( $replace ) {
	}

	/**
	* @return bool
	* @param int $mode
	**/
	public function setImageAlphaChannel ( $mode ) {
	}

	/**
	* @return bool
	* @param string $artifact
	* @param string $value
	**/
	public function setImageArtifact ( $artifact, $value ) {
	}

	/**
	* @return bool
	* @param mixed $background
	**/
	public function setImageBackgroundColor ( $background ) {
	}

	/**
	* @return bool
	* @param float $bias
	**/
	public function setImageBias ( $bias ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function setImageBluePrimary ( $x, $y ) {
	}

	/**
	* @return bool
	* @param mixed $border
	**/
	public function setImageBorderColor ( $border ) {
	}

	/**
	* @return bool
	* @param int $channel
	* @param int $depth
	**/
	public function setImageChannelDepth ( $channel, $depth ) {
	}

	/**
	* @return bool
	* @param Imagick $clip_mask
	**/
	public function setImageClipMask ( $clip_mask ) {
	}

	/**
	* @return bool
	* @param int $index
	* @param ImagickPixel $color
	**/
	public function setImageColormapColor ( $index, $color ) {
	}

	/**
	* @return bool
	* @param int $colorspace
	**/
	public function setImageColorspace ( $colorspace ) {
	}

	/**
	* @return bool
	* @param int $compose
	**/
	public function setImageCompose ( $compose ) {
	}

	/**
	* @return bool
	* @param int $compression
	**/
	public function setImageCompression ( $compression ) {
	}

	/**
	* @return bool
	* @param int $quality
	**/
	public function setImageCompressionQuality ( $quality ) {
	}

	/**
	* @return bool
	* @param int $delay
	**/
	public function setImageDelay ( $delay ) {
	}

	/**
	* @return bool
	* @param int $depth
	**/
	public function setImageDepth ( $depth ) {
	}

	/**
	* @return bool
	* @param int $dispose
	**/
	public function setImageDispose ( $dispose ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	**/
	public function setImageExtent ( $columns, $rows ) {
	}

	/**
	* @return bool
	* @param string $filename
	**/
	public function setImageFilename ( $filename ) {
	}

	/**
	* @return bool
	* @param string $format
	**/
	public function setImageFormat ( $format ) {
	}

	/**
	* @return bool
	* @param float $gamma
	**/
	public function setImageGamma ( $gamma ) {
	}

	/**
	* @return bool
	* @param int $gravity
	**/
	public function setImageGravity ( $gravity ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function setImageGreenPrimary ( $x, $y ) {
	}

	/**
	* @return bool
	* @param int $index
	**/
	public function setImageIndex ( $index ) {
	}

	/**
	* @return bool
	* @param int $interlace_scheme
	**/
	public function setImageInterlaceScheme ( $interlace_scheme ) {
	}

	/**
	* @return bool
	* @param int $method
	**/
	public function setImageInterpolateMethod ( $method ) {
	}

	/**
	* @return bool
	* @param int $iterations
	**/
	public function setImageIterations ( $iterations ) {
	}

	/**
	* @return bool
	* @param bool $matte
	**/
	public function setImageMatte ( $matte ) {
	}

	/**
	* @return bool
	* @param mixed $matte
	**/
	public function setImageMatteColor ( $matte ) {
	}

	/**
	* @return bool
	* @param float $opacity
	**/
	public function setImageOpacity ( $opacity ) {
	}

	/**
	* @return bool
	* @param int $orientation
	**/
	public function setImageOrientation ( $orientation ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function setImagePage ( $width, $height, $x, $y ) {
	}

	/**
	* @return bool
	* @param string $name
	* @param string $profile
	**/
	public function setImageProfile ( $name, $profile ) {
	}

	/**
	* @return bool
	* @param string $name
	* @param string $value
	**/
	public function setImageProperty ( $name, $value ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function setImageRedPrimary ( $x, $y ) {
	}

	/**
	* @return bool
	* @param int $rendering_intent
	**/
	public function setImageRenderingIntent ( $rendering_intent ) {
	}

	/**
	* @return bool
	* @param float $x_resolution
	* @param float $y_resolution
	**/
	public function setImageResolution ( $x_resolution, $y_resolution ) {
	}

	/**
	* @return bool
	* @param int $scene
	**/
	public function setImageScene ( $scene ) {
	}

	/**
	* @return bool
	* @param int $ticks_per_second
	**/
	public function setImageTicksPerSecond ( $ticks_per_second ) {
	}

	/**
	* @return bool
	* @param int $image_type
	**/
	public function setImageType ( $image_type ) {
	}

	/**
	* @return bool
	* @param int $units
	**/
	public function setImageUnits ( $units ) {
	}

	/**
	* @return bool
	* @param int $method
	**/
	public function setImageVirtualPixelMethod ( $method ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function setImageWhitePoint ( $x, $y ) {
	}

	/**
	* @return bool
	* @param int $interlace_scheme
	**/
	public function setInterlaceScheme ( $interlace_scheme ) {
	}

	/**
	* @return bool
	* @param int $index
	**/
	public function setIteratorIndex ( $index ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function setLastIterator (  ) {
	}

	/**
	* @return bool
	* @param string $key
	* @param string $value
	**/
	public function setOption ( $key, $value ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function setPage ( $width, $height, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $point_size
	**/
	public function setPointSize ( $point_size ) {
	}

	/**
	* @return bool
	* @param float $x_resolution
	* @param float $y_resolution
	**/
	public function setResolution ( $x_resolution, $y_resolution ) {
	}

	/**
	* @return bool
	* @param int $type
	* @param int $limit
	**/
	public function setResourceLimit ( $type, $limit ) {
	}

	/**
	* @return bool
	* @param array $factors
	**/
	public function setSamplingFactors ( $factors ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	**/
	public function setSize ( $columns, $rows ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	* @param int $offset
	**/
	public function setSizeOffset ( $columns, $rows, $offset ) {
	}

	/**
	* @return bool
	* @param int $image_type
	**/
	public function setType ( $image_type ) {
	}

	/**
	* @return bool
	* @param bool $gray
	* @param float $azimuth
	* @param float $elevation
	**/
	public function shadeImage ( $gray, $azimuth, $elevation ) {
	}

	/**
	* @return bool
	* @param float $opacity
	* @param float $sigma
	* @param int $x
	* @param int $y
	**/
	public function shadowImage ( $opacity, $sigma, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param int $channel
	**/
	public function sharpenImage ( $radius, $sigma, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	**/
	public function shaveImage ( $columns, $rows ) {
	}

	/**
	* @return bool
	* @param mixed $background
	* @param float $x_shear
	* @param float $y_shear
	**/
	public function shearImage ( $background, $x_shear, $y_shear ) {
	}

	/**
	* @return bool
	* @param bool $sharpen
	* @param float $alpha
	* @param float $beta
	* @param int $channel
	**/
	public function sigmoidalContrastImage ( $sharpen, $alpha, $beta, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param float $angle
	**/
	public function sketchImage ( $radius, $sigma, $angle ) {
	}

	/**
	* @return bool
	* @param int $threshold
	**/
	public function solarizeImage ( $threshold ) {
	}

	/**
	* @return boolean
	* @param int $SPARSE_METHOD
	* @param array $arguments
	* @param int $channel
	**/
	public function sparseColorImage ( $SPARSE_METHOD, $arguments, $channel = Imagick::CHANNEL_DEFAULT ) {
	}

	/**
	* @return bool
	* @param int $width
	* @param int $height
	* @param int $x
	* @param int $y
	**/
	public function spliceImage ( $width, $height, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $radius
	**/
	public function spreadImage ( $radius ) {
	}

	/**
	* @return Imagick
	* @param Imagick $watermark_wand
	* @param int $offset
	**/
	public function steganoImage ( $watermark_wand, $offset ) {
	}

	/**
	* @return bool
	* @param Imagick $offset_wand
	**/
	public function stereoImage ( $offset_wand ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function stripImage (  ) {
	}

	/**
	* @return bool
	* @param float $degrees
	**/
	public function swirlImage ( $degrees ) {
	}

	/**
	* @return bool
	* @param Imagick $texture_wand
	**/
	public function textureImage ( $texture_wand ) {
	}

	/**
	* @return bool
	* @param float $threshold
	* @param int $channel
	**/
	public function thresholdImage ( $threshold, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param int $columns
	* @param int $rows
	* @param bool $bestfit
	**/
	public function thumbnailImage ( $columns, $rows, $bestfit = false ) {
	}

	/**
	* @return bool
	* @param mixed $tint
	* @param mixed $opacity
	**/
	public function tintImage ( $tint, $opacity ) {
	}

	/**
	* @return Imagick
	* @param string $crop
	* @param string $geometry
	**/
	public function transformImage ( $crop, $geometry ) {
	}

	/**
	* @return bool
	* @param mixed $target
	* @param float $alpha
	* @param float $fuzz
	* @param bool $invert
	**/
	public function transparentPaintImage ( $target, $alpha, $fuzz, $invert ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function transposeImage (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function transverseImage (  ) {
	}

	/**
	* @return bool
	* @param float $fuzz
	**/
	public function trimImage ( $fuzz ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function uniqueImageColors (  ) {
	}

	/**
	* @return bool
	* @param float $radius
	* @param float $sigma
	* @param float $amount
	* @param float $threshold
	* @param int $channel
	**/
	public function unsharpMaskImage ( $radius, $sigma, $amount, $threshold, $channel = Imagick::CHANNEL_ALL ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function valid (  ) {
	}

	/**
	* @return bool
	* @param float $blackPoint
	* @param float $whitePoint
	* @param int $x
	* @param int $y
	**/
	public function vignetteImage ( $blackPoint, $whitePoint, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $amplitude
	* @param float $length
	**/
	public function waveImage ( $amplitude, $length ) {
	}

	/**
	* @return bool
	* @param mixed $threshold
	**/
	public function whiteThresholdImage ( $threshold ) {
	}

	/**
	* @return bool
	* @param string $filename
	**/
	public function writeImage ( $filename ) {
	}

	/**
	* @return bool
	* @param resource $filehandle
	**/
	public function writeImageFile ( $filehandle ) {
	}

	/**
	* @return bool
	* @param string $filename
	* @param bool $adjoin
	**/
	public function writeImages ( $filename, $adjoin ) {
	}

	/**
	* @return bool
	* @param resource $filehandle
	**/
	public function writeImagesFile ( $filehandle ) {
	}
}