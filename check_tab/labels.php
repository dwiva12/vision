<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
<style>
  .w3-border{border:1px solid #ccc!important}
  .w3-green,.w3-hover-green:hover{color:#fff!important;background-color:#4CAF50!important}
</style>
<div class="row">
    <div class="col-12">
    <div class="w3-container">
        <ol>
            <?php foreach ($labels as $key => $label): ?>
                <li><h6> <strong><?php echo ucfirst($label->getDescription()) ?></strong></h6>
                Score: <strong><?php echo number_format($label->getScore() * 100 , 2) ?>%</strong><br></li>
                <div class="w3-border">
                <div class="w3-green" style="height:15px; width:<?php echo number_format($label->getScore() * 100 , 2) ?>%"></div>
                </div>
                <br>
            <?php endforeach ?>
        </ol>
    </div>
    </div>
</div>
