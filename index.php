<?php

// set max execution time to 10min (default: 30s) to avoid timing out when spell-checking Alice in Wonderland via linear search
// (spell-checking Alice in Wonderland via linear search usually takes around four minutes)
set_time_limit(600);

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

// set global output variable
$output = '';

// form action
if (isset($_POST['mode'])) {
  // mode: check if word is in dictionary (linear)
  if ($_POST['mode'] == 'wordCheckLinear' && trim(strtolower($_POST['word'])) != '') {
    // convert word to lowercase and trim it
    $word = trim(strtolower($_POST['word']));

    // get current time before search starts
    $t1 = gettimeofday(true);

    // search for word
    if (linearSearch($dictionary, $word) >= 0) {
      // if search is successful:
      // subtract initial time from current time to get search time
      $timeElapsed = gettimeofday(true) - $t1;

      // set output
      $output = "'$word' was found in the dictionary. Search time: $timeElapsed seconds via linear search";
    } else {
      // if search is unsuccessful:
      // subtract initial time from current time to get search time
      $timeElapsed = gettimeofday(true) - $t1;

      // set output
      $output = "'$word' was not found in the dictionary. Search time: $timeElapsed seconds via linear search";
    }
  }

  // mode: check if word is in dictionary (binary)
  if ($_POST['mode'] == 'wordCheckBinary' && trim(strtolower($_POST['word'])) != '') {
    // convert word to lowercase and trim it
    $word = trim(strtolower($_POST['word']));

    // get current time before search starts
    $t1 = gettimeofday(true);

    // search for word
    if (binarySearch($dictionary, $word) >= 0) {
      // if search is successful:
      // subtract initial time from current time to get search time
      $timeElapsed = gettimeofday(true) - $t1;

      // set output
      $output = "'$word' was found in the dictionary. Search time: $timeElapsed seconds via binary search";
    } else {
      // if search is unsuccessful:
      // subtract initial time from current time to get search time
      $timeElapsed = gettimeofday(true) - $t1;

      // set output
      $output = "'$word' was not found in the dictionary. Search time: $timeElapsed seconds via binary search";
    }
  }

  // mode: spell check Alice in Wonderland (linear)
  if ($_POST['mode'] == 'aliceInWonderlandCheckLinear') {
    // set variable to store number of words not found in dictionary
    $misspelledWords = 0;

    // get current time before spell-check starts
    $t1 = gettimeofday(true);

    // set each word to lowercase and spell-check it
    foreach ($aliceWords as $aliceWord) {
      if (linearSearch($dictionary, strtolower($aliceWord)) == -1) {
        // if word is not found in dictionary, update the number of misspelled words
        $misspelledWords++;
      }
    }

    // subtract initial time from current time to get search time
    $timeElapsed = gettimeofday(true) - $t1;

    // set output
    $output = "Number of misspelled words: $misspelledWords. Spell-check time: $timeElapsed seconds via linear search";;
  }

  // mode: spell check Alice in Wonderland (binary)
  if ($_POST['mode'] == 'aliceInWonderlandCheckBinary') {
    // set variable to store number of words not found in dictionary
    $misspelledWords = 0;

    // get current time before spell-check starts
    $t1 = gettimeofday(true);

    // set each word to lowercase and spell-check it
    foreach ($aliceWords as $aliceWord) {
      if (binarySearch($dictionary, strtolower($aliceWord)) == -1) {
        // if word is not found in dictionary, update the number of misspelled words
        $misspelledWords++;
      }
    }

    // subtract initial time from current time to get search time
    $timeElapsed = gettimeofday(true) - $t1;

    // set output
    $output = "Number of misspelled words: $misspelledWords. Spell-check time: $timeElapsed seconds via binary search";
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
      <option value="aliceInWonderlandCheckLinear">Spell-check Alice in Wonderland (linear) - WARNING: takes around 4 minutes</option>
      <option value="aliceInWonderlandCheckBinary">Spell-check Alice in Wonderland (binary)</option>
    </select>
    <p><input type="text" name="word" placeholder="Enter word to check here"></p>
    <p><input type="submit" value="Submit"></p>
  </form>
  <hr>
  <h2><?= $output ?></h2>
</body>

</html>