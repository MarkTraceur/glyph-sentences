<?php
function getGlyphFilename( $glyphname ) {
	return __DIR__ . '/glyphs/' . $glyphname . '.png';
}

function glyphExists( $glyphname ) {
	return file_exists( getGlyphFilename( $glyphname ) );
}

if ( !empty( $_POST['glyphs'] ) ) {
	$input = explode( ' ', strtolower( trim( $_POST['glyphs'] ) ) );

	$glyphs = array();

	foreach ( $input as $glyph ) {
		if ( glyphExists( $glyph ) ) {
			$glyphs[] = $glyph;
		}
	}

	$im = new Imagick();

	foreach ( $glyphs as $glyph ) {
		$handle = file_get_contents( __DIR__ . '/glyphs/' . $glyph . '.png' );
		$im->readImageBlob( $handle );
	}

	$im->resetIterator();

	$draw = new ImagickDraw();

	$size = sizeof( $glyphs );

	if ( $size > 20 ) {
		$size = 20;
	}

	$montage = $im->montageImage( $draw, $size . 'x', '50x55+0+0', imagick::MONTAGEMODE_UNFRAME, '0x0+0+0' );
	$montage->setImageFormat( 'png' );
	$montage->setImageBackgroundColor( 'rgba(0,0,0,255)' );

	header( 'Content-Type: image/png' );
	echo $montage;
} else {
?>
<html>
	<body>
		<form method="POST">
			<label for="glyphs">Input a sentence with glyph names in it:</label>
			<input name="glyphs" id="glyphs" />
			<input type="submit" value="Generate glyph sentence" />
		</form>
	</body>
</html>
<?php
}
