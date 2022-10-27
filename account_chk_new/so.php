<html>
<head></head>
<body>

<?

 echo "aaaa";
 set_time_limit(0);
 $host = "129.200.9.11";
 //$host = "129.200.9.18";
 $port = "11372";
 $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("can't create socket");
 
 
 $result = socket_bind($socket, $host, $port) or die("can't bind socket");
 echo "after bind";
 $result = socket_listen($socket, 3) or die("can't make socket listen");
 echo "we are waiting for accepting..";
 $spawn = socket_accept($socket) or die("can't accept");
 echo "we get accept";
 do
 {
  $input = socket_read($spawn, 1024, 1) or die("can't socket read");

  if( trim($input) != "" )
  {
   echo "$input\n";

   if( trim($input) == "end" ){
    
    socket_close($spawn);
    break;
   }
  }
 }while(true);

 socket_close($socket);
 echo "end socket process!!\n";

?>

</body>
</html>

