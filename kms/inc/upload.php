<?php // You need to add server side validation and better error handling here
/*
$data = array();
if(isset($_FILES['file-0'])) {
    $data[0] = uploadFile($_FILES['file-0']);
} else {
    $data =  array('error' => 'There is no file'.$_FILES['file-0') ;
}
/*
if(isset($_FILES))
{  
    $error = false;
    $files = array();

    $uploaddir = '../uploads/';
    foreach($_FILES as $file)
    {
        if(move_uploaded_file($file['tmp_name'], $uploaddir.basename($file['name'])))
        {
            $files[] = $uploaddir .$file['name'];
        }
        else
        {
            $error = true;
        }
    }
    $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}
else
{
    $data = array('success' => 'Form was submitted', 'formData' => $_POST);
}
*/

 
$count = count($_FILES['arr']['name']); // arr from fd.append('arr[]')
var_dump($count);
echo $count; 
 
$data = array();
$error = false;
$files = array();
   $uploaddir = '../uploads/';
if ($count == 0) {
    $error = true;
    $data = array('error' => $_FILES['arr']['error'][0]);
}
else {
    $i = 0;
    for ($i = 0; $i < $count; $i++) { 
        if (move_uploaded_file($_FILES['arr']['tmp_name'][$i], $uploaddir . $_FILES['arr']['name'][$i])) {
            $files[] = $uploaddir .$file['name'];
        } else {
            $error = true;
        }

    }
    $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
  //   $data = array('error' => 'test');
}
 
/*
function uploadFile($theFile){
    $uploaddir = '../uploads/';
    $error = false;
    $files = array();

    if(move_uploaded_file($theFile['tmp_name'], $uploaddir.basename($theFile['name'])))
    {
        $file = $uploaddir .$theFile['name'];
       
    }
    else
    {
        $error = true;
    }
   
    return ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $file);;
}
*/
echo json_encode($data);
?>