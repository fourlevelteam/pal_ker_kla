<?php

   $servername = "localhost";
   $database = "atm_teamdb";
   $username = "root";
   $password = "";

$conn3 = mysqli_connect($servername,$username,$password,$database);
        mysqli_set_charset($conn3, 'utf8');
        if (!$conn3) {
      die("Connection failed: " . mysqli_connect_error());
                    }
?>

<section>
 <form  action="index.php?action=shopping_cart" method="POST">
 <div id="regtext"> <a name="shopping_cart">Корзина</a></div>
 <table border="1" width="100%" cellpadding="5">

         <?php

            if(isset($_SESSION['neworder'])){
            $id=$_SESSION['neworder'];
            $totalprice=0;
             $sql1="SELECT m.title, m.price, o.amount, m.menudish_id
               FROM  `order_menudish` AS o
               INNER JOIN `menu_dish` AS m ON o.menudish_id=m.menudish_id
               WHERE o.order_id=$id";
               $query1=mysqli_query($conn3,$sql1);

                   if ($sql1){

                   $m1='<td class="zagolovok" colspan="4"><p>Страви з меню</p></td>';
                   echo $m1;
                  ?>
                      <tr>
                          <th><p>Страва</p></th>
                          <th><p>Кількість</p></th>
                          <th><p>Ціна</p></th>
                           <th><p>Сума</p></th>
                      </tr>
                      <?php

                    while($row=mysqli_fetch_array($query1)){

                   $subtotal1=$row["amount"]*$row["price"];
                      $totalprice+=$subtotal1;
                      $d_id = (int)$row['menudish_id'];
                       ?>

                         <tr>
                             <td><?php echo $row["title"] ?></td>


                             <?php
                              echo "<td><button  name='minus1[$d_id]'>-</button>";
                              $amount = (int)$row['amount'];
                              if(isset($_POST['plus1'])){
                              $amountn= $amount+1;
                              $tania =  (int)key($_POST['plus1']);
                              $sql5 = "UPDATE order_menudish SET amount= '$amountn'
                                      WHERE order_id=$id AND menudish_id = $tania";
                              mysqli_query($conn3, $sql5) or die("Ошибка " . mysqli_error($conn3));
                              }
                              if(isset($_POST['minus1'])&&($row['amount']>1)){
                              $amountn= $amount-1;
                              $i1=(int)key($_POST['minus1']);
                              $sql6 = "UPDATE order_menudish SET amount= '$amountn'
                                      WHERE order_id=$id AND menudish_id = $i1";
                                 mysqli_query($conn3, $sql6) or die("Ошибка " . mysqli_error($conn3));
                                    }
                                    echo $row['amount'];
                               echo "<button  name='plus1[$d_id]'>+</button></td>";
                             ?>
                             <td><?php echo $row["price"] ?></td>
                             <td><?php echo  $subtotal1 ?></td>
                             <?php
                              echo "<td><button  class= 'deletebttn' name='delete1[$d_id]'>Видалити</button></td>";
                              ?>
                      
                         </tr>
                        <?php

                           }}



                        $sql2="SELECT t.name, d.price, d.amount, d.coment, d.dish_id
                          FROM  `order_dish` AS o
                          INNER JOIN `dish` AS d ON o.dish_id=d.dish_id
                          INNER JOIN `type_dish` AS t ON d.type=t.id
                          WHERE o.order_id=$id";
                          $query2=mysqli_query($conn3,$sql2);
                              if ($sql2){

                              $m2='<td  class="zagolovok" colspan="4"><p>Власні страви</p></td>';
                              echo $m2;?>

                              <tr>
                                  <th><p>Страва</p></th>
                                  <th><p>Кількість</p></th>
                                   <th><p>Ціна</p></th>
                                   <th><p>Сума</p></th>
                                    </tr>
                                <?php
                               while($row=mysqli_fetch_array($query2)){

                                $subtotal2=$row["amount"]*$row["price"];
                                $totalprice+=$subtotal2;
                                $d2_id = (int)$row["dish_id"];
                                ?>
                                <tr>
                                <td><?php echo "Тип страви: " ?><?php echo $row["name"] ?> <?php echo "." ?>
                                <br><?php echo " Коментар: " ?>
                                <?php if($row["coment"])
                                        {
                                        echo $row["coment"];
                                        } else {
                                        echo "Не має коментаря.";
                                        }
                                ?></td>
                              <?php
                               echo "<td><button  name='minus2[$d2_id]'>-</button>";
                               $amount = (int)$row['amount'];
                               if(isset($_POST['plus2'])){
                               $amountn= $amount+1;
                               $t1 =  (int)key($_POST['plus2']);
                               $sql5 = "UPDATE dish AS d
                                        INNER JOIN order_dish AS o ON o.dish_id= d.dish_id
                                        SET d.amount= '$amountn'
                                        WHERE o.order_id=$id AND d.dish_id =$t1";
                               mysqli_query($conn3, $sql5) or die("Ошибка " . mysqli_error($conn3));
                               }
                               if(isset($_POST['minus2'])&&($row['amount']>1)){
                               $amountn= $amount-1;
                               $t2=(int)key($_POST['minus2']);
                               $sql6 = "UPDATE dish AS d
                                       INNER JOIN order_dish AS o ON o.dish_id= d.dish_id
                                        SET d.amount= '$amountn'
                                          WHERE o.order_id=$id AND d.dish_id = $t2";
                                  mysqli_query($conn3, $sql6) or die("Ошибка " . mysqli_error($conn3));
                                     }
                                     echo $row['amount'];
                                echo "<button  name='plus2[$d2_id]'>+</button></td>";
                              ?>
                                 <td><?php echo $row["price"] ?></td>
                                 <td><?php echo  $subtotal2;  ?></td>
                                  <?php
                              echo "<td><button  class= 'deletebttn' name='delete2[$d2_id]'>Видалити</button></td>";
                              ?>
                            <?php
                              }}
                              ?>
                                 </tr>

                     <tr>
 		   <td colspan="4">Підсумкова ціна: <?php echo  $totalprice; echo" грн";   }   ?></td>
                     </tr>

     </table>

   <?php
      if(isset($_POST['delete1'])){
        $t3=(int)key($_POST['delete1']);
         $sql7 = "DELETE FROM order_menudish
           WHERE order_id=$id AND menudish_id = $t3";
       mysqli_query($conn3, $sql7) or die("Ошибка " . mysqli_error($conn3));
                             }
     if(isset($_POST['delete2'])){
      $t4=(int)key($_POST['delete2']);
        $sql7 = "DELETE FROM order_dish
          WHERE order_id=$id AND dish_id = $t4";
            mysqli_query($conn3, $sql7) or die("Ошибка " . mysqli_error($conn3));
          }
         ?>

     <br />
     <div align = "center"><button type="submit" class="registerbtn">Оновити корзину</button></div>
     <p> <a href="index.php?action=our_menu" class="reglink" >Повернутися до меню.</a></p>
     <p> <a href="index.php?action=constructure" class="reglink" >Повернутися до конструктора страв.</a></p>
     <div align = "center"><button type="submit" name="myord" class="registerbtn">Оформити замовлення </button></div>
    <?php
    //ALTER TABLE `orders` ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `clients`(`client_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

    $sql = "UPDATE `orders` SET`full_price`=$totalprice  WHERE order_id=$id";
    if(isset($_POST['myord'])){
           if (mysqli_query($conn3, $sql)) {echo '<script>location.replace("http://localhost/pal_ker_kla/index.php?action=order");</script>';}
           else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn3);
           }}
        mysqli_close($conn3);
       ?>
 </form>
 <br />

</section>
</form>