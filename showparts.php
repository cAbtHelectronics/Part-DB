<?PHP
/*
    part-db version 0.1
    Copyright (C) 2005 Christoph Lechner
    http://www.cl-projects.de/

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

    $Id: showparts.php,v 1.11 2006/05/23 21:47:14 cl Exp $
*/
    include ("lib.php");
    partdb_init();

    $cid    = ( isset( $_REQUEST['cid']))    ? $_REQUEST['cid'] : '';
    $pid    = ( isset( $_REQUEST['pid']))    ? $_REQUEST['pid'] : '';
    $action = ( isset( $_REQUEST['action'])) ? $_REQUEST['action'] : 'default';

    
    if ( $action == 'r')  //remove one part
    {
        $query = "UPDATE parts SET instock=instock-1 WHERE id=". smart_escape( $pid) ." AND instock >= 1 LIMIT 1;";
        mysql_query( $query);
    }

    if ( $action == 'a')  //add one part
    {
        $query = "UPDATE parts SET instock=instock+1 WHERE id=". smart_escape( $pid) ." LIMIT 1;";
        mysql_query( $query);
    }
    

    function findallsubcategories( $cid)
    {
        $rv = "id_category=". smart_escape( $cid);
        
        $query = "SELECT id FROM categories WHERE parentnode=". smart_escape( $cid) .";";
        $result = mysql_query( $query);
        while ( $d = mysql_fetch_assoc( $result))
        {
            $rv = $rv ." OR ". findallsubcategories( smart_unescape( $d['id']));
        }

        return( $rv);
    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Teileansicht</title>
    <?php print_http_charset(); ?>
    <link rel="StyleSheet" href="css/partdb.css" type="text/css">
    <?php
        require( 'config.php');
        if ($hide_id)
        {
            print '<style type="text/css">.idclass { display: none; } </style>';
        } 
    ?>
    <script type="text/javascript" src="dtree.js"></script>
    <script type="text/javascript" src="popup.php"></script>
</head>
<body class="body">

<div class="outer">
    <h2>Sonstiges</h2>
    <div class="inner">
        <?php
        print "<form action=\"showparts.php\" method=\"post\">";
        print "<input type=\"hidden\" name=\"cid\" value=\"". $cid ."\">";
        print "<input type=\"hidden\" name=\"type\" value=\"index\">";
        if (! isset($_REQUEST["nosubcat"]) )
        {
            print "<input type=\"hidden\" name=\"nosubcat\" value=\"1\">";
            print "<input type=\"submit\" name=\"s\" value=\"Unterkategorien ausblenden\">";
        }
        else
            print "<input type=\"submit\" name=\"s\" value=\"Unterkategorien einblenden\">";
        print "</form>";

        ?>
        <a href="newpart.php?cid=<?php print $cid; ?>" onclick="return popUp('newpart.php?cid=<?php print $cid; ?>');">Neues Teil in dieser Kategorie</a>
    </div>
</div>


<div class="outer">
    <h2>Anzeige der Kategorie &quot;<?PHP print lookup_category_name( $cid); ?>&quot;</h2>
    <div class="inner">
        <table>
        <?PHP
        
        // check if with or without subcategories
        if (! isset($_REQUEST["nosubcat"]) )
            $catclause = findallsubcategories( $cid);
        else
            $catclause = "id_category=". $cid;

        if ( (strcmp ($_REQUEST["type"], "index") == 0))
        {
            print "<tr class=\"trcat\">".
                "<td></td>".
                "<td>Name</td>".
                "<td>Vorh./<br>Min.Best.</td>".
                "<td>Footprint</td>".
                "<td>Lagerort</td>".
                "<td class='idclass'>ID</td>".
                "<td>Datenbl&auml;tter</td>".
                "<td align=\"center\">-</td>".
                "<td align=\"center\">+</td>".
                "</tr>\n";

            $query = "SELECT ".
                "parts.id,".
                "parts.name,".
                "parts.instock,".
                "parts.mininstock,".
                "footprints.name AS 'footprint',".
                "storeloc.name AS 'location',".
                "parts.comment, ".
                "parts.supplierpartnr ".
                " FROM parts".
                " LEFT JOIN footprints ON parts.id_footprint=footprints.id".
                " LEFT JOIN storeloc ON parts.id_storeloc=storeloc.id".
                " WHERE (". $catclause .")".
                " ORDER BY name ASC;";
            $result = mysql_query( $query) or die( mysql_error());

            $rowcount = 0;
            while ( $data_array = mysql_fetch_assoc( $result))
            {
                $rowcount++;
                print_table_row( $rowcount, $data_array);
            }
        }
        ?>
        </table>
    </div>
</div>

</body>
</html>
