<!-- 
  Spell Check Starter
  This start code creates two arrays
  1: dictionary: an array containing all of the words from "dictionary.txt"
  2: aliceWords: an array containing all of the words from "AliceInWonderLand.txt"
 -->

<?php

function fileToArray($file) {
  // Read file as a string
  $fileRef = fopen($file, "r");
  $textData = fread($fileRef, filesize($file));
  fclose($fileRef);

  // Split text by one or more whitespace characters
  return preg_split('/\s+/', $textData);
}

// Load data files into arrays 
$dictionary = fileToArray("data-files/dictionary.txt");
$aliceWords = fileToArray("data-files/AliceInWonderLand.txt");

// Print first 50 values of each array to verify contents
// echo "***DICTIONARY***<br>";
// print_r(array_slice($dictionary, 0, 50));
// echo "<br><br>***ALICEWORDS***<br>";
// print_r(array_slice($aliceWords, 0, 50));

// linear and binary search functions
function linearSearch($anArray, $item) {
  for ($i = 0; $i < sizeof($anArray); $i++) {
    if ($anArray[$i] === $item) {
      return $i;
    }
  }
  return -1;
}
function binarySearch($anArray, $item) {
  $lowerIndex = 0;
  $upperIndex = sizeof($anArray) - 1;
  while ($lowerIndex <= $upperIndex) {
    $middleIndex = floor(($lowerIndex + $upperIndex) / 2);
    if ($anArray[$middleIndex] === $item) {
      return $middleIndex;
    } else if ($anArray[$middleIndex] > $item) {
      $upperIndex = $middleIndex - 1;
    } else {
      $lowerIndex = $middleIndex + 1;
    }
  }
  return -1;
}

// set output variable
$output = '';

// form action
if (isset($_POST['mode'])) {
  // mode: check if word is in dictionary (linear)
  if ($_POST['mode'] == 'wordCheckLinear' && $_POST['word'] != '') {
    $t1 = gettimeofday(true);
    if (linearSearch($dictionary, $_POST['word']) >= 0) {
      $output = "'{$_POST['word']}' was found in the dictionary. Search time: ";
      $output = $output . (gettimeofday(true) - $t1) . ' seconds';
    } else {
      $output = "'{$_POST['word']}' was not found in the dictionary. Search time: ";
      $output = $output . (gettimeofday(true) - $t1) . ' seconds';
    }
  }

  // mode: check if word is in dictionary (binary)
  if ($_POST['mode'] == 'wordCheckBinary' && $_POST['word'] != '') {
    $t1 = gettimeofday(true);
    if (binarySearch($dictionary, $_POST['word']) >= 0) {
      $output = "'{$_POST['word']}' was found in the dictionary. Search time: ";
      $output = $output . (gettimeofday(true) - $t1) . ' seconds';
    } else {
      $output = "'{$_POST['word']}' was not found in the dictionary. Search time: ";
      $output = $output . (gettimeofday(true) - $t1) . ' seconds';
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Spell Check</title>
</head>

<body>
  <h1>Spell Check</h1>
  <hr>
  <form action="index.php" method="post">
    <select name="mode">
      <option value="wordCheckLinear">Check if a word is in dictionary (linear)</option>
      <option value="wordCheckBinary">Check if a word is in dictionary (binary)</option>
      <option value="aliceInWonderlandCheckLinear">Spell check Alice in Wonderland (linear)</option>
      <option value="aliceInWonderlandCheckBinary">Spell check Alice in Wonderland (binary)</option>
    </select>
    <br>
    <p><input type="text" name="word"></p>
    <input type="submit" value="Submit">
  </form>
  <hr>
  <h2><?= $output ?></h2>
</body>

</html>