<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Movie Recommendation System</h1>
      <form action="#" method="POST">
        <label for="genre">Select a genre:</label>
        <select id="genre" name="genre">
            <option value="Action">Action</option>
            <option value="Adventure">Adventure</option>
            <option value="Animation">Animation</option>
            <option value="Comedy">Comedy</option>
            <option value="Crime">Crime</option>
            <option value="Drama">Drama</option>
            <option value="Romance">Romance</option>
            <option value="Horror">Horror</option>
            <option value="Histroy">History</option>
            <option value="Mystery">Mystery</option>
            <option value="Thriller">Thriller</option>
            <option value="Family">Family</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Science Fiction">Sci.Fi</option>
            <option value="War">War</option>
        </select>
        &emsp;
        
        <label for="rating">Select a minimum rating:</label>
        <input type="number" id="rating" name="rating" min="1" max="10">
        
        <br><br>
        <input type="hidden" name="page" value="1">
        <input type="submit" value="Get Recommendations">
        <hr/>  
    </form>
    <?php
    session_start();
    if(isset($_REQUEST['genre']) && isset($_REQUEST['rating'])) {
        $genre = $_REQUEST["genre"];
        $rating=(int)$_REQUEST["rating"];
        $_SESSION['genre']=$genre;
        $_SESSION['rating']=$rating;

        $csvFile = 'mymoviedb.csv';

        $moviesPerPage = 21;
        $currentPage = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        // Check if the file exists
        if (!file_exists($csvFile)) {
            die("The file $csvFile does not exist.");
        }
        
       
        
        // Initialize an empty array to store the data
        $movies = array();
        
        // Read each row of the CSV file
         // Open the CSV file for reading
         if(($fileHandle = fopen($csvFile, 'r'))!== false){
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Skip the header row
            if ($row[0] === 'release_date') {
                continue;
            }
            $movieGenres=explode(',',$row[7]);
            // Assuming the CSV file has two columns: title and genre
            $movie=array(
            'title' => $row[1],
            'genre' => $movieGenres,
            'rating'=> $row[5],
            'img'=> $row[8],
            'lang'=>$row[6]);
         // Store the data in an associative array or any other appropriate data structure
            if(in_array($genre,$movie['genre'])&& $movie['rating'] >= $rating){
            $movies[] = $movie;
            }
        }
        }
        else{
            echo "unable read csv file";
        }
        
        // Close the file handle
        fclose($fileHandle);


        // Now you can work with the $movies array containing the CSV data
        
          // Set the maximum number of movies per page
        $totalMovies = count($movies);
        $totalPages = ceil($totalMovies / $moviesPerPage);

        $currentPage = max(1,min($totalPages,$currentPage));
        $startIndex = ($currentPage - 1) * $moviesPerPage;

       $final_list=array_slice($movies,$startIndex,$moviesPerPage);
        
        // Calculate the starting and ending index of movies for the current page
        
      // $endIndex = min($startIndex + $moviesPerPage - 1, $totalMovies - 1);
        if (empty($final_list)) {
            echo "<p>No movie data found.</p>";
        } else{
            foreach($final_list as $movie){
            echo '<div class="movie-card">';
            echo '<img src="'.$movie['img'].'" alt="' .$movie['title'] .' Poster">';
            echo '<h2>Title: '. $movie['title']. '</h2>';
            echo '<p> Genre: '. implode(',',$movie['genre']). '</p>';
            echo '<p> Rating: '. $movie['rating']. '    Language:'.$movie['lang'].'</p>';
           // echo '<p> Language:'.$movie['lang'].'</p>';
            echo '<button class="favourite-button"> Add To Favorites</button>';
            echo '<button class="details-button" data-title="'.$movie['title'].'" onClick="showMovieDetails(event)"> Details </button>';
            echo '</div>';
            }

            }
            if($currentPage > 1){
                echo '<div class="pagination">';
                echo "<a href=\"recommend.php?genre=$genre&rating=$rating&page=".($currentPage-1)."\">Previous Page</a>";
                echo '</div>';
            }
           
            if($currentPage < $totalPages){
                echo '<div class="pagination">';
                echo "<a href=\"recommend.php?genre=$genre&rating=$rating&page=".($currentPage+1)."\">Next Page</a>";
                echo '</div>';
            }
            echo '<div class="pagination">';
            echo "<a href=\"recommend.php?genre=$genre&rating=$rating&page=1\">Home</a>";
            echo '</div>';
    }else{
        echo '<div class="pagination">';
        echo "<a href=\"recommend.php\">Home</a>";
        echo '</div>';    }
    ?>
</body>
</html>
