<?php

class KeyStruct {
	public $R;
	public $S;
}

$R_Mask;
$Comp0;
$Comp1;
$S_Mask0;
$S_Mask1;

function init()
{
    $GLOBALS['R_Mask'][0] = 0x1d5363d5;
    $GLOBALS['R_Mask'][1] = 0x415a0aac;
    $GLOBALS['R_Mask'][2] = 0x0000d2a8;

    $GLOBALS['Comp0'][0]  = 0x6aa97a30;
    $GLOBALS['Comp0'][1]  = 0x7942a809;
    $GLOBALS['Comp0'][2]  = 0x00003fea;

    $GLOBALS['Comp1'][0]  = 0xdd629e9a;
    $GLOBALS['Comp1'][1]  = 0xe3a21d63;
    $GLOBALS['Comp1'][2]  = 0x00003dd7;

    $GLOBALS['S_Mask0'][0] = 0x9ffa7faf;
    $GLOBALS['S_Mask0'][1] = 0xaf4a9381;
    $GLOBALS['S_Mask0'][2] = 0x00005802;

    $GLOBALS['S_Mask1'][0] = 0x4c8cb877;
    $GLOBALS['S_Mask1'][1] = 0x4911b063;
    $GLOBALS['S_Mask1'][2] = 0x0000c52b;
}	

function CLOCK_R($ctx, $input_bit, $control_bit)
{
    /* Initialise the variables */
    $Feedback_bit = (u32sh_r($ctx->R[2], 15) & 1) ^ $input_bit;
    $Carry0 = u32sh_r($ctx->R[0], 31) & 1;
    $Carry1 = u32sh_r($ctx->R[1], 31) & 1;

    if ($control_bit)
    {
        /* Shift and xor */
        $ctx->R[0] ^= ($ctx->R[0] << 1);
        $ctx->R[1] ^= ($ctx->R[1] << 1) ^ $Carry0;
        $ctx->R[2] ^= ($ctx->R[2] << 1) ^ $Carry1;
    }
    else
    {
        /* Shift only */
        $ctx->R[0] = ($ctx->R[0] << 1);
        $ctx->R[1] = ($ctx->R[1] << 1) ^ $Carry0;
        $ctx->R[2] = ($ctx->R[2] << 1) ^ $Carry1;
    }

    /* Implement feedback into the various register stages */
    if ($Feedback_bit)
    {
        $ctx->R[0] ^= $GLOBALS['R_Mask'][0];
        $ctx->R[1] ^= $GLOBALS['R_Mask'][1];
        $ctx->R[2] ^= $GLOBALS['R_Mask'][2];
    }
}

function CLOCK_S($ctx, $input_bit, $control_bit)
{
    /* Compute the feedback and two carry bits */
    $Feedback_bit = (u32sh_r($ctx->S[2], 15) & 1) ^ $input_bit;
    $Carry0 = u32sh_r($ctx->S[0], 31) & 1;
    $Carry1 = u32sh_r($ctx->S[1], 31) & 1;
    
    $ctx->S[0] = ($ctx->S[0] << 1) ^ (($ctx->S[0] ^ $GLOBALS['Comp0'][0]) & (u32sh_r($ctx->S[0], 1) ^ ($ctx->S[1] << 31) ^ $GLOBALS['Comp1'][0]) & 0xfffffffe);
    $ctx->S[1] = ($ctx->S[1] << 1) ^ (($ctx->S[1] ^ $GLOBALS['Comp0'][1]) & (u32sh_r($ctx->S[1], 1) ^ ($ctx->S[2] << 31) ^ $GLOBALS['Comp1'][1])) ^ $Carry0;
    $ctx->S[2] = ($ctx->S[2] << 1) ^ (($ctx->S[2] ^ $GLOBALS['Comp0'][2]) & (u32sh_r($ctx->S[2], 1) ^ $GLOBALS['Comp1'][2]) & 0x7fff) ^ $Carry1;

    /* Apply suitable feedback from s_79 */
    if ($Feedback_bit)
    {
        if ($control_bit)
        {
            $ctx->S[0] ^= $GLOBALS['S_Mask1'][0];
            $ctx->S[1] ^= $GLOBALS['S_Mask1'][1];
            $ctx->S[2] ^= $GLOBALS['S_Mask1'][2];
        }
        else
        {
            $ctx->S[0] ^= $GLOBALS['S_Mask0'][0];
            $ctx->S[1] ^= $GLOBALS['S_Mask0'][1];
            $ctx->S[2] ^= $GLOBALS['S_Mask0'][2];
        }
    }
}

function CLOCK_KG ($ctx, $mixing, $input_bit)
{
    $Keystream_bit = ($ctx->R[0] ^ $ctx->S[0]) & 1;
    $control_bit_r = (u32sh_r($ctx->S[0], 27) ^ u32sh_r($ctx->R[1], 21)) & 1;
    $control_bit_s = (u32sh_r($ctx->S[1], 21) ^ u32sh_r($ctx->R[0], 26)) & 1;

    if ($mixing) {
		CLOCK_R ($ctx, (u32sh_r($ctx->S[1], 8) & 1) ^ $input_bit, $control_bit_r);
	} else {
		CLOCK_R ($ctx, $input_bit, $control_bit_r);
	}
  
    CLOCK_S ($ctx, $input_bit, $control_bit_s);

    return $Keystream_bit;
}

function setup($ctx, $key, $iv)
{
	$keysize = strlen($key);
	$ivsize = strlen($iv);
	
	$iv = strrev($iv);
	$key = strrev($key);
    /* Initialise R and S to all zeros */
    for ($i = 0; $i < 3; $i++)
    {
        $ctx->R[$i] = 0;
        $ctx->S[$i] = 0;
    }

    /* Load in IV */
    for ($i = 0; $i < $ivsize; $i++)
    {
        $iv_or_key_bit = intval($iv[$i]) & 1; /* Adopt usual, perverse, labelling order */
        CLOCK_KG ($ctx, 1, $iv_or_key_bit);
    }

    /* Load in K */
    for ($i = 0; $i < $keysize; $i++)
    {
        $iv_or_key_bit = intval($key[$i]) & 1; /* Adopt usual, perverse, labelling order */
        CLOCK_KG ($ctx, 1, $iv_or_key_bit);
    }

    /* Preclock */
    for ($i = 0; $i < 80; $i++) 
	{
		CLOCK_KG ($ctx, 1, 0);
	}
}

function keystream($key, $iv, $length)                 /* Length of keystream in bytes. */
{
	$keystream;
	$resoure = "";
	$ctx = new KeyStruct();
	init();
	setup($ctx, $key, $iv);
	
    for ($i = 0; $i < $length; $i++)
    {
        $keystream = 0;

        for ($j = 0; $j < 8; $j++)
		{
			$keystream ^= CLOCK_KG ($ctx, 0, 0) << (7-$j);
		}
		$byte = dechex($keystream); // convert char to bit string
		$binary = substr("00",0,2 - strlen($byte)) . $byte; // 4 bit packed
		$resoure .= $binary;
    }
	
	return strtoupper($resoure);
}

function hexbit($hex_string) {    
    $binary = "";
    $end = strlen($hex_string);
    for($i = 0 ; $i < $end; $i++){
        $byte = decbin(hexdec($hex_string[$i])); // convert char to bit string
        $binary .= substr("00000000",0,4 - strlen($byte)) . $byte; // 4 bit packed
    }
	return strtoupper($binary);
}

function decbit($dec_string) { 
    $byte = decbin($dec_string); // convert char to bit string
    $binary = substr("00000000000000000000000000000000",0,32 - strlen($byte)) . $byte; // 4 bit packed
	
    return strtoupper($binary);
}

function u32sh_r($int, $shft) { 
    return ( $int >> $shft )   //Arithmetic right shift
        & ( 2147483647 >> ( $shft - 1 ) );   //Deleting unnecessary bits
}

?>