<?php

 
function encode_Polyline($points)
{
	$encoded_value = "";
	$previous = "First Point";
	foreach($points as $point)
	{
		// Points only include the offset from the previous point (except of course for the first point)
		if($previous==="First Point") //First Point
		{
			$encoded_value .= encode_Polylinepoint($point[0]).encode_Polylinepoint($point[1]);
		}
		else //Other points
		{
			$encoded_value .= encode_Polylinepoint($point[0] - $previous[0]).encode_Polylinepoint($point[1] - $previous[1]);
		}
		$previous = $point;
	}
	return $encoded_value;
}



function encode_Polylinepoint($point)
{
	
	// Take the decimal value and multiply it by 1e5, rounding the result
	$point = round($point * 100000);

	//Left-shift the binary value one bit:
	$point = $point << 1;

	//If the original decimal value is negative, invert this encoding:
	if($point < 0)
	{
		$point = ~$point;
	}

	//Convert the decimal value to binary
	$point = decbin($point);
	
	//Break the binary value out into 5-bit chunks (starting from the right hand side): and Place the 5-bit chunks into reverse order:
	$splitted_bits = str_split($point);
	$point = array();
	$bit_chunk = "";
	for($c=count($splitted_bits)-1;$c >= 0;$c--)
	{
		$bit_chunk = $splitted_bits[$c].$bit_chunk;
		if(strlen($bit_chunk) == 5)
		{
			array_push($point,$bit_chunk);
			$bit_chunk = "";
		}
	}
	if(strlen($bit_chunk)>0)
	{
		$bit_chunk = str_repeat("0",5 - strlen($bit_chunk)).$bit_chunk;
		array_push($point,$bit_chunk);
	}

	
	//STEP8 - STEP9 - STEP10 - STEP11
	for($c = 0;$c < count($point);$c++)
	{
		
		//FOR IF : ORing each value with 0x20 as another bit chunk follows, coverting each value to decimal, adding 63 to the decimal and converting it to its ASCII equivalent.
		
		//FOR ELSE : No ORing with 0x20 as its the last bit chunk, coverting each value to decimal, adding 63 to the decimal and converting it to its ASCII equivalent.
		
		if($c != count($point)-1)
		{
			$point[$c] = chr(bindec($point[$c]) + 32 + 63);
		}
		else
		{
			$point[$c] = chr(bindec($point[$c]) + 63);
		}
	}
	
	//Joining the array elements with a string.	
	return implode("",$point);
}

function encode_PolylineLevel($level)
{
	//Convert the decimal value to binary
	$level = decbin($level);
	
	//Break the binary value out into 5-bit chunks (starting from the right hand side): and Place the 5-bit chunks into reverse order:
	$splitted_bits = str_split($level);
	$level = array();
	$bit_chunk = "";
	for($c=count($splitted_bits)-1;$c >= 0;$c--)
	{
		$bit_chunk = $splitted_bits[$c].$bit_chunk;
		if(strlen($bit_chunk) == 5)
		{
			array_push($level,$bit_chunk);
			$bit_chunk = "";
		}
	}
	if(strlen($bit_chunk)>0)
	{
		$bit_chunk = str_repeat("0",5 - strlen($bit_chunk)).$bit_chunk;
		array_push($level,$bit_chunk);
	}

	
	//STEP8 - STEP9 - STEP10 - STEP11
	for($c = 0;$c < count($level);$c++)
	{
		
		//FOR IF : ORing each value with 0x20 as another bit chunk follows, coverting each value to decimal, adding 63 to the decimal and converting it to its ASCII equivalent.
		
		//FOR ELSE : No ORing with 0x20 as its the last bit chunk, coverting each value to decimal, adding 63 to the decimal and converting it to its ASCII equivalent.
		
		if($c != count($level)-1)
		{
			$level[$c] = chr(bindec($level[$c]) + 32 + 63);
		}
		else
		{
			$level[$c] = chr(bindec($level[$c]) + 63);
		}
	}
	
	//Joining the array elements with a string.	
	return implode("",$level);

}

//Unit Tests, test cases taken from https://developers.google.com/maps/documentation/utilities/polylinealgorithm

if(encode_Polylinepoint(-179.9832104)=="`~oia@")
{
	$encoded_value = encode_Polylinepoint(-179.9832104);
	echo "Code shows the correct answer for -179.9832104, i.e. ".$encoded_value;
}
echo "<br/><br/>";
if(encode_PolylineLevel(174)=="mD")
{
	$encoded_value = encode_PolylineLevel(174);
	echo "Code shows the correct answer for 174, i.e. ".$encoded_value;
}
echo "<br/><br/>";
if(encode_Polyline(array(array(38.5,-120.2),array(40.7,-120.95),array(43.252,-126.453)))=="_p~iF~ps|U_ulLnnqC_mqNvxq`@")
{
	$encoded_value = encode_Polyline(array(array(38.5,-120.2),array(40.7,-120.95),array(43.252,-126.453)));
	echo "Code shows the correct answer for 174, i.e. ".$encoded_value;
}
echo "<br/><br/>";



?>
