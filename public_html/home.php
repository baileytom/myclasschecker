<?php include('includes/secure.php') ?>

<?php
$pgName = 'Classes | myclasschecker';
include('includes/sessionheader.php')
?>

<?php include('includes/mysqllogin.php') ?>


<?php
function getClasses() {
    global $conn;
    $sql = "SELECT name, term, crn, term_string  FROM Subscriptions, Classes WHERE user_id = '".$_SESSION['user_id']."' AND class_id = id" ;
    $result = mysqli_query($conn, $sql);
    return $result;
}


?>

<div class="row">
  <div class="col-xs-5">
    <form action='process.php' method='post'>
    <!-- CRN  -->
      <div class='form-group'>
        <label for='crnInput'><small>CRN</small></label>
        <input type='text' name='crn' class='form-control' id='crnInput' placeholder='Enter CRN' minlength='5' required>
      </div>
      <!-- TERM -->
      <div class="form-group">
        <label for="term"><small>Term</small></label>
        <select class="form-control" name="term" id="term">
          <option value="20">Winter</option>
          <option value="10">Fall</option>
          <option value="30">Summer</option>
        </select>
      </div>
      <!-- YEAR -->
      <div class="form-group">
        <label for="year"><small>Year</small></label>
        <select class="form-control" name="year" id="year">
          <option value="2019">2019</option>
          <option value="2018">2018</option>
        </select>
      </div>
      <br>
      <button type='submit' name='add' value='add' class='btn btn-primary'>Add class</button>
      <button type='submit' name='remove' value='remove' class='btn btn-primary'>Remove class</button>
    </form>
  </div>
  <div class="col-xs-7">
    <label><small>Search - select to autofill</small></label>

        <div class="search-box">
        <input type="text" class='form-control' autocomplete="off" placeholder="Enter name (ie: Calculus)" />
        <div class="result"></div>
    </div>


    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    // Set search input value on click of result item
    $(document).on("click", ".result .list-group .list-group-item", function(){
        var text = $(this).text();
        //alert(text);
        var textList = text.split(", ");
        var _length = textList.length;
        var crn = textList[_length-2];
        var crn = crn.split(' ');
        var crn = crn[crn.length-1]
        var termString = textList[_length-1];
        var termList = termString.split(" ");
        var tLength = termList.length
        var semester = termList[tLength-2];
        var year = termList[tLength-1];
        var termVal = null;
        switch(semester) {
            case "Fall":
                termVal = "10";
                break;
            case "Winter":
                termVal = "20";
                break;
            case "Summer":
                termVal = "30";
                break;
            }
        document.getElementById("crnInput").value = crn;
        document.getElementById("term").value = termVal;
        document.getElementById("year").value = year;
        //$(this).parents(".search-box").reset();
        $(this).parents(".search-box").find('input[type="text"]').val("");
        $(this).parents(".result").empty();
    });

    $(document).on("mouseover", ".list-group .list-group-item", function(e){
        $(".list-group .list-group-item").removeClass("hover");
        $(e.target).addClass("hover");
    });
    
    $(document).on("mouseout", ".list-group .list-group-item", function(e){
        $(".list-group .list-group-item").removeClass("hover");
        //$(e.target).addClass("active");
    });

    $(document).on("mouseover", ".result .list-group .list-group-item", function(e){
        $(".list-group .list-group-item").removeClass("hover");
        $(e.target).addClass("hover");
    });
    
    $(document).on("mouseout", ".result .list-group .list-group-item", function(e){
        $(".list-group .list-group-item").removeClass("hover");
        //$(e.target).addClass("active");
    });

    $(document).on("click", "#myclass", ".list-group .list-group-item", function(){
        var text = $(this).text();
        var textList = text.split(' - ');
        var crn = textList[1];
        var termString = textList[3];
        var termList = termString.split(' ');
        var semester = termList[0];
        var year = termList[1];

        termVal = null;

        switch(semester) {
            case "Fall":
                termVal = "10";
                break;
            case "Winter":
                termVal = "20";
                break;
            case "Summer":
                termVal = "30";
                break;
            }

        document.getElementById("crnInput").value = crn;
        document.getElementById("term").value = termVal;
        document.getElementById("year").value = year;        

    });

});

function testEr() {
                  
}





</script>

    
    <hr>
    <label><small>Classes (If these course sections become available, you will receive an email) - select to autofill</small></label>
    <ul class="list-group">
    <?php
       $classes = getClasses();
       $names = array();
       $crns = array();
       $terms = array();
       while($row = mysqli_fetch_assoc($classes)) {
           $names[] = $row['name']." - ".$row['term_string'];
           $crns[] = $row['crn'];
           $terms[] = $row['term'];
       }
    foreach($names as $name) {
        
        echo '<li onclick="" class="list-group-item" id="myclass">'.$name.'</li>';
    }
    ?>
    </ul>
  </div>
</div>

<?php $conn->close(); ?>
<?php include('includes/footer.php') ?>
