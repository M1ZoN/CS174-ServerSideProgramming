<?php
	echo <<<_END
		<html>
			<head>
				<title>PHP Form Upload</title>
			</head>
			<body>
				<form method='POST' action='index.php' enctype='multipart/form-data'>Select File: <input type='file' name='file'>
					<input type='submit' value='Upload'>
				</form>
_END;

	function validator($input)
	{
		$pattern = '/^[0-9]*$/';
		return (preg_match($pattern, $input));
	}

	function isOk($input)
	{
		$ok = ((count($input) === 20) ? 1 : 0);
		echo "$ok<br>";
		for($i = 0;$ok && $i < count($input); $i++)
		{
			echo "$ok<br>";
			if (count($input) !== 20)
				$ok = 0;
		}
		return ($ok);
	}

	function getArray($input)
	{
		$res = array(array());
		$rowSize = 20;
		$colSize = 50;
		$k = 0;
		for ($i = 0; $i < $rowSize; $i++)
		{
			for($j = 0; $j < $colSize; $j++)
			{
				$res[$i][$j] = $input[$k];
				$k++;
			}
		}
		return ($res);
	}

	function Horizontal($input)
	{
		$res = 0;
		for ($i = 0; $i < count($input); $i++)
		{
			for($j = 0; $j < (count($input[$i]) - 4); $j++)
			{
				$mult = $input[$i][$j] * $input[$i][$j + 1] * $input[$i][$j + 2] * $input[$i][$j + 3];
				if ($mult > $res)
					$res = $mult;
			}
		}
		return ($res);
	}

	function Vertical($input)
	{
		$res = 0;
		for ($i = 0; $i < (count($input) - 4); $i++)
		{
			for($j = 0; $j < (count($input[$i])); $j++)
			{
				$mult = $input[$i][$j] * $input[$i + 1][$j] * $input[$i + 2][$j] * $input[$i + 3][$j];
				if ($mult > $res)
					$res = $mult;
			}
		}
		return ($res);
	}

	function Diagonal($input)
	{
		$res = 0;
		for ($i = 0; $i < (count($input) - 4); $i++)
		{
			for($j = 3; $j < (count($input[$i])); $j++)
			{
				$mult = $input[$i][$j] * $input[$i + 1][$j - 1] * $input[$i + 2][$j - 2] * $input[$i + 3][$j - 3];
				if ($mult > $res)
					$res = $mult;
			}
		}
		for ($i = 0; $i < (count($input) -4); $i++)
		{
			for($j = (count($input[$i]) - 5); $j >= 0; $j--)
			{
				$mult = $input[$i][$j] * $input[$i + 1][$j + 1] * $input[$i + 2][$j + 2] * $input[$i + 3][$j + 3];
				if ($mult > $res)
					$res = $mult;
			}
		}
		return ($res);
	}

	function factorial($input)
	{
		if ($input === 1 || $input === 0)
			return (1);
		else
			return ($input * factorial($input - 1));
	}

	function getMax($filename)
	{
		$fh = fopen("$filename", "r") or
				die("File does not exist or there is no permission to read it!");
		$input = fread($fh, 1000);
		if (validator($input))
		{
			$arrInput = getArray($input);
			if (!isOk($arrInput))
			{
				echo "Wrong count of numbers! Has to be equal to 400<br>";
				return ;
			}
			$arrMax = max(Horizontal($arrInput), Vertical($arrInput), Diagonal($arrInput));
			echo "The biggest sum of 5 adjacent numbers = $arrMax<br>";
			$sz = strval($arrMax);
			$factProd = 1;
			for($sz = strlen(strval($arrMax)) - 1; $sz >= 0; $sz--)
			{
				$factProd += factorial($arrMax / pow(10, $sz) % 10);
			}
			echo "And the sum of factorials = $factProd<br>";
		}
		else
		{
			echo "Wrong Input! Digit only program.";
		}
		fclose($fh);
	}

	getMax($_FILES['file']['tmp_name']);

	echo"</body></html>";
