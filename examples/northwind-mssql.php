<html>
<head>
<title>ODBTP PEAR DB Example: MS SQL Server</title>
</head>
<body>
<?php

    /*
    ** This example shows how to connect to a MS SQL Server database
    ** using the PEAR DB driver for ODBTP.
    */

    require( "DB.php" );

    $dsn = 'odbtp(mssql)://myuid:mypwd@odbtphost/NorthWind?server=mysrvr&rowcache=yes';

    // The following DSN specification can be used if the ODBTP server
    // and SQL Server reside on the same host machine.
    //
    // $dsn = 'odbtp(mssql)://myuid:mypwd@mysrvr/NorthWind?rowcache=yes';

    $options['persistent'] = true;
    $dbh = DB::connect( $dsn, $options );
    if( PEAR::isError( $dbh ) ) {
        echo "Error message: " . $dbh->getMessage() . "<br>\n";
        echo "Detailed error description: " . $dbh->getDebugInfo() . "<br>\n";
        die;
    }
    // FOR BROWSE allows table names to be included in results
    $sql = "SELECT * FROM Customers AS C, Orders AS O "
         . "WHERE O.CustomerId = C.CustomerId FOR BROWSE";
    $res = $dbh->query( $sql );
    if( PEAR::isError( $res ) ) {
        echo "Error message: " . $res->getMessage() . "<br>\n";
        echo "Detailed error description: " . $res->getDebugInfo() . "<br>\n";
        die;
    }
    do {
        if( !($cols = $res->numCols()) ) continue;

        echo $res->numRows() . " rows<p>\n";

        $colinfo = $res->tableInfo();
        if( PEAR::isError( $colinfo ) ) {
            echo "Error message: " . $colinfo->getMessage() . "<br>\n";
            echo "Detailed error description: " . $colinfo->getDebugInfo() . "<br>\n";
            die;
        }
        echo "<table cellpadding=2 cellspacing=0 border=1>\n";
        echo "<tr>";
        echo "<td>&nbsp;<b>Table</b>&nbsp;</td>";
        echo "<td>&nbsp;<b>Name</b>&nbsp;</td>";
        echo "<td>&nbsp;<b>Type</b>&nbsp;</td>";
        echo "<td>&nbsp;<b>Length</b>&nbsp;</td>";
        echo "<td>&nbsp;<b>Flags</b>&nbsp;</td>";
        echo "</tr>\n";


        for( $i = 0; $i < $cols; $i++ ) {
            echo "<tr>";
            echo "<td>&nbsp;". $colinfo[$i]['table'] . "&nbsp;</td>";
            echo "<td>&nbsp;". $colinfo[$i]['name'] . "&nbsp;</td>";
            echo "<td>&nbsp;". $colinfo[$i]['type'] . "&nbsp;</td>";
            echo "<td>&nbsp;". $colinfo[$i]['len'] . "&nbsp;</td>";
            echo "<td>&nbsp;". $colinfo[$i]['flags'] . "&nbsp;</td>";
            echo "</tr>\n";
        }
        echo "</table><p>\n";
        echo "<table cellpadding=2 cellspacing=0 border=1>\n";
        echo "<tr>";

        for( $col = 0; $col < $cols; $col++ ) {
            echo "<td><nobr>&nbsp;" . $colinfo[$col]['name'] . "&nbsp;</nobr></td>";
        }
        echo "</tr>\n";

        while( $row = $res->fetchRow() ) {
            echo "<tr>";
            for( $col = 0; $col < $cols; $col++ ) {
                if( is_null( $row[$col] ) ) $row[$col] = "NULL";
                echo "<td valign=\"top\"><pre>";
                print_r( $row[$col] );
                echo "</pre></td>";
            }
            echo "</tr>\n";
        }
        echo "</table><p>\n\n";
    }
    while( $res->nextResult() );

    $dbh->disconnect();
?>
</body>
</html>
