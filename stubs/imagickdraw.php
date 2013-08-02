<?php
class ImagickDraw {

	/**
	* @return bool
	* @param array $affine
	**/
	public function affine ( $affine ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	* @param string $text
	**/
	public function annotation ( $x, $y, $text ) {
	}

	/**
	* @return bool
	* @param float $sx
	* @param float $sy
	* @param float $ex
	* @param float $ey
	* @param float $sd
	* @param float $ed
	**/
	public function arc ( $sx, $sy, $ex, $ey, $sd, $ed ) {
	}

	/**
	* @return bool
	* @param array $coordinates
	**/
	public function bezier ( $coordinates ) {
	}

	/**
	* @return bool
	* @param float $ox
	* @param float $oy
	* @param float $px
	* @param float $py
	**/
	public function circle ( $ox, $oy, $px, $py ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function clear (  ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	* @param int $paintMethod
	**/
	public function color ( $x, $y, $paintMethod ) {
	}

	/**
	* @return bool
	* @param string $comment
	**/
	public function comment ( $comment ) {
	}

	/**
	* @return bool
	* @param int $compose
	* @param float $x
	* @param float $y
	* @param float $width
	* @param float $height
	* @param Imagick $compositeWand
	**/
	public function composite ( $compose, $x, $y, $width, $height, $compositeWand ) {
	}

	/**
	* @return ImagickDraw
	* @param void
	**/
	public function __construct (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function destroy (  ) {
	}

	/**
	* @return bool
	* @param float $ox
	* @param float $oy
	* @param float $rx
	* @param float $ry
	* @param float $start
	* @param float $end
	**/
	public function ellipse ( $ox, $oy, $rx, $ry, $start, $end ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getClipPath (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getClipRule (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getClipUnits (  ) {
	}

	/**
	* @return ImagickPixel
	* @param void
	**/
	public function getFillColor (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getFillOpacity (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getFillRule (  ) {
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
	public function getFontFamily (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getFontSize (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getFontStyle (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getFontWeight (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getGravity (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function getStrokeAntialias (  ) {
	}

	/**
	* @return ImagickPixel
	* @param ImagickPixel $stroke_color
	**/
	public function getStrokeColor ( $stroke_color ) {
	}

	/**
	* @return array
	* @param void
	**/
	public function getStrokeDashArray (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getStrokeDashOffset (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getStrokeLineCap (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getStrokeLineJoin (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getStrokeMiterLimit (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getStrokeOpacity (  ) {
	}

	/**
	* @return float
	* @param void
	**/
	public function getStrokeWidth (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getTextAlignment (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function getTextAntialias (  ) {
	}

	/**
	* @return int
	* @param void
	**/
	public function getTextDecoration (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getTextEncoding (  ) {
	}

	/**
	* @return ImagickPixel
	* @param void
	**/
	public function getTextUnderColor (  ) {
	}

	/**
	* @return string
	* @param void
	**/
	public function getVectorGraphics (  ) {
	}

	/**
	* @return bool
	* @param float $sx
	* @param float $sy
	* @param float $ex
	* @param float $ey
	**/
	public function line ( $sx, $sy, $ex, $ey ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	* @param int $paintMethod
	**/
	public function matte ( $x, $y, $paintMethod ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function pathClose (  ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x2
	* @param float $y2
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToAbsolute ( $x1, $y1, $x2, $y2, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToQuadraticBezierAbsolute ( $x1, $y1, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToQuadraticBezierRelative ( $x1, $y1, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToQuadraticBezierSmoothAbsolute ( $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToQuadraticBezierSmoothRelative ( $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x2
	* @param float $y2
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToRelative ( $x1, $y1, $x2, $y2, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x2
	* @param float $y2
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToSmoothAbsolute ( $x2, $y2, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x2
	* @param float $y2
	* @param float $x
	* @param float $y
	**/
	public function pathCurveToSmoothRelative ( $x2, $y2, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $rx
	* @param float $ry
	* @param float $x_axis_rotation
	* @param bool $large_arc_flag
	* @param bool $sweep_flag
	* @param float $x
	* @param float $y
	**/
	public function pathEllipticArcAbsolute ( $rx, $ry, $x_axis_rotation, $large_arc_flag, $sweep_flag, $x, $y ) {
	}

	/**
	* @return bool
	* @param float $rx
	* @param float $ry
	* @param float $x_axis_rotation
	* @param bool $large_arc_flag
	* @param bool $sweep_flag
	* @param float $x
	* @param float $y
	**/
	public function pathEllipticArcRelative ( $rx, $ry, $x_axis_rotation, $large_arc_flag, $sweep_flag, $x, $y ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function pathFinish (  ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathLineToAbsolute ( $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x
	**/
	public function pathLineToHorizontalAbsolute ( $x ) {
	}

	/**
	* @return bool
	* @param float $x
	**/
	public function pathLineToHorizontalRelative ( $x ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathLineToRelative ( $x, $y ) {
	}

	/**
	* @return bool
	* @param float $y
	**/
	public function pathLineToVerticalAbsolute ( $y ) {
	}

	/**
	* @return bool
	* @param float $y
	**/
	public function pathLineToVerticalRelative ( $y ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathMoveToAbsolute ( $x, $y ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function pathMoveToRelative ( $x, $y ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function pathStart (  ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function point ( $x, $y ) {
	}

	/**
	* @return bool
	* @param array $coordinates
	**/
	public function polygon ( $coordinates ) {
	}

	/**
	* @return bool
	* @param array $coordinates
	**/
	public function polyline ( $coordinates ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function pop (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function popClipPath (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function popDefs (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function popPattern (  ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function push (  ) {
	}

	/**
	* @return bool
	* @param string $clip_mask_id
	**/
	public function pushClipPath ( $clip_mask_id ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function pushDefs (  ) {
	}

	/**
	* @return bool
	* @param string $pattern_id
	* @param float $x
	* @param float $y
	* @param float $width
	* @param float $height
	**/
	public function pushPattern ( $pattern_id, $x, $y, $width, $height ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x2
	* @param float $y2
	**/
	public function rectangle ( $x1, $y1, $x2, $y2 ) {
	}

	/**
	* @return bool
	* @param void
	**/
	public function render (  ) {
	}

	/**
	* @return bool
	* @param float $degrees
	**/
	public function rotate ( $degrees ) {
	}

	/**
	* @return bool
	* @param float $x1
	* @param float $y1
	* @param float $x2
	* @param float $y2
	* @param float $rx
	* @param float $ry
	**/
	public function roundRectangle ( $x1, $y1, $x2, $y2, $rx, $ry ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function scale ( $x, $y ) {
	}

	/**
	* @return bool
	* @param string $clip_mask
	**/
	public function setClipPath ( $clip_mask ) {
	}

	/**
	* @return bool
	* @param int $fill_rule
	**/
	public function setClipRule ( $fill_rule ) {
	}

	/**
	* @return bool
	* @param int $clip_units
	**/
	public function setClipUnits ( $clip_units ) {
	}

	/**
	* @return bool
	* @param float $opacity
	**/
	public function setFillAlpha ( $opacity ) {
	}

	/**
	* @return bool
	* @param ImagickPixel $fill_pixel
	**/
	public function setFillColor ( $fill_pixel ) {
	}

	/**
	* @return bool
	* @param float $fillOpacity
	**/
	public function setFillOpacity ( $fillOpacity ) {
	}

	/**
	* @return bool
	* @param string $fill_url
	**/
	public function setFillPatternURL ( $fill_url ) {
	}

	/**
	* @return bool
	* @param int $fill_rule
	**/
	public function setFillRule ( $fill_rule ) {
	}

	/**
	* @return bool
	* @param string $font_name
	**/
	public function setFont ( $font_name ) {
	}

	/**
	* @return bool
	* @param string $font_family
	**/
	public function setFontFamily ( $font_family ) {
	}

	/**
	* @return bool
	* @param float $pointsize
	**/
	public function setFontSize ( $pointsize ) {
	}

	/**
	* @return bool
	* @param int $fontStretch
	**/
	public function setFontStretch ( $fontStretch ) {
	}

	/**
	* @return bool
	* @param int $style
	**/
	public function setFontStyle ( $style ) {
	}

	/**
	* @return bool
	* @param int $font_weight
	**/
	public function setFontWeight ( $font_weight ) {
	}

	/**
	* @return bool
	* @param int $gravity
	**/
	public function setGravity ( $gravity ) {
	}

	/**
	* @return bool
	* @param float $opacity
	**/
	public function setStrokeAlpha ( $opacity ) {
	}

	/**
	* @return bool
	* @param bool $stroke_antialias
	**/
	public function setStrokeAntialias ( $stroke_antialias ) {
	}

	/**
	* @return bool
	* @param ImagickPixel $stroke_pixel
	**/
	public function setStrokeColor ( $stroke_pixel ) {
	}

	/**
	* @return bool
	* @param array $dashArray
	**/
	public function setStrokeDashArray ( $dashArray ) {
	}

	/**
	* @return bool
	* @param float $dash_offset
	**/
	public function setStrokeDashOffset ( $dash_offset ) {
	}

	/**
	* @return bool
	* @param int $linecap
	**/
	public function setStrokeLineCap ( $linecap ) {
	}

	/**
	* @return bool
	* @param int $linejoin
	**/
	public function setStrokeLineJoin ( $linejoin ) {
	}

	/**
	* @return bool
	* @param int $miterlimit
	**/
	public function setStrokeMiterLimit ( $miterlimit ) {
	}

	/**
	* @return bool
	* @param float $stroke_opacity
	**/
	public function setStrokeOpacity ( $stroke_opacity ) {
	}

	/**
	* @return bool
	* @param string $stroke_url
	**/
	public function setStrokePatternURL ( $stroke_url ) {
	}

	/**
	* @return bool
	* @param float $stroke_width
	**/
	public function setStrokeWidth ( $stroke_width ) {
	}

	/**
	* @return bool
	* @param int $alignment
	**/
	public function setTextAlignment ( $alignment ) {
	}

	/**
	* @return bool
	* @param bool $antiAlias
	**/
	public function setTextAntialias ( $antiAlias ) {
	}

	/**
	* @return bool
	* @param int $decoration
	**/
	public function setTextDecoration ( $decoration ) {
	}

	/**
	* @return bool
	* @param string $encoding
	**/
	public function setTextEncoding ( $encoding ) {
	}

	/**
	* @return bool
	* @param ImagickPixel $under_color
	**/
	public function setTextUnderColor ( $under_color ) {
	}

	/**
	* @return bool
	* @param string $xml
	**/
	public function setVectorGraphics ( $xml ) {
	}

	/**
	* @return bool
	* @param int $x1
	* @param int $y1
	* @param int $x2
	* @param int $y2
	**/
	public function setViewbox ( $x1, $y1, $x2, $y2 ) {
	}

	/**
	* @return bool
	* @param float $degrees
	**/
	public function skewX ( $degrees ) {
	}

	/**
	* @return bool
	* @param float $degrees
	**/
	public function skewY ( $degrees ) {
	}

	/**
	* @return bool
	* @param float $x
	* @param float $y
	**/
	public function translate ( $x, $y ) {
	}
}