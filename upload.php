<?php
$uploaded = ""; 
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload Images</label>    
    <input type="file" name="files[]" id="imageUpload" multiple="multiple"/>
    <button>Envoyer</button>
</form>

<?php 
if (!empty($_FILES['files']['name'][0])) {

    $files = $_FILES['files'];

    $uploaded = array();
    $failed = array();
    $allowed = array ('jpg', 'png', 'gif');

    foreach($files['name'] as $position => $file_name) {

        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size < 1048576) {
                    $file_name_new = uniqid('') . '.' . $file_ext;
                    $up ='uploads/';
                    $file_destination = $up . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "$file_name n'a pas été uploadé.";
                    }
                } else {
                    $failed[$position] = "$file_name est trop volumineux";
                }
            } else {
                $failed[$position] = "$file_name a une erreur de type $file_error.";
            }
        } else {
                $failed[$position] = "$file_name a une extension '$file_ext' non-autorisée pour l'upload.";
        }
    }
    foreach($uploaded as $uploadedfile) {
        if (!empty($uploadedfile)) {
            echo (substr($uploadedfile, 8)) . " à bien été uploadé";
            echo '<br/>';
        }
    }
    foreach($failed as $failedfile) {
        if (!empty($failedfile)) {
            echo $failedfile . " et n'a pas été uploadé";
            echo '<br/>';
        }
    }
    foreach ($uploaded as $file => $uploadedfile) {
        ?>
        <figure>
            <img src="<?=$uploadedfile?>" alt="image">
            <figcaption>Nom de l'image : <?=(substr($uploadedfile, 8))?></figcaption>
            <a href="./delete.php?delete=<?=(substr($uploadedfile, 8))?>">SUPPRIMER</a>
        </figure>
        <?php
    }
}
?>
