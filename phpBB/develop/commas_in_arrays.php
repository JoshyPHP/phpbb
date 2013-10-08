<?php

/**
*
* @package phpBB3
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

//
// Security message:
//
// This script is potentially dangerous.
// Remove or comment the next line (die(".... ) to enable this script.
// Do NOT FORGET to either remove this script or disable it after you have used it.
//
die("Please read the first lines of this script for instructions on how to enable it");

convert_dir(__DIR__ . '/..');

function convert_dir($path)
{
	foreach (glob($path . '/*.php') as $filepath)
	{
		convert_file($filepath);
	}

	foreach (glob($path . '/*', GLOB_ONLYDIR) as $dirpath)
	{
		if (!preg_match('#(?>cache|data|develop|vendor)$#', $dirpath))
		{
			convert_dir($dirpath);
		}
	}
}

function convert_file($filepath)
{
	$tokens = token_get_all(file_get_contents($filepath));

	$n = 0;
	$enter_array = false;
	$in_array    = [false];

	$line  = 0;
	$lines = [];

	foreach ($tokens as $k => $token)
	{
		if (is_array($token))
		{
			$line = $token[2];
		}

		if ($token === '(')
		{
			++$n;
			$in_array[$n] = $enter_array;

			continue;
		}

		if ($token === ')')
		{
			if ($in_array[$n] === 'Y')
			{
				$linebreak = false;

				do
				{
					--$k;

					if ($tokens[$k][0] === T_WHITESPACE && strpos($tokens[$k][1], "\n") !== false)
					{
						$linebreak = true;
					}
				}
				while ($tokens[$k][0] === T_WHITESPACE || $tokens[$k][0] === T_COMMENT);

				if ($tokens[$k] !== ',' && $linebreak)
				{
					$lines[] = $line;

					if (is_array($tokens[$k]))
					{
						$tokens[$k] = $tokens[$k][1];
					}

					if ($tokens[$k] !== '(')
					{
						$tokens[$k] .= ',';
					}
				}
			}

			--$n;

			continue;
		}

		if ($token[0] === T_ARRAY)
		{
			$enter_array = true;
		}
		elseif ($token[0] === T_WHITESPACE)
		{
			if ($in_array[$n] && strpos($token[1], "\n") !== false)
			{
				$in_array[$n] = 'Y';
			}
		}
		else
		{
			$enter_array = false;
		}
	}

	if ($lines)
	{
		$php = '';
		foreach ($tokens as $token)
		{
			$php .= (is_array($token)) ? $token[1] : $token;
		}

		file_put_contents($filepath, $php);
	}
}
