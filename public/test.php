
      <?php 
        $conn=mysql_connect('localhost','laxit_sscondo','(zm]1R-F]J}g');
       mysql_select_db('laxit_sacondo',$conn);
       $id=$_REQUEST['clientId'];
       #echo "select * from secondary_client where id='".$id."'";
       $query=mysql_query("select * from secondary_client where id='".$id."'");  
       $result= mysql_fetch_object($query);
       
       
       ?>
<table class='table'>
    

<tr>
        <th>Type</th>
        <td><?php echo  $result->type; ?></td>
    </tr>
    <tr>
        <th>Name:</th>
        <td><?php echo  $result->first_name.' '.$result->last_name?></td>
    </tr>
    <tr>
        <th>Email   :</th>
        <td><?php echo  $result->email_address; ?></td>
    </tr>
    <tr>
        <th>Phone   :</th>
        <td><?php echo  $result->phone_number; ?></td>
    </tr>
     <tr>
        <th>Address   :</th>
        <td><?php echo  $result->address; ?></td>
    </tr>
    <tr>
        <th>Social Insurance Number   :</th>
        <td><?php echo  $result->sin_number; ?></td>
    </tr>
</table>