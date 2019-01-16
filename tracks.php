<?php
 $pdo = new PDO('sqlite:chinook.db');
 $sql = "
  SELECT
    genres.Name,
    genres.GenreId,
    tracks.Name AS trackName,
    tracks.UnitPrice,
    albums.Title,
    artists.Name AS artistName
  FROM genres
  INNER JOIN tracks
  ON genres.GenreId = tracks.GenreId
  INNER JOIN albums
  ON tracks.AlbumId = albums.AlbumId
  INNER JOIN artists
  ON albums.ArtistId = artists.ArtistId
 ";

 if (isset($_GET['genre'])){
    $sql = $sql . 'WHERE genres.GenreId = ?';
 }

 $statement = $pdo->prepare($sql);

 if (isset($_GET['genre'])){
   $statement->bindParam(1, $_GET['genre']);
 }

 $statement->execute();
 $tracks = $statement->fetchAll(PDO::FETCH_OBJ);
 // var_dump($tracks);

//Get the Genre Name
if (isset($_GET['genre'])){
  $genresql = "
    SELECT
      genres.Name
    FROM genres
    WHERE genres.GenreId = ?
  ";

  $genreStatement = $pdo->prepare($genresql);
  $genreStatement->bindParam(1, $_GET['genre']);
  $genreStatement->execute();
  $genreNames = $genreStatement->fetchAll(PDO::FETCH_OBJ);

}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Assignment 1: PDO</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
  <h1>
    <?php if (isset($_GET['genre'])){
      foreach($genreNames as $genreName){
        echo $genreName->Name;
      }
     }?>
  Songs
  </h1>
  <table class="table">
    <tr>
      <th>Track Name</th>
      <th>Album Title</th>
      <th>Artist Name</th>
      <th>Price</th>
    </tr>
    <?php foreach($tracks as $track): ?>
      <tr>
        <td>
          <?php echo $track->trackName?>
        </td>
        <td>
          <?php echo $track->Title?>
        </td>
        <td>
          <?php echo $track->artistName?>
        </td>
        <td>
          $<?php echo $track->UnitPrice?>
        </td>
      </tr>
    <?php endforeach?>
  </table>

</body>
</html>
