  <?php
      require_once "../classes/Furniture.php";
  ?>
  
  <div id="tabs-3" >
    
    <div id="itemSearch">
        <form method="get">
            <label for="query">Search: </label>
            <input  type="text" name="query" id="query" placeholder="What are you looking for?"><br style="border:0;" />
            <label for="min" id="minLabel">$:  </label>
            <input type="range" min="500", max="1400", value="500", id="min" name="min"><br style="border:0;" />
            <label for="max" id="minLabel">- $:  </label><input type="range" min="500", max="1400", value="1400", id="max" name="max">
            <input type="submit" value="search" class="center" value="Search">
        </form>
    </div>
    <div id="workFlex">
        <section  id = "gallery2">
            <ul id="galleryList2">
            <?php
                if (request_is_get()){
                    $furniture = new Furniture();
                    $response = $furniture->getFurniture("right");
                    echo $response;
                }
            ?>
            </ul>
        </section>
        <section  id = "gallery1">
            <ul id="galleryList1">
            <?php
                if (request_is_get()){
                    $furniture = new Furniture();
                    $response = $furniture->getFurniture("left");
                    echo $response;
                }
            ?>
            </ul>
        </section>
    </div>
</div>