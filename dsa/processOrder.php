<?php

// before this script is called, we must create an SQL table. At linux prompt:
//    sqlite3
//    CREATE TABLE customers (id integer PRIMARY KEY AUTOINCREMENT, name text, address text, email text, date text);
//    CREATE TABLE orders (item text, count text);


//  CREATE TABLE orders (id integer PRIMARY KEY AUTOINCREMENT, name text, addr text, email text, cart text, date text);

// extract form data
$name = $_POST["name"];
$addr = $_POST["addr1"] . ", " . $_POST["addr2"] . ", " . $_POST["addr3"];
$email = $_POST["email"];
$cart = $_POST["hiddenCartList"];
$items = $_POST["hiddenItems"];
$total = $_POST["hiddenCartTotal"];
$count = $_POST["hiddenCartCount"];
$orderDate = date('l jS \of F Y h:i:s A');


// add customer to database
$db    = new SQLite3('mydb');
$db->query( 'INSERT INTO customers VALUES( NULL,"' . $name . '", "' . $addr . '", "' . $email . '", "'  . $orderDate . '")' );

// add order item to database (note that I added a new hidden field, hiddenItems, to widget.html)
$itemsList = explode( " ", $items ); // convert from string to array
foreach( $itemsList as $i ) {
     if( $i != '' ) {
          $result = $db->query( 'SELECT count FROM orders WHERE item = "' . $i . '"' );
          $numrow = sizeof( $result->fetchArray() );
          if( $numrow > 1 ) {
               $db->query( 'UPDATE orders SET count=count+1 WHERE item = "' . $i . '"' );
          } else {
               $c = 1;
               $db->query( 'INSERT INTO orders VALUES( "' . $i . '", "' . $c . '")' );
          }
     }
}

// return thank you message
print "thank you " . $name . " for your order of " . $count . " items totally $" . $total . "\n";

?>

