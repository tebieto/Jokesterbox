<?php
 
session_set_save_handler('_open',
                         '_close',
                         '_read',
                         '_write',
                         '_destroy',
                         '_clean');
 
?>


<?php
 
function _open()
{
    global $_sess_db;
 
    $_sess_db = mysqli_connect('localhost:3306', 'jokesterbox', '1223454Allu4%', 'jokesterbox');
}
 
function _close()
{
    global $_sess_db;
	    $_sess_db = mysqli_connect('localhost:3306', 'jokesterbox', '1223454Allu4%', 'jokesterbox'); 
    return mysqli_close($_sess_db);
}
 
?>


<?php
 
function _read($id)
{
    global $_sess_db;
 
    $id = mysqli_real_escape_string($_sess_db,$id);
 
    $sql = "SELECT data
            FROM   sessions
            WHERE  id = '$id'";
 
    if ($result = mysqli_query($_sess_db, $sql)) {
        if (mysqli_num_rows($result)) {
            $record = mysqli_fetch_assoc($result);
 
            return $record['data'];
        }
    }
 
    return '';
}
 
?>

<?php
 
function _write($id, $data)
{
    global $_sess_db;
	    $_sess_db = mysqli_connect('localhost:3306', 'jokesterbox', '1223454Allu4%', 'jokesterbox');
 
    $access = time();
 
    $id = mysqli_real_escape_string($_sess_db,$id);
    $access = mysqli_real_escape_string($_sess_db,$access);
    $data = mysqli_real_escape_string($_sess_db,$data);
 
    $sql = "REPLACE
            INTO    sessions
            VALUES  ('$id', '$access', '$data')";
 
    return mysqli_query($_sess_db,$sql);
}
 
?>


<?php
 
function _destroy($id)
{
    global $_sess_db;
       $_sess_db = mysqli_connect('localhost:3306', 'root', 'BKYiFJ3GpZJL', 'jokesterbox');
	
    $id = mysqli_real_escape_string($_sess_db,$id);
 
    $sql = "DELETE
            FROM   sessions
            WHERE  id = '$id'";
 
    return mysqli_query($_sess_db,$sql);
}
 
?>


<?php
 
function _clean($max)
{
    global $_sess_db;
	
	    $_sess_db = mysqli_connect('localhost:3306', 'root', 'BKYiFJ3GpZJL', 'jokesterbox');
 
    $old = time() - $max;
    $old = mysqli_real_escape_string($_sess_db,$old);
 
    $sql = "DELETE
            FROM   sessions
            WHERE  access < '$old'";
 
    return mysqli_query($_sess_db,$sql);
}
 
?>